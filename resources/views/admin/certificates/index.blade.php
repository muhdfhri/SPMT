@extends('layouts.admin')
@section('title', 'Kelola Sertifikat - Admin Panel')

@php
    // Helper function to get sort direction
    $getSortDirection = function($field) use ($sortField, $sortDirection) {
        if ($sortField !== $field) return 'asc';
        return $sortDirection === 'asc' ? 'desc' : 'asc';
    };
    
    // Helper function to get sort icon
    $sortIcon = function($field) use ($sortField, $sortDirection) {
        $baseClass = 'w-4 h-4 ml-1.5 inline-flex items-center justify-center';
        
        if ($sortField !== $field) {
            return '<span class="inline-flex flex-col space-y-0.5">
                <svg class="' . $baseClass . ' text-gray-300 dark:text-gray-500 -mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                <svg class="' . $baseClass . ' text-gray-300 dark:text-gray-500 -mt-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </span>';
        }
        
        if ($sortDirection === 'asc') {
            return '<span class="inline-flex flex-col space-y-0.5">
                <svg class="' . $baseClass . ' text-blue-600 dark:text-blue-400 -mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                <svg class="' . $baseClass . ' text-gray-300 dark:text-gray-500 -mt-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </span>';
        }
        
        return '<span class="inline-flex flex-col space-y-0.5">
            <svg class="' . $baseClass . ' text-gray-300 dark:text-gray-500 -mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
            <svg class="' . $baseClass . ' text-blue-600 dark:text-blue-400 -mt-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </span>';
    };
