@extends('layouts.mahasiswa')
<title>@yield('title', 'Lamaran Magang - SPMT')</title>


@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lamaran Magang Saya</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Kelola dan pantau status lamaran magang Anda</p>
                </div>
                <a href="{{ route('internships.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Cari Lowongan Lain
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            @if(isset($applications) && $applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Lowongan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal Lamar
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status Lamaran
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status Magang
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Catatan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($applications as $application)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $application->internship->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $application->applied_at ? $application->applied_at->format('d M Y') : $application->created_at->format('d M Y') }}
                            </td>
                            <!-- Status Lamaran -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'menunggu' => [
                                            'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'text' => 'Menunggu',
                                            'tooltip' => 'Lamaran Anda telah berhasil dikirim dan sedang menunggu proses selanjutnya'
                                        ],
                                        'terkirim' => [
                                            'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'text' => 'Terkirim',
                                            'tooltip' => 'Lamaran Anda telah berhasil dikirim dan sedang menunggu proses selanjutnya'
                                        ],
                                        'diproses' => [
                                            'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'text' => 'Diproses',
                                            'tooltip' => 'Lamaran Anda sedang dalam proses peninjauan oleh admin'
                                        ],
                                        'diterima' => [
                                            'class' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'text' => 'Diterima',
                                            'tooltip' => 'Selamat! Lamaran Anda telah diterima'
                                        ],
                                        'ditolak' => [
                                            'class' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'text' => 'Ditolak',
                                            'tooltip' => 'Maaf, lamaran Anda tidak dapat diproses lebih lanjut'
                                        ]
                                    ][$application->status] ?? [
                                        'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'text' => 'Tidak Diketahui',
                                        'tooltip' => 'Status tidak valid: ' . $application->status
                                    ];
                                    
                                    // Add processed/approved/rejected info if available
                                    $processedInfo = '';
                                    if ($application->processed_at) {
                                        $processedInfo = "Diproses pada: " . $application->processed_at->format('d M Y');
                                        if ($application->processedBy) {
                                            $processedInfo .= " oleh " . $application->processedBy->name;
                                        }
                                    } elseif ($application->approved_at) {
                                        $processedInfo = "Disetujui pada: " . $application->approved_at->format('d M Y');
                                        if ($application->approvedBy) {
                                            $processedInfo .= " oleh " . $application->approvedBy->name;
                                        }
                                    } elseif ($application->rejected_at) {
                                        $processedInfo = "Ditolak pada: " . $application->rejected_at->format('d M Y');
                                        if ($application->rejectedBy) {
                                            $processedInfo .= " oleh " . $application->rejectedBy->name;
                                        }
                                    }
                                @endphp
                                
                                <div class="flex flex-col space-y-1">
                                    <span 
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusConfig['class'] }}"
                                        title="{{ $statusConfig['tooltip'] }}"
                                    >
                                        {{ $statusConfig['text'] }}
                                    </span>
                                    
                                    @if($application->status === 'ditolak' && $application->rejection_reason)
                                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                        <span class="font-medium">Alasan:</span> {{ $application->rejection_reason }}
                                    </div>
                                    @endif
                                    
                                    @if($processedInfo)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $processedInfo }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Status Magang -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $internshipStatus = [
                                        'menunggu' => [
                                            'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            'text' => 'Belum Mulai',
                                            'tooltip' => 'Status magang belum dimulai'
                                        ],
                                        'in_progress' => [
                                            'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'text' => 'Berjalan',
                                            'tooltip' => 'Magang sedang berjalan'
                                        ],
                                        'completed' => [
                                            'class' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'text' => 'Selesai',
                                            'tooltip' => 'Magang telah selesai'
                                        ]
                                    ][$application->status_magang ?? 'menunggu'] ?? [
                                        'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'text' => 'Belum Mulai',
                                        'tooltip' => 'Status magang belum dimulai'
                                    ];
                                @endphp
                                
                                <span 
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $internshipStatus['class'] }}"
                                    title="{{ $internshipStatus['tooltip'] }}"
                                >
                                    {{ $internshipStatus['text'] }}
                                </span>
                            </td>
                            
                            <!-- Catatan -->
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                @if($application->notes)
                                    @php
                                        // Format ulang teks untuk menghapus waktu dari tanggal
                                        $catatan = preg_replace_callback(
                                            '/(\d{1,2} [A-Za-z]+ \d{4}) \d{1,2}:\d{2}/',
                                            function($matches) {
                                                return $matches[1]; // Mengembalikan hanya bagian tanggal
                                            },
                                            $application->notes
                                        );
                                    @endphp
                                    {{ $catatan }}
                                @else
                                    -
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($applications->hasPages())
            <div class="mt-4">
                {{ $applications->links() }}
            </div>
            @endif

            @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mb-4">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Belum Ada Lamaran</h3>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Anda belum mengirim lamaran magang.</p>
                <div class="mt-6">
                    <a href="{{ route('mahasiswa.internships.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Lihat Lowongan Magang
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
