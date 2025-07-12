@extends('layouts.mahasiswa')
<title>@yield('title', 'Aktivitas Magang - SPMT')</title>

@section('content')

{{-- Handle alert messages passed from controller --}}
@if(isset($alertMessages) && count($alertMessages) > 0)
    @foreach($alertMessages as $alert)
        @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                showToast('{{ $alert['type'] }}', '{{ addslashes($alert['message']) }}');
            });
        </script>
        @endpush
    @endforeach
@endif
<div class="container mx-auto px-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden p-6">
        <!-- Welcome Section with Animation -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600 dark:text-gray-400">Berikut adalah ringkasan aktivitas magang Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Profile Card with Hover Effect -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="rounded-full p-3 mr-3 shadow-md" style="background: linear-gradient(to right, #55B7E3, #0E73B9);">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Profil</h2>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ $profile ?? false ? 'Profil sudah dilengkapi' : 'Lengkapi profil Anda' }}
                </p>

                <a href="{{ route('mahasiswa.profile.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                    Lihat Profile
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Applications Card with Hover Effect -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="rounded-full p-3 mr-3 shadow-md" style="background: linear-gradient(to right, #55B7E3, #0E73B9);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Lamaran Saya</h2>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ isset($applications) && $applications->count() > 0 ? 'Lihat status lamaran Anda' : 'Anda belum mengirim lamaran' }}
                </p>
                <a href="{{ route('mahasiswa.applications') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-purple-800 dark:hover:text-purple-300 transition-colors duration-200">
                    Lihat Lamaran
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Reports Card with Hover Effect -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="rounded-full p-3 mr-3 shadow-md" style="background: linear-gradient(to right, #55B7E3, #0E73B9);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Laporan Bulanan</h2>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ isset($reports) && $reports->count() > 0 ? 'Lihat atau buat laporan bulanan' : 'Buat laporan bulanan Anda' }}
                </p>
                <a href="{{ route('mahasiswa.reports.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                    Kelola Laporan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

             <!-- Certificate Card with Hover Effect -->
             <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center mb-4">
                    @php
                        $hasCertificates = $certificates->count() > 0;
                        $isRevoked = $hasCertificates && $certificates->first()->status === \App\Models\Certificate::STATUS_REVOKED;
                        $bgClass = $isRevoked ? 'bg-red-500' : ($hasCertificates ? 'bg-green-500' : 'bg-gray-400');
                        $gradientFrom = $isRevoked ? '#EF4444' : ($hasCertificates ? '#10B981' : '#9CA3AF');
                        $gradientTo = $isRevoked ? '#DC2626' : ($hasCertificates ? '#059669' : '#6B7280');
                        $icon = $isRevoked ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                    @endphp
                    <div class="rounded-full p-3 mr-3 shadow-md {{ $bgClass }}" style="background: linear-gradient(to right, {{ $gradientFrom }}, {{ $gradientTo }});">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Sertifikat Magang</h2>
                        @if($hasCertificates)
                            <p class="text-sm {{ $isRevoked ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                @if($isRevoked)
                                    Dicabut
                                @else
                                    {{ $certificates->count() }} sertifikat tersedia
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                
                @if($hasCertificates)
                    @php
                        $latestCert = $certificates->first();
                        $issueDate = \Carbon\Carbon::parse($latestCert->issue_date)->translatedFormat('d F Y');
                        $revokedDate = $latestCert->revoked_at ? \Carbon\Carbon::parse($latestCert->revoked_at)->translatedFormat('d F Y') : null;
                    @endphp
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                        <span class="font-medium">Diterbitkan:</span> {{ $issueDate }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                        No. Sertifikat: {{ $latestCert->certificate_number }}
                    </p>
                @else
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Belum ada sertifikat magang yang tersedia. Sertifikat akan muncul setelah menyelesaikan program magang dan diverifikasi oleh admin.
                    </p>
                @endif
                
                <a href="{{ route('mahasiswa.certificates.index') }}" class="inline-flex items-center mt-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                    {{ $certificates->count() > 0 ? 'Lihat Semua Sertifikat' : 'Lihat Halaman Sertifikat' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Recent Applications with Animation -->
        <div class="mt-8 animate-slide-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Lamaran Terbaru</h2>
                <div class="h-1 flex-1 mx-4 bg-gradient-to-r from-blue-500 to-transparent"></div>
            </div>

            @if(isset($applications) && $applications->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-all duration-300 hover:shadow-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lowongan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Lamar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($applications->take(3) as $application)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $application->internship->title ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $application->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($application->status == 'terkirim')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 animate-pulse">
                                        Terkirim
                                    </span>
                                    @elseif($application->status == 'diproses')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Diproses
                                    </span>
                                    @elseif($application->status == 'ditolak')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Ditolak
                                    </span>
                                    @elseif($application->status == 'diterima')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Diterima
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('mahasiswa.applications.show', $application) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                                     Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($applications->count() > 3)
            <div class="mt-4 text-right">
                <a href="{{ route('mahasiswa.applications') }}"
                    class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                    Lihat Semua Lamaran
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            @endif
            @else
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-lg transform transition-all duration-300 hover:shadow-xl">
                <div class="text-gray-400 dark:text-gray-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Anda belum mengirim lamaran magang.</p>
                <a href="{{ route('mahasiswa.internships.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    Lihat Lowongan Magang
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
            @endif
        </div>

        <!-- Recent Reports with Animation -->
        <div class="mt-8 animate-slide-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Laporan Terbaru</h2>
                <div class="h-1 flex-1 mx-4 bg-gradient-to-r from-blue-500 to-transparent"></div>
            </div>

            @if(isset($reports) && $reports->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-all duration-300 hover:shadow-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                            $months = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                            ];
                            @endphp

                            @foreach($reports->take(3) as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $months[$report->month] ?? 'N/A' }} {{ $report->year ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $report->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('mahasiswa.reports.show', $report) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($reports->count() > 3)
            <div class="mt-4 text-right">
                <a href="{{ route('mahasiswa.reports.index') }}"
                    class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                    Lihat Semua Laporan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            @endif
            @else
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-lg transform transition-all duration-300 hover:shadow-xl">
                <div class="text-gray-400 dark:text-gray-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Anda belum membuat laporan bulanan.</p>
                <a href="{{ route('mahasiswa.reports.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    Buat Laporan Bulanan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

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

<!-- Add these styles to your CSS -->
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }

    .animate-slide-up {
        animation: slide-up 0.5s ease-out;
    }

    .hover\:scale-105:hover {
        transform: scale(1.05);
    }

    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
</style>
@endsection