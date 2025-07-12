@extends('layouts.mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Buat Laporan Bulanan Magang</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Isi laporan kegiatan magang Anda untuk periode yang ditentukan</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <form action="{{ route('mahasiswa.reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/20">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Form Laporan Bulanan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Pastikan mengisi semua data dengan benar dan lengkap
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('mahasiswa.reports.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 space-y-8">
                <!-- Periode Laporan -->
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih Periode Laporan <span class="text-red-500">*</span>
                        </label>
                        <select id="month" name="month" required
                                class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition duration-200 py-2.5 px-3.5 shadow-sm hover:border-gray-400 dark:hover:border-gray-500">
                            @foreach($months as $month)
                                <option value="{{ $month['value'] }}" 
                                        data-year="{{ $month['year'] }}"
                                        {{ $month['is_current'] ? 'selected' : '' }}>
                                    {{ $month['label'] }} {{ $month['year'] }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Periode laporan disesuaikan dengan masa magang Anda
                        </p>
                    </div>
                    <!-- Hidden year field untuk form submission -->
                    <input type="hidden" id="year" name="year" value="{{ $months[0]['year'] }}">
                </div>

                <!-- Tugas yang Dikerjakan -->
                <div>
                    <label for="tasks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tugas yang Dikerjakan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="tasks" name="tasks" rows="5" required
                              class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition duration-200 py-2.5 px-3.5 shadow-sm hover:border-gray-400 dark:hover:border-gray-500"
                              placeholder="Jelaskan Secara Singkat"></textarea>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Gunakan format poin-poin untuk memudahkan pembacaan
                    </p>
                </div>

                <!-- Pencapaian -->
                <div>
                    <label for="achievements" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pencapaian <span class="text-red-500">*</span>
                    </label>
                    <textarea id="achievements" name="achievements" rows="5" required
                              class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition duration-200 py-2.5 px-3.5 shadow-sm hover:border-gray-400 dark:hover:border-gray-500"
                              placeholder="Jelaskan Secara Singkat"></textarea>
                </div>

                <!-- Tantangan dan Solusi -->
                <div>
                    <label for="challenges" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tantangan & Solusi <span class="text-red-500">*</span>
                    </label>
                    <textarea id="challenges" name="challenges" rows="5" required
                              class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition duration-200 py-2.5 px-3.5 shadow-sm hover:border-gray-400 dark:hover:border-gray-500"
                              placeholder="Jelaskan Secara Singkat"></textarea>
                </div>

                <!-- Lampiran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lampiran (Opsional)
                    </label>
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
                                        Unggah file
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
            </div>

            <!-- Card Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <p>Pastikan data yang Anda masukkan sudah benar sebelum mengirim.</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <button type="button" onclick="window.history.back()"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors duration-200 flex items-center justify-center w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border-2 border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-colors duration-200 flex items-center justify-center w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                        </svg>
                        Kirim Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tangani perubahan bulan
        const monthSelect = document.getElementById('month');
        const yearInput = document.getElementById('year');
        
        if (monthSelect) {
            monthSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const year = selectedOption.dataset.year;
                
                // Update nilai tahun yang tersembunyi
                if (year && yearInput) {
                    yearInput.value = year;
                }
                
                // Tampilkan notifikasi jika diperlukan
                const currentDate = new Date();
                const selectedDate = new Date(year, this.value - 1, 1);
                
                if (selectedDate > currentDate) {
                    // Jika memilih bulan di masa depan
                    Swal.fire({
                        title: 'Perhatian',
                        text: 'Anda memilih periode di masa depan. Pastikan untuk mengisi laporan setelah periode tersebut berakhir.',
                        icon: 'warning',
                        confirmButtonText: 'Mengerti'
                    });
                }
            });
        }
        
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
                        listItem.className = 'flex items-center justify-between text-sm p-2 bg-white dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600';
                        
                        const fileInfo = document.createElement('div');
                        fileInfo.className = 'flex items-center';
                        
                        const icon = document.createElement('span');
                        icon.className = 'mr-3 text-blue-500';
                        icon.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        `;
                        
                        const fileName = document.createElement('span');
                        fileName.className = 'text-gray-700 dark:text-gray-300';
                        fileName.textContent = file.name;
                        
                        const fileSize = document.createElement('span');
                        fileSize.className = 'ml-2 text-xs text-gray-500 dark:text-gray-400';
                        fileSize.textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                        
                        fileInfo.appendChild(icon);
                        fileInfo.appendChild(fileName);
                        fileInfo.appendChild(fileSize);
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300';
                        removeBtn.title = 'Hapus file';
                        removeBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        `;
                        
                        removeBtn.onclick = (function(index) {
                            return function() {
                                const dt = new DataTransfer();
                                const input = fileUpload;
                                
                                // Tambahkan semua file kecuali yang dihapus
                                Array.from(input.files).forEach((file, i) => {
                                    if (i !== index) {
                                        dt.items.add(file);
                                    }
                                });
                                
                                // Update file input
                                input.files = dt.files;
                                
                                // Trigger change event untuk memperbarui preview
                                const event = new Event('change');
                                input.dispatchEvent(event);
                            };
                        })(index);
                        
                        listItem.appendChild(fileInfo);
                        listItem.appendChild(removeBtn);
                        list.appendChild(listItem);
                    });
                    
                    fileList.appendChild(list);
                    fileListContainer.appendChild(fileList);
                }
            });
        }
        
        // Validasi form sebelum submit
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value || (field.type === 'file' && field.files.length === 0)) {
                        isValid = false;
                        field.classList.add('border-red-500');
                        
                        // Tambahkan pesan error jika belum ada
                        let errorMessage = field.nextElementSibling;
                        if (!errorMessage || !errorMessage.classList.contains('text-red-500')) {
                            errorMessage = document.createElement('p');
                            errorMessage.className = 'mt-1 text-sm text-red-500';
                            errorMessage.textContent = 'Field ini wajib diisi';
                            field.parentNode.insertBefore(errorMessage, field.nextSibling);
                        }
                    } else {
                        field.classList.remove('border-red-500');
                        // Hapus pesan error jika ada
                        const errorMessage = field.nextElementSibling;
                        if (errorMessage && errorMessage.classList.contains('text-red-500')) {
                            errorMessage.remove();
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Scroll ke field pertama yang error
                    const firstError = this.querySelector('.border-red-500');
                    if (firstError) {
                        firstError.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        firstError.focus();
                    }
                    
                    Swal.fire({
                        title: 'Oops...',
                        text: 'Mohon lengkapi semua field yang wajib diisi',
                        icon: 'error',
                        confirmButtonText: 'Mengerti'
                    });
                }
            });
        }
        
        // Fungsi untuk menangani file yang dipilih
        function handleFiles(files) {
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
                    listItem.className = 'flex items-center justify-between text-sm p-2 bg-white dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600';
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'flex items-center';
                    
                    const icon = document.createElement('span');
                    icon.className = 'mr-3 text-blue-500';
                    icon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                        </svg>
                    `;
                    
                    const fileName = document.createElement('span');
                    fileName.className = 'text-gray-700 dark:text-gray-300';
                    fileName.textContent = file.name;
                    
                    const fileSize = document.createElement('span');
                    fileSize.className = 'ml-2 text-xs text-gray-500 dark:text-gray-400';
                    fileSize.textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300';
                    removeBtn.title = 'Hapus file';
                    removeBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    `;
                    
                    removeBtn.onclick = (function(index) {
                        return function() {
                            const dt = new DataTransfer();
                            const input = fileUpload;
                            
                            // Tambahkan semua file kecuali yang dihapus
                            Array.from(input.files).forEach((file, i) => {
                                if (i !== index) {
                                    dt.items.add(file);
                                }
                            });
                            
                            // Update file input
                            input.files = dt.files;
                            
                            // Perbarui tampilan
                            handleFiles(input.files);
                        };
                    })(index);
                    
                    fileInfo.appendChild(icon);
                    fileInfo.appendChild(fileName);
                    fileInfo.appendChild(fileSize);
                    
                    listItem.appendChild(fileInfo);
                    listItem.appendChild(removeBtn);
                    list.appendChild(listItem);
                });
                
                fileList.appendChild(list);
                fileListContainer.appendChild(fileList);
            }
        }
        
        // Handle file input change
        if (fileUpload && fileListContainer) {
            fileUpload.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });
        }
    });
</script>
@endpush
@endsection