<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lowongan Magang - SPMT')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/webicon-spmt.jpg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/webicon-spmt.jpg') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
    <style>
        /* TinyMCE Content Styling */
        .prose {
            color: inherit !important;
            max-width: 100%;
        }
        .prose p,
        .prose ul,
        .prose ol,
        .prose li {
            margin: 0.75em 0;
            line-height: 1.7;
            color: inherit;
        }
        .prose ul,
        .prose ol {
            padding-left: 1.5em;
        }
        .prose ul {
            list-style-type: disc;
        }
        .prose ol {
            list-style-type: decimal;
        }
        .prose a {
            color: #0E73B9;
            text-decoration: underline;
        }
        .prose a:hover {
            color: #0a5a94;
        }
        .dark .prose a {
            color: #55B7E3;
        }
        .dark .prose a:hover {
            color: #7ac8f0;
        }
        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: inherit;
            font-weight: 600;
            margin-top: 1.5em;
            margin-bottom: 0.75em;
            line-height: 1.3;
        }
        .prose h1 { font-size: 1.75em; }
        .prose h2 { font-size: 1.5em; }
        .prose h3 { font-size: 1.25em; }
        .prose h4 { font-size: 1.125em; }
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.375rem;
            margin: 1.5em 0;
        }
        
    </style>
    @stack('styles')
</head>
<body class="antialiased min-h-screen flex flex-col bg-white dark:bg-[#161615] text-gray-900 dark:text-gray-100">
    <div class="wrapper flex-1">
        <!-- Navbar -->
        <div class="w-full">
            <header id="navbar" class="bg-white dark:bg-[#161615] fixed top-4 left-4 right-4 z-50 shadow-sm rounded-lg">
                <div class="w-full mx-auto px-2 sm:px-4">
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
                            <a href="{{ url('/#faq') }}" class="text-[16px] text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#0E73B9] dark:hover:text-[#55B7E3] font-medium transition-colors duration-200 px-3 py-2 rounded-md hover:bg-[#F8F8F7] dark:hover:bg-gray-800">FAQ</a>
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
                                        <a href="#" onclick="showLogoutConfirmation(event)" class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Keluar
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
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
                            <button id="mobile-menu-button" class="md:hidden ml-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0E73B9]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1b1b18] dark:text-[#EDEDEC]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
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
                            <a href="{{ url('/#faq') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
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

        <div class="container mx-auto px-4" bis_skin_checked="1">
<footer class="relative w-full bg-bg-light dark:bg-bg-dark border-t border-gray-200 dark:border-gray-800">
        <div class="w-full px-4 md:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
                <!-- Company Info with Contact Details -->
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold mb-3 text-text-primary dark:text-text-dark-primary">SPMT - Pelindo Multi Terminal</h3>
                    <p class="text-text-secondary dark:text-text-dark-secondary mb-6 leading-relaxed">
                        Platform digital untuk mengelola proses magang mahasiswa, mulai dari pendaftaran, seleksi, hingga pelaporan selama program magang.
                    </p>
                    
                    <!-- Contact Information -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-start group">
                            <div class="flex-shrink-0 w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary mb-1">Alamat</p>
                                <p class="text-text-secondary dark:text-text-dark-secondary">Jl. Lingkar Pelabuhan No. 1, Belawan, Medan 20411</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start group">
                            <div class="flex-shrink-0 w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary mb-1">Telepon</p>
                                <a href="tel:+62614100055" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors">(061) 41000055</a>
                            </div>
                        </div>
                        
                        <div class="flex items-start group">
                            <div class="flex-shrink-0 w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary mb-1">Email</p>
                                <a href="mailto:multi.terminal@pelindo.co.id" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors">multi.terminal@pelindo.co.id</a>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/PTPelindoMultiTerminal" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                            </svg>
                        </a>
                        <a href="https://x.com/Pelindo_SPMT" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/pelindomultiterminal" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/company/pelindospmt/" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                            </svg>
                        </a>
                        <a href="https://www.youtube.com/c/PelindoMultiTerminal" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-text-primary dark:text-text-dark-primary">Tautan Cepat</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Beranda
                        </a></li>
                        <li><a href="#about" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Tentang Kami
                        </a></li>
                        <li><a href="#jobs" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Lowongan Magang
                        </a></li>
                        <li><a href="#faq" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            FAQ
                        </a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-text-primary dark:text-text-dark-primary">Sumber Daya</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Panduan Magang
                        </a></li>
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Tips Wawancara
                        </a></li>
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Blog
                        </a></li>
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Berita
                        </a></li>
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Acara
                        </a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="border-t border-border-light dark:border-border-dark pt-8 mt-12">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <p class="text-text-secondary dark:text-text-dark-secondary text-sm">
                            &copy; <span id="currentYear"></span> SPMT - Pelindo. Hak Cipta Dilindungi.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-6">
                        <a href="#" class="text-text-secondary dark:text-text-dark-secondary text-sm hover:text-primary dark:hover:text-primary-light transition-colors duration-200">Kebijakan Privasi</a>
                        <a href="#" class="text-text-secondary dark:text-text-dark-secondary text-sm hover:text-primary dark:hover:text-primary-light transition-colors duration-200">Syarat & Ketentuan</a>
                        <a href="#" class="text-text-secondary dark:text-text-dark-secondary text-sm hover:text-primary dark:hover:text-primary-light transition-colors duration-200">Peta Situs</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

    <!-- Logout Modal Component -->
    @auth
        @include('components.logout-modal')
    @endauth

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');

            // Mobile menu toggle handler
            function toggleMobileMenu() {
                mobileMenu.classList.toggle('hidden');
                document.body.style.overflow = mobileMenu.classList.contains('hidden') ? '' : 'hidden';
            }

            // Toggle mobile menu when button is clicked
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', toggleMobileMenu);
            }

            // Close mobile menu when close button is clicked
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function(e) {
                    e.preventDefault();
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                });
            }

            if (mobileMenuBackdrop) {
                mobileMenuBackdrop.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                });
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
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
