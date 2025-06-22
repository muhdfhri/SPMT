<!-- Application Modal -->
<div id="applicationModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" onclick="event.target === this && closeModal()">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay with click handler -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel - stop propagation to prevent closing when clicking inside -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Lamar Magang - <span class="text-blue-600 dark:text-blue-400">{{ $internship->title }}</span>
                    </h3>
                    <button type="button" 
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"
                            onclick="closeModal()">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Progress Steps -->
            <div class="px-6 pt-6">
                <div class="max-w-3xl mx-auto">
                    <div class="flex justify-between items-center">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center">
                            <div id="step1-indicator" class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white font-medium text-sm border-2 border-blue-600 transition-all duration-200">
                                <span>1</span>
                            </div>
                            <span class="mt-2 text-xs font-medium text-blue-600 dark:text-blue-400">Unggah Dokumen</span>
                        </div>
                        
                        <!-- Connector -->
                        <div class="flex-1 h-1 bg-blue-200 dark:bg-gray-600 mx-2 relative">
                            <div id="progress-connector" class="absolute top-0 left-0 h-full bg-blue-600 transition-all duration-500 ease-in-out" style="width: 0%"></div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="flex flex-col items-center">
                            <div id="step2-indicator" class="w-10 h-10 rounded-full flex items-center justify-center bg-white dark:bg-gray-700 text-gray-400 font-medium text-sm border-2 border-gray-300 dark:border-gray-600 transition-all duration-200">
                                <span>2</span>
                            </div>
                            <span class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400">Review & Kirim</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-4">
                    
                <!-- Form Steps -->
                <div class="mt-4">
                        <!-- Step 1: Document Upload -->
                        <div id="step1" class="step-content">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Kelengkapan Berkas</h4>
                    
                            @if($hasCompleteDocuments)
                            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            Dokumen lengkap terdeteksi di profil Anda. Anda dapat menggunakan dokumen yang sudah ada atau mengunggah yang baru.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                    
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                Silakan unggah dokumen-dokumen berikut untuk melengkapi pendaftaran magang Anda.
                            </p>
                            
                            <div class="space-y-6">
                                <!-- KTP -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            KTP (Kartu Tanda Penduduk) <span class="text-red-500">*</span>
                                        </label>
                                        @if(isset($userDocuments['id_card']))
                                    <div class="flex items-center">
                                        <input type="checkbox" id="use_existing_id_card" name="use_existing_id_card" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                               onchange="toggleFileUpload('id_card', this.checked)" checked>
                                        <label for="use_existing_id_card" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                            Gunakan dokumen yang sudah ada
                                        </label>
                                        <input type="hidden" name="use_existing_id_card_value" value="1">
                                    </div>
                                    @endif
                                    </div>
                                    @if(isset($userDocuments['id_card']))
                                    <div id="id_card-existing" class="mb-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="font-medium text-green-700 dark:text-green-300">Dokumen tersedia</span>
                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                    {{ $userDocuments['id_card']->original_filename }}
                                                    <span class="text-xs text-green-500 block">
                                                        Diunggah pada: {{ $userDocuments['id_card']->created_at->format('d M Y H:i') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <a href="{{ route('mahasiswa.profile.view-document', $userDocuments['id_card']->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                               target="_blank"
                                               rel="noopener noreferrer">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    <div id="id_card-upload" class="{{ isset($userDocuments['id_card']) ? 'hidden' : '' }}">
                                        <div class="flex items-center">
                                            <input type="file" id="id_card" name="id_card" 
                                                   class="block w-full text-sm text-gray-500
                                                          file:mr-4 file:py-2 file:px-4
                                                          file:rounded-md file:border-0
                                                          file:text-sm file:font-semibold
                                                          file:bg-blue-50 file:text-blue-700
                                                          hover:file:bg-blue-100"
                                                   accept="image/*,.pdf"
                                                   onchange="handleFileUpload(this, 'id_card')">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Format: JPG, PNG, PDF (Maks. 2MB)
                                        </p>
                                        <p id="id_card-error" class="mt-1 text-xs text-red-600 dark:text-red-400 hidden"></p>
                                        <p id="id_card-name" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                                    </div>
                                </div>

                                <!-- CV -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            CV (Curriculum Vitae) <span class="text-red-500">*</span>
                                        </label>
                                        @if(isset($userDocuments['cv']))
                                        <div class="flex items-center">
                                            <input type="checkbox" id="use_existing_cv" name="use_existing_cv" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                   onchange="toggleFileUpload('cv', this.checked)" checked>
                                            <label for="use_existing_cv" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                Gunakan dokumen yang sudah ada
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    @if(isset($userDocuments['cv']))
                                    <div id="cv-existing" class="mb-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="font-medium text-green-700 dark:text-green-300">Dokumen tersedia</span>
                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                    {{ $userDocuments['cv']->original_filename }}
                                                    <span class="text-xs text-green-500 block">
                                                        Diunggah pada: {{ $userDocuments['cv']->created_at->format('d M Y H:i') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <a href="{{ route('mahasiswa.profile.view-document', $userDocuments['cv']->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                               target="_blank"
                                               rel="noopener noreferrer">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    <div id="cv-upload" class="{{ isset($userDocuments['cv']) ? 'hidden' : '' }}">
                                        <div class="flex items-center">
                                            <input type="file" id="cv" name="cv" 
                                                   class="block w-full text-sm text-gray-500
                                                          file:mr-4 file:py-2 file:px-4
                                                          file:rounded-md file:border-0
                                                          file:text-sm file:font-semibold
                                                          file:bg-blue-50 file:text-blue-700
                                                          hover:file:bg-blue-100"
                                                   accept=".pdf,.doc,.docx"
                                                   onchange="handleFileUpload(this, 'cv')">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Format: PDF, DOC, DOCX (Maks. 2MB)
                                        </p>
                                        <p id="cv-error" class="mt-1 text-xs text-red-600 dark:text-red-400 hidden"></p>
                                        <p id="cv-name" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                                    </div>
                                </div>

                                <!-- Transcript -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Transkrip Nilai <span class="text-red-500">*</span>
                                        </label>
                                        @if(isset($userDocuments['transcript']))
                                        <div class="flex items-center">
                                            <input type="checkbox" id="use_existing_transcript" name="use_existing_transcript" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                   onchange="toggleFileUpload('transcript', this.checked)" checked>
                                            <label for="use_existing_transcript" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                Gunakan dokumen yang sudah ada
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    @if(isset($userDocuments['transcript']))
                                    <div id="transcript-existing" class="mb-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="font-medium text-green-700 dark:text-green-300">Dokumen tersedia</span>
                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                    {{ $userDocuments['transcript']->original_filename }}
                                                    <span class="text-xs text-green-500 block">
                                                        Diunggah pada: {{ $userDocuments['transcript']->created_at->format('d M Y H:i') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <a href="{{ route('mahasiswa.profile.view-document', $userDocuments['transcript']->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                               target="_blank"
                                               rel="noopener noreferrer">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    <div id="transcript-upload" class="{{ isset($userDocuments['transcript']) ? 'hidden' : '' }}">
                                        <div class="flex items-center">
                                            <input type="file" id="transcript" name="transcript" 
                                                   class="block w-full text-sm text-gray-500
                                                          file:mr-4 file:py-2 file:px-4
                                                          file:rounded-md file:border-0
                                                          file:text-sm file:font-semibold
                                                          file:bg-blue-50 file:text-blue-700
                                                          hover:file:bg-blue-100"
                                                   accept=".pdf"
                                                   onchange="handleFileUpload(this, 'transcript')">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Format: PDF (Maks. 2MB)
                                        </p>
                                        <p id="transcript-error" class="mt-1 text-xs text-red-600 dark:text-red-400 hidden"></p>
                                        <p id="transcript-name" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                                    </div>
                                </div>

                                <!-- Certificate (Optional) -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Sertifikat (Opsional)
                                        </label>
                                        @if(isset($userDocuments['certificate']))
                                        <div class="flex items-center">
                                            <input type="checkbox" id="use_existing_certificate" name="use_existing_certificate" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                   onchange="toggleFileUpload('certificate', this.checked)" checked>
                                            <label for="use_existing_certificate" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                Gunakan dokumen yang sudah ada
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    @if(isset($userDocuments['certificate']))
                                    <div id="certificate-existing" class="mb-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="font-medium text-green-700 dark:text-green-300">Dokumen tersedia</span>
                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                    {{ $userDocuments['certificate']->original_filename }}
                                                    <span class="text-xs text-green-500 block">
                                                        Diunggah pada: {{ $userDocuments['certificate']->created_at->format('d M Y H:i') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <a href="{{ route('mahasiswa.profile.view-document', $userDocuments['certificate']->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                               target="_blank"
                                               rel="noopener noreferrer">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    <div id="certificate-upload" class="{{ isset($userDocuments['certificate']) ? 'hidden' : '' }}">
                                        <div class="flex items-center">
                                            <input type="file" id="certificate" name="certificate" 
                                                   class="block w-full text-sm text-gray-500
                                                          file:mr-4 file:py-2 file:px-4
                                                          file:rounded-md file:border-0
                                                          file:text-sm file:font-semibold
                                                          file:bg-blue-50 file:text-blue-700
                                                          hover:file:bg-blue-100"
                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                   onchange="handleFileUpload(this, 'certificate')">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Format: PDF, JPG, PNG (Maks. 2MB)
                                        </p>
                                        <p id="certificate-error" class="mt-1 text-xs text-red-600 dark:text-red-400 hidden"></p>
                                        <p id="certificate-name" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                                    </div>
                                    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-md border border-blue-200 dark:border-blue-800">
                                        <p class="text-xs text-blue-700 dark:text-blue-300 italic">
                                            <span class="font-semibold">Catatan:</span> Pastikan semua dokumen yang diunggah sudah benar dan dapat dibaca. Proses verifikasi dokumen akan mempengaruhi kelulusan Anda dalam seleksi magang ini.
                                        </p>
                                    </div>
                               </div>
                            </div>
                            <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                                <button type="button" 
                                        onclick="nextStep('step1', 'step2')" 
                                        id="nextButton"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Selanjutnya
                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Review and Terms -->
                        <div id="step2" class="step-content">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-6">Review Lamaran</h4>

                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden mb-6">
                                <div class="px-4 py-5 sm:p-6">
                                    <h5 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detail Lowongan</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Posisi</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $internship->title }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Kualifikasi Pendidikan</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $internship->education_qualification ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Lokasi</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $internship->location }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Batas Pendaftaran</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                                {{ $internship->application_deadline ? $internship->application_deadline->translatedFormat('d F Y') : 'Tidak ditentukan' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Divisi</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $internship->division ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Durasi Magang</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                                @if($internship->start_date && $internship->end_date)
                                                    {{ $internship->getDurationInMonths() }} Bulan
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 block mt-1">
                                                        ({{ $internship->start_date->translatedFormat('d F Y') }} - {{ $internship->end_date->translatedFormat('d F Y') }})
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800/50">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-full">
                                                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h4 class="text-sm font-semibold text-green-800 dark:text-green-200 flex items-center">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                                    <circle cx="4" cy="4" r="3" />
                                                                </svg>
                                                                MANFAAT BERGABUNG
                                                            </span>
                                                        </h4>
                                                        <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                                                            Manfaat Bergabung Program Magang Kami:
                                                        </p>
                                                        <ul class="mt-2 space-y-1 text-sm text-green-600 dark:text-green-400">
                                                            <li class="flex items-start">
                                                                <svg class="h-4 w-4 text-green-500 dark:text-green-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                <span>Sertifikat resmi yang dapat memperkaya portofolio Anda</span>
                                                            </li>
                                                            <li class="flex items-start">
                                                                <svg class="h-4 w-4 text-green-500 dark:text-green-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                <span>Jaringan profesional dengan praktisi industri</span>
                                                            </li>
                                                            <li class="flex items-start">
                                                                <svg class="h-4 w-4 text-green-500 dark:text-green-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                <span>Pengalaman kerja nyata yang bernilai di CV</span>
                                                            </li>
                                                            <li class="flex items-start">
                                                                <svg class="h-4 w-4 text-green-500 dark:text-green-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                <span>Kesempatan untuk direkrut sebagai karyawan tetap</span>
                                                            </li>
                                                            <li class="flex items-start">
                                                                <svg class="h-4 w-4 text-green-500 dark:text-green-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                <span>Mentoring langsung dari profesional berpengalaman</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
                                <h5 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Pernyataan Ketentuan dan Komitmen Peserta
                                </h5>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="bg-blue-100 dark:bg-blue-800/50 rounded-full p-1">
                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="ml-3 text-sm text-blue-700 dark:text-blue-300">
                                            Saya bersedia mengikuti seluruh proses seleksi, pembekalan, dan kegiatan magang sesuai jadwal dan ketentuan yang telah ditetapkan oleh penyelenggara.
                                        </p>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="bg-blue-100 dark:bg-blue-800/50 rounded-full p-1">
                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="ml-3 text-sm text-blue-700 dark:text-blue-300">
                                            Saya berkomitmen menyelesaikan seluruh masa magang dengan penuh tanggung jawab, integritas, dan profesionalisme.
                                        </p>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="bg-blue-100 dark:bg-blue-800/50 rounded-full p-1">
                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="ml-3 text-sm text-blue-700 dark:text-blue-300">
                                            Saya memahami bahwa seluruh hasil kerja dan laporan selama magang akan menjadi bagian dari evaluasi performa saya sebagai peserta program magang.
                                        </p>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="bg-blue-100 dark:bg-blue-800/50 rounded-full p-1">
                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="ml-3 text-sm text-blue-700 dark:text-blue-300">
                                            Saya akan mematuhi semua peraturan perusahaan/instansi, termasuk tata tertib, etika kerja, dan kebijakan keselamatan kerja.
                                        </p>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="bg-blue-100 dark:bg-blue-800/50 rounded-full p-1">
                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="ml-3 text-sm text-blue-700 dark:text-blue-300">
                                            Saya bersedia mengikuti penempatan program magang di lokasi yang telah ditentukan oleh perusahaan atau instansi penyelenggara, sesuai dengan kebutuhan dan kebijakan yang berlaku.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Persetujuan Syarat dan Ketentuan -->
                            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5 mt-0.5">
                                        <input id="agreeTerms" name="agree_terms" type="checkbox" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded" 
                                            onchange="validateForm()"
                                            required>
                                    </div>
                                    <div class="ml-3">
                                        <label for="agreeTerms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Pernyataan Persetujuan
                                        </label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Dengan ini, saya menyatakan telah membaca, memahami, dan menyetujui seluruh ketentuan penggunaan dan kebijakan privasi, serta bersedia untuk mematuhinya selama mengikuti proses dan program magang.
                                        </p>
                                        <p id="terms-error" class="mt-2 text-sm text-red-600 dark:text-red-400 hidden">
                                            Anda harus menyetujui syarat dan ketentuan untuk melanjutkan.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                                <button type="button" 
                                        onclick="prevStep('step2', 'step1')" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Kembali
                                </button>
                                <form action="{{ route('internships.apply', $internship) }}" method="POST" id="applicationForm" class="m-0">
                                    @csrf
                                    <button type="submit" 
                                            id="submitApplication"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                            disabled>
                                        Kirim Lamaran
                                        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Function to close the modal
function closeModal() {
    const modal = document.getElementById('applicationModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>