<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Title --}}
    <link rel="icon" type="image/png" href="">
    <title>Hr3 Lumino - Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes logoPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }
        
        @keyframes gradientShift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .logo-pulse {
            animation: logoPulse 2s ease-in-out infinite;
        }
        
        .gradient-bg {
            background: linear-gradient(-45deg, #87CEEB, #E0F6FF, #F0F8FF, #D3D3D3, #FFFFFF);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }
        
        .btn-hover {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-hover:hover::before {
            left: 100%;
        }

        .otp-input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .otp-input-full {
            width: 100%;
            max-width: 200px;
            height: 40px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .otp-input:focus, .otp-input-full:focus {
            border-color: #3b82f6;
            outline: none;
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .delay-1 { animation-delay: 0.5s; }
        .delay-2 { animation-delay: 1s; }
        .delay-3 { animation-delay: 1.5s; }
        .delay-4 { animation-delay: 2s; }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    
    <!-- Logo Animation Container -->
    <div id="logoContainer" class="fixed inset-0 flex items-center justify-center z-50 bg-white">
        <div class="text-center">
            <div class="logo-pulse mb-6">
                <img src="{{ asset('images/newlogo.svg') }}" alt="HR3 Lumino" class="w-32 h-32 mx-auto">
            </div>
            <div class="text-gray-600 text-lg fade-in-up">
                <i class="bi bi-arrow-clockwise animate-spin mr-2"></i>
                Loading...
            </div>
        </div>
    </div>

    <!-- Main Login Form -->
    <div id="loginForm" class="w-full max-w-sm opacity-0">
        <div class="glass-effect rounded-2xl shadow-2xl p-4">
            <!-- Logo and Title -->
            <div class="text-center mb-3 fade-in-up">
                <img src="{{ asset('images/newlogo.svg') }}" alt="HR3 Lumino" class="w-20 h-20 mx-auto mb-4">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h1>
                <p class="text-gray-600">Sign in to your HR3 Lumino account</p>
            </div>

            <!-- Login Form -->
            @if(session('otp_mode'))
                <!-- OTP Verification Form -->
                <form method="POST" action="{{ route('authenticate') }}" class="space-y-4 fade-in-up delay-1">
                    @csrf
                    <input type="hidden" name="otp_verification" value="1">
                    
                    <!-- OTP Title -->
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mb-3">
                            <i class="bi bi-shield-lock text-white text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Verify OTP</h2>
                        <p class="text-gray-600 text-sm">Enter the 6-digit code sent to your email</p>
                        @if(session('otp_email'))
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="bi bi-envelope mr-1"></i>{{ session('otp_email') }}
                            </p>
                        @endif
                    </div>

                    @error('otp')
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="bi bi-exclamation-triangle text-red-500 mr-2"></i>
                                <span class="text-red-700 text-sm">{{ $message }}</span>
                            </div>
                        </div>
                    @enderror

                    <!-- OTP Input Fields -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-3 text-center">
                            Enter OTP Code
                        </label>
                        <div class="flex justify-center space-x-2">
                            <input type="text" name="otp1" maxlength="6" class="otp-input-full" autocomplete="off" required placeholder="Enter 6-digit OTP">
                        </div>
                        <p class="text-xs text-gray-500 text-center mt-2">
                            OTP will expire in 10 minutes
                        </p>
                    </div>

                    <!-- Verify Button -->
                    <div class="fade-in-up delay-2">
                        <button 
                            type="submit" 
                            class="btn-hover w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-2 px-3 rounded text-sm font-semibold hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 transform transition-all duration-200 hover:scale-[1.02]"
                        >
                            <i class="bi bi-check-circle mr-2"></i>
                            Verify OTP
                        </button>
                    </div>
                </form>

                <!-- Action Buttons (Outside main form) -->
                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                    <form method="POST" action="{{ route('authenticate') }}" class="inline">
                        @csrf
                        <input type="hidden" name="resend_otp" value="1">
                        <button 
                            type="submit" 
                            class="text-blue-600 hover:text-blue-800 font-medium text-xs transition-colors"
                        >
                            <i class="bi bi-arrow-clockwise mr-1"></i>
                            Resend OTP
                        </button>
                    </form>
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 text-xs transition-colors">
                        <i class="bi bi-arrow-left mr-1"></i>
                        Back to Login
                    </a>
                </div>
            @else
                <!-- Regular Login Form -->
                <form method="POST" action="{{ route('authenticate') }}" class="space-y-2 fade-in-up delay-1">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                            <i class="bi bi-envelope mr-2"></i>Email Address
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            autocomplete="email"
                            autofocus
                            class="input-focus w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                            <i class="bi bi-lock mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                autocomplete="current-password"
                                class="input-focus w-full px-2 py-1 pr-8 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                            >
                            <button 
                                type="button" 
                                id="togglePassword"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                            >
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="fade-in-up delay-3">
                        <button 
                            type="submit" 
                            class="btn-hover w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2 px-3 rounded text-sm font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 transform transition-all duration-200 hover:scale-[1.02]"
                        >
                            <i class="bi bi-box-arrow-in-right mr-2"></i>
                            Login
                        </button>
                    </div>
                </form>
            @endif

            <!-- Register Link -->
            @if (Route::has('register'))
                <div class="text-center mt-6 fade-in-up delay-4">
                    <p class="text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-800 font-semibold transition-colors">
                            Sign up here
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-white fade-in-up delay-4">
            <p class="text-sm opacity-80">
                <i class="bi bi-shield-check mr-2"></i>
                Secure login powered by HR3 Lumino
            </p>
        </div>
    </div>

    <script>
        // Logo animation and form reveal
        setTimeout(() => {
            const logoContainer = document.getElementById('logoContainer');
            const loginForm = document.getElementById('loginForm');
            
            // Fade out logo container
            logoContainer.style.transition = 'opacity 0.5s ease-out';
            logoContainer.style.opacity = '0';
            
            setTimeout(() => {
                logoContainer.style.display = 'none';
                
                // Show login form with animations
                loginForm.style.transition = 'opacity 0.5s ease-in';
                loginForm.style.opacity = '1';
                
                // Trigger fade-in animations
                document.querySelectorAll('.fade-in-up').forEach((el, index) => {
                    setTimeout(() => {
                        el.style.opacity = '1';
                    }, index * 100);
                });
            }, 500);
        }, 3000); // 3 seconds logo animation

        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (togglePassword && passwordInput && eyeIcon) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
            });
        }

        // Add input focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                if (this.parentElement) {
                    this.parentElement.classList.add('transform', 'scale-[1.02]');
                }
            });
            
            input.addEventListener('blur', function() {
                if (this.parentElement) {
                    this.parentElement.classList.remove('transform', 'scale-[1.02]');
                }
            });
        });
    </script>
</body>
</html>
