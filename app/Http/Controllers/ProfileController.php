<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     */
    public function edit(): View
    {
        $user = request()->user();
        
        // Debug profile picture
        \Log::info('Profile picture debug', [
            'user_id' => $user->id,
            'profile_picture' => $user->profile_picture,
            'file_exists' => $user->profile_picture ? Storage::disk('public')->exists('profile-pictures/' . $user->profile_picture) : false,
            'storage_path' => $user->profile_picture ? 'profile-pictures/' . $user->profile_picture : null,
            'public_url' => $user->profile_picture ? asset('storage/profile-pictures/' . $user->profile_picture) : null
        ]);
        
        return view('admin.profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // Debug upload attempt
        \Log::info('Profile update attempt', [
            'has_file' => $request->hasFile('profile_picture'),
            'all_files' => $request->allFiles(),
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            \Log::info('Processing profile picture', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'generated_filename' => $filename
            ]);
            
            // Delete old profile picture if exists
            if ($request->user()->profile_picture) {
                Storage::disk('public')->delete('profile-pictures/' . $request->user()->profile_picture);
            }
            
            // Store new profile picture
            $path = $file->storeAs('profile-pictures', $filename, 'public');
            
            \Log::info('Profile picture stored', [
                'storage_path' => $path,
                'filename_saved' => $filename,
                'file_exists_after' => Storage::disk('public')->exists('profile-pictures/' . $filename)
            ]);
            
            // Update user profile picture directly
            $user = $request->user();
            $user->profile_picture = $filename;
            $user->save();
            
            \Log::info('Profile picture saved to database', [
                'user_id' => $user->id,
                'profile_picture' => $user->profile_picture
            ]);
            
            // Remove from validated array to avoid conflicts
            unset($validated['profile_picture']);
        } else {
            \Log::info('No profile picture file uploaded');
        }

        // Update other profile fields
        $request->user()->update($validated);

        \Log::info('Profile updated', [
            'user_id' => $request->user()->id,
            'profile_picture_in_db' => $request->user()->fresh()->profile_picture
        ]);

        return redirect()->route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete('profile-pictures/' . $user->profile_picture);
        }

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
