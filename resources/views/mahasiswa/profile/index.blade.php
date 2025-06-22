@extends('layouts.mahasiswa')
<title>@yield('title', 'Profil - SPMT')</title>

@section('content')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<div class="container mx-auto px-4 py-8">
    {{-- Wrap Profile sections in a single styled block with enhanced shadow and depth --}}
    <div class="flex flex-col md:flex-row md:items-stretch md:space-x-8 mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
        <!-- Profile Photo Upload & Preview -->
        <div class="flex flex-col items-center md:items-center lg:items-center mb-6 md:mb-0 w-full md:w-1/4 lg:w-1/6 pr-0 md:pr-8 border-b md:border-b-0 md:border-r border-gray-200 dark:border-gray-700 pb-6 md:pb-0">
            <div class="relative w-40 h-40 flex items-center justify-center">
                <!-- Background Gradient/Glassmorphism -->
                <!-- Foto Profil + Efek -->
                <div class="relative group transition-all duration-300 w-36 h-36 flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full z-10 pointer-events-none
                        group-hover:animate-pulse
                        group-hover:ring-4 group-hover:ring-blue-400/40
                        transition-all duration-300"></div>
                    <img
                        id="profile-photo-preview"
                        src="{{ $profile->profile_photo ? asset('storage/' . $profile->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($profile->full_name) . '&size=128' }}"
                        alt="Foto Profil"
                        class="w-36 h-36 rounded-full object-cover border-4 border-blue-500 shadow-lg bg-gray-200
                            transition-all duration-300
                            group-hover:scale-105
                            group-hover:border-blue-400
                            group-hover:shadow-2xl"
                    >
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-full transition duration-200 z-20 cursor-pointer" id="profile-photo-overlay">
                        {{-- Icon Placeholder (Anda bisa ganti SVG ini dengan ikon gambar lain jika perlu) --}}
                        <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <span class="mt-3 text-sm text-gray-700 dark:text-gray-400 font-semibold">Foto Profil</span>
            <span class="mt-1 px-3 py-1 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 text-white text-xs font-bold shadow text-center whitespace-normal">
                {{ strtoupper($profile->full_name) }}
            </span>
            <!-- Form untuk upload foto profil -->
            <form id="profile-photo-upload-form" action="/mahasiswa/profile/upload-photo" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" id="real-profile-photo-input" name="photo" accept="image/jpeg,image/png" />
            </form>
        </div>
        
        <!-- Profile Completion Status and Introduction -->
        <div class="flex-1 flex flex-col justify-center pl-0 md:pl-8 pt-6 md:pt-0">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Profil</h1>
                <p class="text-gray-600 dark:text-gray-400">Lengkapi profil Anda untuk meningkatkan peluang mendapatkan magang</p>
            </div>
            
            <div class="">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Status Kelengkapan Profil</h2>

                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kelengkapan Profil</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300" id="completion-percentage">{{ isset($percentage) ? $percentage : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                            id="completion-progress-bar"
                            style="width: {{ isset($percentage) ? $percentage : 0 }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-2">
                            @if(isset($is_personal_complete) && $is_personal_complete)
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Informasi Pribadi</span>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-2">
                            @if(isset($is_academic_complete) && $is_academic_complete)
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Data Akademik</span>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-2">
                            @if(isset($is_family_complete) && $is_family_complete)
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Data Keluarga</span>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-2">
                            @if(isset($is_documents_complete) && $is_documents_complete)
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.0rg/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Dokumen Pendukung</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="w-full">
            {{-- Flex column on mobile/tablet (up to md), row on larger desktop (lg and up) --}}
            <nav class="flex flex-col lg:flex-row gap-y-2 lg:gap-x-4 bg-gray-100 dark:bg-gray-900 rounded-lg shadow-md p-4 lg:p-0 lg:bg-transparent lg:shadow-none lg:rounded-none border-b border-gray-200 dark:border-gray-700 lg:border-0">
                <button id="tab-personal" class="tab-button flex items-center justify-center lg:justify-start gap-2 whitespace-nowrap w-full lg:w-auto px-6 py-3 md:px-5 md:py-2 rounded-full font-semibold text-base md:text-sm transition-all duration-200 border-0 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gradient-to-r hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900 dark:hover:to-blue-800 hover:text-blue-700 dark:hover:text-blue-300 active-tab" data-target="personal-content">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Informasi Pribadi
                </button>
                <button id="tab-academic" class="tab-button flex items-center justify-center lg:justify-start gap-2 whitespace-nowrap w-full lg:w-auto px-6 py-3 md:px-5 md:py-2 rounded-full font-semibold text-base md:text-sm transition-all duration-200 border-0 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gradient-to-r hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900 dark:hover:to-blue-800 hover:text-blue-700 dark:hover:text-blue-300" data-target="academic-content">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m0 6a9 9 0 110-18 9 9 0 010 18z" /></svg>
                    Data Akademik
                </button>
                <button id="tab-family" class="tab-button flex items-center justify-center lg:justify-start gap-2 whitespace-nowrap w-full lg:w-auto px-6 py-3 md:px-5 md:py-2 rounded-full font-semibold text-base md:text-sm transition-all duration-200 border-0 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gradient-to-r hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900 dark:hover:to-blue-800 hover:text-blue-700 dark:hover:text-blue-300" data-target="family-content">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 10-8 0 4 4 0 008 0z" /></svg>
                    Data Keluarga
                </button>
                <button id="tab-documents" class="tab-button flex items-center justify-center lg:justify-start gap-2 whitespace-nowrap w-full lg:w-auto px-6 py-3 md:px-5 md:py-2 rounded-full font-semibold text-base md:text-sm transition-all duration-200 border-0 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gradient-to-r hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900 dark:hover:to-blue-800 hover:text-blue-700 dark:hover:text-blue-300" data-target="documents-content">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7V3a1 1 0 011-1h8a1 1 0 011 1v4m-2 4h2a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2h2m2-4v4" /></svg>
                    Dokumen Pendukung
                </button>
                <button id="tab-settings" class="tab-button flex items-center justify-center lg:justify-start gap-2 whitespace-nowrap w-full lg:w-auto px-6 py-3 md:px-5 md:py-2 rounded-full font-semibold text-base md:text-sm transition-all duration-200 border-0 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gradient-to-r hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900 dark:hover:to-blue-800 hover:text-blue-700 dark:hover:text-blue-300" data-target="settings-content">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                    Pengaturan
                </button>
                <button id="tab-reports" class="tab-button flex items-center justify-center lg:justify-start gap-2 whitespace-nowrap w-full lg:w-auto px-6 py-3 md:px-5 md:py-2 rounded-full font-semibold text-base md:text-sm transition-all duration-200 border-0 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gradient-to-r hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900 dark:hover:to-blue-800 hover:text-blue-700 dark:hover:text-blue-300" data-target="reports-content">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-.55 1.539A3.75 3.75 0 0112.75 21H12a3.75 3.75 0 01-3.75-3.75V16z"></path></svg>
                    Laporkan Kendala
                </button>
            </nav>
        </div>
    </div>

    <div id="personal-content" class="tab-content hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Pribadi</h2>
            <form id="personal-info-form" action="{{ route('mahasiswa.profile.update-personal-info') }}" method="POST" enctype="multipart/form-data" data-ajax="true">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="md:col-span-2">
                        <label for="about_me" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tentang Saya</label>
                        <textarea id="about_me" name="about_me" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white resize-y break-words">{{ $profile->about_me }}</textarea>
                    </div>
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="full_name" name="full_name" value="{{ $profile->full_name }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIK <span class="text-red-500">*</span></label>
                        <input type="text" id="nik" name="nik" value="{{ $profile->nik }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required maxlength="16" minlength="16">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan 16 digit NIK tanpa spasi</p>
                    </div>
                    <div>
                        <label for="birth_place" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" id="birth_place" name="birth_place" value="{{ $profile->birth_place }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" id="birth_date" name="birth_date" value="{{ $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat <span class="text-red-500">*</span></label>
                        <textarea id="address" name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white resize-y break-words" required>{{ $profile->address }}</textarea>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white bg-gray-100 dark:bg-gray-600" readonly>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Email tidak dapat diubah</p>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. HP <span class="text-red-500">*</span></label>
                        <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Perbarui Informasi Pribadi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="academic-content" class="tab-content hidden">
        <div class="space-y-8">
            <!-- Education Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Pendidikan</h2>
                    <button id="add-education-btn" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Pendidikan
                    </button>
                </div>

                <div id="education-list" class="space-y-4">
                    @if($profile->educations->count() > 0)
                    @foreach($profile->educations as $education)
                    <div class="education-item border border-gray-200 dark:border-gray-700 rounded-lg p-4" data-id="{{ $education->id }}" data-type="education" data-education="{{ json_encode($education) }}">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ $education->institution_name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $education->degree }} - {{ $education->field_of_study }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500">
                                    {{ $education->start_date->format('M Y') }} -
                                    @if($education->is_current)
                                    Sekarang
                                    @else
                                    {{ $education->end_date ? $education->end_date->format('M Y') : '' }}
                                    @endif
                                </p>
                                @if($education->gpa)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">IPK: {{ $education->gpa }}</p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <button class="edit-button p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" data-id="{{ $education->id }}" data-type="education" data-education="{{ json_encode($education) }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="delete-button p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400" data-id="{{ $education->id }}" data-type="education">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div id="no-education" class="text-center py-6 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <p>Belum ada data pendidikan. Klik "Tambah Pendidikan" untuk menambahkan.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Experience Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Pengalaman</h2>
                    <button id="add-experience-btn" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Pengalaman
                    </button>
                </div>

                <div id="experience-list" class="space-y-4">
                    @if($profile->experiences->count() > 0)
                    @foreach($profile->experiences as $experience)
                    <div class="experience-item border border-gray-200 dark:border-gray-700 rounded-lg p-4" data-id="{{ $experience->id }}" data-type="experience" data-experience="{{ json_encode($experience) }}">
                         <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ $experience->position }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $experience->company_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500">
                                    {{ $experience->start_date->format('M Y') }} -
                                    @if($experience->is_current)
                                    Sekarang
                                    @else
                                    {{ $experience->end_date ? $experience->end_date->format('M Y') : '' }}
                                    @endif
                                </p>
                                @if($experience->description)
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $experience->description }}</p>
                                @endif
                            </div>
                             <div class="flex space-x-2">
                                <button class="edit-button p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" data-id="{{ $experience->id }}" data-type="experience">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="delete-button p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-600" data-id="{{ $experience->id }}" data-type="experience">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div id="no-experience" class="text-center py-6 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <p>Belum ada data pengalaman. Klik "Tambah Pengalaman" untuk menambahkan.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Skills Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Keahlian</h2>
                    <button id="add-skill-btn" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Keahlian
                    </button>
                </div>

                <div id="skill-list" class="flex flex-wrap gap-2">
                    @if($profile->skills->count() > 0)
                    @foreach($profile->skills as $skill)
                    <div class="skill-item inline-flex items-center bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-1" data-id="{{ $skill->id }}" data-type="skill" data-skill="{{ json_encode($skill) }}">
                        <span class="text-sm text-gray-800 dark:text-gray-200">{{ $skill->name }}</span>
                        <button class="delete-button ml-2 text-gray-400 hover:text-red-500" data-id="{{ $skill->id }}" data-type="skill">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                    @else
                    <div id="no-skill" class="w-full text-center py-6 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        <p>Belum ada data keahlian. Klik "Tambah Keahlian" untuk menambahkan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="family-content" class="tab-content hidden">
        <form id="family-info-form" class="space-y-6" action="{{ route('mahasiswa.profile.update-family-info') }}" method="POST" data-ajax="true">
            @csrf
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Data Keluarga</h2>
                <!-- Father Information -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Data Ayah</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="father_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                            <input type="text" id="father_name" name="father_name" value="{{ $profile->familyMembers->where('relationship', 'father')->first()->name ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label for="father_occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pekerjaan Ayah</label>
                            <input type="text" id="father_occupation" name="father_occupation" value="{{ $profile->familyMembers->where('relationship', 'father')->first()->occupation ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="father_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon Ayah</label>
                            <input type="text" id="father_phone" name="father_phone" value="{{ $profile->familyMembers->where('relationship', 'father')->first()->phone_number ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="father_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Ayah</label>
                            <textarea id="father_address" name="father_address" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ $profile->familyMembers->where('relationship', 'father')->first()->address ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Mother Information -->
                <div class="mt-6">
                    <h3 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Data Ibu</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="mother_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                            <input type="text" id="mother_name" name="mother_name" value="{{ $profile->familyMembers->where('relationship', 'mother')->first()->name ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label for="mother_occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pekerjaan Ibu</label>
                            <input type="text" id="mother_occupation" name="mother_occupation" value="{{ $profile->familyMembers->where('relationship', 'mother')->first()->occupation ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="mother_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon Ibu</label>
                            <input type="text" id="mother_phone" name="mother_phone" value="{{ $profile->familyMembers->where('relationship', 'mother')->first()->phone_number ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="mother_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Ibu</label>
                            <textarea id="mother_address" name="mother_address" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ $profile->familyMembers->where('relationship', 'mother')->first()->address ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Data Keluarga
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="documents-content" class="tab-content hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Dokumen Pendukung</h2>
            
            <!-- KTP Upload -->
            <div class="mb-6">
                <label for="id_card" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Upload KTP <span class="text-red-500">*</span>
                </label>
                <div class="relative flex items-center space-x-2">
                    <input type="file" id="id_card" name="file" accept="image/*,.pdf"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <button type="button" class="upload-btn px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                            data-type="id_card" 
                            data-description="Kartu Tanda Penduduk">
                        Upload
                    </button>
                </div>
                @if($profile->documents->where('type', 'id_card')->first())
                <div class="mt-2 flex items-center justify-between">
                    <a href="{{ route('mahasiswa.profile.download-document', $profile->documents->where('type', 'id_card')->first()->id) }}" 
                       class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-file-alt mr-1"></i>
                        {{ $profile->documents->where('type', 'id_card')->first()->original_filename }}
                    </a>
                    <div class="flex space-x-2">
                        <button class="edit-document-btn p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                data-id="{{ $profile->documents->where('type', 'id_card')->first()->id }}"
                                data-type="id_card"
                                data-description="Kartu Tanda Penduduk">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button class="delete-document-btn p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400"
                                data-id="{{ $profile->documents->where('type', 'id_card')->first()->id }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- CV Upload -->
            <div class="mb-6">
                <label for="cv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Upload CV <span class="text-red-500">*</span>
                </label>
                <div class="relative flex items-center space-x-2">
                    <input type="file" id="cv" name="file" accept=".pdf,.doc,.docx"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <button type="button" class="upload-btn px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                            data-type="cv" 
                            data-description="Curriculum Vitae">
                        Upload
                    </button>
                </div>
                @if($profile->documents->where('type', 'cv')->first())
                <div class="mt-2 flex items-center justify-between">
                    <a href="{{ route('mahasiswa.profile.download-document', $profile->documents->where('type', 'cv')->first()->id) }}" 
                       class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-file-alt mr-1"></i>
                        {{ $profile->documents->where('type', 'cv')->first()->original_filename }}
                    </a>
                    <div class="flex space-x-2">
                        <button class="edit-document-btn p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                data-id="{{ $profile->documents->where('type', 'cv')->first()->id }}"
                                data-type="cv"
                                data-description="Curriculum Vitae">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button class="delete-document-btn p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400"
                                data-id="{{ $profile->documents->where('type', 'cv')->first()->id }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Transcript Upload -->
            <div class="mb-6">
                <label for="transcript" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Upload Transkrip Nilai <span class="text-red-500">*</span>
                </label>
                <div class="relative flex items-center space-x-2">
                    <input type="file" id="transcript" name="file" accept=".pdf"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <button type="button" class="upload-btn px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                            data-type="transcript" 
                            data-description="Transkrip Nilai">
                        Upload
                    </button>
                </div>
                @if($profile->documents->where('type', 'transcript')->first())
                <div class="mt-2 flex items-center justify-between">
                    <a href="{{ route('mahasiswa.profile.download-document', $profile->documents->where('type', 'transcript')->first()->id) }}" 
                       class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-file-alt mr-1"></i>
                        {{ $profile->documents->where('type', 'transcript')->first()->original_filename }}
                    </a>
                    <div class="flex space-x-2">
                        <button class="edit-document-btn p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                data-id="{{ $profile->documents->where('type', 'transcript')->first()->id }}"
                                data-type="transcript"
                                data-description="Transkrip Nilai">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button class="delete-document-btn p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400"
                                data-id="{{ $profile->documents->where('type', 'transcript')->first()->id }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Certificate Upload -->
            <div class="mb-6">
                <label for="certificate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Upload Sertifikat (Opsional)
                </label>
                <div class="relative flex items-center space-x-2">
                    <input type="file" id="certificate" name="file" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <button type="button" class="upload-btn px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                            data-type="certificate" 
                            data-description="Sertifikat">
                        Upload
                    </button>
                </div>
                @if($profile->documents->where('type', 'certificate')->first())
                <div class="mt-2 flex items-center justify-between">
                    <a href="{{ route('mahasiswa.profile.download-document', $profile->documents->where('type', 'certificate')->first()->id) }}" 
                       class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-file-alt mr-1"></i>
                        {{ $profile->documents->where('type', 'certificate')->first()->original_filename }}
                    </a>
                    <div class="flex space-x-2">
                        <button class="edit-document-btn p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                data-id="{{ $profile->documents->where('type', 'certificate')->first()->id }}"
                                data-type="certificate"
                                data-description="Sertifikat">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button class="delete-document-btn p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400"
                                data-id="{{ $profile->documents->where('type', 'certificate')->first()->id }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Settings Tab -->
<div id="settings-content" class="tab-content hidden">
    <div class="px-4 sm:px-6">
        <!-- Account Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-xl w-full max-w-lg mx-auto">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Pengaturan Akun</h2>
            <form id="account-settings-form" action="{{ route('mahasiswa.profile.change-password') }}" method="POST" autocomplete="off">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" readonly>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Email tidak dapat diubah. Hubungi administrator untuk perubahan email.</p>
                    </div>
                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 mt-4">Ganti Password</label>
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Password Saat Ini</label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white pr-10" autocomplete="current-password">
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500 focus:outline-none" onclick="togglePasswordVisibility('current_password', this)">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="new_password" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Password Baru</label>
                                <div class="relative">
                                    <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white pr-10" autocomplete="new-password">
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500 focus:outline-none" onclick="togglePasswordVisibility('new_password', this)">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gunakan 8 atau lebih karakter, dengan perpaduan huruf besar, huruf kecil, angka & simbol.</p>
                            </div>
                            <div>
                                <label for="new_password_confirmation" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white pr-10" autocomplete="new-password">
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500 focus:outline-none" onclick="togglePasswordVisibility('new_password_confirmation', this)">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-6 w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 font-semibold">Ganti Password</button>
                        
                        @push('scripts')
                        <script>
                        function togglePasswordVisibility(inputId, button) {
                            const input = document.getElementById(inputId);
                            const icon = button.querySelector('svg');
                            
                            if (input.type === 'password') {
                                input.type = 'text';
                                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                                button.setAttribute('aria-label', 'Sembunyikan password');
                            } else {
                                input.type = 'password';
                                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
                                button.setAttribute('aria-label', 'Tampilkan password');
                            }
                        }
                        </script>
                        @endpush
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="reports-content" class="tab-content hidden">
    <div class="container mx-auto px-4 py-6 max-w-5xl">
        <!-- Form Buat Laporan -->
        <div class="bg-white/80 dark:bg-gray-800/80 rounded-2xl shadow-2xl p-8 backdrop-blur-md border border-blue-100 dark:border-gray-700 transition-all duration-300 mb-8">
            <h2 class="text-2xl font-extrabold mb-6 text-blue-700 dark:text-blue-400">Buat Laporan Masalah</h2>
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded shadow">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('mahasiswa.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="relative z-0 w-full group">
                    <input type="text" name="judul" id="judul" class="peer block py-3 px-4 w-full text-base text-gray-900 bg-transparent border-0 border-b-2 border-blue-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 rounded-t-md transition-all" placeholder=" " required value="{{ old('judul') }}" />
                    <label for="judul" class="absolute text-base text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-400 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Judul Laporan</label>
                </div>
                <div class="relative z-0 w-full group">
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="peer block py-3 px-4 w-full text-base text-gray-900 bg-transparent border-0 border-b-2 border-blue-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 rounded-t-md transition-all resize-y" placeholder=" " required>{{ old('deskripsi') }}</textarea>
                    <label for="deskripsi" class="absolute text-base text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-400 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Deskripsi</label>
                </div>
                <div class="relative z-0 w-full group mb-6">
                    <label for="attachments" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload File Pendukung</label>
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <label for="attachments" class="flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg shadow-md cursor-pointer font-semibold text-base transition hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12" /></svg>
                            Pilih File
                            <input type="file" name="attachments[]" id="attachments" multiple class="hidden" accept=".pdf,.jpg,.jpeg,.png,.docx" />
                        </label>
                        <div id="attachments-list" class="flex-1 text-sm text-gray-600 dark:text-gray-300 truncate">
                            <span class="text-gray-400">Belum ada file dipilih</span>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format: pdf/jpg/png/docx, max 2MB per file. Bisa pilih lebih dari satu file.</p>
                </div>
                
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-xl font-bold text-lg shadow-lg hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-200">
                    Kirim Laporan
                </button>
            </form>
        </div>

        <!-- Daftar Laporan -->
        <div class="bg-white/80 dark:bg-gray-800/80 rounded-2xl shadow-2xl p-8 backdrop-blur-md border border-blue-100 dark:border-gray-700 transition-all duration-300">
            <h2 class="text-2xl font-extrabold mb-6 text-blue-700 dark:text-blue-400">Daftar Laporan Masalah</h2>
            @include('mahasiswa.profile.partials.reports_table', ['reports' => $reports])
        </div>
    </div>
</div>

<!-- Education Form Modal -->
<div id="education-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm hidden p-4">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-lg p-6 md:p-8 relative animate-fadeIn max-h-[90vh] overflow-y-auto">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-red-600 dark:hover:text-red-400 text-2xl font-bold transition-colors" onclick="closeModal('education-modal')" aria-label="Tutup">&times;</button>
        <h3 class="text-xl md:text-2xl font-bold mb-6 text-blue-700 dark:text-blue-400 flex items-center gap-3">
            <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Pendidikan
        </h3>
        <form id="education-form" class="space-y-5">
            @csrf
            <div class="relative z-0 w-full mb-6 group">
                <input type="text" name="institution_name" id="institution_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="institution_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama Institusi <span class="text-red-500">*</span></label>
            </div>
            
            <div class="relative z-0 w-full mb-6 group">
                <input type="text" name="degree" id="degree" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="degree" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Gelar <span class="text-red-500">*</span></label>
            </div>
            
            <div class="relative z-0 w-full mb-6 group">
                <input type="text" name="field_of_study" id="field_of_study" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="field_of_study" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Bidang Studi <span class="text-red-500">*</span></label>
            </div>
            
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 w-full mb-6 group">
                    <input type="date" name="start_date" id="start_date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="start_date" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Mulai <span class="text-red-500">*</span></label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input type="date" name="end_date" id="end_date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                    <label for="end_date" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Selesai</label>
                </div>
            </div>
            
            <div class="flex items-center mb-6">
                <input type="checkbox" name="is_current" id="is_current" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_current" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Masih berlanjut</label>
            </div>
            
            <div class="relative z-0 w-full mb-6 group">
                <input type="number" name="gpa" id="gpa" step="0.01" min="0" max="4" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                <label for="gpa" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">IPK</label>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('education-modal')" class="px-6 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-colors">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>


<!-- Modal Tambah Pengalaman -->
<div id="modal-experience" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm hidden p-4">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-lg p-6 md:p-8 relative animate-fadeIn max-h-[90vh] overflow-y-auto">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-red-600 dark:hover:text-red-400 text-2xl font-bold transition-colors" onclick="closeModal('modal-experience')" aria-label="Tutup">&times;</button>
        <h3 class="text-xl md:text-2xl font-bold mb-6 text-blue-700 dark:text-blue-400 flex items-center gap-3">
            <span class="experience-modal-title">Tambah Pengalaman</span>
        </h3>
        <form id="form-experience" class="space-y-5">
            <div class="relative z-0 w-full mb-6 group">
                <input type="text" name="company_name" id="company_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="company_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama Perusahaan <span class="text-red-500">*</span></label>
            </div>
            <div class="relative z-0 w-full mb-6 group">
                <input type="text" name="position" id="position" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="position" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Posisi <span class="text-red-500">*</span></label>
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 w-full mb-6 group">
                    <input type="month" name="start_date" id="exp_start_date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="exp_start_date" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Mulai <span class="text-red-500">*</span></label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input type="month" name="end_date" id="exp_end_date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                    <label for="exp_end_date" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Selesai</label>
                </div>
            </div>
            <div class="flex items-center mb-6">
                <input type="checkbox" name="is_current" id="exp-current" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="exp-current" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Masih berlanjut</label>
            </div>
            <div class="relative z-0 w-full mb-6 group">
                <textarea name="description" id="exp_description" rows="3" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "></textarea>
                <label for="exp_description" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Deskripsi</label>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modal-experience')" class="px-6 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Keahlian -->
<div id="modal-skill" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm hidden p-4">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-md p-6 md:p-8 relative animate-fadeIn max-h-[90vh] overflow-y-auto">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-red-600 dark:hover:text-red-400 text-2xl font-bold transition-colors" onclick="closeModal('modal-skill')" aria-label="Tutup">&times;</button>
        <h3 class="text-xl md:text-2xl font-bold mb-6 text-blue-700 dark:text-blue-400 flex items-center gap-3">
            Tambah Keahlian
        </h3>
        <form id="form-skill" class="space-y-5">
            <div class="relative z-0 w-full mb-6 group">
                <input type="text" name="name" id="skill_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="skill_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama Keahlian <span class="text-red-500">*</span></label>
            </div>
            <div class="relative z-0 w-full mb-6 group">
                <select name="proficiency_level" id="proficiency_level" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                    <option value="">Pilih Tingkat</option>
                    <option value="beginner">Pemula</option>
                    <option value="intermediate">Menengah</option>
                    <option value="advanced">Lanjutan</option>
                    <option value="expert">Ahli</option>
                </select>
                <label for="proficiency_level" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Tingkat Kemampuan <span class="text-red-500">*</span></label>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modal-skill')" class="px-6 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Document Edit Modal -->
<div id="modal-edit-document" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm hidden p-4">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-lg p-6 md:p-8 relative animate-fadeIn max-h-[90vh] overflow-y-auto">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-red-600 dark:hover:text-red-400 text-2xl font-bold transition-colors" onclick="closeModal('modal-edit-document')" aria-label="Tutup">&times;</button>
        <h3 class="text-xl md:text-2xl font-bold mb-6 text-blue-700 dark:text-blue-400 flex items-center gap-3">
            <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Dokumen
        </h3>
        <form id="form-edit-document" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="document_id" id="edit_document_id">
            <input type="hidden" name="type" id="edit_document_type">
            <input type="hidden" name="description" id="edit_document_description">

            <div class="relative z-0 w-full mb-6 group">
                <label for="edit_document_file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File Baru <span class="text-red-500">*</span></label>
                <input type="file" name="file" id="edit_document_file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modal-edit-document')" class="px-6 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div class="container mx-auto px-4" bis_skin_checked="1">
<footer class="relative w-full bg-bg-light dark:bg-bg-dark border-t border-gray-200 dark:border-gray-800">
        <div class="w-full px-4 md:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
                <!-- Company Info with Contact Details -->
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold mb-3 text-text-primary dark:text-text-dark-primary">SPMT - Pelindo Multi Terminal</h3>
                    <p class="text-text-secondary dark:text-text-dark-secondary mb-6 leading-relaxed">
                        Platform digital untuk mengelola proses magang mahasiswa, mulai dari pendaftaran, seleksi, hingga pelaporan selama program magang.
                    </p>
                    
                    <!-- Contact Information -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-start group">
                            <div class="flex-shrink-0 w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary mb-1">Alamat</p>
                                <p class="text-text-secondary dark:text-text-dark-secondary">Jl. Lingkar Pelabuhan No. 1, Belawan, Medan 20411</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start group">
                            <div class="flex-shrink-0 w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary mb-1">Telepon</p>
                                <a href="tel:+62614100055" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors">(061) 41000055</a>
                            </div>
                        </div>
                        
                        <div class="flex items-start group">
                            <div class="flex-shrink-0 w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary mb-1">Email</p>
                                <a href="mailto:multi.terminal@pelindo.co.id" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors">multi.terminal@pelindo.co.id</a>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/PTPelindoMultiTerminal" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                            </svg>
                        </a>
                        <a href="https://x.com/Pelindo_SPMT" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/pelindomultiterminal" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/company/pelindospmt/" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                            </svg>
                        </a>
                        <a href="https://www.youtube.com/c/PelindoMultiTerminal" class="w-10 h-10 bg-primary/10 dark:bg-primary-light/10 rounded-lg flex items-center justify-center text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-gray-900 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-text-primary dark:text-text-dark-primary">Tautan Cepat</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Beranda
                        </a></li>
                        <li><a href="#about" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Tentang Kami
                        </a></li>
                        <li><a href="#jobs" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Lowongan Magang
                        </a></li>
                        <li><a href="#faq" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            FAQ
                        </a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-text-primary dark:text-text-dark-primary">Sumber Daya</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Panduan Magang
                        </a></li>
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Tips Wawancara
                        </a></li>
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Blog
                        </a></li>
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Berita
                        </a></li>
                        <li><a href="#" class="text-text-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary-light transition-colors duration-200 flex items-center group">
                            <span class="w-1 h-1 bg-current rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            Acara
                        </a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="border-t border-border-light dark:border-border-dark pt-8 mt-12">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <p class="text-text-secondary dark:text-text-dark-secondary text-sm">
                            &copy; <span id="currentYear"></span> SPMT - Pelindo. Hak Cipta Dilindungi.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-6">
                        <a href="#" class="text-text-secondary dark:text-text-dark-secondary text-sm hover:text-primary dark:hover:text-primary-light transition-colors duration-200">Kebijakan Privasi</a>
                        <a href="#" class="text-text-secondary dark:text-text-dark-secondary text-sm hover:text-primary dark:hover:text-primary-light transition-colors duration-200">Syarat & Ketentuan</a>
                        <a href="#" class="text-text-secondary dark:text-text-dark-secondary text-sm hover:text-primary dark:hover:text-primary-light transition-colors duration-200">Peta Situs</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include profile.js -->
<script src="{{ asset('js/profile.js') }}"></script>

@endsection