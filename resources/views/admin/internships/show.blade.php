@extends('layouts.admin')

@section('title', $internship->title)

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $internship->title }}</h2>
            <div class="flex items-center mt-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $internship->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $internship->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ $internship->applications_count }} Pendaftar
                </span>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.internships.edit', $internship) }}" 
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.internships.index') }}" 
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Informasi Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Lokasi</h3>
            <p class="text-gray-900 dark:text-white">{{ $internship->location }}</p>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Divisi</h3>
            <p class="text-gray-900 dark:text-white">{{ $internship->division ?? '-' }}</p>
        </div>
    </div>

    <!-- Informasi Waktu -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Tanggal Mulai</h3>
            <p class="text-gray-900 dark:text-white">{{ $internship->start_date->format('d F Y') }}</p>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Tanggal Selesai</h3>
            <p class="text-gray-900 dark:text-white">{{ $internship->end_date->format('d F Y') }}</p>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg {{ $internship->application_deadline ? ($internship->isOpenForApplication() ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500') : '' }}">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Batas Pendaftaran</h3>
            @if($internship->application_deadline)
                <p class="text-gray-900 dark:text-white font-medium">
                    {{ $internship->application_deadline->format('d F Y') }}
                    @if($internship->isOpenForApplication())
                        <span class="ml-2 text-sm text-green-600 dark:text-green-400">
                            (Sisa {{ now()->diffInDays($internship->application_deadline) }} hari)
                        </span>
                    @else
                        <span class="ml-2 text-sm text-red-600 dark:text-red-400">
                            (Pendaftaran ditutup)
                        </span>
                    @endif
                </p>
            @else
                <p class="text-gray-500 dark:text-gray-400">Tidak ada batas waktu</p>
            @endif
        </div>
    </div>

    <!-- Deskripsi dan Persyaratan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Deskripsi Lowongan</h3>
            <div class="prose prose-gray dark:prose-invert max-w-none">
                {!! $internship->description !!}
            </div>
        </div>
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Persyaratan</h3>
            <div class="prose prose-gray dark:prose-invert max-w-none">
                {!! $internship->requirements !!}
            </div>
        </div>
    </div>

    <!-- Daftar Pendaftar -->
    <div class="mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Pendaftar</h3>
            <span class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $internship->applications_count }} Pendaftar</span>
        </div>

        @if($internship->applications_count > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($internship->applications as $application)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Lokasi</h3>
                                            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                                {{ $internship->location }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kualifikasi Pendidikan -->
                                    <div class="flex items-start mt-6">
                                        <div class="flex-shrink-0">
                                            <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Kualifikasi Pendidikan</h3>
                                            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                                {{ $internship->education_qualification_label }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $application->user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        ][$application->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.applications.show', $application) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada pendaftar</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada yang mendaftar ke lowongan ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
