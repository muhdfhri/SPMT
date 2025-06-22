@extends('layouts.admin')
@section('title', 'Kelola Peserta Magang - Admin Panel')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Data Mahasiswa Aktif Magang</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftar mahasiswa yang sedang menjalani magang</p>
            </div>
            
            <!-- Filter Controls -->
            <div class="w-full md:w-auto">
                <form method="GET" action="{{ route('admin.students.active-internships') }}" class="space-y-3 md:space-y-0 md:space-x-3 flex flex-col md:flex-row items-end md:items-center">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 w-full">
                        <div class="relative">
                            <select name="division" onchange="this.form.submit()" 
                                    class="w-full pl-4 pr-10 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-all appearance-none">
                                <option value="all">Semua Divisi</option>
                                @foreach(\App\Helpers\DivisionHelper::getAllDivisions() as $division)
                                    <option value="{{ $division }}" {{ request('division') == $division ? 'selected' : '' }}>{{ $division }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="month" name="month" value="{{ request('month') }}" onchange="this.form.submit()" 
                                   class="w-full pl-9 pr-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-all">
                        </div>
                        
                        <!-- Always show Reset Filter button -->
                        <a href="{{ route('admin.students.active-internships') }}" 
                           class="flex items-center justify-center space-x-2 px-4 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ !request()->has('month') && !request()->has('division') ? 'opacity-50 cursor-not-allowed' : '' }}"
                           @if(!request()->has('month') && !request()->has('division')) disabled @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset Filter</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Table Header with Show Entries and Search -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Show Entries -->
            <div class="flex items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400 mr-2">Tampilkan</span>
                <form method="GET" action="{{ route('admin.students.active-internships') }}" class="inline-flex items-center">
                    <div class="relative">
                        <select name="per_page" onchange="this.form.submit()" class="pl-2 pr-7 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-colors appearance-none">
                            <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">data</span>
                    
                    <!-- Hidden inputs to preserve other filters -->
                    @foreach(request()->except('per_page', 'page') as $key => $value)
                        @if($value && !is_array($value))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                </form>
            </div>
            
            <!-- Search Form -->
            <div class="w-full md:w-1/3">
                <form method="GET" action="{{ route('admin.students.active-internships') }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari nama..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-all"
                           onchange="this.form.submit()"
                           autocomplete="off">
                    
                    <!-- Hidden fields to preserve other parameters -->
                    @foreach(request()->except('search', 'page') as $key => $value)
                        @if($value && !is_array($value))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if(request('direction'))
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    @php
                        $sortField = request('sort', 'created_at');
                        $sortDirection = request('direction', 'desc');
                        $direction = $sortDirection === 'asc' ? 'desc' : 'asc';
                        
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
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $sortField === 'name' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Nama Mahasiswa
                                {!! $sortIcon('name') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nik', 'direction' => $sortField === 'nik' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                NIK
                                {!! $sortIcon('nik') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Posisi Magang
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'division', 'direction' => $sortField === 'division' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Divisi
                                {!! $sortIcon('division') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_date', 'direction' => $sortField === 'start_date' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Periode
                                {!! $sortIcon('start_date') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status Magang
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($students as $student)
                        @php
                            $latestApplication = $student->applications->first();
                            $internship = $latestApplication->internship ?? null;
                        @endphp
                        @php
                            $hasActiveInternship = $student->applications->contains('status_magang', 'in_progress');
                            $latestApplication = $student->applications->first();
                            $internship = $latestApplication->internship ?? null;
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors {{ $hasActiveInternship ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 relative">
                                        @if($student->studentProfile && $student->studentProfile->profile_photo)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ Storage::url($student->studentProfile->profile_photo) }}" 
                                                 alt="{{ $student->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm font-medium">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                        @endif
                                        @if($hasActiveInternship)
                                            <span class="absolute -top-1 -right-1 bg-yellow-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center" 
                                                  title="Mahasiswa ini sedang menjalani magang">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2h-2V9z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $student->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $student->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $student->student_nik ?? ($student->studentProfile->nik ?? 'N/A') }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $internship->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $internship->division ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($internship && $internship->start_date && $internship->end_date)
                                    {{ \Carbon\Carbon::parse($internship->start_date)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($internship->end_date)->format('d M Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                @if($latestApplication && $latestApplication->status_magang === 'in_progress')
                                    <button onclick="completeInternship({{ $latestApplication->id }}, this)" 
                                            class="inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-800/50 transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tandai Selesai
                                    </button>
                                @elseif($latestApplication && $latestApplication->status_magang === 'completed')
                                    <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Selesai
                                    </span>
                                @elseif($latestApplication && $latestApplication->status_magang === 'completed')
                                    <span class="inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                        Selesai
                                    </span>
                                @endif
                            </div>
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto h-24 w-24 text-gray-300 dark:text-gray-600">
                                    <svg class="h-24 w-24 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada mahasiswa yang sedang magang</h3>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">Saat ini tidak ada mahasiswa yang sedang menjalani magang.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($students->isNotEmpty())
        <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
                    Menampilkan <span class="font-medium">{{ $students->firstItem() }}</span> sampai <span class="font-medium">{{ $students->lastItem() }}</span> dari <span class="font-medium">{{ $students->total() }}</span> mahasiswa
                </div>
                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    {{-- Tombol Previous --}}
                    @if($students->onFirstPage())
                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $students->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif
                    
                    {{-- Nomor Halaman --}}
                    <div class="flex space-x-1">
                        @php
                            $currentPage = $students->currentPage();
                            $lastPage = $students->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                            
                            if ($startPage > 1) {
                                echo '<a href="' . $students->url(1) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($currentPage == 1 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">1</a>';
                                if ($startPage > 2) {
                                    echo '<span class="px-3 py-1.5">...</span>';
                                }
                            }
                            
                            for ($page = $startPage; $page <= $endPage; $page++) {
                                $isCurrent = $page == $currentPage;
                                echo '<a href="' . $students->url($page) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($isCurrent ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">' . $page . '</a>';
                            }
                            
                            if ($endPage < $lastPage) {
                                if ($endPage < $lastPage - 1) {
                                    echo '<span class="px-3 py-1.5">...</span>';
                                }
                                echo '<a href="' . $students->url($lastPage) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($currentPage == $lastPage ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">' . $lastPage . '</a>';
                            }
                        @endphp
                    </div>
                    
                    {{-- Tombol Next --}}
                    @if($students->hasMorePages())
                        <a href="{{ $students->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
    function completeInternship(applicationId, button) {
        const originalHtml = button.innerHTML;
        
        Swal.fire({
            title: 'Konfirmasi Penyelesaian Magang',
            html: 'Apakah Anda yakin ingin menandai magang ini sebagai selesai?<br><span class="text-sm text-gray-500">Pastikan semua laporan sudah disetujui.</span>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tandai Selesai',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Disable button dan tampilkan loading
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg> Memproses...
                `;
                
                return fetch(`/admin/active-internships/applications/${applicationId}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Status magang berhasil diperbarui menjadi Selesai',
                    icon: 'success',
                    confirmButtonColor: '#10B981',
                    timer: 2000,
                    timerProgressBar: true,
                    willClose: () => {
                        window.location.reload();
                    }
                });
            }
        }).catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Gagal!',
                html: error.message || 'Terjadi kesalahan saat memperbarui status magang',
                icon: 'error',
                confirmButtonColor: '#EF4444'
            });
            button.disabled = false;
            button.innerHTML = originalHtml;
        });
        
        return;

        // Kirim permintaan ke server
        fetch(`/admin/active-internships/applications/${applicationId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Tampilkan pesan sukses dan refresh halaman
                window.location.reload();
                // Ganti tombol dengan teks "Selesai"
                button.outerHTML = `
                    <span class="inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                        Selesai
                    </span>
                `;
                
                // Tampilkan alert sukses
                alert('Status magang berhasil diperbarui menjadi Selesai');
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Gagal memperbarui status magang');
            button.disabled = false;
            button.innerHTML = originalHtml;  // Mengganti originalText dengan originalHtml
        });
    }
</script>
@endpush
@endsection
