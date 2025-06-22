<div id="reports-table-container" class="bg-white rounded-xl shadow p-4 overflow-x-auto dark:bg-gray-900">
    <table class="w-full text-left border-separate border-spacing-y-1">
        <thead>
            <tr class="bg-blue-50 dark:bg-gray-800">
                <th class="py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 text-center w-1/4">Judul</th>
                <th class="py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 text-center w-1/5">Tanggal</th>
                <th class="py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 text-center w-1/6">Status</th>
                <th class="py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 text-center w-1/3">File</th>
                <th class="py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 text-center w-1/12">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr class="bg-white dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors border-b border-gray-200 dark:border-gray-700">
                <td class="py-3 px-4 align-middle text-gray-800 dark:text-gray-100 text-center break-words">{{ $report->judul }}</td>
                <td class="py-3 px-4 align-middle text-gray-700 dark:text-gray-200 text-center">{{ $report->created_at->format('d-m-Y H:i') }}</td>
                <td class="py-3 px-4 align-middle text-center">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                        {{ $report->status == 'dikirim' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </td>
                <td class="py-3 px-4 align-middle text-center">
                    @if($report->attachments->count())
                        <ul class="list-none p-0 m-0 space-y-1">
                            @foreach($report->attachments as $file)
                                <li class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-blue-600 hover:underline font-medium truncate max-w-[180px] inline-block">
                                        <i class="fas fa-file-alt mr-1"></i>{{ $file->original_filename }}
                                    </a>
                                    <span class="text-xs text-gray-500">({{ number_format($file->file_size/1024,1) }} KB)</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="py-3 px-4 align-middle text-center">
                    <button class="delete-report-btn text-red-500 hover:text-red-700 p-2 rounded-full transition-colors" title="Hapus Laporan" data-id="{{ $report->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-6 text-center text-gray-500">Belum ada laporan.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reports->hasPages())
        <div class="mt-4 flex justify-center">
            {!! $reports->appends(['tab' => 'reports'])->links('vendor.pagination.ajax') !!}
        </div>
    @endif
</div> 