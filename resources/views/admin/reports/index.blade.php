@extends('layouts.admin')
@section('title', 'Kelola Laporan Bulanan - Admin Panel')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Daftar Laporan Mahasiswa</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftar mahasiswa yang memiliki laporan bulanan</p>
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
                <form method="GET" action="{{ route('admin.reports.index') }}" class="inline-flex items-center">
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
                <form method="GET" action="{{ route('admin.reports.index') }}" class="relative">
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
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    @php
                        $sortField = request('sort_field', '');
                        $sortDirection = request('sort_direction', '');
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
                            <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'name', 'sort_direction' => $sortField === 'name' ? $direction : 'asc']) }}" class="group flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                Nama Mahasiswa
                                {!! $sortIcon('name') !!}
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            NIK
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Ringkasan Laporan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status Laporan
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                    @php
                        $application = $user->applications->first();
                        $showViewButton = false;
                        $buttonText = 'Lihat Laporan';
                        $buttonClass = 'bg-blue-600 hover:bg-blue-700';
                        $allReportsCompleted = false;
                        $totalMonths = 0;
                        
                        if ($application) {
                            // Hitung total bulan magang
                            if ($application->internship) {
                                $startDate = \Carbon\Carbon::parse($application->internship->start_date);
                                $endDate = \Carbon\Carbon::parse($application->internship->end_date);
                                $totalMonths = $startDate->diffInMonths($endDate) + 1; // +1 untuk inklusif bulan terakhir
                                
                                // Hitung jumlah laporan yang sudah disetujui
                                $approvedReportsCount = $application->monthlyReports->where('status', 'approved')->count();
                                
                                // Cek apakah semua laporan sudah lengkap dan disetujui
                                $allReportsCompleted = ($approvedReportsCount >= $totalMonths) && 
                                                    ($application->monthlyReports->count() >= $totalMonths);
                            }
                            
                            // Logika untuk tombol aksi
                            if ($application->monthlyReports->count() > 0) {
                                $showViewButton = true;
                                
                                // Cek ada laporan yang menunggu review
                                $pendingReviews = $application->monthlyReports->whereIn('status', ['submitted', 'Menunggu Review', 'Pending', 'pending']);
                                
                                if ($pendingReviews->count() > 0) {
                                    $buttonText = 'Review Laporan (' . $pendingReviews->count() . ')';
                                    $buttonClass = 'bg-yellow-600 hover:bg-yellow-700';
                                }
                            }
                        }
                    @endphp
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 relative">
                                    @if($user->studentProfile && $user->studentProfile->profile_photo)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ Storage::url($user->studentProfile->profile_photo) }}" 
                                             alt="{{ $user->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm font-medium">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $user->studentProfile->nik ?? '-' }}
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3">
                            <div class="min-w-[180px]">
                                @php
                                    $application = $user->applications->first();
                                    $totalMonths = 0;
                                    $reportsCount = $user->monthly_reports_count ?? 0;
                                    $hasCompleteReports = false;
                                    $expectedMonths = 0;
                                    $approvedCount = 0;
                                    
                                    if ($application) {
                                        $approvedCount = $application->monthlyReports->where('status', 'approved')->count();
                                        $hasCompleteReports = $application->hasCompletedAllReports();
                                        
                                        if ($application->internship) {
                                            $startDate = \Carbon\Carbon::parse($application->internship->start_date);
                                            $endDate = \Carbon\Carbon::parse($application->internship->end_date);
                                            $expectedMonths = round($startDate->floatDiffInMonths($endDate) + 0.5); // Round to nearest whole month
                                        }
                                    }
                                @endphp
                                
                                @if($user->pending_reviews_count > 0)
                                    <div class="inline-flex items-center mb-1">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200 whitespace-nowrap">
                                            {{ $user->pending_reviews_count }} Menunggu Review
                                        </span>
                                    </div>

                                @elseif($hasCompleteReports)
                                    <div class="inline-flex items-center">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200 whitespace-nowrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Semua Laporan Lengkap
                                        </span>
                                    </div>

                                @elseif($approvedCount > 0)
                                    <div class="inline-flex items-center">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200 whitespace-nowrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $approvedCount }}/{{ $expectedMonths }} Laporan
                                        </span>
                                    </div>

                                @else
                                    <div class="inline-flex items-center">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap">
                                            Belum Ada Laporan
                                        </span>
                                    </div>

                                @endif
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3">
                            <div class="min-w-[150px]">
                                @if($application)
                                    @if($approvedCount > 0)
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $approvedCount }}/{{ $expectedMonths }} laporan disetujui
                                        </div>
                                        @if($approvedCount < $expectedMonths)
                                            <div class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                                {{ $expectedMonths - $approvedCount }} laporan belum disetujui
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            0/{{ $expectedMonths }} laporan disetujui
                                        </div>
                                        <div class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                            {{ $expectedMonths }} laporan perlu disetujui
                                        </div>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end">
                                @if($user->monthlyReports->count() > 0)
                                    @php
                                        $latestReport = $user->monthlyReports->sortByDesc('created_at')->first();
                                        $hasPendingReviews = $user->pending_reviews_count > 0;
                                    @endphp
                                    <a href="{{ route('admin.reports.show', $latestReport) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ $hasPendingReviews ? 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ $hasPendingReviews ? 'Review Laporan' : 'Lihat Laporan' }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">Tidak ada laporan</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="text-center">
                            <div class="flex flex-col items-center justify-center py-16 px-6">
                                <div class="h-24 w-24 text-gray-300 dark:text-gray-600">
                                    <svg class="h-full w-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada data</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Belum ada mahasiswa yang memiliki laporan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->isNotEmpty())
        <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
                    Menampilkan <span class="font-medium">{{ $users->firstItem() }}</span> sampai <span class="font-medium">{{ $users->lastItem() }}</span> dari <span class="font-medium">{{ $users->total() }}</span> mahasiswa
                </div>
                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($users->onFirstPage())
                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
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

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-md z-50">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-md z-50">
        {{ session('error') }}
    </div>
@endif

@endsection

@section('modal')
<!-- Review Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="reviewModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full sm:p-6">
            <form id="reviewForm" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Tinjau Laporan Bulanan
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Judul Laporan</h4>
                                <p class="mt-1 text-gray-600 dark:text-gray-300" id="report-title">Laporan Bulanan</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Deskripsi</h4>
                                <p class="mt-1 text-gray-600 dark:text-gray-300" id="report-description">Deskripsi laporan</p>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="pending">Menunggu</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>
                            <div>
                                <label for="feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Feedback</label>
                                <div class="mt-1">
                                    <textarea id="feedback" name="feedback" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeReviewModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openReviewModal(reportId, title, description, status) {
        // Set form action URL
        document.getElementById('reviewForm').action = `/admin/reports/${reportId}`;
        
        // Set report details in modal
        document.getElementById('report-title').textContent = title || 'Laporan Bulanan';
        document.getElementById('report-description').textContent = description || 'Deskripsi laporan';
        
        // Set current status
        const statusSelect = document.getElementById('status');
        for (let i = 0; i < statusSelect.options.length; i++) {
            if (statusSelect.options[i].value === status) {
                statusSelect.selectedIndex = i;
                break;
            }
        }
        
        // Clear feedback field
        document.getElementById('feedback').value = '';
        
        // Show modal
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('reviewModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeReviewModal();
                }
            });
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeReviewModal();
            }
        });
    });
</script>
@endpush
