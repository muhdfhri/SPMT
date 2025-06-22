@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Lamaran {{ $student->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Riwayat lamaran magang mahasiswa</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.students.show', $student) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Kembali ke Profil
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Program Magang
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tanggal Lamar
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @if($applications->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="mx-auto h-16 w-16 text-gray-400">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada lamaran</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mahasiswa ini belum pernah mengajukan lamaran magang.</p>
                        </td>
                    </tr>
                    @else
                        @foreach($applications as $application)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->internship->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $application->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($application->status === 'pending')
                                    <div class="relative group">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 cursor-pointer" id="status-{{ $application->id }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                        <div class="absolute z-10 hidden group-hover:block bg-white dark:bg-gray-800 shadow-lg rounded-md border border-gray-200 dark:border-gray-700 mt-1 w-48" id="status-dropdown-{{ $application->id }}">
                                            <form action="{{ route('admin.applications.approve', $application) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-50 dark:hover:bg-gray-700 dark:text-green-400" onclick="return confirm('Apakah Anda yakin ingin menyetujui lamaran ini?')">
                                                    <i class="fas fa-check-circle mr-2"></i> Setujui
                                                </button>
                                            </form>
                                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                            <button type="button" onclick="showRejectModal('{{ $application->id }}')" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 dark:hover:bg-gray-700 dark:text-red-400">
                                                <i class="fas fa-times-circle mr-2"></i> Tolak
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $application->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.applications.show', $application) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($applications->hasPages())
        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700">
            {{ $applications->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
<!-- Reject Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="rejectModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Tolak Lamaran
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Masukkan alasan penolakan lamaran ini.
                        </p>
                        <form id="rejectForm" method="POST" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <div>
                                <textarea id="rejection_reason" name="rejection_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2" placeholder="Alasan penolakan..." required></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="button" onclick="submitRejectForm()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                    Tolak Lamaran
                </button>
                <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentApplicationId = null;

    function showRejectModal(applicationId) {
        currentApplicationId = applicationId;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('rejection_reason').value = '';
    }

    function submitRejectForm() {
        const reason = document.getElementById('rejection_reason').value;
        if (!reason.trim()) {
            alert('Mohon isi alasan penolakan');
            return;
        }

        const form = document.getElementById('rejectForm');
        form.method = 'POST';
        form.action = '{{ route("admin.applications.reject", "") }}/' + currentApplicationId;
        form.submit();
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('rejectModal');
        if (event.target === modal) {
            closeRejectModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeRejectModal();
        }
    });
</script>
@endpush

@endsection
