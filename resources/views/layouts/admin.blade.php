<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel - SPMT')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/webicon-spmt.jpg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/webicon-spmt.jpg') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Additional Styles -->
    @stack('styles')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
    
        <!-- Base Styles -->
        <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
        
        /* Sidebar Styles */
        .sidebar {
            transition: transform 0.2s ease, width 0.2s ease;
            width: 16rem; /* w-64 */
            border-right: 1px solid #e5e7eb;
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
            z-index: 30; /* Ensure sidebar is above backdrop */
        }
        .content-area {
            transition: all 0.3s ease;
            width: calc(100% - 16rem);
            background-color: #f8fafc;
        }
        
        /* Collapsed Sidebar Styles */
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .nav-item-icon {
            margin: 0 auto;
        }
        
        .sidebar.collapsed .nav-item {
            padding: 0.75rem 0;
            display: flex;
            justify-content: center;
        }
        
        /* Remove extra space when collapsed */
        .sidebar.collapsed .space-y-6,
        .sidebar.collapsed .space-y-1 {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        .sidebar.collapsed nav > div {
            margin-top: 5px !important;
        }
        
        .sidebar.collapsed a {
            margin-bottom: 5px;
            padding: 0.5rem 0;
        }
        
        /* Mobile Backdrop */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 20;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }
        
        .sidebar-backdrop.hidden {
            opacity: 0;
            visibility: hidden;
        }
        
        /* Better touch targets for mobile */
        @media (max-width: 1023px) {
            .sidebar {
                width: 18rem;
                transform: translateX(-100%);
            }
            
            .sidebar a {
                padding: 0.75rem 1rem;
            }
            
            .sidebar .flex-shrink-0 {
                width: 1.75rem;
                height: 1.75rem;
            }
            
            /* Ensure content doesn't shift when sidebar is open */
            .content-area {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
        
        /* Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        ::-webkit-scrollbar-button {
            display: none;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.08);
            border-radius: 2px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.08);
        }
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(106, 106, 255, 0.08) transparent;
        }
        .dark * {
            scrollbar-color: rgba(255, 255, 255, 0.08) transparent;
        }
        
        /* Responsive Sidebar */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed;
                z-index: 40;
                height: 100vh;
            }
            .content-area {
                width: 100%;
                margin-left: 0;
            }
        }
        
        /* TinyMCE Content Styles */
        .prose {
            color: #1f2937; /* gray-800 */
            max-width: none;
        }
        .dark .prose {
            color: #e5e7eb; /* gray-200 */
        }
        .prose :where(p):not(:where([class~="not-prose"] *)) {
            margin-top: 1.25em;
            margin-bottom: 1.25em;
        }
        .prose :where(ul):not(:where([class~="not-prose"] *)) {
            list-style-type: disc;
            margin-top: 1.25em;
            margin-bottom: 1.25em;
            padding-left: 1.625em;
        }
        .prose :where(ol):not(:where([class~="not-prose"] *)) {
            list-style-type: decimal;
            margin-top: 1.25em;
            margin-bottom: 1.25em;
            padding-left: 1.625em;
        }
        .prose :where(h1, h2, h3, h4, h5, h6):not(:where([class~="not-prose"] *)) {
            color: #111827; /* gray-900 */
            font-weight: 600;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            line-height: 1.25;
        }
        .dark .prose :where(h1, h2, h3, h4, h5, h6):not(:where([class~="not-prose"] *)) {
            color: #f9fafb; /* gray-50 */
        }
        .prose :where(a):not(:where([class~="not-prose"] *)) {
            color: #2563eb; /* blue-600 */
            text-decoration: none;
            font-weight: 500;
        }
        .dark .prose :where(a):not(:where([class~="not-prose"] *)) {
            color: #60a5fa; /* blue-400 */
        }
        .prose :where(a:hover):not(:where([class~="not-prose"] *)) {
            text-decoration: underline;
        }
        .prose :where(blockquote):not(:where([class~="not-prose"] *)) {
            font-style: italic;
            color: #4b5563; /* gray-600 */
            border-left-width: 0.25rem;
            border-left-color: #e5e7eb; /* gray-200 */
            quotes: "\201C""\201D""\2018""\2019";
            margin-top: 1.25em;
            margin-bottom: 1.25em;
            padding-left: 1em;
        }
        .dark .prose :where(blockquote):not(:where([class~="not-prose"] *)) {
            color: #9ca3af; /* gray-400 */
            border-left-color: #4b5563; /* gray-600 */
        }

        
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="flex h-screen overflow-hidden" x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        isMobile: window.innerWidth < 1024,
        init() {
            // Handle window resize
            const handleResize = () => {
                this.isMobile = window.innerWidth < 1024;
                this.sidebarOpen = !this.isMobile;
            };
            
            // Initialize
            handleResize();
            window.addEventListener('resize', handleResize);
            
            // Handle body scroll when sidebar is open on mobile
            this.$watch('sidebarOpen', value => {
                if (this.isMobile) {
                    // Toggle body scroll
                    document.body.style.overflow = value ? 'hidden' : '';
                    
                    // Add/remove no-scroll class to body
                    if (value) {
                        document.body.classList.add('sidebar-open');
                        // Focus trap for better accessibility
                        this.$refs.sidebar.setAttribute('aria-hidden', 'false');
                    } else {
                        document.body.classList.remove('sidebar-open');
                        this.$refs.sidebar.setAttribute('aria-hidden', 'true');
                    }
                }
            });
            
            // Cleanup
            return () => {
                window.removeEventListener('resize', handleResize);
                document.body.style.overflow = '';
            };
        }
    }" @keydown.window.escape="if (isMobile) sidebarOpen = false">
        <!-- Sidebar Backdrop (Mobile) -->
        <div 
            x-show="sidebarOpen && isMobile" 
            x-transition:enter="transition-opacity ease-in-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in-out duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="sidebar-backdrop lg:hidden fixed inset-0 z-40"
            @click="sidebarOpen = false">
        </div>
        
        <!-- Mobile Toggle Button (Fixed Position) -->
        <button 
            @click.stop="sidebarOpen = !sidebarOpen" 
            class="fixed left-4 top-4 p-2 rounded-md bg-white dark:bg-gray-800 shadow-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 z-50 lg:hidden"
            aria-label="Toggle sidebar"
            x-show="!sidebarOpen && window.innerWidth < 1024"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-x-2"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-cloak
        >
            <svg viewBox="64 64 896 896" focusable="false" data-icon="menu-unfold" width="1em" height="1em" fill="currentColor" aria-hidden="true">
                <path d="M408 442h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8zm-8 204c0 4.4 3.6 8 8 8h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56zm504-486H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm0 632H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zM142.4 642.1L298.7 519a8.84 8.84 0 000-13.9L142.4 381.9c-5.8-4.6-14.4-.5-14.4 6.9v246.3a8.9 8.9 0 0014.4 7z"></path>
            </svg>
        </button>
        
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-800 shadow-2xl flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:z-40 lg:w-64 lg:shadow-lg"
            :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen,
                'lg:translate-x-0': !isMobile,
                'lg:w-20': !sidebarOpen && !isMobile,
                'shadow-2xl': isMobile && sidebarOpen
            }"
            @click.away="if (isMobile) sidebarOpen = false"
            x-cloak
            x-ref="sidebar"
            x-trap.noscroll.inert="sidebarOpen && isMobile"
        >
            <!-- Brand Section -->
            <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8 rounded-full" src="{{ asset('images/webicon-spmt.jpg') }}" alt="SPMT Logo">
                        </div>
                        <span class="text-lg font-bold text-gray-800 dark:text-white" x-show="sidebarOpen">
                            SPMT
                            <span class="block text-xs font-normal text-gray-500 dark:text-gray-400">Admin Panel</span>
                        </span>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:block hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white ml-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="flex-1 px-3 py-4 overflow-y-auto" :class="{'py-2': !sidebarOpen}">
                <!-- Navigation Section -->
                <div class="space-y-6" :class="{'space-y-1': !sidebarOpen}">
                    <div class="text-xs font-semibold text-gray-400 uppercase px-3 tracking-wider" x-show="sidebarOpen">Navigation</div>
                    <div class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <div class="flex items-center justify-center w-6 h-6 flex-shrink-0 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <span class="ml-3" x-show="sidebarOpen">Dashboard</span>
                            <span x-show="!sidebarOpen" class="sr-only">Dashboard</span>
                        </a>
                        
                        <!-- Internships -->
                        <a href="{{ route('admin.internships.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.internships.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <div class="flex items-center justify-center w-6 h-6 flex-shrink-0 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M12 16h.01M16 16h.01M8 16h.01M3 20h18a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="ml-3" x-show="sidebarOpen">Lowongan Magang</span>
                            <span x-show="!sidebarOpen" class="sr-only">Lowongan Magang</span>
                        </a>
                        
                        <!-- Applications -->
                        <a href="{{ route('admin.applications.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.applications.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <div class="flex items-center justify-center w-6 h-6 flex-shrink-0 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="ml-3" x-show="sidebarOpen">Lamaran</span>
                            <span x-show="!sidebarOpen" class="sr-only">Lamaran</span>
                        </a>
                        
                        
                        <!-- Active Internships -->
                        <a href="{{ route('admin.students.active-internships') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.students.active-internships') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <div class="flex items-center justify-center w-6 h-6 flex-shrink-0 rounded-md bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-300 group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M12 16h.01M8 16h.01M8 12h.01M12 12h.01M16 12h.01m7.08 7.5c.05.3.075.607.07.914a1.5 1.5 0 01-1.5 1.5h-15a1.5 1.5 0 01-1.5-1.5c0-.31.02-.618.058-.922A6.003 6.003 0 016.105 15c.55-.02 1.1.06 1.64.17.55.12 1.08.3 1.59.54.5.24.96.53 1.4.86.44.33.86.7 1.25 1.1.4-.4.82-.77 1.26-1.1.44-.33.9-.62 1.4-.86.51-.24 1.04-.42 1.59-.54.54-.11 1.09-.19 1.64-.17a6.002 6.002 0 014.932 4.5z" />
                                </svg>
                            </div>
                            <span class="ml-3" x-show="sidebarOpen">Mahasiswa Magang</span>
                            <span x-show="!sidebarOpen" class="sr-only">Mahasiswa Magang</span>
                        </a>
                    </div>
                </div>
                
              <!-- Data Management Section -->
                <div class="space-y-6 mt-8" :class="{'mt-2 space-y-1': !sidebarOpen}">
                    <div class="text-xs font-semibold text-gray-400 uppercase px-3 tracking-wider" x-show="sidebarOpen">Manajemen Data</div>
                    <div class="space-y-1">
                        <!-- Reports -->
                        <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <div class="flex items-center justify-center w-6 h-6 flex-shrink-0 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="ml-3" x-show="sidebarOpen">Laporan Bulanan</span>
                            <span x-show="!sidebarOpen" class="sr-only">Laporan Bulanan</span>
                        </a>
                        
                        <!-- Certificates -->
                        <a href="{{ route('admin.certificates.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.certificates.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <div class="flex items-center justify-center w-6 h-6 flex-shrink-0 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="ml-3" x-show="sidebarOpen">Sertifikat</span>
                            <span x-show="!sidebarOpen" class="sr-only">Sertifikat</span>
                        </a>
                    </div>
                </div>
                
                <!-- Support Section -->
                <div class="space-y-6 mt-8" :class="{'mt-2 space-y-1': !sidebarOpen}">
            <div class="text-xs font-semibold text-gray-400 uppercase px-3 tracking-wider" x-show="sidebarOpen">Support</div>
            <div class="space-y-1">
                        <!-- Issue Reports -->
                        <a href="{{ route('admin.issue-reports.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.issue-reports.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <div class="flex items-center justify-center w-6 h-6 flex-shrink-0 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="ml-3" x-show="sidebarOpen">Laporan Kendala</span>
                            <span x-show="!sidebarOpen" class="sr-only">Laporan Kendala</span>
                        </a>
                    </div>
                </div>
                
                <!-- Logout Section -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200">
                            <div class="flex items-center justify-center w-6 h-6 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <span class="ml-3" x-show="sidebarOpen">Keluar</span>
                            <span x-show="!sidebarOpen" class="sr-only">Keluar</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Content Area -->
        <main class="content-area flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900">
            <!-- Top Navigation -->
            <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-20">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Left side - Empty space for alignment -->
                    <div></div>

                    <!-- Right side - Profile -->
                    <div class="flex items-center">
                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 hidden md:inline-block">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Profile dropdown menu -->
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                                role="menu" 
                                aria-orientation="vertical" 
                                aria-labelledby="user-menu"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                x-cloak
                            >
                                <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Pengaturan
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center" role="menuitem">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
    
    <!-- Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            // Initialize any Alpine.js data here if needed
        });
    </script>
</body>
</html>