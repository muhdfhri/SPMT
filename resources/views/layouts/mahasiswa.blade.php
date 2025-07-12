<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SPMT') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#55B7E3',
                        secondary: '#0E73B9'
                    }
                }
            }
        }
    </script>

    <!-- Add this to ensure Tailwind is properly loaded -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/webicon-spmt.jpg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/webicon-spmt.jpg') }}" type="image/x-icon">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Global notification function -->
    <script>
        window.showToast = function(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            
            Toast.fire({
                icon: type,
                title: message
            });
        };
    </script>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="wrapper flex-1">
        <!-- Navbar -->
        <div class="w-full">
            <header id="navbar" class="bg-white dark:bg-[#161615] fixed top-2 left-2 right-2 z-50 shadow-sm rounded-lg">
                <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Logo -->
                        <a href="{{ url('/') }}" class="flex items-center h-16 -my-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="max-h-28 w-auto object-contain" style="max-width:220px;">
                        </a>
                        
                        <!-- Center Navigation -->
                        <nav class="hidden md:flex flex-1 justify-center items-center space-x-2">
                            <a href="{{ route('internships.index') }}" class="text-[16px] text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#0E73B9] dark:hover:text-[#55B7E3] font-medium transition-colors duration-200 px-3 py-2 rounded-md hover:bg-[#F8F8F7] dark:hover:bg-gray-800 {{ request()->routeIs('internships.index') ? 'text-[#0E73B9] dark:text-[#55B7E3]' : '' }}">Cari Lowongan</a>
                            <a href="{{ route('about.index') }}" class="text-[16px] text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#0E73B9] dark:hover:text-[#55B7E3] font-medium transition-colors duration-200 px-3 py-2 rounded-md hover:bg-[#F8F8F7] dark:hover:bg-gray-800 {{ request()->routeIs('about.index') ? 'text-[#0E73B9] dark:text-[#55B7E3]' : '' }}">Tentang Magang Reguler</a>
                            <a href="{{ route('divisions.index') }}" class="text-[16px] text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#0E73B9] dark:hover:text-[#55B7E3] font-medium transition-colors duration-200 px-3 py-2 rounded-md hover:bg-[#F8F8F7] dark:hover:bg-gray-800 {{ request()->routeIs('divisions.index') ? 'text-[#0E73B9] dark:text-[#55B7E3]' : '' }}">List Divisi</a>
                            <a href="#" class="text-[16px] text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#0E73B9] dark:hover:text-[#55B7E3] font-medium transition-colors duration-200 px-3 py-2 rounded-md hover:bg-[#F8F8F7] dark:hover:bg-gray-800">FAQ</a>
                        </nav>
                        
                        <!-- Right: Login/Account -->
                        <div class="flex items-center space-x-2">
                            @auth
                                <!-- User Dropdown -->
                                <div class="relative group">
                                    <button id="user-menu-button" class="flex items-center space-x-2 px-3 py-2 rounded-lg border border-[#0E73B9] hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-[#0E73B9] dark:focus:ring-[#55B7E3] transition-all duration-300">
                                        @php 
                                            $profilePhoto = Auth::user()->studentProfile && Auth::user()->studentProfile->profile_photo 
                                                ? asset('storage/' . Auth::user()->studentProfile->profile_photo) 
                                                : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=128'; 
                                        @endphp
                                        <img src="{{ $profilePhoto }}" alt="Foto Profil" class="w-8 h-8 rounded-full object-cover border-2 border-blue-500 bg-gray-200" />
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ Auth::user()->name }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="user-menu-dropdown" class="hidden group-hover:block absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-1 z-10">
                                        <a href="{{ route('profile') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Lihat Profil
                                        </a>
                                        <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Aktivitas
                                        </a>
                                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                        <a href="#" onclick="showLogoutConfirmation(event)" class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Keluar
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#0E73B9] to-[#55B7E3] text-white rounded-md text-sm font-medium shadow-sm hover:shadow-lg transform hover:-translate-y-0.5 transition duration-300 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Masuk
                                </a>
                            @endauth
                            
                            <!-- Mobile menu button -->
                            <div class="flex items-center md:hidden">
                                <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#0E73B9]" aria-expanded="false">
                                    <span class="sr-only">Buka menu utama</span>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

                        <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden fixed inset-0 z-50">
                <!-- Backdrop -->
                <div id="mobile-menu-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
                
                <!-- Mobile menu panel -->
                <div class="fixed inset-y-0 right-0 w-full max-w-xs bg-white dark:bg-[#161615] shadow-xl overflow-y-auto">
                    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                        </a>
                        <button id="mobile-menu-close" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="px-4 py-6">
                        <nav class="space-y-3">
                            <a href="{{ route('internships.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('internships.index') ? 'text-[#0E73B9] dark:text-[#55B7E3] bg-blue-50 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Cari Lowongan
                            </a>
                            <a href="{{ route('about.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('about.index') ? 'text-[#0E73B9] dark:text-[#55B7E3] bg-blue-50 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Tentang Magang Reguler
                            </a>
                            <a href="{{ route('divisions.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('divisions.index') ? 'text-[#0E73B9] dark:text-[#55B7E3] bg-blue-50 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                List Divisi
                            </a>
                            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                FAQ
                            </a>
                        </nav>
                        
                        <div class="mt-8">
                            @auth
                                <div class="flex items-center space-x-3 px-3 py-4 border-t border-gray-200 dark:border-gray-700">
                                    @php 
                                        $profilePhoto = Auth::user()->studentProfile && Auth::user()->studentProfile->profile_photo 
                                            ? asset('storage/' . Auth::user()->studentProfile->profile_photo) 
                                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=128'; 
                                    @endphp
                                    <img src="{{ $profilePhoto }}" alt="{{ Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover border-2 border-blue-500">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="{{ route('profile') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        Lihat Profil
                                    </a>
                                    <a href="{{ route('mahasiswa.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        Aktivitas
                                    </a>
                                    <a href="#" onclick="showLogoutConfirmation(event)" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        Keluar
                                    </a>
                                </div>
                            @else
                                <div class="mt-6">
                                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gradient-to-r from-[#0E73B9] to-[#55B7E3] hover:from-[#0c63a4] hover:to-[#4a9dca]">
                                        Masuk
                                    </a>
                                    <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                        Belum punya akun? 
                                        <a href="{{ route('register') }}" class="font-medium text-[#0E73B9] dark:text-[#55B7E3] hover:text-[#0c63a4] dark:hover:text-[#4a9dca]">
                                            Daftar
                                        </a>
                                    </p>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 pt-20">
            @yield('content')
        </main>

    @auth
        @include('components.logout-modal')
    @endauth

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
            const mobileMenuClose = document.getElementById('mobile-menu-close');

            // Function to close mobile menu
            function closeMobileMenu() {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            }

            // Function to open mobile menu
            function openMobileMenu() {
                mobileMenu.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            // Toggle mobile menu
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', openMobileMenu);
            }

            // Close mobile menu when clicking close button
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', closeMobileMenu);
            }

            // Close mobile menu when clicking on backdrop
            if (mobileMenuBackdrop) {
                mobileMenuBackdrop.addEventListener('click', closeMobileMenu);
            }

            // Close mobile menu when clicking on a link
            const mobileLinks = document.querySelectorAll('#mobile-menu a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                });
            });

            // Dark mode toggle
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const html = document.documentElement;

            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                html.classList.add('dark');
                if (darkModeToggle) {
                    darkModeToggle.checked = true;
                }
            } else {
                html.classList.remove('dark');
                if (darkModeToggle) {
                    darkModeToggle.checked = false;
                }
            }

            if (darkModeToggle) {
                darkModeToggle.addEventListener('change', function() {
                    if (this.checked) {
                        html.classList.add('dark');
                        localStorage.theme = 'dark';
                    } else {
                        html.classList.remove('dark');
                        localStorage.theme = 'light';
                    }
                });
            }
        });

        // Logout Modal Functions
        function showLogoutConfirmation(event) {
            event.preventDefault();
            const modal = document.getElementById('logout-modal');
            const modalContent = document.getElementById('modal-content');

            if (modal && modalContent) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                // Trigger animation
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }


        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('logout-modal');
            const modalContent = document.getElementById('modal-content');
            
            if (event.target === modal) {
                hideLogoutConfirmation();
            }
        });
    </script>
    @stack('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        // Pastikan Alpine.js sudah terinisialisasi
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized');
        });
        
        // Debug: Cek apakah komponen notifikasi terdaftar
        console.log('Notification bell component:', document.querySelector('[x-data^="notificationBell"]'));
    </script>


</body>
</html>