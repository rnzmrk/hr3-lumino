@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Profile Settings</h2>
            <p class="mt-1 text-sm text-gray-600">Update your account information and preferences.</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            <!-- Profile Picture -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">Profile Picture</label>
                <div class="flex items-center space-x-6">
                    <div class="shrink-0">
                        @if($user->profile_picture)
                            @php
                                $imagePath = 'storage/profile-pictures/' . $user->profile_picture;
                                $fullPath = public_path($imagePath);
                            @endphp
                            @if(file_exists($fullPath))
                                <img src="{{ asset($imagePath) }}" alt="Profile" class="h-20 w-20 object-cover rounded-full">
                            @else
                                <div class="h-20 w-20 bg-gray-300 rounded-full flex items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <p class="text-xs text-red-500 mt-1">File not found</p>
                            @endif
                        @else
                            <div class="h-20 w-20 bg-gray-300 rounded-full flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block">
                            <span class="sr-only">Choose profile picture</span>
                            <input 
                                type="file" 
                                name="profile_picture" 
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            >
                        </label>
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        @if($user->profile_picture)
                            <p class="mt-1 text-xs text-gray-500">Current: {{ $user->profile_picture }}</p>
                            <p class="mt-1 text-xs text-gray-500">Path: {{ asset('storage/profile-pictures/' . $user->profile_picture) }}</p>
                        @endif
                    </div>
                </div>
                @error('profile_picture')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user->name) }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    required
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    required
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Position -->
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                <input 
                    type="text" 
                    id="position" 
                    name="position" 
                    value="{{ old('position', $user->position ?? '') }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    placeholder="e.g. Software Developer"
                >
                @error('position')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Department -->
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <select 
                    id="department" 
                    name="department" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
                    <option value="">Select Department</option>
                    <option value="Human Resource" {{ old('department', $user->department ?? '') == 'Human Resource' ? 'selected' : '' }}>Human Resource</option>
                    <option value="Core Human" {{ old('department', $user->department ?? '') == 'Core Human' ? 'selected' : '' }}>Core Human</option>
                    <option value="Logistics" {{ old('department', $user->department ?? '') == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                    <option value="Financial" {{ old('department', $user->department ?? '') == 'Financial' ? 'selected' : '' }}>Financial</option>
                    <option value="Administration" {{ old('department', $user->department ?? '') == 'Administration' ? 'selected' : '' }}>Administration</option>
                </select>
                @error('department')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Success Message -->
            @if (session('status') === 'profile-updated')
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">Profile updated successfully!</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Save Changes
                </button>
            </div>
        </form>

        <!-- Delete Account Section -->
        <div class="border-t border-gray-200 px-6 py-4">
            <h3 class="text-lg font-medium text-red-600">Delete Account</h3>
            <p class="mt-1 text-sm text-gray-600">Once you delete your account, there is no going back. Please be certain.</p>
            
            <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                
                <button 
                    type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Delete Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
