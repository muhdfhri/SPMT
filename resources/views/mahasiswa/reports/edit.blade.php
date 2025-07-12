@extends('layouts.mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <form action="{{ route('mahasiswa.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Laporan Bulanan</h1>
                        <p class="text-gray-600 dark:text-gray-400">Bulan {{ $monthNames[$report->month] }} {{ $report->year }}</p>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'submitted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                'revision' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
                            ];
                            
                            $statusTexts = [
                                'draft' => 'Draft',
                                'submitted' => 'Menunggu Review',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'revision' => 'Perlu Revisi'
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

            <!-- Card Body -->
            <div class="p-6 space-y-8">
                <!-- Info Periode -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <strong>Periode Laporan:</strong> {{ $monthNames[$report->month] }} {{ $report->year }}
                            </p>
                            @if($report->status === 'rejected')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Laporan Anda ditolak. Silakan perbaiki sesuai dengan catatan.
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tugas yang Dikerjakan -->
                <div>
                    <label for="tasks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tugas yang Dikerjakan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="tasks" name="tasks" rows="5" required
                              class="w-full rounded-lg border-2 @error('tasks') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition duration-200 py-2.5 px-3.5 shadow-sm hover:border-gray-400 dark:hover:border-gray-500"
                              placeholder="Jelaskan tugas-tugas yang telah Anda kerjakan (minimal 10 karakter dan 2 kata)">{{ old('tasks', $report->tasks) }}</textarea>
                    @error('tasks')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pencapaian -->
                <div>
                    <label for="achievements" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pencapaian <span class="text-red-500">*</span>
                    </label>
                    <textarea id="achievements" name="achievements" rows="5" required
                              class="w-full rounded-lg border-2 @error('achievements') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition duration-200 py-2.5 px-3.5 shadow-sm hover:border-gray-400 dark:hover:border-gray-500"
                              placeholder="Jelaskan pencapaian yang telah Anda raih (minimal 10 karakter dan 2 kata)">{{ old('achievements', $report->achievements) }}</textarea>
                    @error('achievements')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tantangan dan Solusi -->
                <div>
                    <label for="challenges" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tantangan & Solusi <span class="text-red-500">*</span>
                    </label>
                    <textarea id="challenges" name="challenges" rows="5" required
                              class="w-full rounded-lg border-2 @error('challenges') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition duration-200 py-2.5 px-3.5 shadow-sm hover:border-gray-400 dark:hover:border-gray-500"
                              placeholder="Jelaskan tantangan yang dihadapi dan solusinya (minimal 10 karakter dan 2 kata)">{{ old('challenges', $report->challenges) }}</textarea>
                    @error('challenges')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lampiran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lampiran (Opsional)
                    </label>
                    
                    <!-- Lampiran yang sudah ada -->
                    @if($report->attachments->count() > 0)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                            <i class="fas fa-paperclip mr-1"></i> Lampiran Saat Ini:
                        </p>
                        <div class="space-y-2">
                            @foreach($report->attachments as $attachment)
                            <div id="attachment-{{ $attachment->id }}" class="flex items-center justify-between bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-200 dark:border-gray-600 transition-all duration-200">
                                <div class="flex items-center min-w-0">
                                    <svg class="h-5 w-5 text-gray-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-xs">
                                        {{ $attachment->original_filename }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ Storage::url($attachment->file_path) }}" 
                                       target="_blank"
                                       title="Lihat Lampiran"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-full text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-gray-600 transition-colors duration-200">
                                        <i class="fas fa-eye text-lg"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="deleteAttachment({{ $attachment->id }})"
                                            title="Hapus Lampiran"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-600 transition-colors duration-200">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Unggah lampiran baru -->
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-blue-400 dark:hover:border-blue-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex flex-col sm:flex-row text-sm text-gray-600 dark:text-gray-400 items-center justify-center">
                                <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus-within:outline-none transition-colors duration-200">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Unggah file baru
                                    </span>
                                    <input id="file-upload" name="attachments[]" type="file" multiple class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Format: PNG, JPG, PDF (maks. 5MB per file)
                            </p>
                        </div>
                    </div>
                    <div id="file-list-container" class="mt-2"></div>
                </div>
                
                <!-- Feedback dari Pembimbing (jika ada) -->
                @if($report->feedback)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                {{ $report->status === 'rejected' ? 'Alasan Penolakan' : 'Catatan Pembimbing' }}
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <p>{{ $report->feedback }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Card Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0 sm:space-x-3">
                <div>
                    @if($report->status === 'rejected')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Perlu Revisi
                    </span>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('mahasiswa.reports.show', $report) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 flex items-center justify-center">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border-2 border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-colors duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v1H9V4z" />
                        </svg>
                        Perbarui Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Fungsi untuk menghapus lampiran
    function deleteAttachment(attachmentId) {
        Swal.fire({
            title: 'Hapus Lampiran',
            text: 'Apakah Anda yakin ingin menghapus lampiran ini?',
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#3B82F6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus lampiran',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Kirim permintaan hapus
                fetch(`/mahasiswa/reports/attachments/${attachmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Sembunyikan loading
                        Swal.close();
                        // Hapus elemen lampiran dari DOM
                        document.getElementById(`attachment-${attachmentId}`).remove();
                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Lampiran berhasil dihapus.',
                            icon: 'success',
                            confirmButtonColor: '#3B82F6'
                        });
                    } else {
                        throw new Error(data.message || 'Gagal menghapus lampiran');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menghapus lampiran',
                        icon: 'error',
                        confirmButtonColor: '#3B82F6'
                    });
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        
        // Prevent double-escaping of quotes in form submission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Clean up the textarea values before submission
                const textareas = form.querySelectorAll('textarea');
                textareas.forEach(textarea => {
                    textarea.value = textarea.value.trim();
                });
                
                // Continue with normal form submission
                return true;
            });
        }
        
        // Existing file upload code
        // Script untuk preview file yang diunggah
        const fileUpload = document.getElementById('file-upload');
        const fileListContainer = document.getElementById('file-list-container');
        
        if (fileUpload && fileListContainer) {
            fileUpload.addEventListener('change', function(e) {
                const files = e.target.files;
                fileListContainer.innerHTML = '';
                
                if (files.length > 0) {
                    const fileList = document.createElement('div');
                    fileList.className = 'space-y-2';
                    
                    const header = document.createElement('p');
                    header.className = 'text-sm font-medium text-gray-700 dark:text-gray-300';
                    header.textContent = 'File yang akan diunggah:';
                    fileList.appendChild(header);
                    
                    const list = document.createElement('ul');
                    list.className = 'space-y-2 bg-gray-50 dark:bg-gray-700/20 rounded-lg p-3 border border-gray-200 dark:border-gray-700';
                    
                    Array.from(files).forEach((file, index) => {
                        const listItem = document.createElement('li');
                        listItem.className = 'flex items-center justify-between';
                        
                        const fileInfo = document.createElement('div');
                        fileInfo.className = 'flex items-center';
                        
                        const icon = document.createElement('span');
                        icon.className = 'mr-2';
                        
                        if (file.type.startsWith('image/')) {
                            icon.innerHTML = '<svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>';
                        } else if (file.type === 'application/pdf') {
                            icon.innerHTML = '<svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>';
                        } else if (file.type.startsWith('application/')) {
                            icon.innerHTML = '<svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>';
                        } else {
                            icon.innerHTML = '<svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>';
                        }
                        
                        const fileName = document.createElement('span');
                        fileName.className = 'text-sm text-gray-700 dark:text-gray-300 truncate';
                        fileName.textContent = file.name;
                        
                        const fileSize = document.createElement('span');
                        fileSize.className = 'text-xs text-gray-500 dark:text-gray-400 ml-2';
                        fileSize.textContent = formatFileSize(file.size);
                        
                        fileInfo.appendChild(icon);
                        fileInfo.appendChild(fileName);
                        fileInfo.appendChild(fileSize);
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300';
                        removeBtn.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>';
                        removeBtn.onclick = function() {
                            // Buat input file baru untuk menghapus file yang dipilih
                            const newFileList = new DataTransfer();
                            const input = document.getElementById('file-upload');
                            
                            // Tambahkan semua file kecuali yang dihapus
                            for (let i = 0; i < input.files.length; i++) {
                                if (i !== index) {
                                    newFileList.items.add(input.files[i]);
                                }
                            }
                            
                            // Perbarui input file
                            input.files = newFileList.files;
                            
                            // Perbarui tampilan
                            fileListContainer.removeChild(fileList);
                            if (newFileList.files.length === 0) {
                                fileListContainer.innerHTML = '';
                            }
                        };
                        
                        listItem.appendChild(fileInfo);
                        listItem.appendChild(removeBtn);
                        list.appendChild(listItem);
                    });
                    
                    fileList.appendChild(list);
                    fileListContainer.appendChild(fileList);
                }
            });
            
            // Fungsi untuk memformat ukuran file
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        }
        
        // Validasi form sebelum submit
        if (form) {
            form.addEventListener('submit', function(e) {
                let valid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    const value = field.value.trim();
                    if (!value) {
                        valid = false;
                        field.classList.add('border-red-500', 'dark:border-red-500');
                        // Add error message if not already present
                        if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('text-red-500')) {
                            const errorMsg = document.createElement('p');
                            errorMsg.className = 'mt-1 text-sm text-red-500 dark:text-red-400';
                            errorMsg.textContent = 'Field ini wajib diisi';
                            field.parentNode.insertBefore(errorMsg, field.nextSibling);
                        }
                    } else {
                        field.classList.remove('border-red-500', 'dark:border-red-500');
                        // Remove error message if exists
                        if (field.nextElementSibling && field.nextElementSibling.classList.contains('text-red-500')) {
                            field.nextElementSibling.remove();
                        }
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                    // Scroll to first error
                    const firstError = form.querySelector('.border-red-500');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    Swal.fire({
                        title: 'Form Tidak Lengkap',
                        text: 'Mohon lengkapi semua field yang wajib diisi',
                        icon: 'warning',
                        confirmButtonText: 'Mengerti'
                    });
                }
            });
        }
    });
</script>
@endpush
@endsection
