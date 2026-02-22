<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Title --}}
    <link rel="icon" type="image/png" href="https://example.com/favicon.png">
    <title>Hr3 Lumino - Register</title>

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
        
        .delay-1 { animation-delay: 0.5s; }
        .delay-2 { animation-delay: 1s; }
        .delay-3 { animation-delay: 1.5s; }
        .delay-4 { animation-delay: 2s; }
        .delay-5 { animation-delay: 2.5s; }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
            opacity: 1 !important;
        }
        
        .modal-content {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .btn-disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    
    <!-- Logo Animation Container -->
    <div id="logoContainer" class="fixed inset-0 flex items-center justify-center z-50 bg-white" style="display: none;">
        <div class="text-center">
            <div class="logo-pulse mb-6">
                <img src="{{ asset('images/newlogo.svg') }}" alt="HR3 Lumino" class="w-32 h-32 mx-auto">
            </div>
            <div class="text-gray-600 text-lg fade-in-up">
                <span class="animate-spin inline-block w-4 h-4 border-2 border-gray-300 border-t-blue-600 rounded-full mr-2"></span>
                Creating your account...
            </div>
        </div>
    </div>

    <!-- Main Register Form -->
    <div id="registerForm" class="w-full max-w-sm opacity-0">
        <div class="glass-effect rounded-2xl shadow-2xl p-4">
            <!-- Logo and Title -->
            <div class="text-center mb-4 fade-in-up">
                <img src="{{ asset('images/newlogo.svg') }}" alt="HR3 Lumino" class="w-20 h-20 mx-auto mb-4">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Join HR3 Lumino</h1>
                <p class="text-gray-600">Create your account to get started</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register.store') }}" class="space-y-3 fade-in-up delay-1">
                @csrf
                
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-xs font-medium text-gray-700 mb-1">
                        <span class="mr-2">👤</span>Full Name
                    </label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autocomplete="name"
                        autofocus
                        class="input-focus w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <span class="mr-1">⚠️</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                        <span class="mr-2">📧</span>Email Address
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email"
                        class="input-focus w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <span class="mr-1">⚠️</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Position Field -->
                <div>
                    <label for="position" class="block text-xs font-medium text-gray-700 mb-1">
                        <span class="mr-2">💼</span>Position
                    </label>
                    <input 
                        id="position" 
                        type="text" 
                        name="position" 
                        value="{{ old('position') }}" 
                        required 
                        autocomplete="organization-title"
                        class="input-focus w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                    >
                    @error('position')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <span class="mr-1">⚠️</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Department Field -->
                <div>
                    <label for="department" class="block text-xs font-medium text-gray-700 mb-1">
                        <span class="mr-2">🏢</span>Department
                    </label>
                    <select 
                        id="department" 
                        name="department" 
                        required 
                        class="input-focus w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                    >
                        <option value="">Select Department</option>
                        <option value="Human Resource" {{ old('department') == 'Human Resource' ? 'selected' : '' }}>Human Resource</option>
                        <option value="Core Human" {{ old('department') == 'Core Human' ? 'selected' : '' }}>Core Human</option>
                        <option value="Logistics" {{ old('department') == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                        <option value="Financial" {{ old('department') == 'Financial' ? 'selected' : '' }}>Financial</option>
                        <option value="Administration" {{ old('department') == 'Administration' ? 'selected' : '' }}>Administration</option>
                    </select>
                    @error('department')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <span class="mr-1">⚠️</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                        <span class="mr-2">🔒</span>Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="input-focus w-full px-2 py-1 pr-8 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <span id="eyeIcon">👁️</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <span class="mr-1">⚠️</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">
                        <span class="mr-2">🔐</span>Confirm Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="input-focus w-full px-2 py-1 pr-8 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-transparent"
                        >
                        <button 
                            type="button" 
                            id="toggleConfirmPassword"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <span id="eyeIconConfirm">👁️</span>
                        </button>
                    </div>
                </div>

                <!-- Terms and Submit -->
                <div class="space-y-2 fade-in-up delay-2">
                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <input 
                            type="checkbox" 
                            id="terms" 
                            name="terms" 
                            required
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1"
                        >
                        <label for="terms" class="ml-2 text-sm text-gray-700">
                            I agree to the <a href="#" onclick="openModal('termsModal'); return false;" class="text-blue-600 hover:text-blue-800 font-semibold">Terms and Conditions</a> 
                            and <a href="#" onclick="openModal('privacyModal'); return false;" class="text-blue-600 hover:text-blue-800 font-semibold">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-hover w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2 px-3 rounded text-sm font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 transform transition-all duration-200 hover:scale-[1.02]"
                    >
                        <span class="mr-2">👤</span>
                        Register
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            @if (Route::has('login'))
                <div class="text-center mt-6 fade-in-up delay-3">
                    <p class="text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                            Sign in here
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-white fade-in-up delay-4">
            <p class="text-sm opacity-80">
                <span class="mr-2">🛡️</span>
                Secure registration powered by HR3 Lumino
            </p>
        </div>
    </div>

    <script>
                
        // Logo animation and form reveal
        setTimeout(() => {
            const logoContainer = document.getElementById('logoContainer');
            const registerForm = document.getElementById('registerForm');
            
            // Fade out logo container
            logoContainer.style.transition = 'opacity 0.5s ease-out';
            logoContainer.style.opacity = '0';
            
            setTimeout(() => {
                logoContainer.style.display = 'none';
                
                // Show register form with animations
                registerForm.style.transition = 'opacity 0.5s ease-in';
                registerForm.style.opacity = '1';
                
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

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.textContent = '🙈';
            } else {
                eyeIcon.textContent = '👁️';
            }
        });

        // Confirm password visibility toggle
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIconConfirm.textContent = '🙈';
            } else {
                eyeIconConfirm.textContent = '👁️';
            }
        });

        // Add input focus effects
        document.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('transform', 'scale-[1.02]');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('transform', 'scale-[1.02]');
            });
        });

        // Real-time password validation
        passwordInput.addEventListener('input', function() {
            const confirmPassword = document.getElementById('password_confirmation');
            if (confirmPassword.value && this.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });

        confirmPasswordInput.addEventListener('input', function() {
            const password = document.getElementById('password');
            if (this.value !== password.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('show');
                }
            });
        });

                const termsCheckbox = document.getElementById('terms');
        const submitButton = document.querySelector('button[type="submit"]');

        function updateSubmitButton() {
            if (termsCheckbox.checked) {
                submitButton.classList.remove('btn-disabled');
                submitButton.disabled = false;
            } else {
                submitButton.classList.add('btn-disabled');
                submitButton.disabled = true;
            }
        }

        termsCheckbox.addEventListener('change', updateSubmitButton);

        // Initialize submit button state
        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.disabled = false;
    </script>

    <!-- Terms and Conditions Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">
                    <span class="mr-2">📄</span>Terms and Conditions
                </h3>
                <button onclick="closeModal('termsModal')" class="text-gray-500 hover:text-gray-700">
                    <span class="text-xl">❌</span>
                </button>
            </div>
            <div class="text-gray-600 space-y-3">
                <p>
                    <strong>Data Collection and Usage:</strong> By creating an account with HR3 Lumino, you agree to the collection and processing of your personal data including but not limited to your name, email address, position, department, and employment-related information.
                </p>
                <p>
                    <strong>Data Storage:</strong> Your data will be securely stored in our encrypted database and will be used solely for HR management purposes, including attendance tracking, leave management, and organizational administration.
                </p>
                <p>
                    <strong>Data Handling:</strong> We implement industry-standard security measures to protect your personal information. Your data will not be shared with third parties without your explicit consent, except as required by law.
                </p>
                <p>
                    <strong>User Responsibilities:</strong> You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.
                </p>
                <p>
                    <strong>Agreement:</strong> By checking the agreement box and creating an account, you confirm that you have read, understood, and agree to these terms and conditions.
                </p>
            </div>
            <div class="mt-6 text-right">
                <button onclick="closeModal('termsModal')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    I Understand
                </button>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">
                    <span class="mr-2">🔒</span>Privacy Policy
                </h3>
                <button onclick="closeModal('privacyModal')" class="text-gray-500 hover:text-gray-700">
                    <span class="text-xl">❌</span>
                </button>
            </div>
            <div class="text-gray-600 space-y-3">
                <p>
                    <strong>Information We Collect:</strong> HR3 Lumino collects personal information necessary for employment management, including your full name, email address, position, department, and work-related data.
                </p>
                <p>
                    <strong>How We Use Your Data:</strong> Your information is used exclusively for HR functions such as payroll processing, attendance management, leave requests, performance tracking, and internal communications.
                </p>
                <p>
                    <strong>Data Security:</strong> We employ advanced encryption and security protocols to protect your data. All personal information is stored in secure servers with restricted access.
                </p>
                <p>
                    <strong>Data Retention:</strong> Your data will be retained for as long as necessary to fulfill the purposes outlined in this policy, unless a longer retention period is required or permitted by law.
                </p>
                <p>
                    <strong>Your Rights:</strong> You have the right to access, update, or request deletion of your personal data, subject to applicable laws and company policies.
                </p>
                <p>
                    <strong>Consent:</strong> By proceeding with registration, you explicitly consent to the collection and processing of your data as described in this privacy policy.
                </p>
            </div>
            <div class="mt-6 text-right">
                <button onclick="closeModal('privacyModal')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    I Understand
                </button>
            </div>
        </div>
    </div>
</body>
</html>