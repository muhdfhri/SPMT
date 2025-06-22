@extends('layouts.admin')

@section('title', 'Kelola Laporan Kendala - Admin Panel')

@php
    // Helper function to get sort direction
    $getSortDirection = function($field) use ($sortField, $sortDirection) {
        if ($sortField !== $field) return 'asc';
        return $sortDirection === 'asc' ? 'desc' : 'asc';
    };
    
    // Helper function to generate sort icon
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
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Daftar Laporan Kendala</h2>
        </div>

        <!-- Filter and Controls -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <!-- Status Filter -->
            <form action="{{ route('admin.issue-reports.index') }}" method="GET" class="w-full md:w-auto">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="relative w-full sm:w-48">
                        <select name="status" onchange="this.form.submit()" 
                            class="appearance-none block w-full pl-4 pr-10 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-all">
                            <option value="">Semua Status</option>
                            <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Reset Filter Button -->
                    @if(request()->has('status') || request()->has('per_page'))
                    <a href="{{ route('admin.issue-reports.index') }}" 
                       class="flex items-center justify-center space-x-2 px-4 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Reset Filter</span>
                    </a>
                    @endif
                </div>
            </form>
            
            <!-- Show Entries -->
            <form method="GET" action="{{ route('admin.issue-reports.index') }}" class="w-full md:w-auto">
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400 mr-2">Tampilkan</span>
                    <div class="relative">
                        <select name="per_page" onchange="this.form.submit()" class="pl-2 pr-7 py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 transition-colors appearance-none">
                            <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
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
                </div>
            </form>
        </div>

        <!-- Tabel Laporan -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        @php
                            $dateSortDirection = $getSortDirection('created_at');
                            $dateSortUrl = request()->fullUrlWithQuery([
                                'sort_field' => 'created_at',
                                'sort_direction' => $dateSortDirection
                            ]);
                            
                            $nameSortDirection = $getSortDirection('name');
                            $nameSortUrl = request()->fullUrlWithQuery([
                                'sort_field' => 'name',
                                'sort_direction' => $nameSortDirection
                            ]);
                        @endphp
                        
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700" onclick="window.location='{{ $dateSortUrl }}'">
                            <div class="flex items-center">
                                <span>Tanggal</span>
                                {!! $sortIcon('created_at') !!}
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700" onclick="window.location='{{ $nameSortUrl }}'">
                            <div class="flex items-center">
                                <span>Mahasiswa</span>
                                {!! $sortIcon('name') !!}
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Judul Laporan
                        </th>
                        @php
                            $statusSortDirection = $getSortDirection('status');
                            $statusSortUrl = request()->fullUrlWithQuery([
                                'sort_field' => 'status',
                                'sort_direction' => $statusSortDirection
                            ]);
                        @endphp
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700" onclick="window.location='{{ $statusSortUrl }}'">
                            <div class="flex items-center">
                                <span>Status</span>
                                {!! $sortIcon('status') !!}
                            </div>
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($reports as $report)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $report->created_at->translatedFormat('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $report->user->name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $report->user->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $report->judul }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                {{ Str::limit($report->deskripsi, 100) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'dikirim' => 'bg-yellow-100 text-yellow-800',
                                    'diproses' => 'bg-blue-100 text-blue-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                ][$report->status] ?? 'bg-gray-100 text-gray-800';
                                
                                $statusLabels = [
                                    'dikirim' => 'Dikirim',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                {{ $statusLabels[$report->status] ?? $report->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.issue-reports.show', $report) }}" 
                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada laporan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->isNotEmpty())
        <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
                    Menampilkan <span class="font-medium">{{ $reports->firstItem() }}</span> sampai <span class="font-medium">{{ $reports->lastItem() }}</span> dari <span class="font-medium">{{ $reports->total() }}</span> laporan
                </div>
                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    {{-- Previous Page Link --}}
                    @if($reports->onFirstPage())
                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Sebelumnya
                        </span>
                    @else
                        <a href="{{ $reports->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Sebelumnya
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="flex space-x-1">
                        @foreach ($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                            @if ($page == $reports->currentPage())
                                <span class="px-3 py-1.5 border rounded-lg text-sm font-medium bg-blue-600 text-white border-blue-600">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                    {{-- Next Page Link --}}
                    @if($reports->hasMorePages())
                        <a href="{{ $reports->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Selanjutnya
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            Selanjutnya
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
@endsection
