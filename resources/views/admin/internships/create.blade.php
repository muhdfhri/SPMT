@extends('layouts.admin')

@section('title', 'Tambah Lowongan Magang')

@php
    $activeMenu = 'internships';
@endphp

@push('styles')

<style>
    .tox-tinymce {
        border-radius: 0.5rem !important;
        margin-top: 0.5rem !important;
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
        background: #111827 !important;
    }
    .dark .tox-tbtn {
        color: #9ca3af !important;
    }
    .dark .tox-tbtn:hover {
        background: #374151 !important;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-plus-circle mt-0.5 text-blue-600"></i> Tambah Lowongan Magang Baru
                </h2>
            </div>
            <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-300">
                Isi form di bawah ini dengan informasi lowongan magang yang valid.
            </p>
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
        
        <form id="internshipForm" action="{{ route('admin.internships.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-8 sm:px-10 sm:py-10 bg-white dark:bg-gray-900 rounded-lg shadow-sm">
    @csrf

    <!-- Form -->
    <div class="space-y-8">
        <!-- Judul dan Lokasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Judul Lowongan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" required
                    value="{{ old('title') }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                    placeholder="Contoh: Lowongan Magang Programmer">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Lokasi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="location" id="location" required
                    value="{{ old('location') }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Divisi dan Pendidikan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
            <div>
                <label for="division" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Divisi</label>
                <select name="division" id="division"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="">Pilih Divisi</option>
                    @foreach(\App\Helpers\DivisionHelper::getAllDivisions() as $division)
                        <option value="{{ $division }}" {{ old('division') == $division ? 'selected' : '' }}>
                            {{ $division }}
                        </option>
                    @endforeach
                </select>
                @error('division')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="education_qualification" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Kualifikasi Pendidikan <span class="text-red-500">*</span>
                </label>
                <select name="education_qualification" id="education_qualification" required
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="">-- Pilih Kualifikasi Pendidikan --</option>
                    <option value="SMA/SMK" {{ old('education_qualification') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                    <option value="Vokasi" {{ old('education_qualification') == 'Vokasi' ? 'selected' : '' }}>Vokasi (D1/D2/D3/D4)</option>
                    <option value="S1" {{ old('education_qualification') == 'S1' ? 'selected' : '' }}>Sarjana (S1)</option>
                </select>
                @error('education_qualification')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Tanggal -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 gap-y-4">
            <div>
                <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" id="start_date" required
                    value="{{ old('start_date') }}" min="{{ now()->format('Y-m-d') }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" id="end_date" required
                    value="{{ old('end_date') }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="application_deadline" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Batas Pendaftaran <span class="text-red-500">*</span></label>
                <input type="date" name="application_deadline" id="application_deadline" required
                    value="{{ old('application_deadline') }}" min="{{ now()->format('Y-m-d') }}"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih tanggal terakhir pendaftaran</p>
                @error('application_deadline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Kuota dan Aktif -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
            <div>
                <label for="quota" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Kuota Peserta <span class="text-red-500">*</span></label>
                <input type="number" name="quota" id="quota" required
                    value="{{ old('quota', 1) }}" min="1" step="1"
                    oninput="this.value = Math.max(1, parseInt(this.value) || 1)"
                    class="block w-full rounded-md ring-1 ring-inset border-gray-300 dark:ring-gray-600 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jumlah maksimal peserta yang akan diterima (minimal 1)</p>
                @error('quota')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="hidden" name="is_active" value="0">
                            <input id="is_active" name="is_active" type="checkbox" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
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
                Deskripsi Lowongan <span class="text-red-500">*</span>
            </label>
            <textarea name="description" id="description" class="hidden">{{ old('description') }}</textarea>
            <div id="description-editor" class="prose max-w-none min-h-[300px] p-4 bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">
                {!! old('description', '') !!}
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
            <textarea name="requirements" id="requirements" class="hidden">{{ old('requirements') }}</textarea>
            <div id="requirements-editor" class="prose max-w-none min-h-[300px] p-4 bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">
                {!! old('requirements', '') !!}
            </div>
            @error('requirements')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i> Pastikan semua data yang dimasukkan sudah benar.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <a href="{{ route('admin.internships.index') }}"
                       class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <i class="fas fa-save mr-2"></i> Simpan Lowongan
                    </button>
                </div>
            </div>
        </div>
    </div>
    </form>

</div>

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
                if (document.getElementById('description').value) {
                    editor.setContent(document.getElementById('description').value);
                }
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
                if (document.getElementById('requirements').value) {
                    editor.setContent(document.getElementById('requirements').value);
                }
            }
        });

        // Validasi form
        document.getElementById('internshipForm').addEventListener('submit', function(e) {
            // Pastikan konten editor disimpan ke textarea
            if (tinymce.get('description-editor')) {
                document.getElementById('description').value = tinymce.get('description-editor').getContent();
            }
            if (tinymce.get('requirements-editor')) {
                document.getElementById('requirements').value = tinymce.get('requirements-editor').getContent();
            }

            // Validasi field yang diperlukan
            const requiredFields = [
                { id: 'title', name: 'Judul Lowongan' },
                { id: 'location', name: 'Lokasi' },
                { id: 'education_qualification', name: 'Kualifikasi Pendidikan' },
                { id: 'start_date', name: 'Tanggal Mulai' },
                { id: 'end_date', name: 'Tanggal Selesai' },
                { id: 'application_deadline', name: 'Batas Pendaftaran' },
                { id: 'quota', name: 'Kuota' },
                { id: 'description', name: 'Deskripsi' },
                { id: 'requirements', name: 'Persyaratan' }
            ];

            let isValid = true;
            let errorMessage = '';

            // Validasi field required
            requiredFields.forEach(field => {
                const element = document.getElementById(field.id);
                if (!element || (element.value === '' || element.value === null)) {
                    isValid = false;
                    errorMessage += `- ${field.name} harus diisi\n`;
                }
            });

            // Validasi tanggal
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            const deadline = new Date(document.getElementById('application_deadline').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (startDate < today) {
                isValid = false;
                errorMessage += '- Tanggal mulai tidak boleh kurang dari hari ini\n';
            }

            if (endDate <= startDate) {
                isValid = false;
                errorMessage += '- Tanggal selesai harus setelah tanggal mulai\n';
            }

            if (deadline <= new Date()) {
                isValid = false;
                errorMessage += '- Batas akhir pendaftaran harus lebih dari waktu sekarang\n';
            }

            // Validasi kuota
            const quota = parseInt(document.getElementById('quota').value);
            if (isNaN(quota) || quota < 1) {
                isValid = false;
                errorMessage += '- Kuota harus lebih dari 0\n';
            }

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: errorMessage.replace(/\n/g, '<br>'),
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Mengerti'
                });
                return false;
            }

            // Tampilkan loading
            const submitButton = document.querySelector('button[type="submit"]');
            if (submitButton) {
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Menyimpan...';
                
                // Reset button state if form submission fails
                setTimeout(() => {
                    if (!document.querySelector('.swal2-container')) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }
                }, 10000);
            }
        });

        // Update minimum end date when start date changes
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = this.value;
            const endDateField = document.getElementById('end_date');
            
            if (startDate) {
                endDateField.min = startDate;
                
                // If current end date is before new start date, update it
                if (endDateField.value && new Date(endDateField.value) < new Date(startDate)) {
                    endDateField.value = startDate;
                }
            }
        });

        // Update minimum application deadline when dates change
        function updateDeadlineMin() {
            const deadlineField = document.getElementById('application_deadline');
            const now = new Date();
            const minDate = new Date(now.getTime() + 3600000); // 1 hour from now
            
            // Format to YYYY-MM-DDThh:mm
            const minDateString = minDate.toISOString().slice(0, 16);
            
            deadlineField.min = minDateString;
            
            // If current deadline is in the past, update it
            if (deadlineField.value && new Date(deadlineField.value) < now) {
                deadlineField.value = minDateString;
            }
        }

        // Set initial minimum date for deadline
        updateDeadlineMin();
        
        // Update deadline min when page loads in case it wasn't set properly
        window.addEventListener('load', updateDeadlineMin);
    });
</script>
@endpush
@endsection
