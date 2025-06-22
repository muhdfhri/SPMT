@extends('layouts.admin')

@section('title', 'Edit Lowongan Magang')

@php
    $activeMenu = 'internships';
@endphp

@push('styles')
<style>
    .tox-tinymce {
        border-radius: 0.5rem !important;
    }
    .tox .tox-toolbar__primary {
        background: #f9fafb !important;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
    }
    .tox .tox-edit-area__iframe {
        background: #fff !important;
    }
    .dark .tox-tinymce {
        border-color: #4b5563 !important;
    }
    .dark .tox-toolbar,
    .dark .tox-toolbar__primary,
    .dark .tox-toolbar__group {
        background: #1f2937 !important;
        border-color: #4b5563 !important;
    }
    .dark .tox-edit-area__iframe {
        background: #1f2937 !important;
    }
    .dark .tox-tbtn {
        color: #d1d5db !important;
    }
    .dark .tox-tbtn:hover {
        background: #374151 !important;
    }
    .tox-tinymce {
        margin-top: 0.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                                <i class="fas fa-edit mt-0.5 text-blue-600"></i> Edit Lowongan Magang
                            </h2>
                            <span class="ml-3 px-2 py-0.5 text-xs font-semibold text-blue-700 bg-blue-100 dark:bg-blue-900 dark:text-blue-100 rounded-full">
                                ID: {{ $internship->id }}
                            </span>
                        </div>
                        <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-300">
                            Perbarui informasi lowongan magang dengan data yang valid.
                        </p>
                    </div>
                    <a href="{{ route('admin.internships.index') }}" 
                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
            
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Terjadi kesalahan saat menyimpan data. Silakan periksa form di bawah ini.
                            </h3>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('admin.internships.update', $internship->id) }}" method="POST" class="p-4 sm:p-5" id="internshipForm">
    @csrf
    @method('PUT')
    
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 dark:bg-red-900/20 dark:border-red-500 dark:text-red-100">
            <p class="font-bold">Perhatian!</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="space-y-8">
        <!-- Judul dan Lokasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Judul Lowongan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" required
                    value="{{ old('title', $internship->title) }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                    placeholder="Contoh: Lowongan Magang Programmer">
            </div>
            
            <div>
                <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Lokasi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="location" id="location" required
                    value="{{ old('location', $internship->location) }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
        </div>
        
        <!-- Divisi dan Kualifikasi Pendidikan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
            <div>
                <label for="division" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Divisi
                </label>
                <select name="division" id="division"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="">Pilih Divisi</option>
                    @foreach(\App\Helpers\DivisionHelper::getAllDivisions() as $division)
                        <option value="{{ $division }}" {{ old('division', $internship->division) == $division ? 'selected' : '' }}>
                            {{ $division }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="education_qualification" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Kualifikasi Pendidikan <span class="text-red-500">*</span>
                </label>
                <select name="education_qualification" id="education_qualification" required
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="SMA/SMK" {{ old('education_qualification', $internship->education_qualification) == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                    <option value="Vokasi" {{ old('education_qualification', $internship->education_qualification) == 'Vokasi' ? 'selected' : '' }}>Vokasi</option>
                    <option value="S1" {{ old('education_qualification', $internship->education_qualification) == 'S1' ? 'selected' : '' }}>S1</option>
                </select>
            </div>
        </div>
        
        <!-- Tanggal Mulai, Selesai, dan Batas Pendaftaran -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 gap-y-4">
            <div>
                <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date" name="start_date" id="start_date" required
                    value="{{ old('start_date', $internship->start_date->format('Y-m-d')) }}"
                    min="{{ now()->format('Y-m-d') }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Tanggal Selesai <span class="text-red-500">*</span>
                </label>
                <input type="date" name="end_date" id="end_date" required
                    value="{{ old('end_date', $internship->end_date->format('Y-m-d')) }}"
                    min="{{ $internship->start_date->format('Y-m-d') }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            
            <div>
                <label for="application_deadline" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Batas Pendaftaran <span class="text-red-500">*</span>
                </label>
                <input type="date" name="application_deadline" id="application_deadline" required
                    value="{{ old('application_deadline', $internship->application_deadline->format('Y-m-d')) }}"
                    min="{{ now()->format('Y-m-d') }}"
                    max="{{ $internship->end_date->format('Y-m-d') }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Pilih tanggal terakhir pendaftaran
                </p>
            </div>
        </div>
        
        <!-- Kuota dan Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
            <div>
                <label for="quota" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Kuota Peserta <span class="text-red-500">*</span>
                </label>
                <input type="number" name="quota" id="quota" required min="1"
                    value="{{ old('quota', $internship->quota) }}"
                    oninput="this.value = Math.max(1, parseInt(this.value) || 1)"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Jumlah maksimal peserta yang akan diterima (minimal 1)
                </p>
            </div>
            
            <div class="flex items-center">
                <div class="flex items-center h-5">
                    <input type="hidden" name="is_active" value="0">
                    <input id="is_active" name="is_active" type="checkbox" value="1"
                        {{ old('is_active', $internship->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                </div>
                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Aktifkan Lowongan
                </label>
            </div>
        </div>
        
        <!-- Deskripsi -->
        <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                Deskripsi <span class="text-red-500">*</span>
            </label>
            <textarea name="description" id="description" class="hidden">{{ old('description', $internship->description) }}</textarea>
            <div id="description-editor" class="prose max-w-none min-h-[300px] p-4 bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">
                {!! old('description', $internship->description) !!}
            </div>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Persyaratan -->
        <div>
            <label for="requirements" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                Persyaratan <span class="text-red-500">*</span>
            </label>
            <textarea name="requirements" id="requirements" class="hidden">{{ old('requirements', $internship->requirements) }}</textarea>
            <div id="requirements-editor" class="prose max-w-none min-h-[300px] p-4 bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">
                {!! old('requirements', $internship->requirements) !!}
            </div>
            @error('requirements')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <!-- Tombol Aksi -->
    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <button type="button" onclick="confirmDelete()" 
                class="inline-flex justify-center items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-red-400 dark:hover:bg-gray-600 transition">
                <i class="fas fa-trash mr-2"></i> Hapus
            </button>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                <a href="{{ route('admin.internships.index') }}" 
                   class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

            

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi TinyMCE untuk deskripsi
        tinymce.init({
            selector: '#description-editor',
            plugins: 'lists link image table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | link image',
            menubar: false,
            skin: 'oxide',
            content_css: 'default',
            height: 300,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                    document.getElementById('description').value = editor.getContent();
                });
            },
            init_instance_callback: function(editor) {
                editor.setContent(document.getElementById('description').value);
            }
        });

        // Inisialisasi TinyMCE untuk persyaratan
        tinymce.init({
            selector: '#requirements-editor',
            plugins: 'lists link image table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | link image',
            menubar: false,
            skin: 'oxide',
            content_css: 'default',
            height: 300,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                    document.getElementById('requirements').value = editor.getContent();
                });
            },
            init_instance_callback: function(editor) {
                editor.setContent(document.getElementById('requirements').value);
            }
        });

        // Validasi tanggal
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const deadlineInput = document.getElementById('application_deadline');
        const form = document.getElementById('internshipForm');

        // Update min date untuk end_date saat start_date berubah
        if (startDateInput) {
            startDateInput.addEventListener('change', function() {
                if (endDateInput && this.value) {
                    endDateInput.min = this.value;
                    if (endDateInput.value < this.value) {
                        endDateInput.value = this.value;
                    }
                    updateDeadlineMaxDate();
                }
            });
        }

        // Update max date untuk deadline saat end_date berubah
        if (endDateInput) {
            endDateInput.addEventListener('change', updateDeadlineMaxDate);
        }

        function updateDeadlineMaxDate() {
            if (endDateInput && endDateInput.value && deadlineInput) {
                // Set max deadline ke akhir hari dari end_date
                const endDate = new Date(endDateInput.value);
                endDate.setHours(23, 59, 0, 0);
                
                const year = endDate.getFullYear();
                const month = String(endDate.getMonth() + 1).padStart(2, '0');
                const day = String(endDate.getDate()).padStart(2, '0');
                
                deadlineInput.max = `${year}-${month}-${day}T23:59`;
                
                // Jika deadline saat ini melebihi max, atur ke max
                if (deadlineInput.value > deadlineInput.max) {
                    deadlineInput.value = deadlineInput.max;
                }
            }
        }

        // Validasi form sebelum submit
        if (form) {
            form.addEventListener('submit', function(e) {
                // Pastikan konten TinyMCE tersimpan
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }

                // Validasi tanggal
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                const deadline = new Date(deadlineInput.value);

                if (startDate > endDate) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Tanggal mulai tidak boleh melebihi tanggal selesai',
                    });
                    return false;
                }

                if (deadline > endDate) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Batas pendaftaran tidak boleh melebihi tanggal selesai',
                    });
                    return false;
                }


                return true;
            });
        }
    });

    // Fungsi konfirmasi hapus
    function confirmDelete() {
        Swal.fire({
            title: '<span style="color: #ef4444"><i class="fas fa-exclamation-triangle fa-2x mb-4"></i><br>Hapus Lowongan?</span>',
            html: '<div class="text-gray-700">Anda yakin ingin menghapus lowongan ini?<br>Tindakan ini tidak dapat dibatalkan!</div>',
            icon: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
            buttonsStyling: true,
            reverseButtons: true,
            focusConfirm: false,
            focusCancel: true,
            customClass: {
                confirmButton: 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
                cancelButton: 'px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500',
                closeButton: 'text-gray-500 hover:text-gray-700',
                popup: 'rounded-xl',
                icon: '!text-red-500',
                actions: 'mt-4 flex justify-end space-x-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim form hapus
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.internships.destroy', $internship->id) }}';
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endpush
@endsection