@endphp

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="space-y-1">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Manajemen Sertifikat</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola dan terbitkan sertifikat untuk mahasiswa</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Daftar Aplikasi Siap Sertifikat -->
    @if($eligibleApplications->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Siap Sertifikat</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Berikut adalah daftar mahasiswa yang sudah menyelesaikan semua laporan dan siap untuk diterbitkan sertifikatnya.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        @php
                            $nameSortDirection = $getSortDirection('name');
                            $nameSortUrl = request()->fullUrlWithQuery([
                                'sort_field' => 'name',
                                'sort_direction' => $nameSortDirection
                            ]);
                        @endphp
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700" onclick="window.location='{{ $nameSortUrl }}'">
                            <div class="flex items-center">
                                <span>Nama Mahasiswa</span>
                                {!! $sortIcon('name') !!}
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Program Magang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status Sertifikat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($eligibleApplications as $application)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 relative">
                                    @if(optional($application->user)->studentProfile && optional($application->user->studentProfile)->profile_photo)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ Storage::url($application->user->studentProfile->profile_photo) }}" 
                                             alt="{{ optional($application->user)->name ?? 'User' }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm font-medium">
                                            {{ optional($application->user)->name ? substr($application->user->name, 0, 1) : '?' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ optional($application->user)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ optional($application->user)->email ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ optional(optional($application->user)->studentProfile)->nik ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ optional($application->internship)->title ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $application->internship && $application->internship->start_date ? \Carbon\Carbon::parse($application->internship->start_date)->translatedFormat('j M Y') : 'N/A' }} - 
                                {{ $application->internship && $application->internship->end_date ? \Carbon\Carbon::parse($application->internship->end_date)->translatedFormat('j M Y') : 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($application->certificate)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Sudah Digenerate
                                </span>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ optional($application->certificate->created_at)->translatedFormat('j M Y') }}
                                </div>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Belum Digenerate
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if(!$application->certificate)
                                <form action="{{ route('admin.certificates.generate', $application->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Generate Sertifikat
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('admin.certificates.show', $application->certificate) }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Sertifikat
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Daftar Sertifikat -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Sertifikat</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Berikut adalah daftar sertifikat yang telah diterbitkan.</p>
            </div>
            <div class="px-6 pb-4">
                <form action="{{ route('admin.certificates.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full">
                    <div class="relative flex-grow">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Cari nama mahasiswa..." 
                                class="block w-full pl-10 pr-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-all"
                            >
                        </div>
                    </div>
                    <div class="relative">
                        <select 
                            name="status" 
                            onchange="this.form.submit()"
                            class="w-full pl-4 pr-10 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-all appearance-none"
                        >
                            <option value="">Semua Status</option>
                            @foreach(\App\Models\Certificate::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex-shrink-0 flex items-center justify-center"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nomor Sertifikat</th>
                        @php
                            $certNameSortDirection = $getSortDirection('name');
                            $certNameSortUrl = request()->fullUrlWithQuery([
                                'sort_field' => 'name',
                                'sort_direction' => $certNameSortDirection
                            ]);
                        @endphp
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700" onclick="window.location='{{ $certNameSortUrl }}'">
                            <div class="flex items-center">
                                <span>Nama Mahasiswa</span>
                                {!! $sortIcon('name') !!}
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Program Magang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Terbit</th>

                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($certificates as $certificate)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $certificate->certificate_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 relative">
                                    @if(optional($certificate->user)->studentProfile && optional($certificate->user->studentProfile)->profile_photo)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($certificate->user->studentProfile->profile_photo) }}" alt="{{ $certificate->user->name ?? 'User' }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300 font-medium">
                                            {{ optional($certificate->user)->name ? strtoupper(substr($certificate->user->name, 0, 1)) : '?' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ optional($certificate->user)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ optional($certificate->user)->email ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ optional(optional($certificate->user)->studentProfile)->nik ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($certificate->internship)
                                <div class="text-sm text-gray-900 dark:text-white">{{ $certificate->internship->title }}</div>
                                @if($certificate->internship->company)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $certificate->internship->company->name }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $certificate->internship && $certificate->internship->start_date ? \Carbon\Carbon::parse($certificate->internship->start_date)->translatedFormat('j M Y') : 'N/A' }} - 
                                {{ $certificate->internship && $certificate->internship->end_date ? \Carbon\Carbon::parse($certificate->internship->end_date)->translatedFormat('j M Y') : 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {!! $certificate->status_badge !!}
                            @if($certificate->isRevoked() && $certificate->revoked_reason)
                                <div class="text-xs text-red-600 dark:text-red-400 mt-1" title="Alasan pembatalan: {{ $certificate->revoked_reason }}">
                                    {{ Str::limit($certificate->revoked_reason, 30) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $certificate->issue_date->translatedFormat('j M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                    <td colspan="7" class="px-6 py-16">
                        <div class="flex flex-col items-center justify-center w-full">
                            <div class="mx-auto text-gray-300 dark:text-gray-600">
                                <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-center text-gray-900 dark:text-white">Belum ada sertifikat</h3>
                            <p class="mt-2 text-center text-gray-500 dark:text-gray-400">Tidak ada sertifikat yang tersedia saat ini.</p>
                        </div>
                    </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Generate Certificate Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="generateModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
            <div>
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Generate Sertifikat
                    </h3>
                    <div class="mt-4 space-y-4">
                        <form id="generateCertificateForm" method="POST">
                            @csrf
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mahasiswa</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Pilih Mahasiswa</option>
                                    @foreach($eligibleStudents ?? [] as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->studentProfile->nim ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                                <input type="date" id="end_date" name="end_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label for="certificate_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Sertifikat</label>
                            <input type="text" id="certificate_number" name="certificate_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Contoh: CERT/2023/001">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="submit" form="generateCertificateForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                    Generate Sertifikat
                </button>
                <button type="button" onclick="closeGenerateModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openGenerateModal() {
        document.getElementById('generateModal').classList.remove('hidden');
    }

    function closeGenerateModal() {
        document.getElementById('generateModal').classList.add('hidden');
    }

    // Update form action based on selected user
    document.getElementById('user_id').addEventListener('change', function() {
        const userId = this.value;
        const form = document.getElementById('generateCertificateForm');
        
        if (userId) {
            form.action = '{{ url("admin/certificates") }}/' + userId + '/generate';
        } else {
            form.action = '';
        }
    });

    // Close modal when clicking outside
    document.getElementById('generateModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeGenerateModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeGenerateModal();
        }
    });
</script>
@endpush
@endsection


