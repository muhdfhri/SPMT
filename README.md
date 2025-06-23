# Sistem Pengelolaan Magang Terpadu (SPMT)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-B73BFE?style=for-the-badge&logo=vite&logoColor=FFD62E)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

## 📋 Deskripsi
(SPMT) adalah sebuah platform berbasis web yang dirancang untuk memudahkan pengelolaan proses magang mahasiswa. Aplikasi ini menyediakan fitur lengkap mulai dari pendaftaran magang, pengajuan laporan, hingga penerbitan sertifikat secara digital.

## ✨ Fitur Utama
- **Manajemen Pengguna** (Admin, Dosen, Mahasiswa)
- **Pendaftaran Magang** dengan proses persetujuan
- **Pengajuan dan Penilaian Laporan**
- **Penerbitan Sertifikat Digital**
- **Notifikasi Real-time**
- **Manajemen Dokumen**
- **Dashboard Interaktif**

## 🛠 Teknologi yang Digunakan

### Backend
- **PHP 8.2**
- **Laravel 12**
- **MySQL** (Database)

### Frontend
- **Tailwind CSS** (Styling)
- **Vite** (Build Tool)
- **Axios** (HTTP Client)
- **Flatpickr** (Date Picker)

### Package Tambahan
- **Laravel DomPDF** (Generate PDF)
- **L5-Swagger** (API Documentation)
- **Spatie Permission** (Manajemen Role & Permission)
- **Spatie Activity Log** (Log Aktivitas)

## 🚀 Cara Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 5.7+

### Langkah-langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/muhdfhri/SPMT.git
   cd SPMT
   ```

2. **Install Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Sesuaikan konfigurasi database di file `.env`:
   ```
   DB_DATABASE=spmt
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan Migrasi & Seeder**
   ```bash
   php artisan migrate --seed
   ```

5. **Build Aset**
   ```bash
   npm run build
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

7. **Akses Aplikasi**
   Buka browser dan akses: `http://localhost:8000`

## 👥 Akun Default
- **Admin**
  - Email: admin@spmt.test
  - Password: password
  
- **Mahasiswa**
  - Email: mahasiswa@spmt.test
  - Password: password

## 📚 Dokumentasi API
Dokumentasi API dapat diakses di `/api/documentation` setelah menjalankan perintah:
```bash
php artisan l5-swagger:generate
```

## 📄 Lisensi
Aplikasi ini bersifat open source di bawah lisensi [MIT](LICENSE).

## 🤝 Kontribusi
Kontribusi sangat diterima. Silakan buat Pull Request dengan perubahan yang diusulkan.

## 📧 Kontak
Muhammad Fahri - [mhdfahri2003@gmail.com]
Project Link: [https://github.com/muhdfhri/SPMT](https://github.com/muhdfhri/SPMT)
