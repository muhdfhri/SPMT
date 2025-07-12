@extends('layouts.internship')
@section('title', $internship->title . ' - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back button -->
        <div class="mb-6">
            <a href="{{ route('internships.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200 group">
                <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-medium">Kembali ke Daftar Lowongan</span>
            </a>
        </div>

        <!-- Internship Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex flex-col space-y-2 mb-4">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">{{ $internship->title }}</h1>
                            <div class="flex flex-wrap items-center gap-2">
                                @if($internship->isOpenForApplication())
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 flex items-center">
                                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                        Buka Pendaftaran
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 flex items-center">
                                        <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                        Pendaftaran Ditutup
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center text-gray-500 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $internship->location }}</span>
                        </div>
                        
                        <!-- Application Deadline -->
                        @if($internship->application_deadline)
                            <div class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-800/50 dark:to-blue-900/20 rounded-lg border-l-4 {{ $internship->isOpenForApplication() ? 'border-green-500' : 'border-red-500' }} transition-all duration-300">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Batas Pendaftaran</h3>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 {{ $internship->isOpenForApplication() ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-lg font-semibold {{ $internship->isOpenForApplication() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $internship->application_deadline->format('d F Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div id="countdown-{{ $internship->id }}" class="flex items-center text-sm font-medium px-3 py-1.5 rounded-full {{ $internship->isOpenForApplication() ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span id="deadline-text">
                                            @php
                                                $daysLeft = now()->startOfDay()->diffInDays(
                                                    \Carbon\Carbon::parse($internship->application_deadline)->startOfDay(),
                                                    false
                                                );
                                            @endphp
                                            @if($internship->isOpenForApplication() && $daysLeft >= 0)
                                                Sisa waktu: {{ $daysLeft }} hari
                                            @else
                                                Pendaftaran ditutup
                                            @endif
                                        </span>
                                    </div>
                                    <div id="deadline-{{ $internship->id }}" data-deadline="{{ $internship->application_deadline->format('Y-m-d') }}" class="hidden"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @auth
                        @if($hasApplied)
                            <div class="flex items-center px-4 py-2.5 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm font-medium rounded-lg whitespace-nowrap">
                                <svg class="w-5 h-5 mr-1.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Sudah Dilamar
                            </div>
                        @elseif(auth()->user()->hasActiveInternship())
                            <div class="flex items-center px-4 py-2.5 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-300 text-sm font-medium rounded-lg whitespace-nowrap">
                                <svg class="w-5 h-5 mr-1.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Anda sudah memiliki magang aktif
                            </div>
                        @elseif($internship->isOpenForApplication())
                            @if($isProfileComplete)
                                <button type="button" 
                                        onclick="openRegistrationModal()"
                                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Daftar Sekarang
                                </button>
                            @else
                                <button type="button" 
                                        onclick="showIncompleteProfileWarning()"
                                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Daftar Sekarang
                                </button>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                            Login untuk Melamar
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Job Overview - Moved up for mobile -->
        <div class="block lg:hidden mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Lowongan</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Divisi</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $internship->division ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kualifikasi Pendidikan</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if($internship->education_qualification == 'SMA/SMK')
                                        SMA/SMK
                                    @elseif($internship->education_qualification == 'Vokasi')
                                        Vokasi (D1/D2/D3/D4)
                                    @elseif($internship->education_qualification == 'S1')
                                        Sarjana (S1)
                                    @else
                                        {{ $internship->education_qualification ?? '-' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kuota Peserta</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $internship->quota }} orang
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Periode Magang</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $internship->start_date->translatedFormat('d F Y') }} - {{ $internship->end_date->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        @if($internship->start_date && $internship->end_date)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Durasi</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $internship->getDurationInMonths() }} bulan
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Job Description -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Deskripsi Lowongan</h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <div class="prose-headings:text-gray-900 dark:prose-headings:text-white">
                                {!! $internship->description ?: '<p class="text-gray-500 dark:text-gray-400">Tidak ada deskripsi tersedia</p>' !!}
                            </div>
                        </div>
                        <style>
                            .prose {
                                color: inherit !important;
                            }
                            .prose p,
                            .prose ul,
                            .prose ol,
                            .prose li {
                                margin: 0.5em 0;
                                line-height: 1.6;
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
                        </style>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Persyaratan</h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <div class="prose-headings:text-gray-900 dark:prose-headings:text-white">
                                {!! $internship->requirements ?: '<p class="text-gray-500 dark:text-gray-400">Tidak ada persyaratan khusus</p>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Hidden on mobile, shown on lg+ -->
            <div class="hidden lg:block space-y-6 sticky top-6">
                <!-- Job Overview -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Lowongan</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Divisi</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $internship->division ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kualifikasi Pendidikan</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        @if($internship->education_qualification == 'SMA/SMK')
                                            SMA/SMK
                                        @elseif($internship->education_qualification == 'Vokasi')
                                            Vokasi (D1/D2/D3/D4)
                                        @elseif($internship->education_qualification == 'S1')
                                            Sarjana (S1)
                                        @else
                                            {{ $internship->education_qualification ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kuota Peserta</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $internship->quota }} orang
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Periode Magang</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $internship->start_date->translatedFormat('d F Y') }} - {{ $internship->end_date->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            @if($internship->start_date && $internship->end_date)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Durasi</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $internship->getDurationInMonths() }} bulan
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('internships.partials.application-modal')

