@extends('layouts.internship')

@section('title', 'Tentang Program Magang - PT Pelindo Multi Terminal')

@push('styles')
<style>
    /* Animations */
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    
    /* Base Styles */
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 15s ease infinite;
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .animate-blob {
        animation: blob 7s infinite;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    /* Scroll Indicator */
    .animate-bounce {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    /* Hover Effects */
    .hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Glass Morphism */
    .glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Utility Animations */
    .animate-fade-in {
        animation: fadeIn 1s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Existing Styles */
    .benefit-card {
        transition: all 0.3s ease;
    }
    
    .benefit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .testimonial-card {
        transition: all 0.3s ease;
    }
    
    .testimonial-card:hover {
        transform: scale(1.02);
    }
    
    .faq-item {
        transition: all 0.3s ease;
    }
    
    .faq-item:hover {
        transform: translateX(5px);
    }
    
    .highlight-text {
        position: relative;
        display: inline-block;
    }
    
    .highlight-text:after {
        content: '';
        position: absolute;
        bottom: 2px;
        left: 0;
        width: 100%;
        height: 8px;
        background-color: rgba(96, 165, 250, 0.3);
        z-index: -1;
        transition: height 0.3s ease;
    }
    
    .highlight-text:hover:after {
        height: 12px;
    }
</style>
@endpush

@section('content')
<!-- Modern Hero Section -->
<div class="full mx-auto mt-6">

<div class="relative overflow-hidden">
    <!-- Animated Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-cyan-700 animate-gradient"></div>
    
    <!-- Floating Elements -->
    <div class="absolute inset-0 overflow-hidden opacity-20">
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-1/3 -right-20 w-96 h-96 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-20 left-1/2 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="relative z-10" data-aos="fade-right">
                <div class="backdrop-blur-sm bg-white/5 p-8 rounded-2xl border border-white/10 shadow-2xl transition-all duration-500 hover:shadow-cyan-500/20 hover:border-cyan-500/30">
                    <div class="inline-block px-4 py-1.5 text-sm font-semibold text-cyan-400 bg-cyan-400/10 rounded-full mb-6">
                        #MasaDepanBersinar
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Program Magang 
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-300 to-blue-400">
                            SPMT
                        </span>
                    </h1>
                    <p class="text-lg md:text-xl text-blue-100 mb-8 leading-relaxed">
                        Wadah pengembangan diri bagi generasi muda untuk mengasah kompetensi dan berkontribusi dalam pengembangan industri pelabuhan Indonesia.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#program" class="group relative overflow-hidden px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-cyan-500/30 hover:-translate-y-0.5">
                            <span class="relative z-10">Jelajahi Program</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>
                        <a href="{{ route('internships.index') }}" class="group relative overflow-hidden px-8 py-4 bg-transparent border-2 border-white/20 text-white font-semibold rounded-xl transition-all duration-300 hover:bg-white/10 hover:border-transparent">
                            <span class="relative z-10">Lihat Lowongan</span>
                            <span class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        </a>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="mt-8 grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-white/5 backdrop-blur-sm p-4 rounded-xl border border-white/10">
                        <div class="text-2xl font-bold text-cyan-400">1000+</div>
                        <div class="text-sm text-blue-100">Alumni</div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm p-4 rounded-xl border border-white/10">
                        <div class="text-2xl font-bold text-cyan-400">12+</div>
                        <div class="text-sm text-blue-100">Program</div>
                    </div>
                </div>
            </div>
            
            <!-- Illustration -->
            <div class="relative block w-full mt-8 lg:mt-0 lg:w-auto" data-aos="fade-left">
                <div class="relative z-10">
                    <img src="{{ asset('images/CTA.png') }}" alt="Team Collaboration" class="w-full h-auto max-w-xs md:max-w-md lg:max-w-lg mx-auto">
                </div>
                <!-- Floating elements -->
                <div class="absolute -top-6 -right-6 w-24 h-24 md:w-32 md:h-32 bg-cyan-500/20 rounded-full filter blur-xl animate-pulse"></div>
                <div class="absolute -bottom-6 -left-6 w-28 h-28 md:w-40 md:h-40 bg-blue-500/20 rounded-full filter blur-xl animate-pulse animation-delay-2000"></div>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
        <a href="#program" class="flex flex-col items-center justify-center group" aria-label="Scroll down">
            <span class="text-sm text-white/80 font-medium mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Scroll</span>
            <div class="w-8 h-12 border-2 border-white/30 rounded-full flex justify-center p-1 group-hover:border-cyan-400 transition-colors duration-300">
                <div class="w-1.5 h-3 bg-white/70 rounded-full animate-bounce [animation-duration:2s] [animation-iteration-count:infinite]"></div>
            </div>
        </a>
    </div>
</div>

<!-- Program Overview -->
<section id="program" class="relative py-20 overflow-hidden bg-gradient-to-b from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5 dark:opacity-[0.02]">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMwMDc3YjYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzRjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTR6bS0yMiA0N2MyMi4wOTIgMCA0MC0xNy45MDggNDAtNDBzLTE3LjkwOC00MC00MC00MC00MCAxNy45MDgtNDAgNDAgMTcuOTA4IDQwIDQwIDQwek0yNCA2MGMxOS44ODIgMCAzNi0xNi4xMTggMzYtM1M0My44ODIgNTQgMjQgNTRzLTM2IDE2LjExOC0zNi0zIDE2LjExOC0zIDM2LTN6Ii8+PC9nPjwvZz48L3N2Zz4=')]"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20" data-aos="fade-up">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 mb-6">
                <span class="relative flex h-2 w-2 mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                </span>
                Program Unggulan
            </div>
            <h2 class="text-4xl md:text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500 dark:from-blue-400 dark:to-cyan-300 mb-6 leading-tight pb-2">
                Tentang Program Kami
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Program Magang PT Pelindo Multi Terminal dirancang untuk memberikan pengalaman nyata di dunia kerja sektor maritim dan logistik, dengan fokus pada pengembangan kompetensi dan karakter profesional.
            </p>
        </div>
        
        <!-- Content Grid -->
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
            <!-- Left Column - Image -->
            <div class="relative" data-aos="fade-right">
                <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl transform hover:scale-[1.02] transition-all duration-500">
                    <picture>
                        <!-- Desktop (lg and up) -->
                        <source media="(min-width: 1024px)" srcset="{{ asset('images/CTA3.jpg') }}">
                        <!-- Mobile & Tablet (default) -->
                        <img src="{{ asset('images/CTA2.jpg') }}" alt="Program Magang SPMT" class="w-full h-auto rounded-2xl">
                    </picture>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>
                <!-- Floating Badge -->
                <div class="absolute -bottom-6 -right-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-20">
                    <div class="text-3xl font-bold text-blue-600 dark:text-cyan-400">12+</div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-300">Program Tersedia</div>
                </div>
            </div>
            
            <!-- Right Column - Content -->
            <div class="space-y-8" data-aos="fade-left">
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Mengapa Memilih Magang di SPMT?
                    </h3>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                        Sebagai anak perusahaan PT Pelindo yang bergerak di bidang jasa kepelabuhanan, kami menawarkan pengalaman magang yang komprehensif dengan mentor yang berpengalaman di industri maritim Indonesia.
                    </p>
                </div>
                
                <!-- Features List -->
                <div class="space-y-6">
                    <div class="flex items-start group">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 dark:group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Belajar Langsung dari Ahli</h4>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">Dibimbing oleh praktisi industri pelabuhan terkemuka dengan pengalaman bertahun-tahun.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 dark:group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Akses Eksklusif</h4>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">Kesempatan untuk mengoperasikan peralatan dan sistem terbaru di industri pelabuhan.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 dark:group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Sertifikat Resmi</h4>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">Dapatkan sertifikat resmi yang diakui industri setelah menyelesaikan program magang.</p>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <div class="pt-4">
                    <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-0.5">
                        Daftar Sekarang
                        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="relative py-24 overflow-hidden bg-gradient-to-b from-white via-blue-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-10 dark:opacity-[0.03]">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMwMDc3YjYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzRjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTR6bS0yMiA0N2MyMi4wOTIgMCA0MC0xNy45MDggNDAtNDBzLTE3LjkwOC00MC00MC00MC00MCAxNy45MDgtNDAgNDAgMTcuOTA4IDQwIDQwIDQwek0yNCA2MGMxOS44ODIgMCAzNi0xNi4xMTggMzYtM1M0My44ODIgNTQgMjQgNTRzLTM2IDE2LjExOC0zNi0zIDE2LjExOC0zIDM2LTN6Ii8+PC9nPjwvZz48L3N2Zz4=')]"></div>
    </div>
    <!-- Floating Blobs -->
    <div class="absolute -top-32 -right-32 w-64 h-64 bg-blue-500/10 rounded-full filter blur-3xl animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-cyan-500/10 rounded-full filter blur-3xl animate-blob"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20" data-aos="fade-up">
            <div class="inline-flex items-center px-5 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 mb-6 border border-blue-200/50 dark:border-blue-800/30">
                <span class="relative flex h-2.5 w-2.5 mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                </span>
                Keuntungan Bergabung
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                Apa yang Akan Kamu 
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500 dark:from-blue-400 dark:to-cyan-300">
                    Dapatkan?
                </span>
            </h2>
            <div class="w-24 h-1.5 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full mx-auto mb-8"></div>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Dapatkan berbagai manfaat dan pengalaman berharga selama mengikuti program magang di
                <span class="font-semibold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500 dark:from-blue-400 dark:to-cyan-300">
                    SPMT PT Pelindo Multi Terminal
                </span>
            </p>
        </div>

        <!-- Benefits Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($benefits as $index => $benefit)
            <div class="benefit-card group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Decorative element -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-cyan-400/10 rounded-bl-full transition-all duration-500 group-hover:opacity-0"></div>
                
                <!-- Icon with gradient background -->
                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-100 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/20 flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110">
                    <span class="text-2xl">{{ $benefit['icon'] }}</span>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 relative">
                    {{ $benefit['title'] }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $benefit['description'] }}</p>
                
                <!-- Hover effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
            </div>
            @endforeach
        </div>
        
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-20 -left-20 w-64 h-64 bg-blue-500/5 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 -right-20 w-80 h-80 bg-cyan-400/5 rounded-full filter blur-3xl"></div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // FAQ Toggle Function
    function toggleFAQ(index) {
        const content = document.getElementById(`faq-content-${index}`);
        const icon = document.getElementById(`faq-icon-${index}`);
        
        if (content.classList.contains('hidden')) {
            // Close all other FAQs
            document.querySelectorAll('[id^=faq-content-]').forEach(el => {
                el.classList.add('hidden');
            });
            document.querySelectorAll('[id^=faq-icon-]').forEach(el => {
                el.classList.remove('rotate-180');
            });
            
            // Open clicked FAQ
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements with animation classes
    document.querySelectorAll('.animate-fade-in').forEach(el => {
        observer.observe(el);
    });
</script>
@endpush
