@extends('layouts.mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sertifikat Magang</h1>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Daftar sertifikat magang yang telah Anda peroleh</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            @if($certificates->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mb-4">
                        <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Belum Ada Sertifikat</h3>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">
                        Sertifikat akan tersedia setelah Anda:
                    </p>
                    <ul class="mt-3 text-gray-600 dark:text-gray-400 list-disc list-inside">
                        <li>Menyelesaikan program magang</li>
                        <li>Semua laporan bulanan telah disetujui</li>
                        <li>Mendapat persetujuan dari pembimbing</li>
                    </ul>
                    <div class="mt-6">
                        <a href="{{ route('mahasiswa.reports.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            Lihat Laporan Bulanan
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @else
                <!-- Certificate List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Program Magang
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tanggal Terbit
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($certificates as $certificate)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $certificate->internship->title ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        No. Sertifikat: {{ $certificate->certificate_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $certificate->issue_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                    <a href="#" 
                                       onclick="handleCertificateDownload(event, '{{ route('mahasiswa.certificates.download', $certificate) }}')" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Unduh
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($certificates, 'hasPages') && $certificates->hasPages())
                <div class="mt-4">
                    {{ $certificates->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    // Function to handle certificate download
    async function handleCertificateDownload(event, url) {
        event.preventDefault();
        
        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            // If response is a file download
            if (response.headers.get('content-type')?.includes('application/pdf')) {
                // Create a temporary link to trigger download
                const blob = await response.blob();
                const downloadUrl = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = downloadUrl;
                link.setAttribute('download', 'sertifikat.pdf');
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(downloadUrl);
                return;
            }
            
            const data = await response.json();
            
            // If certificate is revoked, show modal with reason
            if (data.status === 'revoked') {
                showRevokedCertificateModal(data);
            } else {
                // For other errors
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Terjadi kesalahan saat mengunduh sertifikat',
                    confirmButtonText: 'Tutup'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan. Silakan coba lagi nanti.',
                confirmButtonText: 'Tutup'
            });
        }
    }
    
    // Function to show revoked certificate modal
    function showRevokedCertificateModal(data) {
        Swal.fire({
            icon: 'warning',
            title: 'Sertifikat Telah Dicabut',
            html: `
                <div class="text-left space-y-3">
                    <p class="mb-2">${data.message}</p>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Alasan Pencabutan</h3>
                                <div class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                                    <p>${data.revoked_reason || 'Tidak ada alasan yang dicantumkan'}</p>
                                </div>
                                <div class="mt-2 text-xs text-yellow-600 dark:text-yellow-400">
                                    <p>Dicabut pada: ${data.revoked_at || 'Tanggal tidak tersedia'}</p>
                                    <p>Oleh: ${data.revoked_by || 'Administrator'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#3B82F6',
            customClass: {
                confirmButton: 'px-4 py-2 text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .swal2-popup {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .swal2-title {
        @apply text-lg font-semibold text-gray-900 dark:text-white;
    }
    .swal2-html-container {
        @apply text-sm text-gray-600 dark:text-gray-300;
    }
</style>
@endpush

@endsection
