@if($internships->isNotEmpty())
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateCountdowns() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        
        document.querySelectorAll('[id^="countdown-"]').forEach(element => {
            const internshipId = element.id.replace('countdown-', '');
            const deadlineElement = document.querySelector(`#deadline-${internshipId}`);
            
            if (!deadlineElement) return;
            
            const [year, month, day] = deadlineElement.dataset.deadline.split('-').map(Number);
            const deadlineDate = new Date(year, month - 1, day);
            
            const timeDiff = deadlineDate - today;
            const daysLeft = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            
            if (daysLeft < 0) {
                element.textContent = 'Pendaftaran ditutup';
                element.classList.remove('text-blue-600', 'dark:text-blue-400');
                element.classList.add('text-red-600', 'dark:text-red-400');
            } else {
                element.textContent = `Sisa waktu: ${daysLeft} hari`;
            }
        });
    }
    
    // Utility function to get accurate scroll position
    function getOptimalScrollPosition(element) {
        const rect = element.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Dynamic offset calculation
        const header = document.querySelector('header, .header, [class*="header"]');
        const navbar = document.querySelector('nav:not([role="navigation"]), .navbar, [class*="nav"]');
        const breadcrumb = document.querySelector('.breadcrumb, [class*="breadcrumb"]');
        
        let totalOffset = 20; // Base padding
        
        if (header) totalOffset += header.offsetHeight;
        if (navbar) totalOffset += navbar.offsetHeight;
        if (breadcrumb) totalOffset += breadcrumb.offsetHeight;
        
        // Add extra padding for better visual positioning
        totalOffset += 60;
        
        const targetPosition = rect.top + scrollTop - totalOffset;
        
        console.log('Optimal scroll calculation:', {
            elementRect: rect,
            currentScroll: scrollTop,
            headerHeight: header?.offsetHeight || 0,
            navbarHeight: navbar?.offsetHeight || 0,
            breadcrumbHeight: breadcrumb?.offsetHeight || 0,
            totalOffset,
            targetPosition
        });
        
        return Math.max(0, targetPosition); // Don't scroll above page top
    }

    updateCountdowns();
    setInterval(updateCountdowns, 86400000);
    
    // Enhanced smooth scroll function with better positioning
    function smoothScrollTo(element, duration = 800, offset = 200) {
        // Calculate better offset based on viewport and potential headers
        const viewportHeight = window.innerHeight;
        const headerHeight = document.querySelector('header')?.offsetHeight || 0;
        const navHeight = document.querySelector('nav')?.offsetHeight || 0;
        const dynamicOffset = Math.max(offset, headerHeight + navHeight + 50);
        
        const targetPosition = element.getBoundingClientRect().top + window.pageYOffset - dynamicOffset;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        let startTime = null;

        console.log('Scroll calculation:', {
            elementTop: element.getBoundingClientRect().top,
            pageYOffset: window.pageYOffset,
            dynamicOffset: dynamicOffset,
            targetPosition: targetPosition
        });

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = easeInOutCubic(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        function easeInOutCubic(t, b, c, d) {
            t /= d/2;
            if (t < 1) return c/2*t*t*t + b;
            t -= 2;
            return c/2*(t*t*t + 2) + b;
        }

        requestAnimationFrame(animation);
    }

    // Enhanced pagination handler
    document.addEventListener('click', function(e) {
        // Only target pagination links specifically
        const paginationLink = e.target.closest('.pagination a, [aria-label="Pagination Navigation"] a');
        
        // Check if it's a pagination link and not disabled
        if (!paginationLink || 
            paginationLink.classList.contains('disabled') ||
            paginationLink.getAttribute('href') === '#' ||
            // Skip if it's a navigation menu link
            paginationLink.closest('header, [role="navigation"]:not([aria-label="Pagination Navigation"])')) {
            return;
        }
        
        // Prevent default only for pagination links
        e.preventDefault();
        e.stopPropagation();
        
        const url = paginationLink.getAttribute('href');
        console.log('Pagination clicked:', url); // Debug log
        
        // Get elements with fallback selectors
        const paginationContainer = document.querySelector('.pagination') || 
                                  document.querySelector('nav[role="navigation"]') ||
                                  document.querySelector('[aria-label="Pagination Navigation"]');
        
        const internshipsSection = document.getElementById('internships');
        const internshipsContainer = document.getElementById('internships-container');
        
        if (!internshipsSection) {
            console.log('Internships section not found, falling back to normal navigation');
            window.location.href = url;
            return;
        }
        
        // Show loading state immediately
        document.body.classList.add('loading');
        
        // Disable pagination during loading
        if (paginationContainer) {
            paginationContainer.style.opacity = '0.6';
            paginationContainer.style.pointerEvents = 'none';
        }
        
        // Add loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'loading-indicator';
        loadingIndicator.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border';
        loadingIndicator.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 dark:text-gray-300">Memuat...</span>
            </div>
        `;
        document.body.appendChild(loadingIndicator);
        
        // Scroll to internships section immediately and more aggressively
        const headerHeight = document.querySelector('header')?.offsetHeight || 0;
        const navHeight = document.querySelector('nav:not([role="navigation"])')?.offsetHeight || 0;
        const totalOffset = headerHeight + navHeight + 80; // Extra padding
        
        const internshipsTop = internshipsSection.getBoundingClientRect().top + window.pageYOffset;
        const targetScroll = internshipsTop - totalOffset;
        
        console.log('Scroll calculation:', {
            internshipsTop,
            headerHeight,
            navHeight,
            totalOffset,
            targetScroll
        });
        
        // Force scroll immediately with multiple methods
        window.scrollTo({
            top: targetScroll,
            behavior: 'smooth'
        });
        
        // Backup scroll method
        setTimeout(() => {
            smoothScrollTo(internshipsSection, 600, totalOffset);
        }, 50);
        
        // Triple backup - ensure we're at the right position
        setTimeout(() => {
            const currentTop = internshipsSection.getBoundingClientRect().top + window.pageYOffset;
            if (Math.abs(window.pageYOffset - (currentTop - totalOffset)) > 50) {
                window.scrollTo({
                    top: currentTop - totalOffset,
                    behavior: 'smooth'
                });
            }
        }, 200);
        
        // Load content with AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, 'text/html');
                
                        // Get new content with multiple fallback selectors
                        const newInternshipsSection = doc.getElementById('internships') ||
                                                    doc.querySelector('.internships-grid') ||
                                                    doc.querySelector('[id*="internship"]');
                        
                        const newPaginationContainer = doc.querySelector('.pagination') || 
                                                     doc.querySelector('nav[role="navigation"]') ||
                                                     doc.querySelector('[aria-label="Pagination Navigation"]');
                        
                        console.log('New content found:', !!newInternshipsSection); // Debug log
                
                if (newInternshipsSection) {
                    // Fade out current content
                    internshipsSection.style.transition = 'opacity 0.4s ease-in-out, transform 0.4s ease-in-out';
                    internshipsSection.style.opacity = '0';
                    internshipsSection.style.transform = 'translateY(20px)';
                    
                    // Update content after fade out
                    setTimeout(() => {
                        // Replace internships content
                        internshipsSection.innerHTML = newInternshipsSection.innerHTML;
                        
                        // Update pagination if exists
                        if (paginationContainer && newPaginationContainer) {
                            paginationContainer.innerHTML = newPaginationContainer.innerHTML;
                        }
                        
                        // Re-initialize countdowns
                        updateCountdowns();
                        
                        // Update URL
                        window.history.pushState({page: url}, '', url);
                        
                        // Force scroll to internships again after content update
                        setTimeout(() => {
                            const headerHeight = document.querySelector('header')?.offsetHeight || 0;
                            const navHeight = document.querySelector('nav:not([role="navigation"])')?.offsetHeight || 0;
                            const totalOffset = headerHeight + navHeight + 80;
                            
                            const updatedTop = internshipsSection.getBoundingClientRect().top + window.pageYOffset;
                            const finalTarget = updatedTop - totalOffset;
                            
                            console.log('Final scroll after content update:', finalTarget);
                            
                            window.scrollTo({
                                top: finalTarget,
                                behavior: 'smooth'
                            });
                            
                            // Extra assurance scroll
                            setTimeout(() => {
                                const checkTop = internshipsSection.getBoundingClientRect().top + window.pageYOffset;
                                if (Math.abs(window.pageYOffset - (checkTop - totalOffset)) > 30) {
                                    window.scrollTo(0, checkTop - totalOffset);
                                }
                            }, 300);
                        }, 50);
                        
                        // Fade in new content
                        setTimeout(() => {
                            internshipsSection.style.opacity = '1';
                            internshipsSection.style.transform = 'translateY(0)';
                            
                            // Re-enable pagination
                            if (paginationContainer) {
                                setTimeout(() => {
                                    paginationContainer.style.opacity = '1';
                                    paginationContainer.style.pointerEvents = 'auto';
                                }, 200);
                            }
                            
                            // Remove loading state
                            document.body.classList.remove('loading');
                            
                            // Remove loading indicator
                            const indicator = document.getElementById('loading-indicator');
                            if (indicator) {
                                indicator.style.opacity = '0';
                                setTimeout(() => indicator.remove(), 300);
                            }
                            
                        }, 100);
                        
                    }, 400); // Match transition duration
                }
            } else {
                // Error fallback
                handleError();
            }
        };
        
        xhr.onerror = handleError;
        
        function handleError() {
            // Remove loading state
            document.body.classList.remove('loading');
            const indicator = document.getElementById('loading-indicator');
            if (indicator) indicator.remove();
            
            // Re-enable pagination
            if (paginationContainer) {
                paginationContainer.style.opacity = '1';
                paginationContainer.style.pointerEvents = 'auto';
            }
            
            // Show error message briefly then redirect
            const errorMsg = document.createElement('div');
            errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white p-3 rounded-lg shadow-lg z-50';
            errorMsg.textContent = 'Terjadi kesalahan, memuat ulang halaman...';
            document.body.appendChild(errorMsg);
            
            setTimeout(() => {
                window.location.href = url;
            }, 1500);
        }
        
        xhr.send();
    });
    
    // Handle browser navigation
    window.addEventListener('popstate', function(e) {
        // Smooth scroll to internships section before reload
        const internshipsSection = document.getElementById('internships');
        if (internshipsSection) {
            smoothScrollTo(internshipsSection, 400, 100);
            setTimeout(() => {
                window.location.reload();
            }, 400);
        } else {
            window.location.reload();
        }
    });
    
    // Add intersection observer for better scroll behavior
    const observerOptions = {
        root: null,
        rootMargin: '-20% 0px -20% 0px',
        threshold: [0, 0.25, 0.5, 0.75, 1]
    };
    
    const internshipsSection = document.getElementById('internships');
    if (internshipsSection) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    internshipsSection.classList.add('in-view');
                } else {
                    internshipsSection.classList.remove('in-view');
                }
            });
        }, observerOptions);
        
        observer.observe(internshipsSection);
    }
    
});
</script>
@endpush

@push('styles')
<style>
    /* Enhanced loading states */
    body.loading {
        cursor: wait;
        overflow-x: hidden;
    }
    
    body.loading * {
        pointer-events: none;
    }
    
    body.loading .pagination {
        pointer-events: auto;
    }
    
    /* Enhanced transitions for internships section */
    #internships {
        transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
        will-change: opacity, transform;
    }
    
    #internships.in-view {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Enhanced pagination styles */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin: 2rem 0;
        padding: 1.5rem 0;
        list-style: none;
        transition: opacity 0.3s ease-in-out;
        position: relative;
    }
    
    .pagination > * {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
        position: relative;
        overflow: hidden;
    }
    
    .pagination a {
        color: #4b5563;
        background-color: white;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        position: relative;
    }
    
    .pagination a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.1), transparent);
        transition: left 0.3s ease-in-out;
    }
    
    .pagination a:hover::before {
        left: 100%;
    }
    
    .pagination a:hover {
        background-color: #f3f4f6;
        color: #1f2937;
        border-color: #10B981;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .pagination .active a {
        background-color: #10B981;
        color: white;
        border-color: #10B981;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }
    
    .pagination .active a:hover {
        background-color: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.4);
    }
    
    .pagination .disabled {
        color: #9ca3af;
        pointer-events: none;
        opacity: 0.5;
    }
    
    /* Navigation arrows enhancement */
    .pagination a[rel="prev"]::before,
    .pagination a[rel="next"]::before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        top: 50%;
        transform: translateY(-50%);
    }
    
    /* Dark mode enhancements */
    .dark .pagination a {
        background-color: #1f2937;
        border-color: #374151;
        color: #d1d5db;
    }
    
    .dark .pagination a:hover {
        background-color: #374151;
        color: white;
        border-color: #10B981;
    }
    
    .dark .pagination .active a {
        background-color: #10B981;
        color: white;
        border-color: #10B981;
    }
    
    .dark .pagination .active a:hover {
        background-color: #059669;
    }
    
    /* Loading indicator styles */
    #loading-indicator {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }
    
    /* Enhanced card hover effects during navigation */
    #internships .bg-white {
        transition: all 0.3s ease-in-out;
    }
    
    body.loading #internships .bg-white {
        transform: scale(0.98);
        opacity: 0.7;
    }
    
    /* Smooth scroll enhancement */
    html {
        scroll-behavior: smooth;
    }
    
    /* Focus styles for accessibility */
    .pagination a:focus {
        outline: 2px solid #10B981;
        outline-offset: 2px;
    }
    
    .dark .pagination a:focus {
        outline-color: #10B981;
    }
    
    /* Mobile responsive enhancements */
    @media (max-width: 768px) {
        .pagination {
            gap: 0.25rem;
            padding: 1rem 0;
        }
        
        .pagination > * {
            min-width: 2rem;
            height: 2rem;
            font-size: 0.75rem;
        }
        
        .pagination a {
            padding: 0 0.5rem;
        }
    }
</style>
@endpush

<div id="internships-container" class="relative">
    <!-- Scroll target marker -->
    <div id="internships-scroll-target" class="absolute -top-20"></div>
    

    <div id="internships" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 -mx-2 sm:mx-0">
        @foreach($internships as $internship)
        <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden transition-all duration-300 border-2 border-blue-500 dark:border-blue-900 shadow-md hover:shadow-xl hover:-translate-y-0.5 relative">
            <div class="p-6 relative z-10">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $internship->title }}</h3>
                    @if($internship->isOpenForApplication())
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $internship->isOpenForApplication() ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }} border {{ $internship->isOpenForApplication() ? 'border-green-200 dark:border-green-700/50' : 'border-red-200 dark:border-red-700/50' }}">
                        {{ $internship->isOpenForApplication() ? 'Dibuka' : 'Ditutup' }}
                    </span>
                    @endif
                </div>
              
                
                <div class="space-y-3 border-t border-gray-100 dark:border-gray-700 pt-3">
                    <div class="flex items-center text-gray-600 dark:text-gray-300 text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $internship->location }}</span>
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-300 text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>{{ $internship->education_qualification_label }}</span>
                    </div>
                    @php
                        $division = $internship->division;
                        $allDivisions = \App\Helpers\DivisionHelper::getAllDivisions();
                        $isValidDivision = in_array($division, $allDivisions);
                    @endphp
                    @if($division)
                    <div class="flex items-center text-gray-600 dark:text-gray-300 text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Divisi: {{ $isValidDivision ? $division : 'Divisi Lainnya' }}</span>
                    </div>
                    @endif
                    @if($internship->start_date && $internship->end_date)
                    <div class="flex items-center text-gray-600 dark:text-gray-300 text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Durasi: {{ $internship->getDurationInMonths() }} Bulan</span>
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-300 text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @php
                            // Hitung jumlah aplikasi yang sudah diterima
                            $acceptedCount = $internship->applications()
                                ->where('status_magang', 'diterima')
                                ->count();
                            $remainingQuota = $internship->quota - $acceptedCount;
                        @endphp
                        <span>Kuota: {{ $remainingQuota }} / {{ $internship->quota }} Orang</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-xs mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span>Dibuat {{ $internship->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                @if($internship->application_deadline)
                    @php
                        $isOpen = $internship->isOpenForApplication();
                        $daysLeft = now()->startOfDay()->diffInDays(
                            \Carbon\Carbon::parse($internship->application_deadline)->startOfDay(),
                            false
                        );
                    @endphp
                    <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-300">Batas Pendaftaran:</span>
                            <span id="deadline-{{ $internship->id }}" data-deadline="{{ $internship->application_deadline->format('Y-m-d') }}" class="font-medium {{ $isOpen ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $internship->application_deadline->translatedFormat('l, j F Y') }}
                            </span>
                        </div>
                        @if($isOpen)
                            <div id="countdown-{{ $internship->id }}" class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                                Sisa waktu: {{ $daysLeft }} hari
                            </div>
                        @else
                            <div class="mt-1 text-xs text-red-600 dark:text-red-400">
                                Pendaftaran ditutup
                            </div>
                        @endif
                    </div>
                @endif

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                        {{ $internship->applications_count ?? 0 }} Pelamar
                    </div>
                    <a href="{{ route('internships.show', $internship) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium flex items-center">
                        Lihat Detail
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Enhanced Pagination -->
@if($internships->hasPages())
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 mt-8">
    {{ $internships->appends(request()->except('page'))->links() }}
</div>
@endif
@else
<div class="text-center py-12">
    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada lowongan tersedia</h3>
    <p class="mt-1 text-gray-500 dark:text-gray-400">Tidak ada lowongan yang sesuai dengan filter yang Anda pilih.</p>
</div>
@endif