<!-- Incomplete Profile Warning Modal -->
<div id="incompleteProfileModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeIncompleteProfileModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Profil Belum Lengkap
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Mohon lengkapi profil Anda terlebih dahulu sebelum mendaftar magang. Berikut bagian yang perlu dilengkapi:
                            </p>
                            <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                                @foreach($missingSections as $section => $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-2 text-sm text-gray-500">
                                Silakan lengkapi profil Anda di halaman profil sebelum melanjutkan pendaftaran.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <a href="{{ route('mahasiswa.profile.index') }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Ke Halaman Profil
                </a>
                <button type="button" onclick="closeIncompleteProfileModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
// Fungsi untuk update countdown
document.addEventListener('DOMContentLoaded', function() {
    function updateCountdowns() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate()); // Set ke awal hari
        
        document.querySelectorAll('[id^="countdown-"]').forEach(element => {
            const internshipId = element.id.replace('countdown-', '');
            const deadlineElement = document.querySelector(`#deadline-${internshipId}`);
            const textElement = element.querySelector('#deadline-text');
            
            if (!deadlineElement || !textElement) return;
            
            // Parse tanggal deadline (format YYYY-MM-DD)
            const [year, month, day] = deadlineElement.dataset.deadline.split('-').map(Number);
            const deadlineDate = new Date(year, month - 1, day);
            
            // Hitung selisih hari
            const timeDiff = deadlineDate - today;
            const daysLeft = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            
            if (daysLeft < 0) {
                // Jika sudah lewat deadline
                textElement.textContent = 'Pendaftaran ditutup';
                element.classList.remove('bg-green-100', 'text-green-800', 'dark:bg-green-900/30', 'dark:text-green-400');
                element.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900/30', 'dark:text-red-400');
            } else {
                // Tampilkan sisa hari
                textElement.textContent = `Sisa waktu: ${daysLeft} hari`;
                element.classList.remove('bg-red-100', 'text-red-800', 'dark:bg-red-900/30', 'dark:text-red-400');
                element.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-900/30', 'dark:text-green-400');
            }
        });
    }
    
    // Update immediately
    updateCountdowns();
    
    // Update setiap hari (86400000 ms = 1 hari)
    setInterval(updateCountdowns, 86400000);
});
</script>

