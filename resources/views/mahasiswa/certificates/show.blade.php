@extends('layouts.mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Sertifikat</h1>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Informasi lengkap sertifikat magang Anda</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('mahasiswa.certificates.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Certificate Preview -->
        <div class="p-6">
            <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Sertifikat Magang</h2>
                        <p class="text-gray-600 dark:text-gray-400">Nomor: {{ $certificate->id }}</p>
                    </div>
                    
                    <div class="text-center my-8">
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $certificate->user->name }}
                        </div>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Telah menyelesaikan program magang pada:
                        </p>
                        <div class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $certificate->internship->title ?? 'Program Magang' }}
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $certificate->internship->company_name ?? 'Nama Perusahaan' }}
                        </p>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Terbit</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $certificate->issue_date->format('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                @if($certificate->status === 'revoked')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <svg class="-ml-1 mr-1.5 h-2 w-2 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Dicabut
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="-ml-1 mr-1.5 h-2 w-2 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Tervalidasi
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 text-right">
                    @if($certificate->status === 'revoked')
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                        Sertifikat ini telah dicabut. Silakan hubungi administrator untuk informasi lebih lanjut.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('mahasiswa.certificates.download', $certificate) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Unduh Sertifikat (PDF)
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Embed PDF Preview -->
<div class="container mx-auto px-4 py-6 mt-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Pratinjau Sertifikat</h2>
        </div>
        <div class="p-4">
            <div class="aspect-w-16 aspect-h-9">
                @if($certificate->status === 'revoked')
                    <div class="text-center p-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Sertifikat Dicabut</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sertifikat ini telah dicabut dan tidak dapat ditampilkan.</p>
                        @if($certificate->revoked_reason)
                            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/30 rounded-md">
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    <span class="font-medium">Alasan Pencabutan:</span> {{ $certificate->revoked_reason }}
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    <iframe 
                        src="{{ route('mahasiswa.certificates.download', $certificate) }}#toolbar=0&view=FitH" 
                        class="w-full h-[800px] border border-gray-200 dark:border-gray-700 rounded-lg"
                        frameborder="0">
                    </iframe>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
