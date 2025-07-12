@extends('layouts.admin')
@section('title', 'Dashboard - Admin Panel')

@section('content')
<div class="container mx-auto px-4 py-6">

    
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang di panel admin SPMT</p>
            </div>
        </div>
    </div>

    <!-- Statistik -->    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Lamaran Baru -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lamaran Baru</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_applications'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                    <a href="{{ route('admin.applications.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                        Lihat semua lamaran
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Laporan Perlu Review -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center">
                            <div class="bg-yellow-100 dark:bg-yellow-900/30 p-2 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Laporan Perlu Review</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['pending_reports'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                    <a href="{{ route('admin.reports.index') }}" class="text-sm text-yellow-600 dark:text-yellow-400 hover:underline flex items-center">
                        Lihat semua laporan
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Magang Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Magang Aktif</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active_internships'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                    <a href="{{ route('admin.students.active-internships') }}" class="text-sm text-green-600 dark:text-green-400 hover:underline flex items-center">
                        Lihat semua mahasiswa
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sertifikat Dicetak -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center">
                            <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sertifikat Dicetak</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['certificates_issued'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                    <a href="{{ route('admin.certificates.index') }}" class="text-sm text-purple-600 dark:text-purple-400 hover:underline flex items-center">
                        Lihat semua sertifikat
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Status Magang dan Laporan Bulanan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Grafik Status Magang -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status Magang</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Distribusi status magang mahasiswa</p>
            </div>
            <div class="p-5">
                <div class="w-full mx-auto">
                    <canvas id="statusMagangChart"></canvas>
                </div>
                <!-- Legend -->
                <div class="mt-4 flex flex-wrap justify-center gap-4 text-sm">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                        <span>Menunggu</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        <span>Diterima</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                        <span>Dalam Proses</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-purple-500 mr-2"></span>
                        <span>Selesai</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                        <span>Ditolak</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Laporan Bulanan -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status Laporan Bulanan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Distribusi status laporan bulanan mahasiswa</p>
            </div>
            <div class="p-5">
                <div class="w-full mx-auto">
                    <canvas id="laporanBulananChart"></canvas>
                </div>
                <!-- Legend -->
                <div class="mt-4 flex flex-wrap justify-center gap-4 text-sm">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                        <span>Menunggu Review</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        <span>Disetujui</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                        <span>Ditolak</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Pertumbuhan Pendaftar -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pertumbuhan Pendaftar Magang</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah pendaftar baru per bulan (6 bulan terakhir)</p>
        </div>
        <div class="p-5">
            <div class="w-full" style="height: 300px;">
                <canvas id="pendaftarChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat 5 aktivitas terbaru di sistem</p>
        </div>
        
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recentActivities as $activity)
                @php
                    // Tentukan ikon dan warna berdasarkan jenis aktivitas
                    $icon = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
                    $iconClass = 'text-yellow-600 dark:text-yellow-400';
                    $bgClass = 'bg-yellow-100 dark:bg-yellow-900/30';
                    
                    if (str_contains($activity->description, 'created')) {
                        $icon = 'M12 6v6m0 0v6m0-6h6m-6 0H6';
                        $iconClass = 'text-blue-600 dark:text-blue-400';
                        $bgClass = 'bg-blue-100 dark:bg-blue-900/30';
                    } elseif (str_contains($activity->description, 'updated')) {
                        $icon = 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15';
                        $iconClass = 'text-green-600 dark:text-green-400';
                        $bgClass = 'bg-green-100 dark:bg-green-900/30';
                    } elseif (str_contains($activity->description, 'deleted')) {
                        $icon = 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16';
                        $iconClass = 'text-red-600 dark:text-red-400';
                        $bgClass = 'bg-red-100 dark:bg-red-900/30';
                    }
                    
                    // Dapatkan nama user yang melakukan aksi
                    $causerName = $activity->causer ? $activity->causer->name : 'Sistem';
                    
                    // Format waktu
                    $timeAgo = $activity->created_at->diffForHumans();
                @endphp
                
                <div class="px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full {{ $bgClass }} flex items-center justify-center">
                                <svg class="h-5 w-5 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $activity->description }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Oleh: {{ $causerName }}
                                @if($activity->subject)
                                    @php
                                        $subjectName = '';
                                        if (method_exists($activity->subject, 'getActivityLogName')) {
                                            $subjectName = $activity->subject->getActivityLogName();
                                        } else {
                                            $subjectName = class_basename($activity->subject_type) . ' #' . $activity->subject_id;
                                        }
                                    @endphp
                                    • {{ $subjectName }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $timeAgo }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas terbaru</p>
                </div>
            @endforelse
        </div>
    </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aksi Cepat</h2>
        </div>
        
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Action 1 -->
            <a href="{{ route('admin.applications.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex-shrink-0 mr-4">
                    <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Kelola Lamaran</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Review dan proses lamaran</p>
                </div>
            </a>
            
            <!-- Action 2 -->
            <a href="{{ route('admin.reports.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex-shrink-0 mr-4">
                    <div class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                        <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Review Laporan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Periksa laporan mingguan</p>
                </div>
            </a>
            
            <!-- Action 3 -->
            <a href="{{ route('admin.students.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex-shrink-0 mr-4">
                    <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Kelola Mahasiswa</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Lihat data mahasiswa</p>
                </div>
            </a>
            
            <!-- Action 4 -->
            <a href="{{ route('admin.certificates.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex-shrink-0 mr-4">
                    <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Cetak Sertifikat</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kelola sertifikat magang</p>
                </div>
            </a>
        </div>
    </div>
