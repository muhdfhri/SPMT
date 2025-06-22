@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-12">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-cyan-400/20 to-blue-400/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Glass morphism card -->
        <div class="backdrop-blur-xl bg-white/80 dark:bg-gray-900/80 rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
            <!-- Header section -->
            <div class="px-8 pt-8 pb-6 text-center">
                <!-- Company Logo -->
                <div class="mx-auto w-48 h-auto mb-2">
                    <a href="{{ url('/') }}" class="block">
                        <img src="{{ asset('images/logo.PNG') }}" alt="Company Logo" class="w-full max-w-xs h-auto hover:opacity-90 transition-opacity duration-200">
                    </a>
                </div>
                
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                    {{ __('Daftar Akun') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                    {{ __('Buat akun baru untuk melanjutkan') }}
                </p>
            </div>

            <!-- Form section -->
            <div class="px-8 pb-12">

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <!-- Hidden role field -->
                <input type="hidden" name="role" value="mahasiswa">

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">{{ __('Nama Lengkap') }}</label>
                    <input id="name" type="text" class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">{{ __('No. HP') }}</label>
                    <input id="phone" type="text" class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] @error('phone') border-red-500 @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                    @error('phone')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">{{ __('Email') }}</label>
                    <input id="email" type="email" class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">{{ __('Password') }}</label>
                    <div class="relative">
                        <input id="password" type="password" class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="eyeIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gunakan 8 atau lebih karakter, dengan perpaduan huruf besar, huruf kecil, angka & simbol.</p>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">{{ __('Konfirmasi Password') }}</label>
                    <div class="relative">
                        <input id="password-confirm" type="password" class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC]" name="password_confirmation" required autocomplete="new-password">
                        <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="eyeIconConfirm">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex items-start">
                        <input type="checkbox" name="terms" id="terms" required class="mt-1 mr-2">
                        <label for="terms" class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            {{ __('Saya menyatakan bahwa seluruh informasi yang telah saya isi adalah benar dan lengkap sesuai kenyataan') }}
                        </label>
                    </div>
                    @error('terms')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <button type="submit" id="registerBtn" class="w-full px-4 py-2 bg-gradient-to-r from-[#0E73B9] to-[#55B7E3] text-white rounded-md hover:shadow-lg transition duration-300">
                        {{ __('Daftar') }}
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        {{ __('Sudah terdaftar? Klik') }} 
                        <a href="{{ route('login') }}" class="text-[#0E73B9] dark:text-[#55B7E3]">
                            <span class="hover:underline">{{ __('di sini') }}</span>
                        </a> 
                        {{ __('untuk login.') }}
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer text -->
    <div class="mt-8 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            © 2025 SPMT - Pelindo Multi Terminal. Dilindungi dengan enkripsi end-to-end.
        </p>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            if (type === 'text') {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });

        // Toggle Confirm Password Visibility
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('password-confirm');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        toggleConfirmPassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            
            // Toggle the eye icon
            if (type === 'text') {
                eyeIconConfirm.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                eyeIconConfirm.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });

        // Handle form submission
        const registerForm = document.getElementById('registerForm');
        const registerBtn = document.getElementById('registerBtn');
        const originalBtnText = registerBtn.innerHTML;

        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Debug: Log form data before submission
            const formData = new FormData(registerForm);
            console.log('Form data before submission:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            // Disable button and show loading state
            registerBtn.disabled = true;
            registerBtn.innerHTML = `
                <div class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
                </div>
            `;

            // Submit the form
            fetch(registerForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrasi Berhasil',
                        text: 'Silakan login untuk mengakses dashboard.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0E73B9'
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });
                } else {
                    // Show validation errors
                    if (data.errors) {
                        let errorMessage = '';
                        Object.keys(data.errors).forEach(key => {
                            errorMessage += data.errors[key][0] + '<br>';
                        });
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Registrasi Gagal',
                            html: errorMessage,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#0E73B9'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registrasi Gagal',
                            text: data.message || 'Terjadi kesalahan saat registrasi',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#0E73B9'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Registrasi Gagal',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0E73B9'
                });
            })
            .finally(() => {
                // Reset button state
                registerBtn.disabled = false;
                registerBtn.innerHTML = originalBtnText;
            });
        });
    });
</script>

<!-- Add Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection