@extends('layouts.internship')

@section('title', 'Divisi Magang - PT Pelindo Multi Terminal')

@push('styles')
<style>
    /* Modern Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 1000;
        overflow-y: auto;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal.show {
        opacity: 1;
    }
    
    .modal-content {
        position: relative;
        background: rgba(255, 255, 255, 0.95);
        margin: 5% auto;
        padding: 0;
        width: 90%;
        max-width: 900px;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: translateY(20px);
        transition: transform 0.3s ease, opacity 0.3s ease;
        opacity: 0;
        overflow: hidden;
    }
    
    .modal.show .modal-content {
        transform: translateY(0);
        opacity: 1;
    }
    
    .dark .modal-content {
        background: rgba(17, 24, 39, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .close-btn {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 0.75rem;
        color: #6b7280;
        transition: all 0.2s ease;
        z-index: 10;
        border: none;
        cursor: pointer;
    }
    
    .close-btn:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }
    
    /* Modern Card Styles */
    .division-card {
        position: relative;
        overflow: hidden;
        border-radius: 1.25rem;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .dark .division-card {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .division-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #0E73B9 0%, #55B7E3 100%);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .division-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .dark .division-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    
    .division-card:hover::before {
        transform: scaleX(1);
    }
    
    .division-number {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        color: #0ea5e9;
        border-radius: 0.75rem;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
    }
    
    .dark .division-number {
        background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 100%);
        color: white;
    }
    
    .division-card:hover .division-number {
        transform: scale(1.1) rotate(5deg);
        background: linear-gradient(135deg, #0E73B9 0%, #55B7E3 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(14, 115, 185, 0.3);
    }
    
    .division-title {
        position: relative;
        display: inline-block;
        font-weight: 700;
        font-size: 1.25rem;
        line-height: 1.4;
        color: #000000;
        transition: all 0.3s ease;
    }
    
    .dark .division-title {
        color: #000000;
    }
    
    .division-highlight {
        @apply font-medium text-blue-600 dark:text-blue-400;
        position: relative;
        display: inline-block;
    }
    
    .division-highlight::after {
        content: '';
        position: absolute;
        bottom: 0.1em;
        left: 0;
        width: 100%;
        height: 40%;
        background: rgba(14, 115, 185, 0.15);
        z-index: -1;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .division-card:hover .division-highlight::after {
        height: 100%;
        background: rgba(14, 115, 185, 0.25);
    }
    
    /* Modern Select Styling */
    .select-container {
        position: relative;
        max-width: 36rem;
        margin: 0 auto 3rem;
    }
    
    .select-container::before {
        content: '🔍';
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.25rem;
        z-index: 10;
    }
    
    .select-input {
        appearance: none;
        width: 100%;
        padding: 1.25rem 1.5rem 1.25rem 3.5rem;
        font-size: 1.125rem;
        line-height: 1.5;
        color: #1f2937;
        background-color: white;
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .dark .select-input {
        background-color: #1f2937;
        border-color: #374151;
        color: #f3f4f6;
    }
    
    .select-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    
    .select-arrow {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6b7280;
        transition: transform 0.2s ease;
    }
    
    .select-container:focus-within .select-arrow {
        transform: translateY(-50%) rotate(180deg);
    }
    
    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .select-input {
            font-size: 1rem;
            padding: 1rem 1.25rem 1rem 3rem;
        }
        
        .select-container::before {
            left: 1rem;
            font-size: 1rem;
        }
        
        .select-arrow {
            right: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="full mx-auto mt-6">
<!-- Hero Section with Animated Background -->
<div class="relative overflow-hidden min-h-screen bg-gradient-to-br from-blue-50 to-white dark:from-gray-900 dark:to-gray-800 py-16 sm:py-24 px-4 sm:px-6 lg:px-8">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-gradient-radial from-blue-100 to-transparent dark:from-blue-900/20 dark:to-transparent opacity-50"></div>
        <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-gradient-radial from-cyan-100 to-transparent dark:from-cyan-900/20 dark:to-transparent opacity-50"></div>
        
        <!-- Animated Grid Pattern -->
        <div class="absolute inset-0 opacity-10 dark:opacity-[0.03]">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMwMDc3YjYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzRjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTR6bS0yMiA0N2MyMi4wOTIgMCA0MC0xNy45MDggNDAtNDBzLTE3LjkwOC00MC00MC00MC00MCAxNy45MDgtNDAgNDAgMTcuOTA4IDQwIDQwIDQwek0yNCA2MGMxOS44ODIgMCAzNi0xNi4xMTggMzYtM1M0My44ODIgNTQgMjQgNTRzLTM2IDE2LjExOC0zNi0zIDE2LjExOC0zIDM2LTN6Ii8+PC9nPjwvZz48L3N2Zz4=')]"></div>
        </div>
    </div>

    <div class="relative max-w-7xl mx-auto">
        <!-- Hero Content -->
        <div class="text-center mb-16 px-4 sm:px-6 lg:px-8" data-aos="fade-up">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 mb-6 border border-blue-200/50 dark:border-blue-800/30">
                <span class="relative flex h-2.5 w-2.5 mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                </span>
                Divisi & Departemen
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                Eksplor <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500">Divisi Kami</span>
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Temukan berbagai divisi yang membentuk struktur operasional PT Pelindo Multi Terminal. Setiap divisi memiliki peran dan tanggung jawab yang berbeda sesuai dengan bidangnya masing-masing.
            </p>
        </div>

        <!-- Division Selector -->
        <div class="select-container" data-aos="fade-up" data-aos-delay="100">
            <select id="division-selector" class="select-input">
                <option value="">Cari divisi...</option>
                @foreach(\App\Helpers\DivisionHelper::getAllDivisions() as $index => $division)
                    <option value="{{ $division }}">{{ $division }}</option>
                @endforeach
            </select>
            <div class="select-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Divisions Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
            @foreach(\App\Helpers\DivisionHelper::getAllDivisions() as $index => $division)
                @php
                    $descriptions = [
                        'Satuan Pengawasan Intern' => [
                            'desc' => 'Sebagai garda terdepan dalam menjaga integritas perusahaan, divisi ini melakukan pengawasan internal dan audit menyeluruh di seluruh unit kerja untuk memastikan kepatuhan terhadap regulasi dan standar operasional yang berlaku.',
                            'highlight' => 'Menjaga integritas dan kepatuhan perusahaan'
                        ],
                        'Sekretariat Perusahaan' => [
                            'desc' => 'Menjadi pusat kendali administrasi dan kesekretariatan perusahaan, mengelola dokumen penting, rapat direksi, serta menjadi penghubung antara manajemen dengan dewan komisaris dan pemangku kepentingan eksternal.',
                            'highlight' => 'Pusat kendali administrasi dan komunikasi perusahaan'
                        ],
                        'Tranformation Management Office' => [
                            'desc' => 'Mengawal transformasi digital dan inovasi bisnis perusahaan melalui inisiatif strategis yang berdampak signifikan terhadap efisiensi operasional dan pengalaman pelanggan.',
                            'highlight' => 'Pendorong utama transformasi digital dan inovasi bisnis'
                        ],
                        'PPSDM' => [
                            'desc' => 'Mengembangkan potensi sumber daya manusia melalui program pelatihan yang komprehensif, manajemen kinerja, dan pengembangan karir untuk menciptakan SDM yang kompeten dan berdaya saing tinggi.',
                            'highlight' => 'Pengembangan SDM berkelas dunia'
                        ],
                        'Layanan SDM dan HSSE' => [
                            'desc' => 'Berkomitmen menciptakan lingkungan kerja yang aman, sehat, dan berkelanjutan melalui penerapan standar K3 (Keselamatan dan Kesehatan Kerja) serta manajemen lingkungan yang bertanggung jawab.',
                            'highlight' => 'Menjaga keselamatan dan kesejahteraan karyawan'
                        ],
                        'Hukum' => [
                            'desc' => 'Memberikan perlindungan hukum bagi perusahaan melalui analisis mendalam terhadap aspek hukum, penyusunan kontrak, hingga penanganan sengketa dengan pendekatan yang profesional dan berintegritas.',
                            'highlight' => 'Perlindungan hukum yang komprehensif'
                        ],
                        'Anggaran, Akuntansi, dan Pelaporan' => [
                            'desc' => 'Mengelola keuangan perusahaan secara transparan dan akurat, mulai dari penyusunan anggaran, pencatatan transaksi, hingga penyajian laporan keuangan yang dapat dipertanggungjawabkan.',
                            'highlight' => 'Manajemen keuangan yang transparan dan akurat'
                        ],
                        'Keuangan dan Perpajakan' => [
                            'desc' => 'Mengoptimalkan pengelolaan keuangan perusahaan dan memastikan kepatuhan perpajakan melalui strategi perencanaan pajak yang efisien dan sesuai regulasi yang berlaku.',
                            'highlight' => 'Optimalisasi keuangan dan kepatuhan pajak'
                        ],
                        'Manajemen Risiko' => [
                            'desc' => 'Mengidentifikasi, menilai, dan memitigasi berbagai risiko bisnis untuk memastikan kelangsungan operasional perusahaan dan pencapaian tujuan strategis.',
                            'highlight' => 'Perlindungan berkelanjutan dari risiko bisnis'
                        ],
                        'Perencanaan Strategis' => [
                            'desc' => 'Menyusun peta jalan perusahaan melalui analisis mendalam terhadap tren pasar, kompetitor, dan peluang bisnis untuk menciptakan keunggulan kompetitif yang berkelanjutan.',
                            'highlight' => 'Panduan menuju keunggulan kompetitif'
                        ],
                        'Kerjasama Usaha dan Pembinaan Anak Perusahaan' => [
                            'desc' => 'Mengembangkan kemitraan strategis dan mengoptimalkan kinerja anak perusahaan untuk menciptakan sinergi bisnis yang saling menguntungkan dan berkelanjutan.',
                            'highlight' => 'Pengembangan kemitraan strategis'
                        ],
                        'Komersial dan Hubungan Pelanggan' => [
                            'desc' => 'Membangun hubungan jangka panjang dengan pelanggan melalui layanan yang unggul, solusi yang inovatif, dan pemahaman mendalam akan kebutuhan bisnis mereka.',
                            'highlight' => 'Membangun hubungan pelanggan yang berkelanjutan'
                        ],
                        'Pengelolaan Operasi' => [
                            'desc' => 'Memastikan kelancaran operasional harian dengan mengoptimalkan sumber daya yang ada dan menerapkan praktik terbaik dalam manajemen operasi pelabuhan.',
                            'highlight' => 'Operasional pelabuhan yang efisien dan handal'
                        ],
                        'Perencanaan dan Pengembangan Operasi' => [
                            'desc' => 'Merancang strategi operasional yang inovatif dan berkelanjutan untuk meningkatkan kinerja pelabuhan serta mengantisipasi tantangan di masa depan.',
                            'highlight' => 'Inovasi dalam strategi operasional'
                        ],
                        'Sistem Manajemen' => [
                            'desc' => 'Mengembangkan dan memelihara sistem manajemen terintegrasi yang mendukung operasional perusahaan sesuai dengan standar internasional.',
                            'highlight' => 'Sistem manajemen terpadu berstandar internasional'
                        ],
                        'Peralatan Pelabuhan' => [
                            'desc' => 'Mengelola dan memelihara seluruh peralatan bongkar muat serta fasilitas pendukung operasional pelabuhan untuk memastikan ketersediaan dan keandalan yang optimal.',
                            'highlight' => 'Manajemen peralatan pelabuhan yang andal'
                        ],
                        'Fasilitas Pelabuhan' => [
                            'desc' => 'Mengembangkan dan mengoptimalkan pemanfaatan fasilitas pelabuhan untuk mendukung pertumbuhan bisnis dan memberikan layanan terbaik kepada pengguna jasa pelabuhan.',
                            'highlight' => 'Pengembangan fasilitas pelabuhan berkelas dunia'
                        ]
                    ];
                    $divisionData = $descriptions[$division] ?? [
                        'desc' => 'Informasi lebih lanjut tentang divisi ini akan segera tersedia.',
                        'highlight' => 'Segera hadir'
                    ];
                @endphp
                
                <div class="division-card group relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="p-6">
                        <div class="flex items-start mb-4">
                            <div class="division-number flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-cyan-50 dark:from-blue-900/50 dark:to-cyan-900/20 text-blue-600 dark:text-blue-300 text-xl font-bold mr-4 transition-all duration-300">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h2 class="division-title text-xl font-bold text-black dark:text-black mb-1">{{ $division }}</h2>
                                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-3">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $divisionData['highlight'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 text-justify">{{ $divisionData['desc'] }}</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <button onclick="showDivisionDetail('{{ $division }}', '{{ $divisionData['highlight'] }}', '{{ $divisionData['desc'] }}', '{{ $index + 1 }}')" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors">
                                Lihat detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- CTA Section -->
        <div class="mt-16 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl p-8 md:p-12 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Tertarik Bergabung dengan Tim Kami?</h2>
            <p class="text-blue-100 max-w-2xl mx-auto mb-8">
                Jadilah bagian dari perusahaan pelabuhan terkemuka di Indonesia dan kembangkan karirmu bersama para profesional di bidangnya.
            </p>
            <a href="{{ route('internships.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 transition-colors">
                Lihat Lowongan Tersedia
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

    <!-- Division Detail Modal -->
    <div id="divisionModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()" aria-label="Close modal">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div id="modalContent" class="p-6 sm:p-8">
                <!-- Modal content will be inserted here by JavaScript -->
            </div>
        </div>
    </div>

@push('scripts')
<script>
    // Modal functions
    function getTugasPokok(divisionName) {
        const tugasPokok = {
            'Satuan Pengawasan Intern': [
                'Melakukan audit internal menyeluruh di seluruh unit kerja',
                'Memastikan kepatuhan terhadap regulasi dan standar operasional',
                'Menganalisis temuan audit dan memberikan rekomendasi perbaikan',
                'Melakukan pemantauan tindak lanjut temuan audit',
                'Mengembangkan standar pengawasan internal yang efektif'
            ],
            'Sekretariat Perusahaan': [
                'Mengelola administrasi dan korespondensi perusahaan',
                'Mengkoordinasikan rapat direksi dan rapat dewan komisaris',
                'Mengelola arsip dan dokumentasi perusahaan',
                'Menyiapkan laporan tahunan dan laporan keuangan',
                'Menjadi penghubung dengan pemangku kepentingan eksternal'
            ],
            'Transformation Management Office': [
                'Mengawal inisiatif transformasi digital perusahaan',
                'Mengkoordinasikan proyek-proyek transformasi bisnis',
                'Memantau pencapaian target transformasi',
                'Mengidentifikasi peluang peningkatan efisiensi operasional',
                'Berkoordinasi dengan divisi terkait implementasi perubahan'
            ],
            'PPSDM': [
                'Merancang program pengembangan kompetensi karyawan',
                'Mengelola proses rekrutmen dan seleksi',
                'Mengembangkan sistem penilaian kinerja',
                'Menyusun program pengembangan karir',
                'Mengelola administrasi kepegawaian dan database SDM'
            ],
            'Layanan SDM dan HSSE': [
                'Menerapkan standar K3 di seluruh area kerja',
                'Melakukan audit dan inspeksi K3 secara berkala',
                'Mengembangkan program kesejahteraan karyawan',
                'Menangani kecelakaan kerja dan analisis akar masalah',
                'Mengelola program lingkungan dan keberlanjutan'
            ],
            'Hukum': [
                'Memberikan pendapat hukum atas transaksi bisnis',
                'Menyusun dan meninjau kontrak dan perjanjian',
                'Menangani sengketa dan litigasi',
                'Memastikan kepatuhan hukum perusahaan',
                'Memberikan pembinaan hukum kepada seluruh karyawan'
            ],
            'Anggaran, Akuntansi, dan Pelaporan': [
                'Menyusun anggaran tahunan perusahaan',
                'Mengelola pembukuan dan akuntansi keuangan',
                'Menyusun laporan keuangan bulanan dan tahunan',
                'Melakukan rekonsiliasi bank dan akun-akun',
                'Mengelola arus kas dan likuiditas perusahaan'
            ],
            'Keuangan dan Perpajakan': [
                'Menyusun perencanaan pajak perusahaan',
                'Memastikan kepatuhan perpajakan yang berlaku',
                'Menyiapkan dan melaporkan SPT Tahunan dan Bulanan',
                'Mengelola hubungan dengan kantor pajak',
                'Melakukan analisis dampak perpajakan atas transaksi'
            ],
            'Manajemen Risiko': [
                'Mengidentifikasi risiko operasional dan bisnis',
                'Melakukan penilaian dan pemetaan risiko',
                'Mengembangkan strategi mitigasi risiko',
                'Memantau dan mengevaluasi risiko yang ada',
                'Menyusun laporan manajemen risiko ke direksi'
            ],
            'Perencanaan Strategis': [
                'Menyusun rencana strategis perusahaan 5 tahunan',
                'Melakukan analisis pasar dan kompetitor',
                'Mengembangkan strategi bisnis dan portofolio',
                'Memantau pencapaian target strategis',
                'Melakukan evaluasi kinerja perusahaan'
            ],
            'Kerjasama Usaha dan Pembinaan Anak Perusahaan': [
                'Mengembangkan kemitraan strategis dengan pihak ketiga',
                'Mengawasi kinerja dan laporan keuangan anak perusahaan',
                'Mengkoordinasikan sinergi bisnis antar grup',
                'Melakukan evaluasi kinerja kerja sama usaha',
                'Mengembangkan rencana ekspansi bisnis'
            ],
            'Komersial dan Hubungan Pelanggan': [
                'Mengembangkan strategi pemasaran dan penjualan',
                'Membangun dan memelihara hubungan dengan pelanggan',
                'Menganalisis tren pasar dan kebutuhan pelanggan',
                'Mengembangkan produk dan layanan baru',
                'Melakukan negosiasi kontrak dengan pelanggan'
            ],
            'Operasional Terminal': [
                'Mengawasi operasional harian terminal',
                'Memastikan kelancaran bongkar muat barang',
                'Mengoptimalkan penggunaan peralatan dan fasilitas',
                'Memantau kinerja mitra operator',
                'Menjaga standar keselamatan operasional'
            ],
            'Teknik dan Pemeliharaan': [
                'Melakukan perawatan rutin peralatan terminal',
                'Menangani perbaikan dan troubleshooting',
                'Mengelola inventaris suku cadang',
                'Mengawasi kontraktor pemeliharaan',
                'Mengembangkan program perawatan preventif'
            ],
            'Sistem Informasi dan Teknologi': [
                'Mengelola infrastruktur TI perusahaan',
                'Mengembangkan dan memelihara sistem aplikasi',
                'Menjaga keamanan siber dan data',
                'Memberikan dukungan teknis kepada pengguna',
                'Mengawasi proyek pengembangan sistem'
            ],
            'Logistik dan Rantai Pasok': [
                'Mengelola rantai pasok dan distribusi',
                'Mengoptimalkan biaya logistik',
                'Mengkoordinasikan dengan vendor dan supplier',
                'Memantau tingkat persediaan barang',
                'Mengembangkan strategi pengadaan'
            ],
            'Kepatuhan dan Tata Kelola': [
                'Memastikan kepatuhan terhadap regulasi',
                'Mengembangkan kebijakan dan prosedur perusahaan',
                'Melakukan pelatihan kepatuhan',
                'Memantau perubahan regulasi',
                'Menyiapkan laporan kepatuhan'
            ],
            'Pengembangan Bisnis': [
                'Mengidentifikasi peluang bisnis baru',
                'Menyusun studi kelayakan bisnis',
                'Mengembangkan proposal investasi',
                'Menganalisis potensi pasar',
                'Membangun jaringan bisnis'
            ],
            'default': [
                'Mengkoordinasikan program kerja divisi',
                'Mengawasi pelaksanaan kegiatan operasional',
                'Menyusun laporan kinerja berkala',
                'Berkolaborasi dengan divisi terkait',
                'Mengembangkan inovasi dan perbaikan berkelanjutan'
            ]
        };
        
        return tugasPokok[divisionName] || tugasPokok['default'];
    }

    function showDivisionDetail(name, highlight, description, number) {
        const modal = document.getElementById('divisionModal');
        const modalContent = document.getElementById('modalContent');
        
        // Add loading state
        modalContent.innerHTML = `
            <div class="flex justify-center items-center h-64">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
            </div>
        `;
        
        // Show modal with animation
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        
        // Add show class for animation
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        // Get tugas pokok
        const tugasPokok = getTugasPokok(name);
        
        // Create modal content
        const modalHTML = `
            <div class="space-y-6">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-blue-100 to-cyan-50 dark:from-blue-900/50 dark:to-cyan-900/20 text-blue-600 dark:text-blue-300 text-2xl font-bold">
                        ${number}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-black !important dark:text-black">${name}</h2>
                        <p class="text-blue-600 dark:text-blue-400 font-medium">
                            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            ${highlight}
                        </p>
                    </div>
                </div>
                
                <div class="bg-blue-50 dark:bg-gray-700/30 p-5 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Deskripsi</h3>
                    <p class="text-gray-700 dark:text-gray-300">${description}</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Tugas Pokok</h3>
                    <ul class="space-y-2">
                        ${tugasPokok.map(item => `
                            <li class="flex items-start">
                                <span class="flex-shrink-0 mt-1 mr-2 text-blue-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                                <span class="text-gray-700 dark:text-gray-300">${item}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
                
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button onclick="closeModal()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        `;
        
        // Add slight delay for better UX
        setTimeout(() => {
            modalContent.innerHTML = modalHTML;
            // Add animation class
            modalContent.classList.add('animate-fade-in-up');
        }, 300);
    }
    
    function closeModal() {
        const modal = document.getElementById('divisionModal');
        const modalContent = document.getElementById('modalContent');
        
        // Remove animation class
        modalContent.classList.remove('animate-fade-in-up');
        
        // Hide modal after animation
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }
    
    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('divisionModal');
        if (event.target === modal) {
            closeModal();
        }
    };
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        const modal = document.getElementById('divisionModal');
        if (event.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });
    // Division selector functionality
    // Close modal when clicking outside content
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('divisionModal');
        const modalContent = document.querySelector('.modal-content');
        
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        const modal = document.getElementById('divisionModal');
        if (event.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Close any open modals when page loads
        closeModal();
        const divisionSelector = document.getElementById('division-selector');
        const divisionCards = document.querySelectorAll('.division-card');
        
        // Add click animation to cards
        divisionCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Only trigger if not clicking on a button or link
                if (!e.target.closest('button, a')) {
                    const btn = this.querySelector('button');
                    if (btn) btn.click();
                }
            });
            
            // Add keyboard navigation
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const btn = this.querySelector('button');
                    if (btn) btn.click();
                }
            });
        });
        const allDivisions = document.querySelectorAll('.division-card');
        
        // Show all divisions by default
        function showAllDivisions() {
            divisionCards.forEach(card => {
                card.style.display = 'block';
                card.style.animation = 'fadeIn 0.5s ease-in-out';
            });
        }

        // Handle division selection
        divisionSelector.addEventListener('change', function(e) {
            const selectedDivision = e.target.value;
            
            if (!selectedDivision) {
                showAllDivisions();
                return;
            }
            
            divisionCards.forEach(card => {
                const title = card.querySelector('h2').textContent;
                if (title === selectedDivision) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.5s ease-in-out';
                    // Scroll to the selected division
                    card.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Add animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .division-card {
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
@endsection
