@extends('layouts.admin')
@section('title', 'Manajemen Lamaran Magang- Admin Panel')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Daftar Lamaran Magang</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola dan Tinjau Lamaran Magang</p>
            </div>
            
            <!-- Filter Controls -->
            <div class="w-full md:max-w-4xl">
                <form method="GET" action="{{ route('admin.applications.index') }}" class="space-y-3 md:space-y-0 md:space-x-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="relative">
                            <select name="status" onchange="this.form.submit()" 
                                    class="w-full pl-4 pr-10 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-all appearance-none">
                                <option value="all" {{ request('status') == 'all' || !request()->has('status') ? 'selected' : '' }}>Semua Status</option>
                                <option value="terkirim" {{ request('status') == 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Ditinjau</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        
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
                        <a href="{{ route('admin.applications.index') }}" 
                           class="flex items-center justify-center space-x-2 px-4 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ !request()->has('status') && !request()->has('month') && !request()->has('division') ? 'opacity-50 cursor-not-allowed' : '' }}"
                           @if(!request()->has('status') && !request()->has('month') && !request()->has('division')) disabled @endif>
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

    <!-- Applications Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Table Header with Show Entries and Search -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Show Entries -->
            <div class="flex items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400 mr-2">Tampilkan</span>
                <form method="GET" action="{{ route('admin.applications.index') }}" class="inline-flex items-center">
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
                <form method="GET" action="{{ route('admin.applications.index') }}" class="relative">
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
                    <tr>
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
                        
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $sortField === 'name' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Kandidat
                                {!! $sortIcon('name') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => $sortField === 'title' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Posisi
                                {!! $sortIcon('title') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'division', 'direction' => $sortField === 'division' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Divisi
                                {!! $sortIcon('division') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $sortField === 'created_at' ? $direction : 'desc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Tanggal Daftar
                                {!! $sortIcon('created_at') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => $sortField === 'status' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Status
                                {!! $sortIcon('status') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Data Pelamar
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @if($applications->isEmpty())
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="mx-auto h-24 w-24 text-gray-300 dark:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada lamaran ditemukan</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">Saat ini tidak ada lamaran magang.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Muat Ulang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @else
                        @foreach($applications as $application)
                        @php
                            // Cek apakah status_magang = 'in_progress' untuk menandai magang aktif
                            $hasActiveInternship = $application->status_magang === 'in_progress';
                            $activeInternship = $hasActiveInternship ? $application : null;
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors {{ $hasActiveInternship ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 relative">
                                        @if($application->user->studentProfile && $application->user->studentProfile->profile_photo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($application->user->studentProfile->profile_photo) }}" alt="{{ $application->user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm font-medium">
                                                {{ substr($application->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        @if($hasActiveInternship)
                                            <span class="absolute -top-1 -right-1 bg-yellow-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center" 
                                                  title="Mahasiswa ini sudah memiliki magang aktif">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2h-2V9z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white flex items-center gap-1">
                                            {{ $application->user->name }}
                                            @if($hasActiveInternship)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                    Sedang Magang
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $application->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->internship->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->internship->division ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $application->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $application->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @php
                                    $statusInfo = [
                                        'terkirim' => ['label' => 'Terkirim', 'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'],
                                        'diproses' => ['label' => 'Sedang Ditinjau', 'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'],
                                        'diterima' => ['label' => 'Diterima', 'class' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'],
                                        'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200']
                                    ][$application->status] ?? ['label' => $application->status, 'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'];
                                @endphp
                                
                                @php
                                    $hasActiveInternship = $application->user->hasActiveInternship() && $application->status !== 'diterima';
                                    $isDisabled = $hasActiveInternship ? 'opacity-50 cursor-not-allowed' : '';
                                    $tooltip = $hasActiveInternship ? 'Mahasiswa ini sudah memiliki magang aktif' : '';
                                @endphp

                                @if($application->status === 'terkirim')
                                    <div class="space-y-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusInfo['class'] }}">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                        <div class="flex space-x-2" @if($hasActiveInternship) title="{{ $tooltip }}" @endif>
                                            <form action="{{ route('admin.applications.process', $application) }}" method="POST" class="inline-block" @if($hasActiveInternship) onclick="event.preventDefault(); return false;" @endif>
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-2.5 py-1 border border-transparent text-xs font-medium rounded-lg shadow-sm text-yellow-800 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-200 dark:hover:bg-yellow-800 transition-colors {{ $isDisabled }}"
                                                        @if($hasActiveInternship) disabled @endif>
                                                    Mulai Tinjau
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($application->status === 'diproses')
                                    <div class="space-y-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusInfo['class'] }}">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                        <div class="flex space-x-2" @if($hasActiveInternship) title="{{ $tooltip }}" @endif>
                                            <button onclick="@if(!$hasActiveInternship)openApproveModal({{ $application->id }})@endif" 
                                                    class="inline-flex items-center px-2.5 py-1 border border-transparent text-xs font-medium rounded-lg shadow-sm text-green-800 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800 transition-colors {{ $isDisabled }}"
                                                    @if($hasActiveInternship) disabled @endif>
                                                Terima
                                            </button>
                                            <button onclick="@if(!$hasActiveInternship)openRejectModal({{ $application->id }})@endif" 
                                                    class="inline-flex items-center px-2.5 py-1 border border-transparent text-xs font-medium rounded-lg shadow-sm text-red-800 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800 transition-colors {{ $isDisabled }}"
                                                    @if($hasActiveInternship) disabled @endif>
                                                Tolak
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusInfo['class'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                @endif
                            </td>
                            <!-- Kolom Aksi - Hanya tombol Mulai Magang -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($application->status === 'diterima' && $application->status_magang !== 'in_progress' && $application->status_magang !== 'completed')
                                <form id="start-internship-form-{{ $application->id }}" action="{{ route('admin.applications.start-internship', $application) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="button" 
                                            class="start-internship-btn inline-flex items-center px-3 py-1.5 border border-green-200 dark:border-green-800 text-sm font-medium rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-800/50 transition-colors shadow-sm w-full justify-center"
                                            data-application-id="{{ $application->id }}"
                                            data-student-name="{{ $application->user->name }}">
                                        Mulai Magang
                                    </button>
                                </form>
                                @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <!-- Kolom Lihat Data Pelamar -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <a href="{{ route('admin.applications.show', $application) }}" 
                                   class="inline-flex items-center justify-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Data
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($applications->isNotEmpty())
        <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
                    Menampilkan <span class="font-medium">{{ $applications->firstItem() }}</span> sampai <span class="font-medium">{{ $applications->lastItem() }}</span> dari <span class="font-medium">{{ $applications->total() }}</span> lamaran
                </div>
                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    {{-- Tombol Previous --}}
                    @if($applications->onFirstPage())
                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $applications->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif
                    
                    {{-- Nomor Halaman --}}
                    <div class="flex space-x-1">
                        @php
                            $currentPage = $applications->currentPage();
                            $lastPage = $applications->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                            
                            if ($startPage > 1) {
                                echo '<a href="' . $applications->url(1) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($currentPage == 1 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">1</a>';
                                if ($startPage > 2) {
                                    echo '<span class="px-3 py-1.5">...</span>';
                                }
                            }
                            
                            for ($page = $startPage; $page <= $endPage; $page++) {
                                $isCurrent = $page == $currentPage;
                                echo '<a href="' . $applications->url($page) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($isCurrent ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">' . $page . '</a>';
                            }
                            
                            if ($endPage < $lastPage) {
                                if ($endPage < $lastPage - 1) {
                                    echo '<span class="px-3 py-1.5">...</span>';
                                }
                                echo '<a href="' . $applications->url($lastPage) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($currentPage == $lastPage ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">' . $lastPage . '</a>';
                            }
                        @endphp
                    </div>
                    
                    {{-- Tombol Next --}}
                    @if($applications->hasMorePages())
                        <a href="{{ $applications->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
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

<!-- Approval Confirmation Modal -->
<div class="fixed z-30 inset-0 overflow-y-auto hidden" aria-labelledby="approve-modal-title" role="dialog" aria-modal="true" id="approveModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="approve-modal-title">
                        Konfirmasi Persetujuan
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-300">
                            Apakah Anda yakin ingin menyetujui lamaran ini?
                        </p>
                    </div>
                </div>
            </div>
            <form id="approveForm" method="POST" action="">
                @csrf
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Setujui
                    </button>
                    <button type="button" onclick="closeApproveModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="fixed z-20 inset-0 overflow-y-auto hidden" aria-labelledby="reject-modal-title" role="dialog" aria-modal="true" id="rejectModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="reject-modal-title">
                            Tolak Lamaran
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-300">
                                Masukkan alasan penolakan untuk lamaran ini.
                            </p>
                            <div class="mt-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left mb-1">Alasan Penolakan</label>
                                <textarea id="rejection_reason" name="rejection_reason" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                        Tolak Lamaran
                    </button>
                    <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Application ID untuk approval/penolakan
    let currentApplicationId = null;
    let currentRejectId = null;

    // Fungsi untuk menampilkan modal approval
    function openApproveModal(applicationId) {
        currentApplicationId = applicationId;
        const modal = document.getElementById('approveModal');
        const form = document.getElementById('approveForm');
        form.action = `/admin/applications/${applicationId}/approve`;
        modal.classList.remove('hidden');
        document.body.classList.add('modal-open');
    }

    // Fungsi untuk menyembunyikan modal approval
    function closeApproveModal() {
        const modal = document.getElementById('approveModal');
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
        currentApplicationId = null;
    }

    // Handle Start Internship Confirmation
    document.addEventListener('DOMContentLoaded', function() {
        // Tangkap semua tombol mulai magang
        document.querySelectorAll('.start-internship-btn').forEach(button => {
            button.addEventListener('click', function() {
                const studentName = this.getAttribute('data-student-name');
                const formId = this.closest('form').id;
                
                Swal.fire({
                    title: 'Konfirmasi Mulai Magang',
                    html: `Apakah Anda yakin ingin memulai magang untuk <b>${studentName}</b>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Ya, Mulai Magang</span>',
                    cancelButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Batal</span>',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'px-4 py-2.5 text-sm font-medium rounded-lg bg-green-600 hover:bg-green-700 text-white focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors',
                        cancelButton: 'px-4 py-2.5 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600 mr-2'
                    },
                    buttonsStyling: false,
                    showClass: {
                        popup: 'animate-fade-in-up animate-duration-200',
                        backdrop: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80'
                    },
                    hideClass: {
                        popup: 'animate-fade-out-down animate-duration-200'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        const loadingSwal = Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang memulai magang',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit form
                        document.getElementById(formId).submit();
                    }
                });
            });
        });

        // Handle form submission
        // Handle approve form submission
        const approveForm = document.getElementById('approveForm');
        if (approveForm) {
            approveForm.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = 'Menyetujui...';
                this.submit();
            });
        }
        // Tangkap semua form proses
        const processForms = document.querySelectorAll('form[action*="process"]');
        
        processForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const url = form.getAttribute('action');
                const button = form.querySelector('button[type="submit"]');
                const originalButtonText = button.innerHTML;
                
                // Tampilkan loading state
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                
                // Kirim request
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Tampilkan pesan sukses
                        showToast('success', data.message || 'Lamaran berhasil diproses');
                        // Reload halaman setelah 1 detik
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', error.message || 'Terjadi kesalahan saat memproses lamaran');
                    button.disabled = false;
                    button.innerHTML = originalButtonText;
                });
            });
        });
    });
    
    // Fungsi untuk menampilkan toast notifikasi
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Hapus toast setelah 5 detik
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    // Fungsi untuk membuka modal penolakan
    function openRejectModal(applicationId) {
        currentRejectId = applicationId;
        const form = document.getElementById('rejectForm');
        form.action = `/admin/applications/${applicationId}/reject`;
        document.getElementById('rejection_reason').value = '';
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal penolakan
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        currentRejectId = null;
    }
    
    // Tangani submit form penolakan
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
            try {
                // Tampilkan loading state
                submitButton.disabled = true;
                submitButton.innerHTML = 'Memproses...';
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        rejection_reason: formData.get('rejection_reason'),
                        _method: 'POST'
                    })
                });

                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses permintaan');
                }
                
                // Tampilkan pesan sukses
                showToast('success', data.message || 'Lamaran berhasil ditolak');
                
                // Tutup modal dan reload halaman setelah 1.5 detik
                closeRejectModal();
                setTimeout(() => window.location.reload(), 1500);
                
            } catch (error) {
                console.error('Error:', error);
                showToast('error', error.message || 'Terjadi kesalahan saat menolak lamaran');
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }
    

    // Fungsi untuk membuka modal review
    function openReviewModal() {
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal review
    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }

    // Menutup modal saat mengklik di luar modal
    const modals = ['reviewModal', 'rejectModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modalId === 'reviewModal') closeReviewModal();
                    if (modalId === 'rejectModal') closeRejectModal();
                }
            });
        }
    });

    // Menutup modal dengan tombol escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReviewModal();
            closeRejectModal();
        }
    });
</script>
@endpush
@endsection