</div>
@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Pertumbuhan Pendaftar
    document.addEventListener('DOMContentLoaded', function() {
        const pendaftarCtx = document.getElementById('pendaftarChart').getContext('2d');
        new Chart(pendaftarCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyData['labels']),
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: @json($monthlyData['data']),
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'white',
                    pointBorderColor: 'rgb(79, 70, 229)',
                    pointBorderWidth: 2,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: 'white',
                    pointHoverBorderColor: 'rgb(79, 70, 229)',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 12 },
                        bodyFont: { size: 13 },
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y} pendaftar`;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grafik Status Magang (Pie Chart)
        const statusMagangCtx = document.getElementById('statusMagangChart').getContext('2d');
        const statusMagangData = @json($statusMagang);
        
        new Chart(statusMagangCtx, {
            type: 'pie',
            data: {
                labels: ['Menunggu', 'Diterima', 'Dalam Proses', 'Selesai', 'Ditolak'],
                datasets: [{
                    data: [
                        statusMagangData.menunggu,
                        statusMagangData.diterima,
                        statusMagangData.in_progress,
                        statusMagangData.completed,
                        statusMagangData.ditolak
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',  // blue-500
                        'rgba(34, 197, 94, 0.7)',    // green-500
                        'rgba(234, 179, 8, 0.7)',    // yellow-500
                        'rgba(168, 85, 247, 0.7)',   // purple-500
                        'rgba(239, 68, 68, 0.7)'     // red-500
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(168, 85, 247, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Grafik Laporan Bulanan (Horizontal Bar Chart)
        const laporanBulananCtx = document.getElementById('laporanBulananChart').getContext('2d');
        const laporanBulananData = @json($laporanBulanan);

        new Chart(laporanBulananCtx, {
            type: 'bar',
            data: {
                labels: ['Menunggu Review', 'Disetujui', 'Ditolak'],
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: [
                        laporanBulananData.pending,
                        laporanBulananData.approved,
                        laporanBulananData.rejected
                    ],
                    backgroundColor: [
                        'rgba(234, 179, 8, 0.7)',    // yellow-500
                        'rgba(34, 197, 94, 0.7)',    // green-500
                        'rgba(239, 68, 68, 0.7)'     // red-500
                    ],
                    borderColor: [
                        'rgba(234, 179, 8, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
</script>
@endpush

@endsection