<script>
// Function to open the registration modal
function showIncompleteProfileWarning() {
    document.getElementById('incompleteProfileModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeIncompleteProfileModal() {
    document.getElementById('incompleteProfileModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openRegistrationModal() {
    const modal = document.getElementById('applicationModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        // Reset to step 1 when opening modal
        nextStep('step2', 'step1');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    function updateRemainingTime() {
        const deadlineElement = document.getElementById('application-deadline');
        if (!deadlineElement) return;
        
        const deadline = new Date(deadlineElement.dataset.deadline);
        const now = new Date();
        
        if (now >= deadline) {
            deadlineElement.textContent = 'Pendaftaran telah ditutup';
            deadlineElement.className = 'text-sm text-red-600 dark:text-red-400';
            return;
        }
        
        // Calculate time difference
        const diff = deadline - now;
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        
        // Format the time left
        const timeLeft = [];
        if (days > 0) timeLeft.push(days + ' hari');
        if (hours > 0) timeLeft.push(hours + ' jam');
        if (minutes > 0 || timeLeft.length === 0) timeLeft.push(minutes + ' menit');
        
        deadlineElement.textContent = 'Sisa waktu: ' + timeLeft.join(', ');
    }
    
    // Update immediately
    updateRemainingTime();
    
    // Then update every minute
    setInterval(updateRemainingTime, 60000);
});

// Function to handle step navigation
function nextStep(currentStepId, nextStepId) {
    const currentStep = document.getElementById(currentStepId);
    const nextStep = document.getElementById(nextStepId);
    
    if (currentStep && nextStep) {
        currentStep.classList.add('hidden');
        nextStep.classList.remove('hidden');
        
        // Update progress indicators
        updateProgressIndicators(nextStepId);
        
        // Scroll to top of the modal content
        const modalContent = document.querySelector('.modal-content');
        if (modalContent) {
            modalContent.scrollTop = 0;
        }
    }
}

function prevStep(currentStepId, prevStepId) {
    const currentStep = document.getElementById(currentStepId);
    const prevStep = document.getElementById(prevStepId);
    
    if (currentStep && prevStep) {
        currentStep.classList.add('hidden');
        prevStep.classList.remove('hidden');
        
        // Update progress indicators
        updateProgressIndicators(prevStepId);
        
        // Scroll to top of the modal content
        const modalContent = document.querySelector('.modal-content');
        if (modalContent) {
            modalContent.scrollTop = 0;
        }
    }
}

function updateProgressIndicators(stepId) {
    const step1Indicator = document.getElementById('step1-indicator');
    const step2Indicator = document.getElementById('step2-indicator');
    const progressConnector = document.getElementById('progress-connector');
    
    if (stepId === 'step2') {
        // Moving to step 2
        if (step1Indicator && step2Indicator && progressConnector) {
            // Update step 1 indicator (completed)
            step1Indicator.classList.remove('bg-blue-100', 'dark:bg-blue-900/30', 'text-blue-600', 'dark:text-blue-400');
            step1Indicator.classList.add('bg-green-100', 'dark:bg-green-900/20', 'border-green-500', 'text-green-600');
            step1Indicator.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            
            // Update step 2 indicator (active)
            step2Indicator.classList.remove('bg-white', 'dark:bg-gray-700', 'text-gray-400', 'border-gray-300', 'dark:border-gray-600');
            step2Indicator.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            
            // Update step labels
            const step1Label = step1Indicator.parentElement.querySelector('span:last-child');
            const step2Label = step2Indicator.parentElement.querySelector('span:last-child');
            
            if (step1Label) step1Label.classList.replace('text-blue-600', 'text-green-600');
            if (step2Label) step2Label.classList.replace('text-gray-500', 'text-blue-600');
            
            // Animate progress connector
            progressConnector.style.width = '100%';
        }
    } else {
        // Moving back to step 1
        if (step1Indicator && step2Indicator && progressConnector) {
            // Reset step 1 indicator (active)
            step1Indicator.classList.remove('bg-green-100', 'dark:bg-green-900/20', 'border-green-500', 'text-green-600');
            step1Indicator.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            step1Indicator.innerHTML = '<span>1</span>';
            
            // Reset step 2 indicator (inactive)
            step2Indicator.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-400', 'border-gray-300', 'dark:border-gray-600');
            step2Indicator.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            step2Indicator.innerHTML = '<span>2</span>';
            
            // Update step labels
            const step1Label = step1Indicator.parentElement.querySelector('span:last-child');
            const step2Label = step2Indicator.parentElement.querySelector('span:last-child');
            
            if (step1Label) step1Label.classList.replace('text-green-600', 'text-blue-600');
            if (step2Label) step2Label.classList.replace('text-blue-600', 'text-gray-500');
            
            // Reset progress connector
            progressConnector.style.width = '0%';
            if (step1Indicator && step2Indicator && progressConnector) {
                // Update step 1 indicator (completed)
                step1Indicator.classList.remove('bg-blue-100', 'dark:bg-blue-900/30', 'text-blue-600', 'dark:text-blue-400');
                step1Indicator.classList.add('bg-green-100', 'dark:bg-green-900/20', 'border-green-500', 'text-green-600');
                step1Indicator.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                
                // Update step 2 indicator (active)
                step2Indicator.classList.remove('bg-white', 'dark:bg-gray-700', 'text-gray-400', 'border-gray-300', 'dark:border-gray-600');
                step2Indicator.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                
                // Update step labels
                const step1Label = step1Indicator.parentElement.querySelector('span:last-child');
                const step2Label = step2Indicator.parentElement.querySelector('span:last-child');
                
                if (step1Label) step1Label.classList.replace('text-blue-600', 'text-green-600');
                if (step2Label) step2Label.classList.replace('text-gray-500', 'text-blue-600');
                
                // Animate progress connector
                progressConnector.style.width = '100%';
            }
        } else {
            // Moving back to step 1
            if (step1Indicator && step2Indicator && progressConnector) {
                // Reset step 1 indicator (active)
                step1Indicator.classList.remove('bg-green-100', 'dark:bg-green-900/20', 'border-green-500', 'text-green-600');
                step1Indicator.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                step1Indicator.innerHTML = '<span>1</span>';
                
                // Reset step 2 indicator (inactive)
                step2Indicator.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-400', 'border-gray-300', 'dark:border-gray-600');
                step2Indicator.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                step2Indicator.innerHTML = '<span>2</span>';
                
                // Update step labels
                const step1Label = step1Indicator.parentElement.querySelector('span:last-child');
                const step2Label = step2Indicator.parentElement.querySelector('span:last-child');
                
                if (step1Label) step1Label.classList.replace('text-green-600', 'text-blue-600');
                if (step2Label) step2Label.classList.replace('text-blue-600', 'text-gray-500');
                
                // Reset progress connector
                progressConnector.style.width = '0%';
            }
        }
    }
}

// Function to validate if all required files are uploaded
function validateFilesUploaded() {
    const requiredFields = ['cv', 'transcript', 'id_card', 'photo'];
    let allValid = true;
    
    requiredFields.forEach(fieldId => {
        const fileInput = document.getElementById(fieldId);
        const useExistingCheckbox = document.getElementById(`use_existing_${fieldId}`);
        const hiddenInput = document.querySelector(`input[name="use_existing_${fieldId}"]`);
        const errorElement = document.getElementById(`${fieldId}-error`);
        
        // Check if either a file is selected or 'use existing' is checked
        const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
        const useExisting = useExistingCheckbox && useExistingCheckbox.checked;
        const hiddenValue = hiddenInput ? hiddenInput.value : '0';
        
        if (!hasFile && !useExisting && hiddenValue === '0') {
            allValid = false;
            if (errorElement) {
                errorElement.textContent = 'Dokumen ini wajib diisi';
                errorElement.classList.remove('hidden');
            }
        } else if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
    });
    
    // Update next button state
    const nextButton = document.querySelector('button[onclick^="nextStep"][onclick*="step1"]');
    if (nextButton) {
        nextButton.disabled = !allValid;
        
        // Update button style based on state
        if (allValid) {
            nextButton.classList.remove('opacity-50', 'cursor-not-allowed');
            nextButton.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
        } else {
            nextButton.classList.add('opacity-50', 'cursor-not-allowed');
            nextButton.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white');
        }
    }
    
    return allValid;
}

// Function to toggle file upload field based on checkbox state
function toggleFileUpload(fieldId, useExisting) {
    const uploadDiv = document.getElementById(`${fieldId}-upload`);
    const fileInput = document.getElementById(fieldId);
    const hiddenInput = document.querySelector(`input[name="use_existing_${fieldId}"]`);
    const existingDocElement = document.getElementById(`${fieldId}-existing`);
    
    if (useExisting) {
        // When using existing document
        if (uploadDiv) uploadDiv.classList.add('hidden');
        if (fileInput) {
            fileInput.removeAttribute('required');
            fileInput.value = ''; // Clear the file input
        }
        
        // Show existing document preview
        if (existingDocElement) {
            existingDocElement.classList.remove('hidden');
        }
        
        // Set hidden input value to indicate using existing file
        if (hiddenInput) {
            hiddenInput.value = '1';
        }
        
        // Clear any error messages
        const errorElement = document.getElementById(`${fieldId}-error`);
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
    } else {
        // When uploading new file
        if (uploadDiv) uploadDiv.classList.remove('hidden');
        if (fileInput) {
            fileInput.setAttribute('required', 'required');
        }
        
        // Hide existing document preview
        if (existingDocElement) {
            existingDocElement.classList.add('hidden');
        }
        
        // Set hidden input value to indicate uploading new file
        if (hiddenInput) {
            hiddenInput.value = '0';
        }
    }
    
    // Re-validate files
    validateFilesUploaded();
}

// Function to handle file selection
function handleFileUpload(input, fieldId) {
    const file = input.files[0];
    const fileNameElement = document.getElementById(`${fieldId}-name`);
    const errorElement = document.getElementById(`${fieldId}-error`);
    const hiddenInput = document.querySelector(`input[name="use_existing_${fieldId}"]`);
    const useExistingCheckbox = document.getElementById(`use_existing_${fieldId}`);
    const fileInputContainer = input.closest('.file-upload-container');
    
    // Reset previous states
    if (errorElement) {
        errorElement.classList.add('hidden');
        errorElement.textContent = '';
    }
    
    // Uncheck 'use existing' checkbox if a new file is selected
    if (useExistingCheckbox && useExistingCheckbox.checked) {
        useExistingCheckbox.checked = false;
        if (hiddenInput) {
            hiddenInput.value = '0';
        }
    }
    
    if (!file) {
        resetFileInput(input, fileNameElement);
        return false;
    }

    // Check file size (2MB max)
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    if (file.size > maxSize) {
        showError(fieldId, 'Ukuran file melebihi 2MB. Maksimal 2MB');
        resetFileInput(input, fileNameElement);
        return false;
    }
    
    // Check file type
    const validTypes = ['application/pdf', 'application/msword', 
                       'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                       'image/jpeg', 'image/jpg', 'image/png'];
    if (!validTypes.includes(file.type)) {
        showError(fieldId, 'Format file tidak didukung. Gunakan PDF, DOC, DOCX, JPG, atau PNG');
        resetFileInput(input, fileNameElement);
        return false;
    }
    
    // Show file info and upload button with better UI
    if (fileNameElement) {
        const displayName = file.name.length > 25 
            ? file.name.substring(0, 12) + '...' + file.name.substring(file.name.length - 10)
            : file.name;
            
        fileNameElement.innerHTML = `
            <div class="flex items-center justify-between w-full">
                <span class="truncate flex-1">${displayName}</span>
                <div class="flex items-center space-x-2 ml-4">
                    <span class="text-xs text-gray-500">${formatFileSize(file.size)}</span>
                    <button type="button" 
                            onclick="uploadSelectedFile('${fieldId}')" 
                            class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 flex items-center">
                        <i class="fas fa-upload mr-1.5"></i> Upload
                    </button>
                </div>
            </div>
        `;
        fileNameElement.className = 'text-sm text-gray-700 dark:text-gray-300 w-full';
    }
    
    // Show file preview if it's an image
    const filePreview = document.getElementById(`${fieldId}-preview`);
    if (filePreview && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            filePreview.innerHTML = `
                <div class="mt-2 relative">
                    <img src="${e.target.result}" alt="Pratinjau" class="h-32 object-cover rounded border border-gray-200 dark:border-gray-600">
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
    
    // Store the file in a data attribute for later upload
    if (fileInputContainer) {
        fileInputContainer.dataset.pendingFile = fieldId;
    }
    
    return false; // Prevent form submission
}

// Helper function to format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Function to upload the selected file
async function uploadSelectedFile(fieldId) {
    const input = document.getElementById(fieldId);
    const file = input.files[0];
    const fileNameElement = document.getElementById(`${fieldId}-name`);
    const errorElement = document.getElementById(`${fieldId}-error`);
    const uploadButton = document.querySelector(`#${fieldId}-name button`);
    const useExistingCheckbox = document.getElementById(`use_existing_${fieldId}`);
    
    if (!file) {
        showError(fieldId, 'Tidak ada file yang dipilih');
        return;
    }
    
    try {
        // Show loading state
        if (uploadButton) {
            uploadButton.disabled = true;
            uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengunggah...';
            uploadButton.classList.add('opacity-75', 'cursor-not-allowed');
        }
        
        // Create FormData
        const formData = new FormData();
        formData.append('document_type', fieldId);
        formData.append('document', file);
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Show loading overlay
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50';
        loadingOverlay.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl max-w-sm w-full mx-4">
                <div class="flex items-center space-x-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <div>
                        <p class="text-gray-800 dark:text-gray-200 font-medium">Mengunggah dokumen...</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Harap tunggu sebentar</p>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
        
        // Send file to server
        const response = await fetch(`/internships/{{ $internship->id }}/upload-document`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Gagal mengunggah dokumen');
        }
        
        // Update UI with new file
        updateFileUI(fieldId, {
            name: file.name,
            size: file.size,
            type: file.type,
            url: result.document?.url || '#'
        }, true);
        
        // Uncheck "use existing" if checked
        if (useExistingCheckbox && useExistingCheckbox.checked) {
            useExistingCheckbox.checked = false;
            const hiddenInput = document.querySelector(`input[name="use_existing_${fieldId}"]`);
            if (hiddenInput) {
                hiddenInput.value = '0';
            }
        }
        
        // Update hidden input with document ID
        const docIdInput = document.querySelector(`input[name="${fieldId}_id"]`);
        if (docIdInput && result.document?.id) {
            docIdInput.value = result.document.id;
        }
        
        // Show success message
        showToast('success', 'Dokumen berhasil diunggah');
        
        // Re-validate form
        validateFilesUploaded();
        
    } catch (error) {
        console.error('Upload error:', error);
        // Show error message to user
        const errorMessage = error.message || 'Gagal mengunggah dokumen';
        const errorElement = document.getElementById(`${fieldId}-error`);
        if (errorElement) {
            errorElement.textContent = errorMessage;
            errorElement.classList.remove('hidden');
        }
        
        // Show error toast
        showToast('error', errorMessage);
    } finally {
        // Remove loading overlay
        const overlay = document.querySelector('.fixed.inset-0.bg-black');
        if (overlay) overlay.remove();
        
        // Reset button state
        if (uploadButton) {
            uploadButton.disabled = false;
            uploadButton.innerHTML = '<i class="fas fa-upload mr-1.5"></i> Upload';
            uploadButton.classList.remove('opacity-75', 'cursor-not-allowed');
        }
        
        // Reset file input
        if (input) input.value = '';
    }
}

// Function to show error message for a specific field
function showError(fieldId, message) {
    const errorElement = document.getElementById(`${fieldId}-error`);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
    
    // Also show as toast
    showToast('error', message);
}

// Helper function to update file UI after upload
function updateFileUI(fieldId, fileData, isUploaded = false) {
    const fileNameElement = document.getElementById(`${fieldId}-name`);
    const existingDocElement = document.getElementById(`${fieldId}-existing`);
    const filePreview = document.getElementById(`${fieldId}-preview`);
    const input = document.getElementById(fieldId);
    const errorElement = document.getElementById(`${fieldId}-error`);
    
    if (!fileNameElement) return;
    
    // Clear any previous errors
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.add('hidden');
    }
    
    // Handle case when no file is selected or invalid file data
    if (!fileData || (input && !input.files?.length && !isUploaded)) {
        fileNameElement.innerHTML = `
            <span class="text-gray-500 dark:text-gray-400">Belum ada file dipilih</span>
            <span class="text-xs text-gray-400">(Maks. 2MB, format: PDF, JPG, PNG, DOC, DOCX)</span>
        `;
        fileNameElement.className = 'text-sm text-gray-500 dark:text-gray-400 flex flex-col';
        
        // Hide and clear previews
        [filePreview, document.getElementById(`${fieldId}-img-preview`)].forEach(el => {
            if (el) {
                el.classList.add('hidden');
                if (el.tagName === 'IMG') el.src = '#';
                if (el.tagName === 'A') el.href = '#';
            }
        });
        
        return;
    }
    
    const displayName = fileData.name?.length > 25 
        ? fileData.name.substring(0, 12) + '...' + fileData.name.substring(fileData.name.length - 10)
        : fileData.name || 'Dokumen';
    
    const fileSize = fileData.size ? formatFileSize(fileData.size) : '';
    const fileUrl = fileData.url || '#';
    
    if (isUploaded) {
        // After successful upload
        fileNameElement.innerHTML = `
            <div class="flex items-center justify-between w-full">
                <span class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="truncate" title="${fileData.name || ''}">${displayName}</span>
                    <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                        Tersimpan
                    </span>
                </span>
                <div class="flex items-center space-x-2">
                    ${fileSize ? `<span class="text-xs text-gray-500">${fileSize}</span>` : ''}
                    <button type="button" 
                            onclick="document.getElementById('${fieldId}').click()" 
                            class="px-2.5 py-1 text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                            title="Ganti file">
                        <i class="fas fa-sync-alt mr-1"></i> Ganti
                    </button>
                </div>
            </div>
        `;
        
        // Update existing document card if it exists
        if (existingDocElement) {
            const now = new Date();
            const formattedDate = now.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            existingDocElement.innerHTML = `
                <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-green-200 dark:border-green-800/50 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate flex items-center">
                                <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                <span class="document-name truncate" title="${fileData.name || ''}">${displayName}</span>
                            </p>
                            <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400 flex-wrap gap-x-2">
                                <span class="document-date">Diperbarui: ${formattedDate}</span>
                                ${fileSize ? `<span>•</span><span>${fileSize}</span>` : ''}
                            </div>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex items-center space-x-1">
                            <a href="${fileUrl}" 
                               target="_blank" 
                               class="p-1.5 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                               title="Pratinjau">
                                <i class="fas fa-eye"></i>
                                <span class="sr-only">Pratinjau</span>
                            </a>
                            <a href="${fileUrl}" 
                               download 
                               class="p-1.5 rounded-full text-gray-400 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors"
                               title="Unduh">
                                <i class="fas fa-download"></i>
                                <span class="sr-only">Unduh</span>
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }
    } else {
        // When file is selected but not yet uploaded
        fileNameElement.innerHTML = `
            <div class="flex items-center justify-between w-full">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate" title="${fileData.name || ''}">
                        ${displayName}
                    </p>
                    ${fileSize ? `<p class="text-xs text-gray-500 dark:text-gray-400">${fileSize}</p>` : ''}
                </div>
                <button type="button" 
                        onclick="uploadSelectedFile('${fieldId}')" 
                        class="ml-3 px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 flex items-center whitespace-nowrap"
                        id="${fieldId}-upload-btn">
                    <i class="fas fa-upload mr-1.5"></i> Upload
                </button>
            </div>
        `;
    }
    
    // Handle file preview
    const file = input?.files?.[0];
    if (filePreview && file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                filePreview.innerHTML = `
                    <div class="mt-2 relative group">
                        <img src="${e.target.result}" 
                             alt="Pratinjau" 
                             class="h-40 w-full object-contain bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                            <a href="${e.target.result}" 
                               target="_blank" 
                               class="text-white bg-black bg-opacity-60 p-2 rounded-full hover:bg-opacity-80 transition-colors"
                               title="Buka di tab baru">
                                <i class="fas fa-expand"></i>
                            </a>
                        </div>
                    </div>
                `;
                filePreview.classList.remove('hidden');
            };
            reader.onerror = () => {
                filePreview.innerHTML = `
                    <div class="mt-2 p-4 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-800 text-center">
                        <i class="fas fa-exclamation-triangle text-amber-500 text-xl mb-2"></i>
                        <p class="text-sm text-amber-700 dark:text-amber-300">Gagal memuat pratinjau</p>
                    </div>
                `;
                filePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            const fileIcon = getFileIcon(file.name);
            filePreview.innerHTML = `
                <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600 text-center">
                    <div class="text-4xl text-gray-400 mb-2">${fileIcon}</div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-1 truncate" title="${file.name}">
                        ${file.name}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">${fileSize} • ${file.type || 'Unknown type'}</p>
                </div>
            `;
            filePreview.classList.remove('hidden');
        }
    } else if (filePreview && !file) {
        filePreview.classList.add('hidden');
    }
    
    // Helper function to get file icon based on extension
    function getFileIcon(filename) {
        if (!filename) return '<i class="far fa-file"></i>';
        
        const ext = filename.split('.').pop().toLowerCase();
        const icons = {
            // Documents
            'pdf': 'file-pdf',
            'doc': 'file-word',
            'docx': 'file-word',
            'xls': 'file-excel',
            'xlsx': 'file-excel',
            'ppt': 'file-powerpoint',
            'pptx': 'file-powerpoint',
            'txt': 'file-alt',
            'csv': 'file-csv',
            // Images
            'jpg': 'file-image',
            'jpeg': 'file-image',
            'png': 'file-image',
            'gif': 'file-image',
            'bmp': 'file-image',
            'svg': 'file-image',
            // Archives
            'zip': 'file-archive',
            'rar': 'file-archive',
            '7z': 'file-archive',
            'tar': 'file-archive',
            'gz': 'file-archive',
            // Code
            'js': 'file-code',
            'html': 'file-code',
            'css': 'file-code',
            'php': 'file-code',
            'json': 'file-code',
        };
        
        const icon = icons[ext] || 'file';
        return `<i class="far fa-${icon}"></i>`;
    }
}


// Function to show toast notifications
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    // Add animation
    toast.style.transition = 'all 0.3s ease';
    toast.style.transform = 'translateX(120%)';
    
    // Trigger reflow
    toast.offsetHeight;
    
    // Slide in
    toast.style.transform = 'translateX(0)';
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(120%)';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 5000);
}

// Function to validate if all required files are uploaded or using existing
function validateFilesUploaded() {
    const requiredFields = ['cv', 'transcript', 'id_card', 'photo'];
    let allValid = true;
    
    requiredFields.forEach(field => {
        const useExistingCheckbox = document.querySelector(`input[name="use_existing_${field}"][type="checkbox"]`);
        const useExistingValue = document.querySelector(`input[name="use_existing_${field}_value"]`);
        const fileInput = document.getElementById(field);
        const errorElement = document.getElementById(`${field}-error`);
        const fileNameElement = document.getElementById(`${field}-name`);
        
        // Check if using existing document
        const isUsingExisting = (useExistingCheckbox && useExistingCheckbox.checked) || 
                              (useExistingValue && useExistingValue.value === '1');
        
        if (isUsingExisting) {
            // If using existing, make sure the field is not required
            if (fileInput) {
                fileInput.removeAttribute('required');
            }
            if (errorElement) {
                errorElement.classList.add('hidden');
            }
            return; // Skip validation for this field
        }
        
        // If not using existing, validate the file
        if (fileInput) {
            fileInput.setAttribute('required', 'required');
            
            // Check if file is selected
            if (!fileInput.files || fileInput.files.length === 0) {
                allValid = false;
                if (errorElement) {
                    errorElement.textContent = 'File ini wajib diunggah';
                    errorElement.classList.remove('hidden');
                }
                return;
            }
            
            const file = fileInput.files[0];
            
            // Check file size (2MB max)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                allValid = false;
                if (errorElement) {
                    errorElement.textContent = 'Ukuran file melebihi 2MB';
                    errorElement.classList.remove('hidden');
                }
                return;
            }
            
            // Check file type
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                allValid = false;
                if (errorElement) {
                    errorElement.textContent = 'Format file tidak didukung. Harap unggah file PDF, JPG, atau PNG.';
                    errorElement.classList.remove('hidden');
                }
                return;
            }
            
            // If we get here, file is valid
            if (errorElement) {
                errorElement.classList.add('hidden');
            }
            
            // Update file name display
            if (fileNameElement) {
                fileNameElement.textContent = file.name;
                fileNameElement.className = 'text-sm text-green-600 dark:text-green-400';
            }
        }
    });
    
    // Update next button state
    const nextButton = document.querySelector('button[onclick^="nextStep("][onclick*="step1"]');
    if (nextButton) {
        nextButton.disabled = !allValid;
        
        // Update button style
        if (allValid) {
            nextButton.classList.remove('opacity-50', 'cursor-not-allowed');
            nextButton.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
        } else {
            nextButton.classList.add('opacity-50', 'cursor-not-allowed');
            nextButton.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white');
        }
    }
    
    return allValid;
}

// Function to validate the form before submission
function validateForm() {
    const submitButton = document.getElementById('submitApplication');
    const agreeTerms = document.getElementById('agreeTerms');
    
    if (!submitButton || !agreeTerms) return false;
    
    const termsAgreed = agreeTerms.checked;
    
    // Update submit button state based on terms agreement only
    submitButton.disabled = !termsAgreed;
    
    // Update button style based on state
    if (termsAgreed) {
        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
    } else {
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white');
    }
    
    return termsAgreed;
}
</script>
@endpush

@endsection
