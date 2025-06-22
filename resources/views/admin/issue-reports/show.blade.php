@extends('layouts.admin')

@section('title', 'Detail Laporan Kendala')

@section('content')
<div class="container mx-auto px-4 py-6">
<div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Laporan Bulanan</h1>
            <p class="text-gray-600 dark:text-gray-400">Informasi lengkap laporan bulanan magang</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('admin.issue-reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Laporan Kendala</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @if($report->created_at)
                        Dibuat pada {{ $report->created_at->translatedFormat('d F Y') }}
                    @else
                        Waktu pembuatan tidak tersedia
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-2">
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
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusClasses }}">
                    {{ $statusLabels[$report->status] ?? $report->status }}
                </span>
            </div>
        </div>

        <!-- Informasi Pelapor -->
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-300 mb-2">Dilaporkan Oleh</h3>
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">
                        @if($report->user)
                            {{ substr($report->user->name ?? '?', 0, 1) }}
                        @else
                            ?
                        @endif
                    </span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $report->user->name ?? 'Pengguna Tidak Ditemukan' }}
                    </p>
                    @if($report->user)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $report->user->email ?? 'Email tidak tersedia' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Laporan -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ $report->judul }}</h3>
            <div class="prose max-w-none text-gray-700 dark:text-gray-300">
                {!! nl2br(e($report->deskripsi)) !!}
            </div>
        </div>

        <!-- Lampiran -->
