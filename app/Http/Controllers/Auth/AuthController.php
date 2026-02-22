<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // Check if this is OTP resend (check this first)
        if ($request->has('resend_otp') && $request->resend_otp) {
            \Log::info('OTP resend route hit');
            return $this->handleResendOtp($request);
        }

        // Check if this is OTP verification
        if ($request->has('otp_verification') && $request->otp_verification) {
            \Log::info('OTP verification route hit');
            return $this->handleOtpVerification($request);
        }

        // Regular login process
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists and password is correct
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP in session
        Session::put('login_otp', $otp);
        Session::put('login_otp_email', $request->email);
        Session::put('login_otp_expires_at', now()->addMinutes(10));

        // Send OTP email
        try {
            Mail::raw("Your login OTP is: {$otp}\n\nThis OTP will expire in 10 minutes.", function($message) use ($request) {
                $message->to($request->email)
                        ->subject('Your Login OTP - HR3 Lumino');
            });
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Failed to send OTP. Please try again.',
            ])->onlyInput('email');
        }

        // Redirect back with OTP mode
        return back()
            ->with('otp_mode', true)
            ->with('otp_email', $request->email)
            ->with('success', 'OTP has been sent to your email address.');
    }

    /**
     * Handle OTP verification.
     */
    private function handleOtpVerification(Request $request): RedirectResponse
    {
        \Log::info('handleOtpVerification called with data', $request->all());
        
        // Get OTP from single input field and ensure it's a string
        $otp = (string) $request->otp1;
        
        // Validate OTP field - allow 4-6 digits to be more flexible
        $request->validate([
            'otp1' => ['required', 'string', 'min:4', 'max:6'],
        ]);

        $storedOtp = Session::get('login_otp');
        $storedEmail = Session::get('login_otp_email');
        $expiresAt = Session::get('login_otp_expires_at');

        // Debug logging
        \Log::info('OTP Verification Attempt', [
            'request_otp' => $otp,
            'stored_otp' => $storedOtp,
            'stored_email' => $storedEmail,
            'expires_at' => $expiresAt,
            'current_time' => now(),
            'is_expired' => now()->greaterThan($expiresAt),
            'otp_match' => $otp === $storedOtp,
            'otp_length' => strlen($otp),
            'stored_otp_length' => strlen($storedOtp ?? '')
        ]);

        // Verify OTP
        if (!$storedOtp || !$storedEmail || now()->greaterThan($expiresAt)) {
            \Log::info('OTP expired or missing', [
                'stored_otp_exists' => !empty($storedOtp),
                'stored_email_exists' => !empty($storedEmail),
                'is_expired' => now()->greaterThan($expiresAt)
            ]);
            
            Session::forget(['login_otp', 'login_otp_email', 'login_otp_expires_at']);
            return back()
                ->with('otp_mode', false)
                ->withErrors([
                    'email' => 'OTP expired. Please login again.',
                ]);
        }

        if ($otp !== $storedOtp) {
            \Log::info('OTP mismatch', [
                'request_otp' => $otp,
                'stored_otp' => $storedOtp,
                'request_otp_type' => gettype($otp),
                'stored_otp_type' => gettype($storedOtp),
                'request_data' => $request->all()
            ]);
            
            return back()
                ->with('otp_mode', true)
                ->with('otp_email', $storedEmail)
                ->withErrors([
                    'otp1' => 'Invalid OTP. Please try again.',
                ]);
        }

        // Find user and login
        $user = User::where('email', $storedEmail)->first();
        if (!$user) {
            \Log::info('User not found', ['email' => $storedEmail]);
            
            return back()
                ->with('otp_mode', false)
                ->withErrors([
                    'email' => 'User not found. Please login again.',
                ]);
        }

        \Log::info('About to login user', ['user_id' => $user->id, 'email' => $user->email]);

        Auth::login($user);
        Session::regenerate();

        \Log::info('User logged in successfully', ['user_id' => $user->id, 'is_authenticated' => Auth::check()]);

        // Clear OTP from session
        Session::forget(['login_otp', 'login_otp_email', 'login_otp_expires_at']);

        \Log::info('Redirecting to dashboard', ['dashboard_route' => route('dashboard')]);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle OTP resend.
     */
    private function handleResendOtp(Request $request): RedirectResponse
    {
        $email = Session::get('login_otp_email');
        
        if (!$email) {
            return back()
                ->with('otp_mode', false)
                ->withErrors([
                    'email' => 'Session expired. Please login again.',
                ]);
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update OTP in session
        Session::put('login_otp', $otp);
        Session::put('login_otp_expires_at', now()->addMinutes(10));

        // Send OTP email
        try {
            Mail::raw("Your login OTP is: {$otp}\n\nThis OTP will expire in 10 minutes.", function($message) use ($email) {
                $message->to($email)
                        ->subject('Your Login OTP - HR3 Lumino');
            });
        } catch (\Exception $e) {
            return back()
                ->with('otp_mode', true)
                ->with('otp_email', $email)
                ->withErrors([
                    'otp' => 'Failed to resend OTP. Please try again.',
                ]);
        }

        return back()
            ->with('otp_mode', true)
            ->with('otp_email', $email)
            ->with('success', 'OTP has been resent to your email.');
    }

    /**
     * Display the registration view.
     */
    public function register(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'position' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'department' => $request->department,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect(route('login'))->with('success', 'Account created successfully! Please login with your credentials.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
