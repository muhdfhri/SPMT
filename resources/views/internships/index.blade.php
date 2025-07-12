@extends('layouts.internship')

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .internship-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .internship-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .skill-tag {
        transition: all 0.2s ease;
    }
    
    .skill-tag:hover {
        background-color: #0E73B9;
        color: white;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="full mx-auto mt-6">
<section class="relative bg-gradient-to-br from-primary to-secondary text-white py-20 md:py-28 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute inset-0">
            <img src="/images/background-hero2.jpg" 
                 alt="Background" 
                 class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/90 via-primary/70 to-secondary/60"></div>
        </div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">Temukan Lowongan Magang Terbaik</h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">Bergabunglah dengan program magang di PT Pelindo Multi Terminal dan raih pengalaman berharga di industri pelabuhan terkemuka.</p>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white dark:from-gray-900 to-transparent"></div>
</section>

  <!-- Internship Listings Section -->
  <section id="jobs" class="py-8 md:py-12 bg-white dark:bg-[#121211] min-h-[60vh] pb-24 md:pb-12">
        <div id="internships-container" class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Filter Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-4 border-2 border-gray-200 dark:border-gray-700 -mx-2 sm:mx-0">
                <form id="filter-form" method="GET" action="{{ route('internships.index') }}" class="space-y-4" onsubmit="event.preventDefault(); applyFilters();">
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
                    // Skip empty values and the 'filter' parameter
                    if (value && key !== 'filter') {
                        params.append(key, value);
                    }
                });
                
                // Remove any existing filter parameter from the URL
                const url = new URL(window.location.href);
                url.search = params.toString();
                
                // Use history.pushState to update the URL without reloading the page
                window.history.pushState({}, '', url);
                
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
        </div>
    </section>

@endsection
