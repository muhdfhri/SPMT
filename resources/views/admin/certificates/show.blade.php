@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Sertifikat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Informasi lengkap sertifikat magang</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.certificates.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Certificate Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Certificate Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Sertifikat</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Sertifikat</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $certificate->certificate_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Diterbitkan</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $certificate->issue_date->translatedFormat('j F Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                            @if($certificate->status === 'published')
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Diterbitkan
                                </span>
                            @elseif($certificate->status === 'revoked')
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Dicabut
                                </span>
                            @else
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Menunggu
                                </span>
                            @endif
                        </div>
                        @if($certificate->revoked_at)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dicabut Pada</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $certificate->revoked_at->format('d F Y H:i:s') }}
                                @if($certificate->revoked_by_user)
                                    <span class="text-gray-500 dark:text-gray-400">oleh {{ $certificate->revoked_by_user->name }}</span>
                                @endif
                            </p>
                            @if($certificate->revocation_reason)
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <span class="font-medium">Alasan:</span> {{ $certificate->revocation_reason }}
                                </p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Mahasiswa</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            @if($certificate->user->studentProfile && $certificate->user->studentProfile->profile_photo)
                                <img class="h-16 w-16 rounded-full object-cover" 
                                     src="{{ asset('storage/' . $certificate->user->studentProfile->profile_photo) }}" 
                                     alt="{{ $certificate->user->name }}">
                            @else
                                <div class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $certificate->user->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $certificate->user->email }}</p>
                            @if($certificate->user->studentProfile)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <span class="font-medium">NIK:</span> {{ $certificate->user->studentProfile->nik ?? '-' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Internship Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Magang</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Judul Magang</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $certificate->internship->title ?? '-' }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if($certificate->internship)
                                        {{ \Carbon\Carbon::parse($certificate->internship->start_date)->translatedFormat('j F Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if($certificate->internship)
                                        {{ \Carbon\Carbon::parse($certificate->internship->end_date)->translatedFormat('j F Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Divisi</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $certificate->internship->division ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Sertifikat</h3>
                </div>
                <div class="p-6">
                    <div class="aspect-[1.4142] bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                        <div class="text-center p-4">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Sertifikat</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Klik tombol di bawah untuk mengunduh</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col space-y-3">
                        <a href="{{ route('admin.certificates.download', $certificate) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh Sertifikat
                        </a>
                        @if($certificate->status !== 'revoked')
                        <button type="button" 
                                onclick="event.stopPropagation(); openRevokeModal('{{ $certificate->id }}')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-900 mt-2">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Cabut Sertifikat
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revoke Certificate Modal -->
<div id="revokeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center px-4 py-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRevokeModal()"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block w-full transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:max-w-lg sm:p-6 sm:align-middle">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Cabut Sertifikat
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Apakah Anda yakin ingin mencabut sertifikat ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                    <div class="mt-4">
                        <label for="revoked_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan Pencabutan</label>
                        <textarea id="revoked_reason" name="revoked_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="Masukkan alasan pencabutan" required></textarea>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form id="revokeForm" method="POST" onsubmit="return handleRevokeSubmit(event)">
                    @csrf
                    @method('POST')
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                            Ya, Cabut Sertifikat
                        </button>
                        <button type="button" onclick="closeRevokeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openRevokeModal(certificateId, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const modal = document.getElementById('revokeModal');
        const form = document.getElementById('revokeForm');
        const scrollY = window.scrollY;
        
        form.action = `/admin/certificates/${certificateId}/revoke`;
        modal.classList.remove('hidden');
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}px`;
        document.body.style.width = '100%';
        
        // Store the scroll position
        modal.dataset.scrollY = scrollY;
        
        // Reset form when opening modal
        form.reset();
        
        return false;
    }
    
    async function handleRevokeSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const url = form.action;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        try {
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    revoked_reason: document.getElementById('revoked_reason').value
                })
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan saat mencabut sertifikat');
            }
            
            // Show success message
            alert('Sertifikat berhasil dicabut');
            // Reload the page to reflect changes
            window.location.reload();
            
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
        
        return false;
    }
    
    function closeRevokeModal() {
        const modal = document.getElementById('revokeModal');
        const scrollY = modal.dataset.scrollY || 0;
        
        modal.classList.add('hidden');
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        
        // Restore the scroll position
        if (scrollY) {
            window.scrollTo(0, parseInt(scrollY));
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('revokeModal');
        if (event.target === modal) {
            closeRevokeModal();
        }
    }

    // Handle form submission
    document.getElementById('revokeForm').addEventListener('submit', function(e) {
        const reason = document.getElementById('revocation_reason').value;
        document.getElementById('revocation_reason_input').value = reason;
    });
</script>
@endpush

@endsection
