@extends('layouts.mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Laporan Bulanan</h1>
                    <p class="text-gray-600 dark:text-gray-400">Bulan {{ $monthNames[$report->month] }} {{ $report->year }}</p>
                </div>
                <div>
                    @php
                        $statusColors = [
                            'draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            'submitted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                        ];
                        
                        $statusTexts = [
                            'draft' => 'Draft',
                            'submitted' => 'Menunggu Review',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak'
                        ];
                        
                        $statusColor = $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                        $statusText = $statusTexts[$report->status] ?? $report->status;
                    @endphp
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Report Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Tugas -->
                    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Tugas yang Dikerjakan</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="prose max-w-none dark:prose-invert">
                                {!! nl2br(e($report->tasks)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Pencapaian -->
                    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Pencapaian</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="prose max-w-none dark:prose-invert">
                                {!! nl2br(e($report->achievements)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Kendala -->
                    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Kendala yang Dihadapi</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="prose max-w-none dark:prose-invert">
                                {!! nl2br(e($report->challenges)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Feedback -->
                    @if($report->feedback)
                    <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Catatan Pembimbing</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>{!! nl2br(e($report->feedback)) !!}</p>
                                </div>
                                @if($report->reviewed_at)
                                    <div class="mt-2 text-xs text-blue-600 dark:text-blue-400">
                                        Diberikan pada: {{ $report->reviewed_at->format('d F Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Info Laporan -->
                    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Informasi Laporan</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat pada</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $report->created_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir diperbarui</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $report->updated_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @if($report->reviewer)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Direview oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $report->reviewer->name }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Lampiran -->
                    @if(!$report->attachments->isEmpty())
                    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Lampiran</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($report->attachments as $attachment)
                                <li class="py-3">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @php
                                                $icon = 'file';
                                                $iconColor = 'text-gray-400';
                                                $ext = pathinfo($attachment->original_filename, PATHINFO_EXTENSION);
                                                
                                                $fileIcons = [
                                                    'pdf' => ['color' => 'text-red-500', 'icon' => 'file-pdf'],
                                                    'doc' => ['color' => 'text-blue-500', 'icon' => 'file-word'],
                                                    'docx' => ['color' => 'text-blue-500', 'icon' => 'file-word'],
                                                    'xls' => ['color' => 'text-green-500', 'icon' => 'file-excel'],
                                                    'xlsx' => ['color' => 'text-green-500', 'icon' => 'file-excel'],
                                                    'jpg' => ['color' => 'text-purple-500', 'icon' => 'file-image'],
                                                    'jpeg' => ['color' => 'text-purple-500', 'icon' => 'file-image'],
                                                    'png' => ['color' => 'text-purple-500', 'icon' => 'file-image'],
                                                    'zip' => ['color' => 'text-yellow-500', 'icon' => 'file-archive'],
                                                    'rar' => ['color' => 'text-yellow-500', 'icon' => 'file-archive'],
                                                ];
                                                
                                                if (array_key_exists(strtolower($ext), $fileIcons)) {
                                                    $icon = $fileIcons[strtolower($ext)]['icon'];
                                                    $iconColor = $fileIcons[strtolower($ext)]['color'];
                                                }
                                            @endphp
                                            <svg class="h-8 w-8 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                @if($icon === 'file-pdf')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 9h6v6H9z" />
                                                @elseif($icon === 'file-word')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                @elseif($icon === 'file-excel')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                @elseif($icon === 'file-image')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                @elseif($icon === 'file-archive')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                @endif
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $attachment->original_filename }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($attachment->file_size / 1024, 1) }} KB
                                            </p>
                                        </div>
                                        <div>
                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Unduh
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 pt-5 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                <a href="{{ route('mahasiswa.reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Daftar
                </a>
                @if($report->status === 'draft')
                <div class="space-x-3">
                    <a href="{{ route('mahasiswa.reports.edit', $report->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('mahasiswa.reports.destroy', $report->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