@if(isset($report->attachments) && $report->attachments->count() > 0)
<div class="mb-6">
    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Lampiran ({{ $report->attachments->count() }})</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($report->attachments as $attachment)
        <div class="border rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
            <div class="p-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @php
                            $iconClass = 'text-blue-500';
                            $icon = 'document-text';
                            
                            if (isset($attachment->original_filename)) {
                                $extension = strtolower(pathinfo($attachment->original_filename, PATHINFO_EXTENSION));
                                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                    $icon = 'photograph';
                                    $iconClass = 'text-green-500';
                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                    $icon = 'document-text';
                                    $iconClass = 'text-blue-600';
                                } elseif (in_array($extension, ['xls', 'xlsx', 'csv'])) {
                                    $icon = 'table';
                                    $iconClass = 'text-green-600';
                                } elseif ($extension === 'pdf') {
                                    $icon = 'document';
                                    $iconClass = 'text-red-500';
                                } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
                                    $icon = 'archive';
                                    $iconClass = 'text-yellow-500';
                                }
                            }
                        @endphp
                        <svg class="h-10 w-10 {{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            @if($icon === 'photograph')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            @elseif($icon === 'document-text')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            @elseif($icon === 'table')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            @elseif($icon === 'archive')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            @endif
                        </svg>
                    </div>
                    <div class="ml-4 flex-1 overflow-hidden">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $attachment->original_filename ?? 'File tanpa nama' }}">
                            {{ $attachment->original_filename ?? 'File tanpa nama' }}
                        </p>
                    </div>
                </div>
                <div class="mt-3 text-center p-2 bg-gray-100 dark:bg-gray-800">
                    @php
                        // Debug: tampilkan file path untuk troubleshooting
                        $originalPath = $attachment->file_path ?? '';
                        
                        // Coba beberapa kemungkinan path
                        $possiblePaths = [
                            $originalPath, // Path asli dari database
                            'public/' . $originalPath, // Dengan prefix public/
                            'public/' . ltrim($originalPath, '/'), // Dengan prefix public/ dan remove leading slash
                            ltrim($originalPath, 'public/'), // Remove public/ prefix jika ada
                            'storage/' . ltrim($originalPath, '/'), // Dengan prefix storage/
                        ];
                        
                        $fileExists = false;
                        $correctPath = '';
                        
                        // Cek path mana yang benar-benar ada
                        foreach ($possiblePaths as $path) {
                            if (!empty($path) && Storage::exists($path)) {
                                $fileExists = true;
                                $correctPath = $path;
                                break;
                            }
                        }
                        
                        // Jika masih tidak ditemukan, coba cek dengan disk public
                        if (!$fileExists && !empty($originalPath)) {
                            try {
                                if (Storage::disk('public')->exists($originalPath)) {
                                    $fileExists = true;
                                    $correctPath = $originalPath;
                                } elseif (Storage::disk('public')->exists(ltrim($originalPath, '/'))) {
                                    $fileExists = true;
                                    $correctPath = ltrim($originalPath, '/');
                                }
                            } catch (Exception $e) {
                                // Ignore exception and continue
                            }
                        }
                        
                        $fileExtension = $fileExists ? strtolower(pathinfo($correctPath, PATHINFO_EXTENSION)) : '';
                        $isImage = $fileExists && in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $isPdf = $fileExists && ($fileExtension === 'pdf');
                    @endphp
                    
                    @if($fileExists)
                        <!-- Tombol Preview -->
                        <a href="{{ route('admin.issue-reports.preview', $attachment->id) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $isImage ? 'Lihat Gambar' : ($isPdf ? 'Buka PDF' : 'Buka File') }}
                        </a>
                        
                    @else
                        <div class="text-center">
                            <svg class="mx-auto h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="mt-1 text-xs text-red-500 dark:text-red-400">
                                File tidak ditemukan
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

        <!-- Form Update Status -->
        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Update Status Laporan</h3>
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.issue-reports.update', $report) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @php
                                $currentStatus = old('status', $report->status ?? 'dikirim');
                            @endphp
                            <option value="dikirim" {{ $currentStatus == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="diproses" {{ $currentStatus == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $currentStatus == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ $report->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Catatan Admin
                        </label>
                        <textarea id="admin_notes" name="admin_notes" rows="3"
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Tambahkan catatan atau tanggapan...">{{ old('admin_notes', $report->admin_notes) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.issue-reports.index') }}"
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                        Kembali
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Riwayat Status -->
        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Riwayat Status</h3>
            <div class="flow-root">
                <ul class="-mb-8">
                    @php
                        $statusHistory = [
                            [
                                'status' => 'dikirim',
                                'label' => 'Dikirim',
                                'color' => 'bg-yellow-500',
                                'icon' => 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2h-2V9z',
                                'date' => $report->created_at,
                                'description' => 'Laporan dikirim oleh pengguna',
                                'user' => $report->user->name ?? 'Pengguna'
                            ]
                        ];

                        if ($report->status === 'diproses' || $report->status === 'selesai' || $report->status === 'ditolak') {
                            $statusHistory[] = [
                                'status' => 'diproses',
                                'label' => 'Diproses',
                                'color' => 'bg-blue-500',
                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                'date' => $report->updated_at,
                                'description' => 'Laporan sedang diproses',
                                'user' => $report->admin->name ?? 'Sistem'
                            ];
                        }

                        if ($report->status === 'selesai' || $report->status === 'ditolak') {
                            $statusHistory[] = [
                                'status' => $report->status,
                                'label' => $report->status === 'selesai' ? 'Selesai' : 'Ditolak',
                                'color' => $report->status === 'selesai' ? 'bg-green-500' : 'bg-red-500',
                                'icon' => $report->status === 'selesai' 
                                    ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' 
                                    : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'date' => $report->status === 'selesai' ? ($report->resolved_at ?? $report->updated_at) : $report->updated_at,
                                'description' => $report->status === 'selesai' 
                                    ? 'Laporan telah selesai ditangani' 
                                    : 'Laporan ditolak' . ($report->admin_notes ? ' dengan alasan: ' . $report->admin_notes : ''),
                                'user' => $report->admin->name ?? 'Sistem'
                            ];
                        }
                    @endphp

                    @foreach($statusHistory as $index => $history)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full {{ $history['color'] }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="{{ $history['icon'] }}" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $history['description'] }}
                                                @if(isset($history['user']))
                                                    <span class="font-medium text-gray-900 dark:text-white">
                                                        oleh {{ $history['user'] }}
                                                    </span>
                                                @endif
                                            </p>
                                            @if(!empty($history['notes']))
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $history['notes'] }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            @if(isset($history['date']) && $history['date'])
                                                <time datetime="{{ $history['date']->toIso8601String() }}">
                                                    {{ $history['date']->diffForHumans() }}
                                                </time>
                                            @else
                                                <span class="text-xs text-gray-400">Waktu tidak tersedia</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-resize textarea
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('admin_notes');
        if (textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
            
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    });
</script>
@endpush
@endsection
