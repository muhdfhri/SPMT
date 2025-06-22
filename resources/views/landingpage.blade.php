@extends('layouts.internship')

<!DOCTYPE html>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPMT - Platform Magang Reguler PT. Pelindo Multi Terminal</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/webicon-spmt.jpg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/webicon-spmt.jpg') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

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
     <!-- Splide CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .process-step {
            transition: all 0.4s ease-in-out;
            transform: translateY(20px);
            opacity: 0;
        }
        .process-step.animate {
            transform: translateY(0);
            opacity: 1;
        }
        .step-number {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(14, 115, 185, 0.1), 0 2px 4px -1px rgba(14, 115, 185, 0.06);
        }
        .process-step:hover .step-number {
            transform: scale(1.1);
            box-shadow: 0 10px 15px -3px rgba(14, 115, 185, 0.2), 0 4px 6px -2px rgba(14, 115, 185, 0.1);
        }
        .step-connector {
            position: absolute;
            top: 2rem;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #0E73B9, #55B7E3);
            transition: width 0.8s ease;
            z-index: 1;
        }
        .process-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 2rem;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }
        @media (max-width: 768px) {
            .process-step:not(:last-child)::after {
                left: 50%;
                top: 100%;
                width: 2px;
                height: 2rem;
            }
        }
    </style>

