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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="-ml-1 mr-1.5 h-2 w-2 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Tervalidasi
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 text-right">
                    <a href="{{ route('mahasiswa.certificates.download', $certificate) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Unduh Sertifikat (PDF)
                    </a>
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
                <iframe 
                    src="{{ route('mahasiswa.certificates.download', $certificate) }}#toolbar=0&view=FitH" 
                    class="w-full h-[800px] border border-gray-200 dark:border-gray-700 rounded-lg"
                    frameborder="0">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection
