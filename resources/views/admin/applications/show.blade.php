@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Lamaran Magang</h1>
            <p class="text-gray-600 dark:text-gray-400">Informasi lengkap lamaran magang</p>
        </div>
        <div class="space-x-2">
            @if($application->status === 'pending')
            <form action="{{ route('admin.applications.approve', $application) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Setujui Lamaran
                </button>
            </form>
            <button onclick="openRejectModal()" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Tolak Lamaran
            </button>
            @endif
            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Status Bar -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                @php
                    // Mapping status dari enum ke tampilan
                    $statusMapping = [
                        'pending' => [
                            'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'label' => 'Menunggu',
                            'icon' => '⏳'
                        ],
                        'submitted' => [
                            'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                            'label' => 'Terkirim',
                            'icon' => '📤'
                        ],
                        'terkirim' => [
                            'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                            'label' => 'Terkirim',
                            'icon' => '📤'
                        ],
                        'diproses' => [
                            'class' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                            'label' => 'Diproses',
                            'icon' => '🔄'
                        ],
                        'diterima' => [
                            'class' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'label' => 'Diterima',
                            'icon' => '✅'
                        ],
                        'approved' => [
                            'class' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'label' => 'Diterima',
                            'icon' => '✅'
                        ],
                        'ditolak' => [
                            'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                            'label' => 'Ditolak',
                            'icon' => '❌',
                            'reason' => $application->rejection_reason ?? null
                        ],
                        'rejected' => [
                            'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                            'label' => 'Ditolak',
                            'icon' => '❌',
                            'reason' => $application->rejection_reason ?? null
                        ]
                    ];

                    // Konversi ke lowercase untuk memastikan pencocokan case-insensitive
                    $statusKey = strtolower($application->status);
                    
                    // Dapatkan konfigurasi status
                    $statusConfig = $statusMapping[$statusKey] ?? [
                        'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-700/30 dark:text-gray-300',
                        'label' => $application->status,
                        'icon' => '❓'
                    ];
                @endphp
                
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 inline-flex items-center text-sm leading-5 font-semibold rounded-full {{ $statusConfig['class'] }}">
                        <span class="mr-1">{{ $statusConfig['icon'] ?? '' }}</span>
                        {{ $statusConfig['label'] }}
                    </span>
                    
                    @if(($application->status === 'approved' || $application->status === 'diterima') && $application->approved_by)
                        <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                            <span class="hidden sm:inline">Disetujui oleh</span>
                            @if($application->approver && $application->approver->profile_photo_path)
                                <img class="h-5 w-5 rounded-full ml-2" src="{{ asset('storage/' . $application->approver->profile_photo_path) }}" alt="{{ $application->approver->name }}">
                            @else
                                <div class="h-5 w-5 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center ml-2">
                                    <span class="text-xs text-green-800 dark:text-green-200">
                                        {{ $application->approver ? substr($application->approver->name, 0, 1) : '?' }}
                                    </span>
                                </div>
                            @endif
                            <span class="font-medium ml-1">{{ $application->approver->name ?? 'Admin' }}</span>
                            <span class="text-gray-500 dark:text-gray-400 ml-1">•</span>
                            <span>{{ $application->approved_at?->format('d M Y') ?? 'N/A' }}</span>
                        </div>
                    @elseif(($application->status === 'rejected' || $application->status === 'ditolak') && $application->rejected_by)
                        <div class="flex items-center text-sm text-red-600 dark:text-red-400">
                            <span class="hidden sm:inline">Ditolak oleh</span>
                            @if($application->rejector && $application->rejector->profile_photo_path)
                                <img class="h-5 w-5 rounded-full ml-2" src="{{ asset('storage/' . $application->rejector->profile_photo_path) }}" alt="{{ $application->rejector->name }}">
                            @else
                                <div class="h-5 w-5 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center ml-2">
                                    <span class="text-xs text-red-800 dark:text-red-200">
                                        {{ $application->rejector ? substr($application->rejector->name, 0, 1) : '?' }}
                                    </span>
                                </div>
                            @endif
                            <span class="font-medium ml-1">{{ $application->rejector->name ?? 'Admin' }}</span>
                            <span class="text-gray-500 dark:text-gray-400 ml-1">•</span>
                            <span>{{ $application->rejected_at?->format('d M Y') ?? 'N/A' }}</span>
                        </div>
                    @endif
                </div>
                
                @if(isset($statusConfig['reason']) && $statusConfig['reason'])
                    <div class="mt-2 sm:mt-0 text-sm text-red-600 dark:text-red-400">
                        Alasan: {{ $statusConfig['reason'] }}
                    </div>
                @endif
            </div>
            
            <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-3 py-1 rounded-full">
                <span class="hidden sm:inline">Diajukan pada</span>
                <span class="font-medium">{{ $application->created_at->format('d M Y') }}</span>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button type="button" onclick="switchTab('profile')" id="tab-profile" class="border-blue-500 text-gray-900 dark:text-white whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Informasi Pribadi
                </button>
                <button type="button" onclick="switchTab('academic')" id="tab-academic" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Data Akademik
                </button>
                <button type="button" onclick="switchTab('family')" id="tab-family" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Data Keluarga
                </button>
                <button type="button" onclick="switchTab('documents')" id="tab-documents" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Dokumen Pendukung
                </button>
            </nav>
        </div>

        <!-- Profile Tab Content -->
        <div id="profile-content" class="p-6 tab-content">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Informasi Pribadi</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Detail informasi pribadi</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="px-4 py-3 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Nama Lengkap</dt>
                            <dd class="text-sm text-gray-900 dark:text-white sm:col-span-3">
                                {{ $application->user->studentProfile->full_name ?? 'N/A' }}
                            </dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">NIK</dt>
                            <dd class="text-sm text-gray-900 dark:text-white sm:col-span-3">
                                {{ $application->user->studentProfile->nik ?? 'N/A' }}
                            </dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Tempat, Tanggal Lahir</dt>
                            <dd class="text-sm text-gray-900 dark:text-white sm:col-span-3">
                                {{ $application->user->studentProfile->birth_place ?? 'N/A' }}, 
                                @if($application->user->studentProfile->birth_date)
                                    @php
                                        $months = [
                                            'January' => 'Januari',
                                            'February' => 'Februari',
                                            'March' => 'Maret',
                                            'April' => 'April',
                                            'May' => 'Mei',
                                            'June' => 'Juni',
                                            'July' => 'Juli',
                                            'August' => 'Agustus',
                                            'September' => 'September',
                                            'October' => 'Oktober',
                                            'November' => 'November',
                                            'December' => 'Desember'
                                        ];
                                        $date = \Carbon\Carbon::parse($application->user->studentProfile->birth_date);
                                        $formattedDate = $date->format('d ') . $months[$date->format('F')] . $date->format(' Y');
                                    @endphp
                                    {{ $formattedDate }}
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Alamat</dt>
                            <dd class="text-sm text-gray-900 dark:text-white sm:col-span-3">
                                {{ $application->user->studentProfile->address ?? 'N/A' }}
                            </dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Email</dt>
                            <dd class="text-sm text-gray-900 dark:text-white sm:col-span-3">
                                {{ $application->user->email }}
                            </dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Nomor Telepon</dt>
                            <dd class="text-sm text-gray-900 dark:text-white sm:col-span-3">
                                {{ $application->user->phone ?? 'N/A' }}
                            </dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Tentang Saya</dt>
                            <dd class="text-sm text-gray-900 dark:text-white sm:col-span-3">
                                {{ $application->user->studentProfile->about_me ?? 'Tidak ada deskripsi' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Academic Data Tab Content -->
        <div id="academic-content" class="p-6 hidden tab-content space-y-6">
            <!-- Education Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Riwayat Pendidikan</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Informasi riwayat pendidikan</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    @if($application->user->studentProfile && $application->user->studentProfile->educations->count() > 0)
                        @foreach($application->user->studentProfile->educations as $education)
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 {{ $loop->even ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                {{ $education->degree }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                <div class="font-semibold">{{ $education->institution_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($education->start_date)->format('M Y') }} - 
                                    {{ $education->is_current || !$education->end_date ? 'Sekarang' : \Carbon\Carbon::parse($education->end_date)->format('M Y') }}
                                </div>
                                @if($education->gpa)
                                    <div class="mt-1">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">IPK:</span>
                                        <span class="font-medium">{{ number_format($education->gpa, 2) }}</span>
                                    </div>
                                @endif
                                @if($education->description)
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $education->description }}</p>
                                @endif
                            </dd>
                        </div>
                        @endforeach
                    @else
                        <div class="px-4 py-5 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data pendidikan yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Experience Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Pengalaman</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Riwayat pengalaman</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    @if($application->user->studentProfile && $application->user->studentProfile->experiences->count() > 0)
                        @foreach($application->user->studentProfile->experiences as $experience)
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 {{ $loop->even ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                {{ $experience->position }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                <div class="font-semibold">{{ $experience->company_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($experience->start_date)->format('M Y') }} - 
                                    {{ $experience->is_current ? 'Sekarang' : \Carbon\Carbon::parse($experience->end_date)->format('M Y') }}
                                </div>
                                @if($experience->description)
                                    <div class="mt-2 prose prose-sm max-w-none text-gray-600 dark:text-gray-300">
                                        {!! nl2br(e($experience->description)) !!}
                                    </div>
                                @endif
                            </dd>
                        </div>
                        @endforeach
                    @else
                        <div class="px-4 py-5 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada pengalaman yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Skills Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Keahlian</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Kemampuan dan keahlian yang dikuasai</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-6">
                    @if($application->user->studentProfile && $application->user->studentProfile->skills->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($application->user->studentProfile->skills as $skill)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $skill->name }}
                                    @if($skill->proficiency_level)
                                        <span class="ml-1 text-blue-600 dark:text-blue-400">({{ $skill->proficiency_level }})</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada keahlian yang tercantum</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Family Data Tab Content -->
        <div id="family-content" class="p-6 hidden tab-content">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Data Keluarga</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Informasi anggota keluarga</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    @if($application->user->studentProfile && $application->user->studentProfile->familyMembers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hubungan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pekerjaan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kontak Darurat</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($application->user->studentProfile->familyMembers as $member)
                                    @php
                                        $relationships = [
                                            'father' => 'Ayah',
                                            'mother' => 'Ibu',
                                            'brother' => 'Saudara Laki-laki',
                                            'sister' => 'Saudara Perempuan',
                                            'husband' => 'Suami',
                                            'wife' => 'Istri',
                                            'grandfather' => 'Kakek',
                                            'grandmother' => 'Nenek',
                                            'uncle' => 'Paman',
                                            'aunt' => 'Bibi',
                                            'other' => 'Lainnya'
                                        ];
                                        $relationship = isset($relationships[strtolower($member->relationship)]) ? 
                                            $relationships[strtolower($member->relationship)] : $member->relationship;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $member->name }}
                                            @if($member->is_emergency_contact)
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                    Kontak Darurat
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $relationship }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $member->occupation ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $member->phone_number ?? '-' }}
                                            @if($member->address)
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $member->address }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-4 py-5 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data keluarga yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Documents Tab Content -->
        <div id="documents-content" class="p-6 hidden tab-content">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Dokumen Pendukung</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Dokumen pendukung yang diunggah</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    @if($application->user->studentProfile && $application->user->studentProfile->documents->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Dokumen</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama File</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ukuran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($application->user->studentProfile->documents as $document)
                                    @php
                                        $documentTypes = [
                                            'cv' => 'Curriculum Vitae (CV)',
                                            'transcript' => 'Transkrip Nilai',
                                            'id_card' => 'KTP',
                                            'family_card' => 'Kartu Keluarga',
                                            'student_id' => 'Kartu Tanda Mahasiswa',
                                            'photo' => 'Pas Foto',
                                            'health_certificate' => 'Surat Keterangan Sehat',
                                            'recommendation_letter' => 'Surat Rekomendasi',
                                            'other' => 'Lainnya'
                                        ];
                                        $documentType = isset($documentTypes[strtolower($document->type)]) ? 
                                            $documentTypes[strtolower($document->type)] : $document->type;
                                        
                                        // Format file size
                                        $size = $document->file_size;
                                        $units = ['B', 'KB', 'MB', 'GB'];
                                        $i = 0;
                                        while ($size >= 1024 && $i < count($units) - 1) {
                                            $size /= 1024;
                                            $i++;
                                        }
                                        $formattedSize = round($size, 2) . ' ' . $units[$i];
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $documentType }}
                                            @if($document->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $document->description }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $document->original_filename }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $formattedSize }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Dokumen
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-4 py-5 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada dokumen pendukung yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Internship Tab Content -->
        <div id="internship-content" class="p-6 hidden tab-content">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Informasi Magang</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Detail program magang yang dilamar</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Program Magang</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                {{ $application->internship->title ?? 'N/A' }}
                            </dd>
                        </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lokasi</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $application->internship->location ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $application->internship->start_date ? \Carbon\Carbon::parse($application->internship->start_date)->format('d M Y') : 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $application->internship->end_date ? \Carbon\Carbon::parse($application->internship->end_date)->format('d M Y') : 'N/A' }}</dd>
                </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-5">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Status Lamaran</h3>
        </div>
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ][$application->status] ?? 'bg-gray-100 text-gray-800';
                            $statusLabels = [
                                'pending' => 'Menunggu',
                                'approved' => 'Diterima',
                                'rejected' => 'Ditolak',
                            ][$application->status] ?? 'Tidak Diketahui';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }} dark:bg-opacity-20">
                            {{ $statusLabels }}
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lamar</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $application->created_at->format('d M Y') }}</dd>
                </div>
                @if($application->status === 'approved' && $application->approver)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Disetujui Oleh</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $application->approver->name }} pada {{ $application->approved_at->format('d M Y') }}
                    </dd>
                </div>
                @endif
                @if($application->status === 'rejected' && $application->rejector)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ditolak Oleh</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $application->rejector->name }} pada {{ $application->rejected_at->format('d M Y') }}
                        @if($application->rejection_reason)
                        <div class="mt-2 p-3 bg-red-50 dark:bg-red-900 dark:bg-opacity-20 rounded-md">
                            <p class="text-sm text-red-700 dark:text-red-300">
                                <span class="font-medium">Alasan Penolakan:</span> {{ $application->rejection_reason }}
                            </p>
                        </div>
                        @endif
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        @if($application->notes)
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-5">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Catatan Tambahan</h3>
        </div>
        <div class="px-6 py-5">
            <div class="prose prose-sm max-w-none dark:prose-invert">
                {!! nl2br(e($application->notes)) !!}
            </div>
        </div>
        @endif

        @if($application->status === 'pending')
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right">
            <form action="{{ route('admin.applications.approve', $application) }}" method="POST" class="inline-block mr-2">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Setujui Lamaran
                </button>
            </form>
            <button onclick="openRejectModal()" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Tolak Lamaran
            </button>
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
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Tolak Lamaran
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Masukkan alasan penolakan lamaran ini.
                        </p>
                        <form id="rejectForm" action="{{ route('admin.applications.reject', $application) }}" method="POST" class="mt-4">
                            @csrf
                            <div>
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Penolakan</label>
                                <div class="mt-1">
                                    <textarea id="rejection_reason" name="rejection_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="submit" form="rejectForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                    Tolak Lamaran
                </button>
                <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching functionality
    function switchTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Remove active class from all tab buttons
        document.querySelectorAll('button[onclick^="switchTab"]').forEach(button => {
            button.classList.remove('border-blue-500', 'text-gray-900', 'dark:text-white');
            button.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:hover:text-gray-300');
        });
        
        // Show the selected tab content
        const selectedTab = document.getElementById(`${tabId}-content`);
        if (selectedTab) {
            selectedTab.classList.remove('hidden');
        }
        
        // Add active class to the clicked tab button
        const activeButton = document.getElementById(`tab-${tabId}`);
        if (activeButton) {
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:hover:text-gray-300');
            activeButton.classList.add('border-blue-500', 'text-gray-900', 'dark:text-white');
        }
    }
    
    // Show profile tab by default
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('profile');
    });

    // Reject modal functions
    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>
@endpush

@endsection