@section('content')
    <div class="full mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <section class="relative bg-white dark:bg-[#161615] overflow-hidden rounded-2xl shadow max-w-7xl mx-auto">
        <div id="hero-carousel" class="splide rounded-2xl" role="group" aria-label="SPMT Hero Carousel">
            <div class="splide__track">
                <ul class="splide__list">
                    <!-- Carousel Item 1 (Background Image)-->
                    <li class="splide__slide">
                        <div class="relative overflow-hidden bg-cover bg-center h-full rounded-2xl" style="background-image: url('/images/background-hero2.jpg'); min-height: 80vh;">
                                <!-- Enhanced overlay with animated gradient -->
                                <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/60 to-[#0E73B9]/40"></div>

                                <!-- Animated particles background -->
                                <div id="particles-js" class="absolute inset-0 z-10 opacity-40"></div>

                                <!-- Centered content container with enhanced animations -->
                                <div class="flex flex-col items-center justify-center px-8 py-24 md:py-32 w-full mx-auto relative z-20 h-full text-center">
                                    <!-- Animated content with enhanced styling -->
                                    <div class="max-w-4xl mx-auto mb-8">
                                        <!-- Animated badge -->
                                        <div class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6 animate__animated animate__fadeInDown animate__delay-1s">
                                            <span class="text-white/90 font-medium">SPMT</span>
                                        </div>

                                        <!-- Enhanced heading with animated typing effect -->
                                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 text-white leading-tight animate__animated animate__fadeInUp">
                                            <span class="block mb-2">Program Magang</span>
                                            <span class="relative">
                                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] typewrite" data-period="2000" data-type='[ "Reguler"]'></span>
                                            <span class="absolute -bottom-1 left-0 w-full h-1 bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] rounded-full animate-pulse"></span>
                                            </span>
                                        </h1>

                                        <p class="text-lg md:text-xl mb-10 text-white/90 max-w-2xl mx-auto animate__animated animate__fadeInUp animate__delay-1s leading-relaxed">
                                            Platform digital <span class="font-semibold text-[#55B7E3]">terintegrasi</span> dari PT Pelindo untuk mengelola program magang mahasiswa,
                                            mulai dari pendaftaran daring, seleksi transparan, hingga pelaporan dan sertifikasi akhir program.
                                        </p>


                                        <!-- Enhanced buttons with animations and hover effects -->
                                        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6 animate__animated animate__fadeInUp animate__delay-2s">
                                            <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#0E73B9] to-[#55B7E3] text-white rounded-lg font-medium overflow-hidden shadow-lg hover:shadow-[#55B7E3]/30 transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
                                                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out"></span>
                                                <span class="relative flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                    </svg>
                                                    Daftar Sekarang
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <a href="{{ route('about.index') }}" class="group relative inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white/50 text-white rounded-lg font-medium overflow-hidden transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
                                                <span class="absolute inset-0 w-0 bg-white/10 group-hover:w-full transition-all duration-300 ease-out"></span>
                                                <span class="relative flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Pelajari Lebih Lanjut
                                                </span>
                                            </a>
                                        </div>
                                        <!-- Stats counter section -->
                                        <div class="mt-12 max-w-2xl mx-auto animate__animated animate__fadeInUp animate__delay-3s">
                                            <div class="text-center">
                                                <div class="text-4xl font-bold text-white counter-value" data-count="1000">0</div>
                                                <div class="text-lg text-white/80 mt-2">Alumni Magang SPMT</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Enhanced scroll indicator -->
                                    <div class="absolute bottom-8 left-0 right-0 flex justify-center animate__animated animate__fadeInUp animate__delay-4s">
                                        <a href="#jobs" class="flex flex-col items-center text-white/70 hover:text-white transition-colors duration-300">
                                            <span class="text-sm mb-2">Scroll untuk melihat lowongan</span>
                                            <div class="w-8 h-12 border-2 border-white/30 rounded-full flex justify-center p-1">
                                                <div class="w-1.5 h-3 bg-white/70 rounded-full animate-bounce"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Carousel Item 2 - Only background changes -->
                        <li class="splide__slide">
                            <div class="relative overflow-hidden bg-cover bg-center h-full" style="background-image: url('/images/background-hero.jpeg'); min-height: 80vh;">

                                <!-- Enhanced overlay with animated gradient -->
                                <div class="absolute inset-0 bg-gradient-to-tl from-[#0E73B9]/70 via-black/60 to-black/80"></div>

                                <!-- Animated particles background -->
                                <div id="particles-js-2" class="absolute inset-0 z-10 opacity-40"></div>

                                <!-- Same content as first slide -->
                                <div class="flex flex-col items-center justify-center px-8 py-24 md:py-32 w-full mx-auto relative z-20 h-full text-center">
                                    <!-- Animated content with enhanced styling -->
                                    <div class="max-w-4xl mx-auto mb-8">
                                          <!-- Animated badge -->
                                          <div class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6 animate__animated animate__fadeInDown animate__delay-1s">
                                            <span class="text-white/90 font-medium">SPMT</span>
                                        </div>

                                        <!-- Enhanced heading with animated typing effect -->
                                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 text-white leading-tight animate__animated animate__fadeInUp">
                                            <span class="block mb-2">Program Magang</span>
                                            <span class="relative">
                                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] typewrite" data-period="2000" data-type='[ "Reguler"]'></span>
                                                <span class="absolute -bottom-1 left-0 w-full h-1 bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] rounded-full animate-pulse"></span>
                                            </span>
                                        </h1>

                                        <p class="text-lg md:text-xl mb-10 text-white/90 max-w-2xl mx-auto animate__animated animate__fadeInUp animate__delay-1s leading-relaxed">
                                            Platform digital <span class="font-semibold text-[#55B7E3]">terintegrasi</span> dari PT Pelindo untuk mengelola program magang mahasiswa,
                                            mulai dari pendaftaran daring, seleksi transparan, hingga pelaporan dan sertifikasi akhir program.
                                        </p>


                                        <!-- Enhanced buttons with animations and hover effects -->
                                        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6 animate__animated animate__fadeInUp animate__delay-2s">
                                            <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#0E73B9] to-[#55B7E3] text-white rounded-lg font-medium overflow-hidden shadow-lg hover:shadow-[#55B7E3]/30 transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
                                                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out"></span>
                                                <span class="relative flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                    </svg>
                                                    Daftar Sekarang
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <a href="#about" class="group relative inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white/50 text-white rounded-lg font-medium overflow-hidden transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
                                                <span class="absolute inset-0 w-0 bg-white/10 group-hover:w-full transition-all duration-300 ease-out"></span>
                                                <span class="relative flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Pelajari Lebih Lanjut
                                                </span>
                                            </a>
                                        </div>

                                         <!-- Stats counter section -->
                                         <div class="mt-12 max-w-2xl mx-auto animate__animated animate__fadeInUp animate__delay-3s">
                                            <div class="text-center">
                                                <div class="text-4xl font-bold text-white counter-value" data-count="1000">0</div>
                                                <div class="text-lg text-white/80 mt-2">Alumni Magang SPMT</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Enhanced scroll indicator -->
                                    <div class="absolute bottom-8 left-0 right-0 flex justify-center animate__animated animate__fadeInUp animate__delay-4s">
                                        <a href="#jobs" class="flex flex-col items-center text-white/70 hover:text-white transition-colors duration-300">
                                            <span class="text-sm mb-2">Scroll untuk melihat lowongan</span>
                                            <div class="w-8 h-12 border-2 border-white/30 rounded-full flex justify-center p-1">
                                                <div class="w-1.5 h-3 bg-white/70 rounded-full animate-bounce"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Carousel Item 3 - Only background changes -->
                        <li class="splide__slide">
                            <div class="relative overflow-hidden bg-cover bg-center h-full" style="background-image: url('/images/background-hero3.jpeg'); min-height: 80vh;">
                                <!-- Enhanced overlay with animated gradient -->
                                <div class="absolute inset-0 bg-gradient-to-tr from-black/80 via-black/60 to-[#0E73B9]/40"></div>

                                <!-- Animated particles background -->
                                <div id="particles-js-3" class="absolute inset-0 z-10 opacity-40"></div>

                                <!-- Same content as first slide -->
                                <div class="flex flex-col items-center justify-center px-8 py-24 md:py-32 w-full mx-auto relative z-20 h-full text-center">
                                    <!-- Animated content with enhanced styling -->
                                    <div class="max-w-4xl mx-auto mb-8">
                                          <!-- Animated badge -->
                                          <div class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6 animate__animated animate__fadeInDown animate__delay-1s">
                                            <span class="text-white/90 font-medium">SPMT</span>
                                        </div>

                                        <!-- Enhanced heading with animated typing effect -->
                                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 text-white leading-tight animate__animated animate__fadeInUp">
                                            <span class="block mb-2">Program Magang</span>
                                            <span class="relative">
                                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] typewrite" data-period="2000" data-type='[ "Reguler"]'></span>
                                            <span class="absolute -bottom-1 left-0 w-full h-1 bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] rounded-full animate-pulse"></span>
                                            </span>
                                        </h1>

                                        <p class="text-lg md:text-xl mb-10 text-white/90 max-w-2xl mx-auto animate__animated animate__fadeInUp animate__delay-1s leading-relaxed">
                                            Platform digital <span class="font-semibold text-[#55B7E3]">terintegrasi</span> dari PT Pelindo untuk mengelola program magang mahasiswa,
                                            mulai dari pendaftaran daring, seleksi transparan, hingga pelaporan dan sertifikasi akhir program.
                                        </p>


                                        <!-- Enhanced buttons with animations and hover effects -->
                                        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6 animate__animated animate__fadeInUp animate__delay-2s">
                                            <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#0E73B9] to-[#55B7E3] text-white rounded-lg font-medium overflow-hidden shadow-lg hover:shadow-[#55B7E3]/30 transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
                                                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-[#55B7E3] to-[#0E73B9] opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out"></span>
                                                <span class="relative flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                    </svg>
                                                    Daftar Sekarang
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <a href="#about" class="group relative inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white/50 text-white rounded-lg font-medium overflow-hidden transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
                                                <span class="absolute inset-0 w-0 bg-white/10 group-hover:w-full transition-all duration-300 ease-out"></span>
                                                <span class="relative flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Pelajari Lebih Lanjut
                                                </span>
                                            </a>
                                        </div>

                                         <!-- Stats counter section -->
                                         <div class="mt-12 max-w-2xl mx-auto animate__animated animate__fadeInUp animate__delay-3s">
                                            <div class="text-center">
                                                <div class="text-4xl font-bold text-white counter-value" data-count="1000">0</div>
                                                <div class="text-lg text-white/80 mt-2">Alumni Magang SPMT</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Enhanced scroll indicator -->
                                    <div class="absolute bottom-8 left-0 right-0 flex justify-center animate__animated animate__fadeInUp animate__delay-4s">
                                        <a href="#jobs" class="flex flex-col items-center text-white/70 hover:text-white transition-colors duration-300">
                                            <span class="text-sm mb-2">Scroll untuk melihat lowongan</span>
                                            <div class="w-8 h-12 border-2 border-white/30 rounded-full flex justify-center p-1">
                                                <div class="w-1.5 h-3 bg-white/70 rounded-full animate-bounce"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
            </div>
        </section>



    <!-- Internship Listings Section -->
    <section id="jobs" class="py-12 bg-white dark:bg-[#121211] overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Lowongan Magang Terbaru</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Temukan kesempatan magang terbaik di PT Pelindo Multiterminal untuk mengembangkan potensi dan kariermu
                </p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-4 border-2 border-gray-200 dark:border-gray-700 -mx-2 sm:mx-0">
                <form id="filter-form" method="GET" action="{{ route('landing.page') }}" class="space-y-4" onsubmit="event.preventDefault(); applyFilters();">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Lowongan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="search" name="search" 
                                       value="{{ $filters['search'] ?? '' }}" 
                                       placeholder="Cari judul, deskripsi, atau lokasi..."
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <!-- Education Level Filter -->
                        <div>
                            <label for="education" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tingkat Pendidikan</label>
                            <select id="education" name="education" 
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Pendidikan</option>
                                <option value="SMA/SMK" {{ (isset($filters['education']) && $filters['education'] == 'SMA/SMK') ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="Vokasi" {{ (isset($filters['education']) && $filters['education'] == 'Vokasi') ? 'selected' : '' }}>Vokasi (D1-D4)</option>
                                <option value="S1" {{ (isset($filters['education']) && $filters['education'] == 'S1') ? 'selected' : '' }}>Sarjana (S1)</option>
                            </select>
                        </div>

                        <!-- Division Filter -->
                        <div>
                            <label for="division" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Divisi</label>
                            <select id="division" name="division" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Divisi</option>
                                @foreach(\App\Helpers\DivisionHelper::getAllDivisions() as $division)
                                    <option value="{{ $division }}" {{ request('division') == $division ? 'selected' : '' }}>{{ $division }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex justify-end space-x-3 pt-2">
                        <!-- Reset Button -->
                        <button type="button" 
                                id="reset-filter-btn"
                                class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filter
                        </button>
                        
                        <!-- Apply Filter Button -->
                        <button type="submit" 
                                id="apply-filter-btn"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Cari</span>
                        </button>
                    </div>
                </form>
            </div>

            @push('scripts')
            <script>
            // Prevent form submission on Enter key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.closest('#filter-form')) {
                    e.preventDefault();
                    applyFilters();
                }
            });

            // Function to handle filter submission with animation
            async function applyFilters() {
                const form = document.getElementById('filter-form');
                const formData = new FormData(form);
                const params = new URLSearchParams();
                
                // Convert form data to URL parameters
                formData.forEach((value, key) => {
                    if (value) params.append(key, value);
                });
                
                // Add loading state
                const applyBtn = document.getElementById('apply-filter-btn');
                const originalBtnText = applyBtn.innerHTML;
                applyBtn.disabled = true;
                applyBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menerapkan...
                `;
                
                try {
                    // Fade out current content
                    const content = document.getElementById('internship-list');
                    if (content) {
                        content.style.opacity = '0';
                        content.style.transition = 'opacity 300ms ease-in-out';
                    }
                    
                    // Build URL with current path and new params
                    const url = new URL(window.location.pathname, window.location.origin);
                    url.search = params.toString();
                    
                    // Fetch new content
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html, */*; q=0.01'
                        }
                    });
                    
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('internship-list');
                    
                    if (newContent && content) {
                        // Update browser URL without page reload
                        window.history.pushState({}, '', url);
                        
                        // Replace content with fade effect
                        content.innerHTML = newContent.innerHTML;
                        content.style.opacity = '1';
                        
                        // Re-initialize any dynamic content if needed
                        initializeDynamicContent();
                    }
                } catch (error) {
                    console.error('Error applying filters:', error);
                    // Show error message to user
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                } finally {
                    // Reset button state
                    applyBtn.disabled = false;
                    applyBtn.innerHTML = originalBtnText;
                }
            }
            
            // Function to reset filters with animation
            async function resetFilters() {
                const resetBtn = document.getElementById('reset-filter-btn');
                const originalBtnText = resetBtn.innerHTML;
                resetBtn.disabled = true;
                resetBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mereset...
                `;
                
                try {
                    // Fade out current content
                    const content = document.getElementById('internship-list');
                    if (content) {
                        content.style.opacity = '0';
                        content.style.transition = 'opacity 300ms ease-in-out';
                    }
                    
                    // Reset form
                    document.getElementById('filter-form').reset();
                    
                    // Fetch fresh content
                    const response = await fetch(window.location.pathname, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html, */*; q=0.01'
                        }
                    });
                    
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('internship-list');
                    
                    if (newContent && content) {
                        // Update browser URL without page reload
                        window.history.pushState({}, '', window.location.pathname);
                        
                        // Replace content with fade effect
                        content.innerHTML = newContent.innerHTML;
                        content.style.opacity = '1';
                        
                        // Re-initialize any dynamic content if needed
                        initializeDynamicContent();
                    }
                } catch (error) {
                    console.error('Error resetting filters:', error);
                    // Show error message to user
                    alert('Terjadi kesalahan saat mereset filter. Silakan coba lagi.');
                } finally {
                    // Reset button state
                    resetBtn.disabled = false;
                    resetBtn.innerHTML = originalBtnText;
                }
            }
            
            // Initialize event listeners
            document.addEventListener('DOMContentLoaded', function() {
                // Apply filter button
                const applyBtn = document.getElementById('apply-filter-btn');
                if (applyBtn) {
                    applyBtn.addEventListener('click', applyFilters);
                }
                
                // Reset filter button
                const resetBtn = document.getElementById('reset-filter-btn');
                if (resetBtn) {
                    resetBtn.addEventListener('click', resetFilters);
                }
                
                // Handle browser back/forward buttons
                window.addEventListener('popstate', function() {
                    // Reload the content when history changes
                    window.location.reload();
                });
            });
            
            // Function to re-initialize any dynamic content after AJAX load
            function initializeDynamicContent() {
                // Re-initialize any plugins or event listeners here
                // For example, if you have tooltips or other interactive elements:
                // $('[data-toggle="tooltip"]').tooltip();
            }
            </script>
            @endpush
            
            <!-- Internship List -->
            @if($featuredInternships->count() > 0)
            <div id="internship-list" class="mt-8">
                @include('partials.internship-list', ['internships' => $featuredInternships])
            </div>
            @else
            <div id="internship-list" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex flex-col items-center">
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white text-center">
                        @if(session('no_results'))
                            {{ session('no_results') }}
                        @else
                            Belum ada lowongan tersedia
                        @endif
                    </h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 text-center max-w-md">
                        Silakan periksa kembali di lain waktu atau hubungi kami untuk informasi lebih lanjut.
                    </p>
                </div>
                @if(request()->has('search') || request()->has('education') || request()->has('division'))
                <div class="mt-4">
                </div>
                @endif
            </div>
            @endif
            
            <div class="mt-10 text-center">
                <a href="{{ route('internships.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                    Lihat Semua Lowongan
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Process Flow Section -->
    <section id="process" class="py-20 bg-white dark:bg-[#161615] overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4 text-[#1b1b18] dark:text-[#EDEDEC] relative inline-block">
                    Alur Proses Magang
                </h2>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto mt-4">
                    Ikuti langkah-langkah di bawah ini untuk memulai perjalanan magang Anda bersama kami
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 relative">
                <!-- Step 1 -->
                <div class="process-step text-center relative" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative z-10">
                        <div class="step-number w-20 h-20 bg-gradient-to-br from-[#e6f5fc] to-[#b3e0ff] dark:from-[#0a2c45] dark:to-[#0E73B9] rounded-full flex items-center justify-center mx-auto mb-6 transform transition-all duration-500 hover:rotate-6">
                            <span class="text-3xl font-bold text-[#0E73B9] dark:text-[#55B7E3]">1</span>
                        </div>
                        <div class="relative z-20">
                            <div class="w-4 h-4 bg-[#0E73B9] dark:bg-[#55B7E3] rounded-full mx-auto mb-6"></div>
                            <h3 class="text-xl font-semibold mb-3 text-[#1b1b18] dark:text-[#EDEDEC] transition-colors duration-300 group-hover:text-[#0E73B9] dark:group-hover:text-[#55B7E3]">Pendaftaran</h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] px-2 transition-all duration-300 group-hover:scale-105">Daftar akun dan lengkapi profil Anda dengan informasi pendidikan dan keterampilan.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="process-step text-center relative" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative z-10">
                        <div class="step-number w-20 h-20 bg-gradient-to-br from-[#e6f5fc] to-[#b3e0ff] dark:from-[#0a2c45] dark:to-[#0E73B9] rounded-full flex items-center justify-center mx-auto mb-6 transform transition-all duration-500 hover:-rotate-6">
                            <span class="text-3xl font-bold text-[#0E73B9] dark:text-[#55B7E3]">2</span>
                        </div>
                        <div class="relative z-20">
                            <div class="w-4 h-4 bg-[#0E73B9] dark:bg-[#55B7E3] rounded-full mx-auto mb-6"></div>
                            <h3 class="text-xl font-semibold mb-3 text-[#1b1b18] dark:text-[#EDEDEC] transition-colors duration-300 group-hover:text-[#0E73B9] dark:group-hover:text-[#55B7E3]">Seleksi</h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] px-2 transition-all duration-300 group-hover:scale-105">Lamar posisi magang yang sesuai dan ikuti proses seleksi dari perusahaan.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="process-step text-center relative" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative z-10">
                        <div class="step-number w-20 h-20 bg-gradient-to-br from-[#e6f5fc] to-[#b3e0ff] dark:from-[#0a2c45] dark:to-[#0E73B9] rounded-full flex items-center justify-center mx-auto mb-6 transform transition-all duration-500 hover:rotate-6">
                            <span class="text-3xl font-bold text-[#0E73B9] dark:text-[#55B7E3]">3</span>
                        </div>
                        <div class="relative z-20">
                            <div class="w-4 h-4 bg-[#0E73B9] dark:bg-[#55B7E3] rounded-full mx-auto mb-6"></div>
                            <h3 class="text-xl font-semibold mb-3 text-[#1b1b18] dark:text-[#EDEDEC] transition-colors duration-300 group-hover:text-[#0E73B9] dark:group-hover:text-[#55B7E3]">Wawancara</h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] px-2 transition-all duration-300 group-hover:scale-105">Ikuti sesi wawancara dengan tim kami untuk mengetahui kesesuaian dengan posisi yang dilamar.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="process-step text-center relative" data-aos="fade-up" data-aos-delay="400">
                    <div class="relative z-10">
                        <div class="step-number w-20 h-20 bg-gradient-to-br from-[#e6f5fc] to-[#b3e0ff] dark:from-[#0a2c45] dark:to-[#0E73B9] rounded-full flex items-center justify-center mx-auto mb-6 transform transition-all duration-500 hover:-rotate-6">
                            <span class="text-3xl font-bold text-[#0E73B9] dark:text-[#55B7E3]">4</span>
                        </div>
                        <div class="relative z-20">
                            <div class="w-4 h-4 bg-[#0E73B9] dark:bg-[#55B7E3] rounded-full mx-auto mb-6"></div>
                            <h3 class="text-xl font-semibold mb-3 text-[#1b1b18] dark:text-[#EDEDEC] transition-colors duration-300 group-hover:text-[#0E73B9] dark:group-hover:text-[#55B7E3]">Mulai Magang</h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] px-2 transition-all duration-300 group-hover:scale-105">Selamat! Anda akan memulai perjalanan magang di perusahaan kami.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="#" class="inline-block px-6 py-3 bg-[#0E73B9] dark:bg-[#55B7E3] text-white rounded-md hover:bg-[#0a5a8f] dark:hover:bg-[#3a9fd0] transition duration-300">Mulai Proses Magang</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-[#f8f8f7] dark:bg-[#121211] overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4 text-[#1b1b18] dark:text-[#EDEDEC] relative inline-block">
                    Tentang Program Magang
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mt-6" data-aos="fade-up" data-aos-delay="100">
                    Temukan pengalaman magang terbaik bersama Pelindo Multi Terminal
                </p>
            </div>

            <div class="flex flex-col md:flex-row items-center" data-aos="fade-up" data-aos-delay="150">
                <div class="md:w-1/2 mb-8 md:mb-0 transform transition-all duration-500 hover:scale-105">
                    <div class="relative overflow-hidden rounded-xl shadow-2xl border-4 border-white dark:border-gray-800">
                        <img src="/images/background-hero.jpeg" alt="About SPMT" class="w-full h-auto rounded-lg">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent rounded-lg"></div>
                    </div>
                </div>
                <div class="md:w-1/2 md:pl-12">
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <p class="text-[#706f6c] dark:text-[#A1A09A] text-justify leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                                SPMT adalah platform magang reguler yang diselenggarakan oleh perusahaan Pelindo Multi Terminal. Platform ini dirancang untuk memberikan pengalaman magang yang terstruktur dan profesional bagi mahasiswa di berbagai bidang.
                            </p>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] text-justify leading-relaxed" data-aos="fade-up" data-aos-delay="250">
                                Melalui SPMT, mahasiswa dapat mengeksplorasi berbagai peluang magang yang sesuai dengan minat dan kompetensi mereka, sementara Pelindo Multi Terminal mendapatkan akses terhadap talenta muda potensial yang dapat berkontribusi dalam mendukung operasional dan pengembangan perusahaan.
                            </p>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] text-justify leading-relaxed" data-aos="fade-up" data-aos-delay="300">
                                Dengan proses yang transparan, terintegrasi, dan berbasis digital, SPMT menjadi jembatan kolaboratif antara dunia pendidikan dan industri, serta mendukung terciptanya ekosistem magang yang berkualitas dan berkelanjutan.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-[#e6f5fc] to-[#b3e0ff] dark:from-[#0a2c45] dark:to-[#0E73B9] rounded-full flex items-center justify-center mr-4 transform transition-all duration-300 group-hover:rotate-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0E73B9] dark:text-[#55B7E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1 text-[#1b1b18] dark:text-[#EDEDEC]">100+</h3>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Lowongan Tersedia</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="250">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-[#e6f5fc] to-[#b3e0ff] dark:from-[#0a2c45] dark:to-[#0E73B9] rounded-full flex items-center justify-center mr-4 transform transition-all duration-300 group-hover:-rotate-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0E73B9] dark:text-[#55B7E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1 text-[#1b1b18] dark:text-[#EDEDEC]">10,000+</h3>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Mahasiswa</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-[#e6f5fc] to-[#b3e0ff] dark:from-[#0a2c45] dark:to-[#0E73B9] rounded-full flex items-center justify-center mr-4 transform transition-all duration-300 group-hover:rotate-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0E73B9] dark:text-[#55B7E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1 text-[#1b1b18] dark:text-[#EDEDEC]">50+</h3>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Universitas</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="350">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-[#e6f5fc] to-[#b3e0ff] dark:from-[#0a2c45] dark:to-[#0E73B9] rounded-full flex items-center justify-center mr-4 transform transition-all duration-300 group-hover:-rotate-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0E73B9] dark:text-[#55B7E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1 text-[#1b1b18] dark:text-[#EDEDEC]">95%</h3>
                                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Tingkat Kepuasan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-white dark:bg-[#161615] overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4 text-[#1b1b18] dark:text-[#EDEDEC] relative inline-block">
                    Apa Kata Mereka?
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mt-6" data-aos="fade-up" data-aos-delay="100">
                    Testimoni dari para mahasiswa yang telah merasakan pengalaman magang bersama kami
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700" data-aos="fade-up" data-aos-delay="150">
                    <!-- User Info -->
                    <div class="flex items-center mb-6">
                        <div class="relative">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Fiqri" class="w-14 h-14 rounded-full border-2 border-white dark:border-gray-700 shadow-md">
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-br from-[#0E73B9] to-[#55B7E3] rounded-full flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Fiqri</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Alumni Magang Reguler SPMT</p>
                        </div>
                    </div>
                    
                    <!-- Rating -->
                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-400 mr-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Testimonial Content -->
                    <div class="relative">
                        <svg class="absolute -top-3 -left-2 w-8 h-8 text-gray-200 dark:text-gray-700" fill="currentColor" viewBox="0 0 32 32">
                            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"/>
                        </svg>
                        <p class="relative z-10 text-gray-600 dark:text-gray-300 text-justify leading-relaxed italic">
                            "Pengalaman magang di SPMT sangat berharga. Saya belajar banyak tentang dunia kerja yang sesungguhnya dan mendapatkan bimbingan yang sangat baik dari mentor. Program ini membantu saya mengembangkan keterampilan teknis dan soft skill yang sangat berguna untuk karir saya di masa depan."
                        </p>
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700" data-aos="fade-up" data-aos-delay="300">
                    <!-- User Info -->
                    <div class="flex items-center mb-6">
                        <div class="relative">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Cut Melinda" class="w-14 h-14 rounded-full border-2 border-white dark:border-gray-700 shadow-md">
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-br from-[#0E73B9] to-[#55B7E3] rounded-full flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cut Melinda</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Alumni Magang Reguler SPMT</p>
                        </div>
                    </div>
                    
                    <!-- Rating -->
                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-400 mr-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Testimonial Content -->
                    <div class="relative">
                        <svg class="absolute -top-3 -left-2 w-8 h-8 text-gray-200 dark:text-gray-700" fill="currentColor" viewBox="0 0 32 32">
                            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"/>
                        </svg>
                        <p class="relative z-10 text-gray-600 dark:text-gray-300 text-justify leading-relaxed italic">
                            "Sangat puas dengan program magang dari SPMT. Saya mendapatkan kesempatan untuk mengembangkan keterampilan di bidang pemasaran digital dan membangun jaringan profesional yang luas. Mentor yang berpengalaman dan suasana kerja yang mendukung membuat pengalaman magang ini sangat berharga."
                        </p>
                    </div>    
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700" data-aos="fade-up" data-aos-delay="450">
                    <!-- User Info -->
                    <div class="flex items-center mb-6">
                        <div class="relative">
                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Ahmad Fauzi" class="w-14 h-14 rounded-full border-2 border-white dark:border-gray-700 shadow-md">
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-br from-[#0E73B9] to-[#55B7E3] rounded-full flex items-center justify-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ahmad Fauzi</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Alumni Magang Reguler SPMT</p>
                        </div>
                    </div>
                    
                    <!-- Rating -->
                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-400 mr-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Testimonial Content -->
                    <div class="relative">
                        <svg class="absolute -top-3 -left-2 w-8 h-8 text-gray-200 dark:text-gray-700" fill="currentColor" viewBox="0 0 32 32">
                            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"/>
                        </svg>
                        <p class="relative z-10 text-gray-600 dark:text-gray-300 text-justify leading-relaxed italic">
                            "Sebagai perusahaan, SPMT memudahkan kami untuk menemukan kandidat magang yang berkualitas. Fitur penyaringan yang canggih dan manajemen aplikasi yang terintegrasi sangat membantu tim HR kami dalam proses rekrutmen. Platform yang sangat direkomendasikan untuk perusahaan yang mencari talenta muda berbakat."
                        </p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- FAQ Section -->
    <section id="faq" class="py-16 md:py-24 bg-gradient-to-b from-white to-[#f8f8f7] dark:from-[#161615] dark:to-[#0f0f0e] overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 md:mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4 text-[#1b1b18] dark:text-[#EDEDEC] relative inline-block">
                    Pertanyaan Umum
                </h2>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto mt-4">
                    Temukan jawaban atas pertanyaan yang sering diajukan seputar program magang SPMT
                </p>
            </div>

            <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-6" data-aos="fade-up" data-aos-delay="100">
                <!-- FAQ Item 1 -->
                <div class="group">
                    <div class="h-full bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <button class="flex justify-between items-center w-full p-5 text-left group-focus:outline-none">
                            <span class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] transition-colors duration-300 group-hover:text-[#0E73B9] dark:group-hover:text-[#55B7E3]">
                                Bagaimana cara mendaftar di SPMT?
                            </span>
                            <div class="flex-shrink-0 ml-4 transition-transform duration-300 group-focus:rotate-180">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0E73B9] dark:text-[#55B7E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                        <div class="px-5 pb-5 pt-0 max-h-0 overflow-hidden transition-all duration-300 group-focus:max-h-96 group-focus:mt-3">
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                                    Untuk mendaftar di SPMT, klik tombol "Register" di bagian atas halaman. Isi formulir pendaftaran dengan informasi yang diminta, verifikasi email Anda, dan lengkapi profil dengan informasi pendidikan dan keterampilan Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="group">
                    <div class="h-full bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <button class="flex justify-between items-center w-full p-5 text-left group-focus:outline-none">
                            <span class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] transition-colors duration-300 group-hover:text-[#0E73B9] dark:group-hover:text-[#55B7E3]">
                                Berapa lama proses seleksi magang?
                            </span>
                            <div class="flex-shrink-0 ml-4 transition-transform duration-300 group-focus:rotate-180">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0E73B9] dark:text-[#55B7E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                        <div class="px-5 pb-5 pt-0 max-h-0 overflow-hidden transition-all duration-300 group-focus:max-h-96 group-focus:mt-3">
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                                    Proses seleksi magang bervariasi tergantung pada perusahaan. Secara umum, proses seleksi memakan waktu 1-3 minggu sejak penutupan pendaftaran. Anda dapat memantau status aplikasi Anda melalui Aktivitas SPMT.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="group">
                    <div class="h-full bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <button class="flex justify-between items-center w-full p-5 text-left group-focus:outline-none">
                            <span class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] transition-colors duration-300 group-hover:text-[#0E73B9] dark:group-hover:text-[#55B7E3]">
                                Apakah program magang ini dibayar?
                            </span>
                            <div class="flex-shrink-0 ml-4 transition-transform duration-300 group-focus:rotate-180">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0E73B9] dark:text-[#55B7E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                        <div class="px-5 pb-5 pt-0 max-h-0 overflow-hidden transition-all duration-300 group-focus:max-h-96 group-focus:mt-3">
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                                    Program magang ini dapat bersifat dibayar maupun tidak dibayar, tergantung pada kebijakan perusahaan. Informasi terkait kompensasi atau tunjangan selama magang akan dicantumkan secara jelas dalam deskripsi lowongan magang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="group">
                    <div class="h-full bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <button class="flex justify-between items-center w-full p-5 text-left group-focus:outline-none">
                            <span class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] transition-colors duration-300 group-hover:text-[#0E73B9] dark:group-hover:text-[#55B7E3]">
                                Bagaimana cara mendapatkan sertifikat magang?
                            </span>
                            <div class="flex-shrink-0 ml-4 transition-transform duration-300 group-focus:rotate-180">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0E73B9] dark:text-[#55B7E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                        <div class="px-5 pb-5 pt-0 max-h-0 overflow-hidden transition-all duration-300 group-focus:max-h-96 group-focus:mt-3">
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                                    Setelah menyelesaikan program magang, pembimbing Anda akan memberikan evaluasi kinerja. Jika Anda berhasil menyelesaikan program dengan baik, perusahaan akan menerbitkan sertifikat magang melalui platform SPMT yang dapat Anda unduh dari Aktivitas Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-6 right-6 bg-[#0E73B9] dark:bg-[#55B7E3] text-white p-3 rounded-full shadow-xl opacity-0 transition-all duration-300 z-50 hover:scale-110 hover:shadow-2xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('js/main.js') }}"></script>

    
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });

            // Add hover effect for process steps
            const processSteps = document.querySelectorAll('.process-step');
            processSteps.forEach(step => {
                step.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                    this.style.transition = 'all 0.3s ease-in-out';
                });
                
                step.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
                
                // Add click effect for mobile
                step.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            });
            
            // Animate step numbers on scroll
            const observerOptions = {
                threshold: 0.5
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const stepNumber = entry.target.querySelector('.step-number');
                        if (stepNumber) {
                            stepNumber.style.transform = 'scale(1.1) rotate(5deg)';
                            setTimeout(() => {
                                stepNumber.style.transform = 'scale(1) rotate(0)';
                            }, 300);
                        }
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.process-step').forEach(step => {
                observer.observe(step);
            });
        });
    </script>
    
</section>
@endsection
