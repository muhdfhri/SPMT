@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
        <!-- Profile Card -->
        <div class="w-full md:w-1/3 lg:w-1/4 mb-6 md:mb-0">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col items-center">
                        <img class="h-24 w-24 rounded-full object-cover" src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}">
                        <h2 class="mt-3 text-lg font-medium text-gray-900 dark:text-white">{{ $student->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->studentProfile->nim ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $student->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Program Studi</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $student->studentProfile->study_program ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Angkatan</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $student->studentProfile->batch ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $student->studentProfile->phone ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Documents Section -->
            @if($student->documents->isNotEmpty())
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white">Dokumen</h3>
                </div>
                <div class="px-6 py-4">
                    <ul class="space-y-3">
                        @foreach($student->documents as $document)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ $document->name }}</span>
                            </div>
                            <a href="{{ route('documents.download', $document) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                Unduh
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Magang</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @if($student->applications->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto h-16 w-16 text-gray-400">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada riwayat magang</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mahasiswa ini belum pernah mendaftar magang.</p>
                    </div>
                    @else
                        @foreach($student->applications as $application)
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">{{ $application->internship->title ?? 'N/A' }}</h4>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                    {{ $application->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                    {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->start_date?->format('d M Y') ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->end_date?->format('d M Y') ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if($application->status === 'rejected' && $application->rejection_reason)
                            <div class="mt-4">
                                <p class="text-sm text-red-600 dark:text-red-400">
                                    <span class="font-medium">Alasan Penolakan:</span> {{ $application->rejection_reason }}
                                </p>
                            </div>
                            @endif
                            <div class="mt-4 flex space-x-3">
                                <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                    Lihat Detail
                                </a>
                                @if($application->status === 'approved' && $application->certificate)
                                <a href="{{ route('admin.certificates.show', $application->certificate) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800">
                                    Lihat Sertifikat
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Reports -->
            @if($student->applications->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Laporan Bulanan</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $reports = $student->applications->flatMap->reports;
                    @endphp
                    
                    @if($reports->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto h-16 w-16 text-gray-400">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada laporan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada laporan yang tersedia.</p>
                    </div>
                    @else
                        @foreach($reports as $report)
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Laporan Bulan {{ $report->month }} {{ $report->year }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->application->internship->name ?? 'N/A' }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                    {{ $report->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                    {{ $report->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kegiatan:</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ Str::limit($report->activities, 150) }}</p>
                            </div>
                            <div class="mt-4 flex space-x-3">
                                <a href="{{ route('admin.reports.show', $report) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                    Lihat Detail
                                </a>
                                @if($report->file_path)
                                <a href="{{ route('reports.download', $report) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                    Unduh Laporan
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
