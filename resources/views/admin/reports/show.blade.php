@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Laporan Bulanan</h1>
            <p class="text-gray-600 dark:text-gray-400">Informasi lengkap laporan bulanan magang</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Student Info -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                @if($report->user->studentProfile && $report->user->studentProfile->profile_photo)
                    <img class="h-12 w-12 rounded-full object-cover" 
                         src="{{ Storage::url($report->user->studentProfile->profile_photo) }}" 
                         alt="{{ $report->user->name }}">
                @else
                    <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-lg font-medium">
                        {{ substr($report->user->name, 0, 1) }}
                    </div>
                @endif
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $report->user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $report->user->email }}
                        @if($report->user->studentProfile && $report->user->studentProfile->nim)
                            • {{ $report->user->studentProfile->nim }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
<div class="border-b border-gray-200 dark:border-gray-700">
    <nav class="-mb-px flex space-x-1 px-6 overflow-x-auto" aria-label="Tabs">
        @foreach($months as $monthKey => $monthData)
            @php
                $isActive = ($monthData['year'] == $currentYear && $monthData['month'] == $currentMonth);
                $reportForMonth = $allReports->first(function($r) use ($monthData) {
                    return $r->year == $monthData['year'] && $r->month == $monthData['month'];
                });
                
                // Generate URL with all required parameters
                if ($reportForMonth) {
                    $url = route('admin.reports.show', [
                        'report' => $reportForMonth->id,
                        'month' => $monthData['month'],
                        'year' => $monthData['year']
                    ]);
                } else {
                    // Jika belum ada laporan, arahkan ke halaman yang sama dengan parameter bulan dan tahun
                    // Gunakan report saat ini sebagai fallback jika tersedia, jika tidak gunakan 0
                    $reportId = $report->id ?? 0;
                    $url = route('admin.reports.show', [
                        'report' => $reportId,
                        'month' => $monthData['month'],
                        'year' => $monthData['year']
                    ]);
                }
                
                // Status indicator
                $status = $reportForMonth ? $reportForMonth->status : 'belum_terisi';
                $statusClasses = [
                    'belum_terisi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800',
                    'submitted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200 border border-blue-200 dark:border-blue-800',
                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800',
                    'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200 border border-green-200 dark:border-green-800',
                    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200 border border-red-200 dark:border-red-800'
                ][$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600';
                
                $statusTexts = [
                    'belum_terisi' => 'Belum Terisi',
                    'submitted' => 'Terkirim',
                    'pending' => 'Menunggu Review',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak'
                ][$status] ?? ucfirst(str_replace('_', ' ', $status));
                
                $statusIcons = [
                    'belum_terisi' => 'M12 4v16m8-8H4',
                    'submitted' => 'M5 13l4 4L19 7',
                    'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'approved' => 'M5 13l4 4L19 7',
                    'rejected' => 'M6 18L18 6M6 6l12 12'
                ][$status] ?? '';
            @endphp
            <a href="{{ $url }}" 
               class="group whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm flex items-center space-x-2
                      {{ $isActive ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                <span>{{ $monthData['label'] }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcons }}" />
                    </svg>
                    {{ $statusTexts }}
                </span>
            </a>
        @endforeach
    </nav>
</div>
        <!-- Tab Content -->
        <div class="p-6">
            <!-- Tampilkan konten laporan -->
            <div class="space-y-6">
                @php
                    $currentReport = $allReports->first(function($r) use ($currentMonth, $currentYear) {
                        return $r->month == $currentMonth && $r->year == $currentYear;
                    });
                    $showEmptyState = !$currentReport || $currentReport->status === 'belum_terisi';
                @endphp

                @if($showEmptyState)
                        <!-- Pesan jika laporan belum diisi -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Belum Ada Laporan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Mahasiswa belum mengisi laporan untuk {{ $monthNames[$currentMonth] }} {{ $currentYear }}.
                        </p>
                    </div>
                    @else
                        @if($currentReport)
                        <!-- Informasi Tanggal Submit -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-600 p-4 mb-6 rounded-r">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Laporan ini disubmit pada <span class="font-medium">{{ $report->created_at->translatedFormat('l, d F Y') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    
                    @if($report->status !== 'belum_terisi')
                        <!-- Tugas yang Dikerjakan -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tugas yang Dikerjakan</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                @php
                                    $tasks = [];
                                    if (is_array($report->tasks)) {
                                        $tasks = $report->tasks;
                                    } elseif (is_string($report->tasks)) {
                                        // Try to decode as JSON
                                        $decoded = json_decode($report->tasks, true);
                                        $tasks = json_last_error() === JSON_ERROR_NONE ? $decoded : [$report->tasks];
                                    }
                                    $tasks = is_array($tasks) ? array_filter($tasks) : [];
                                @endphp
                                @if(!empty($tasks))
                                    <ul class="list-disc pl-5 space-y-2">
                                        @foreach($tasks as $task)
                                            @if(!empty(trim($task)))
                                                <li class="text-gray-700 dark:text-gray-300">{{ is_array($task) ? json_encode($task) : $task }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada tugas yang dicantumkan</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($report->status !== 'belum_terisi')
                    <!-- Pencapaian -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Pencapaian</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            @php
                                $achievements = [];
                                if (isset($report->achievements)) {
                                    if (is_array($report->achievements)) {
                                        $achievements = $report->achievements;
                                    } elseif (is_string($report->achievements)) {
                                        // Try to decode as JSON
                                        $decoded = json_decode($report->achievements, true);
                                        $achievements = json_last_error() === JSON_ERROR_NONE ? $decoded : [$report->achievements];
                                    }
                                    $achievements = is_array($achievements) ? array_filter($achievements) : [];
                                }
                            @endphp
                            @if(!empty($achievements))
                                <ul class="list-disc pl-5 space-y-2">
                                    @foreach($achievements as $achievement)
                                        @if(!empty(trim($achievement ?? '')))
                                            <li class="text-gray-700 dark:text-gray-300">{{ is_array($achievement) ? json_encode($achievement) : $achievement }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada pencapaian yang dicantumkan</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($report->status !== 'belum_terisi')
                    <!-- Kendala yang Dihadapi -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Kendala yang Dihadapi</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            @php
                                $challenges = [];
                                if (isset($report->challenges)) {
                                    if (is_array($report->challenges)) {
                                        $challenges = $report->challenges;
                                    } elseif (is_string($report->challenges)) {
                                        // Try to decode as JSON
                                        $decoded = json_decode($report->challenges, true);
                                        $challenges = json_last_error() === JSON_ERROR_NONE ? $decoded : [$report->challenges];
                                    }
                                    $challenges = is_array($challenges) ? array_filter($challenges) : [];
                                }
                            @endphp
                            @if(!empty($challenges))
                                <ul class="list-disc pl-5 space-y-2">
                                    @foreach($challenges as $challenge)
                                        @if(!empty(trim($challenge)))
                                            <li class="text-gray-700 dark:text-gray-300">{{ is_array($challenge) ? json_encode($challenge) : $challenge }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada kendala yang dicantumkan</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($report->status !== 'belum_terisi')
                    @if(isset($report->attachments) && $report->attachments->count() > 0)
                    <!-- Lampiran -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Lampiran</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($report->attachments as $attachment)
                                    @php
                                        $isImage = in_array(strtolower(pathinfo($attachment->original_filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        $isPdf = strtolower(pathinfo($attachment->original_filename, PATHINFO_EXTENSION)) === 'pdf';
                                        $fileUrl = Storage::url($attachment->file_path);
                                    @endphp
                                    
                                    <div class="border rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-700 hover:shadow-md transition-shadow duration-200">
                                        @if($isImage)
                                            <!-- Preview untuk gambar -->
                                            <div class="h-40 bg-gray-200 dark:bg-gray-600 flex items-center justify-center overflow-hidden">
                                                <img src="{{ $fileUrl }}" alt="{{ $attachment->original_filename }}" class="max-h-full max-w-full object-contain">
                                            </div>
                                        @elseif($isPdf)
                                            <!-- Preview untuk PDF -->
                                            <div class="h-40 bg-gray-200 dark:bg-gray-600 flex flex-col items-center justify-center p-4">
                                                <svg class="h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">PDF Document</span>
                                            </div>
                                        @else
                                            <!-- Preview untuk file lainnya -->
                                            <div class="h-40 bg-gray-200 dark:bg-gray-600 flex flex-col items-center justify-center p-4">
                                                <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ strtoupper(pathinfo($attachment->original_filename, PATHINFO_EXTENSION)) }} File</span>
                                            </div>
                                        @endif
                                        
                                        <div class="p-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4 truncate">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $attachment->original_filename }}">
                                                        {{ $attachment->original_filename }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ round($attachment->file_size / 1024) }} KB</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex space-x-3">
                                                @if($isImage || $isPdf)
                                                    <button onclick="openPreview('{{ $fileUrl }}', '{{ $attachment->original_filename }}')" 
                                                            class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                                        Preview
                                                    </button>
                                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                                @endif
                                                <a href="{{ $fileUrl }}" 
                                                   class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
                                                   download="{{ $attachment->original_filename }}">
                                                    Unduh
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Modal Preview -->
                        <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
                            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] flex flex-col">
                                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                                    <h3 id="previewTitle" class="text-lg font-medium text-gray-900 dark:text-white"></h3>
                                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-white">
                                        <span class="sr-only">Tutup</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-4 flex-1 overflow-auto">
                                    <iframe id="previewIframe" class="w-full h-full border-0" sandbox="allow-scripts allow-same-origin"></iframe>
                                    <img id="previewImage" class="max-w-full max-h-[70vh] mx-auto hidden" alt="Preview" />
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                    @endif

                    <!-- Status Laporan -->
                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Status Laporan</h3>
                        
                        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        @php
                                            $statusConfig = [
                                                'pending' => ['label' => 'Menunggu Review', 'color' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'],
                                                'approved' => ['label' => 'Disetujui', 'color' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'],
                                                'rejected' => ['label' => 'Ditolak', 'color' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'],
                                            ][$report->status] ?? ['label' => $report->status, 'color' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusConfig['color'] }}">
                                            {{ $statusConfig['label'] }}
                                        </span>
                                        @if($report->reviewed_at)
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Direview pada {{ $report->reviewed_at->translatedFormat('d F Y') }}
                                                @if($report->reviewer)
                                                    oleh {{ $report->reviewer->name }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @if($report->feedback)
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Feedback:</h4>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->feedback }}</p>
                                    </div>
                                @endif
                            </div>

                            @if(($report->status === 'pending' || $report->status === 'belum_terisi' || auth()->user()->role === 'admin') && $report->status !== 'belum_terisi')
                                <!-- Form untuk mengubah status laporan -->
                                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
                                    <form action="{{ route('admin.reports.update', ['report' => $report->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-4">
                                            <div>
                                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ubah Status</label>
                                                <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    @foreach(\App\Models\MonthlyReport::$statuses as $value => $label)
                                                        @if(in_array($value, ['submitted', 'pending', 'approved', 'rejected', 'belum_terisi']))
                                                            <option value="{{ $value }}" {{ old('status', $report->status) === $value ? 'selected' : '' }}>
                                                                @if($value === 'belum_terisi')
                                                                    Belum Terisi
                                                                @elseif($value === 'submitted')
                                                                    Terkirim
                                                                @else
                                                                    {{ $label }}
                                                                @endif
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label for="feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Feedback</label>
                                                <textarea id="feedback" name="feedback" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('feedback', $report->feedback) }}</textarea>
                                            </div>

                                            <div class="flex justify-end">
                                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @if(!$hasReport && $report->status !== 'belum_terisi')
                    <!-- Tampilan ketika data laporan kosong -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Data Laporan Kosong</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Data Laporan Bulanan untuk bulan {{ request('month_year') ? \Carbon\Carbon::createFromFormat('Y-m', request('month_year'))->translatedFormat('F Y') : 'ini' }} 
                            masih kosong atau belum diisi oleh mahasiswa.
                        </p>
                    </div>
                @endif
            </div> <!-- Penutup div.space-y-6 -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('button[data-tab]');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const monthYear = this.getAttribute('data-tab');
            const url = new URL(window.location.href);
            
            // Hapus parameter page jika ada
            url.searchParams.delete('page');
            
            // Update parameter month_year
            url.searchParams.set('month_year', monthYear);
            
            // Redirect ke URL baru
            window.location.href = url.toString();
        });
    });
});

    // File preview functionality
    function openPreview(url, filename) {
        const modal = document.getElementById('previewModal');
        const iframe = document.getElementById('previewIframe');
        const image = document.getElementById('previewImage');
        const title = document.getElementById('previewTitle');
        
        title.textContent = filename;
        
        if (url.toLowerCase().match(/\.(jpeg|jpg|png|gif|webp)$/)) {
            // For images
            iframe.classList.add('hidden');
            image.classList.remove('hidden');
            image.src = url;
        } else if (url.toLowerCase().endsWith('.pdf')) {
            // For PDFs
            iframe.classList.remove('hidden');
            image.classList.add('hidden');
            iframe.src = url + '#view=FitH';
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }

    function closePreview() {
        const modal = document.getElementById('previewModal');
        const iframe = document.getElementById('previewIframe');
        
        // Reset iframe source
        iframe.src = 'about:blank';
        
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }
    
    // Close modal when clicking outside content
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('previewModal');
        const content = document.querySelector('#previewModal > div');
        
        if (event.target === modal) {
            closePreview();
        }
    });


</script>
@endpush