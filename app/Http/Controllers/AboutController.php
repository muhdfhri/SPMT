<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $benefits = [
            [
                'icon' => '👨‍💼',
                'title' => 'Pengalaman Nyata',
                'description' => 'Dapatkan pengalaman kerja langsung di perusahaan pelabuhan terkemuka di Indonesia.'
            ],
            [
                'icon' => '👥',
                'title' => 'Networking',
                'description' => 'Bangun jaringan profesional dengan para ahli di industri maritim dan logistik.'
            ],
            [
                'icon' => '🎯',
                'title' => 'Pengembangan Karir',
                'description' => 'Kesempatan untuk bergabung sebagai karyawan tetap setelah menyelesaikan program.'
            ],

            [
                'icon' => '📚',
                'title' => 'Pembelajaran',
                'description' => 'Akses ke pelatihan dan workshop eksklusif selama program magang.'
            ],
 
            [
                'icon' => '💼',
                'title' => 'Proyek Nyata',
                'description' => 'Berpartisipasi dalam pelaksanaan proyek-proyek aktual perusahaan guna meningkatkan pemahaman praktis serta kontribusi terhadap tujuan perusahaan.'
            ],

            [
                'icon' => '📜',
                'title' => 'Sertifikat Resmi',
                'description' => 'Mendapatkan sertifikat resmi dari PT Pelindo Multi Terminal setelah menyelesaikan program magang.'
            ]
        ];

        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'university' => 'Universitas Indonesia',
                'quote' => 'Magang di SPMT memberikan saya wawasan berharga tentang operasional pelabuhan yang tidak bisa didapatkan di bangku kuliah.',
                'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg'
            ],
            [
                'name' => 'Anita Rahayu',
                'university' => 'Institut Teknologi Bandung',
                'quote' => 'Program magang yang terstruktur dengan baik dan mentor yang sangat membantu pengembangan karir saya.',
                'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg'
            ],
            [
                'name' => 'Dewi Kurnia',
                'university' => 'Universitas Gadjah Mada',
                'quote' => 'Sangat merekomendasikan program magang SPMT untuk yang ingin berkarier di sektor maritim.',
                'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg'
            ]
        ];

        $faqs = [
            [
                'question' => 'Berapa lama durasi program magang reguler?',
                'answer' => 'Program magang reguler berlangsung selama 3-6 bulan, disesuaikan dengan kebijakan kampus dan kebutuhan divisi.'
            ],
            [
                'question' => 'Apa persyaratan untuk mendaftar?',
                'answer' => 'Mahasiswa aktif minimal semester 5 dari semua jurusan dengan IPK minimal 3.00 (skala 4.00).'
            ],
            [
                'question' => 'Apakah ada tunjangan selama magang?',
                'answer' => 'Ya, peserta magang akan mendapatkan tunjangan bulanan dan fasilitas sesuai kebijakan perusahaan.'
            ],
            [
                'question' => 'Bagaimana proses seleksinya?',
                'answer' => 'Proses seleksi meliputi administrasi, tes online, wawancara, dan pengumuman hasil.'
            ]
        ];

        return view('about.index', compact('benefits', 'testimonials', 'faqs'));
    }
}
