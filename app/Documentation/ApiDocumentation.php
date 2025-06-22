<?php

namespace App\Documentation;

/**
 * @OA\Info(
 *     title="SPMT API Documentation",
 *     version="1.0.0",
 *     description="Dokumentasi API lengkap untuk SPMT - Magang Reguler PT Pelindo Multi Terminal"
 * )
 * 
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     securityScheme="bearerAuth",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Tag(name="Authentication", description="Autentikasi Pengguna")
 * @OA\Tag(name="Mahasiswa - Profil", description="Manajemen Profil Mahasiswa")
 * @OA\Tag(name="Mahasiswa - Pendidikan", description="Riwayat Pendidikan Mahasiswa")
 * @OA\Tag(name="Mahasiswa - Pengalaman", description="Pengalaman Kerja/Organisasi")
 * @OA\Tag(name="Mahasiswa - Penghargaan", description="Penghargaan Mahasiswa")
 * @OA\Tag(name="Mahasiswa - Keterampilan", description="Keterampilan Mahasiswa")
 * @OA\Tag(name="Mahasiswa - Dokumen", description="Dokumen Pendukung")
 * @OA\Tag(name="Magang", description="Manajemen Magang")
 * @OA\Tag(name="Pencarian", description="Pencarian Global")
 * @OA\Tag(name="Umum", description="Endpoint Umum Sistem")
 * @OA\Tag(name="Keluarga", description="Manajemen Data Keluarga")
 * @OA\Tag(name="Pengaturan", description="Pengaturan Sistem")
 * @OA\Tag(name="Laporan", description="Laporan Magang")
 * @OA\Tag(name="Admin - Aplikasi", description="Manajemen Aplikasi (Admin)")
 * @OA\Tag(name="Admin - Issue", description="Manajemen Laporan Masalah")
 * @OA\Tag(name="Admin - Sertifikat", description="Manajemen Sertifikat")
 * @OA\Tag(name="Admin - Divisi", description="Manajemen Divisi")
 * @OA\Tag(name="Admin - Dashboard", description="Dashboard Admin")
 * @OA\Tag(name="Admin - Ekspor", description="Ekspor Data")
 * @OA\Tag(name="Admin - Pengaturan", description="Pengaturan Sistem")
 */
class ApiDocumentation
{
    // ==================== AUTENTIKASI ====================
    
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login pengguna",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Kredensial tidak valid")
     * )
     */
    private function login() {}

    // ==================== MAHASISWA - PROFIL ====================

    /**
     * @OA\Get(
     *     path="/api/mahasiswa/profile",
     *     summary="Dapatkan profil mahasiswa",
     *     tags={"Mahasiswa - Profil"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data profil mahasiswa",
     *         @OA\JsonContent(ref="#/components/schemas/StudentProfile")
     *     )
     * )
     */
    private function getProfile() {}

    // ==================== MAHASISWA - PENDIDIKAN ====================
    
    /**
     * @OA\Get(
     *     path="/api/mahasiswa/educations",
     *     summary="Daftar riwayat pendidikan",
     *     tags={"Mahasiswa - Pendidikan"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Daftar pendidikan")
     * )
     */
    private function getEducations() {}

    // ==================== MAHASISWA - PENGALAMAN ====================
    
    /**
     * @OA\Get(
     *     path="/api/mahasiswa/experiences",
     *     summary="Daftar pengalaman",
     *     tags={"Mahasiswa - Pengalaman"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Daftar pengalaman")
     * )
     */
    private function getExperiences() {}

    // ==================== MAHASISWA - PENGHARGAAN ====================
    
    /**
     * @OA\Get(
     *     path="/api/mahasiswa/awards",
     *     summary="Daftar penghargaan",
     *     tags={"Mahasiswa - Penghargaan"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Daftar penghargaan")
     * )
     */
    private function getAwards() {}

    // ==================== MAHASISWA - KETERAMPILAN ====================
    
    /**
     * @OA\Get(
     *     path="/api/mahasiswa/skills",
     *     summary="Daftar keterampilan",
     *     tags={"Mahasiswa - Keterampilan"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Daftar keterampilan")
     * )
     */
    private function getSkills() {}

    // ==================== MAGANG ====================
    
    /**
     * @OA\Get(
     *     path="/api/internships",
     *     summary="Daftar lowongan magang",
     *     tags={"Magang"},
     *     @OA\Response(response=200, description="Daftar lowongan")
     * )
     */
    private function getInternships() {}

    // ==================== LAPORAN ====================
    
    /**
     * @OA\Get(
     *     path="/api/mahasiswa/reports",
     *     summary="Daftar laporan magang",
     *     tags={"Laporan"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Daftar laporan")
     * )
     */
    private function getReports() {}

    // ==================== ADMIN - APLIKASI ====================
    
    /**
     * @OA\Get(
     *     path="/api/admin/applications",
     *     summary="Daftar aplikasi magang (Admin)",
     *     tags={"Admin - Aplikasi"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar aplikasi",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Application"))
     *     )
     * )
     */
    private function getAdminApplications() {}

    // ==================== MAHASISWA - DOKUMEN ====================
    
    /**
     * @OA\Post(
     *     path="/api/mahasiswa/documents",
     *     summary="Unggah dokumen",
     *     tags={"Mahasiswa - Dokumen"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file", "type"},
     *                 @OA\Property(property="file", type="string", format="binary"),
     *                 @OA\Property(property="type", type="string", enum={"cv", "transcript", "certificate", "other"})
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Dokumen berhasil diunggah")
     * )
     */
    private function uploadDocument() {}

    // ==================== ADMIN - ISSUE ====================
    
    /**
     * @OA\Get(
     *     path="/api/admin/issues",
     *     summary="Daftar laporan masalah",
     *     tags={"Admin - Issue"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar laporan masalah",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Issue"))
     *     )
     * )
     */
    private function getIssues() {}

    // ==================== ADMIN - SERTIFIKAT ====================
    
    /**
     * @OA\Post(
     *     path="/api/admin/certificates/generate",
     *     summary="Generate sertifikat",
     *     tags={"Admin - Sertifikat"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CertificateRequest")
     *     ),
     *     @OA\Response(response=201, description="Sertifikat berhasil digenerate")
     * )
     */
    private function generateCertificate() {}

    // ==================== ADMIN - DIVISI ====================
    
    /**
     * @OA\Get(
     *     path="/api/admin/divisions",
     *     summary="Daftar divisi",
     *     tags={"Admin - Divisi"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar divisi",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Division"))
     *     )
     * )
     */
    private function getDivisions() {}

    // ==================== ADMIN - DASHBOARD ====================
    
    /**
     * @OA\Get(
     *     path="/api/admin/dashboard/stats",
     *     summary="Statistik dashboard admin",
     *     tags={"Admin - Dashboard"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data statistik",
     *         @OA\JsonContent(ref="#/components/schemas/DashboardStats")
     *     )
     * )
     */
    private function getDashboardStats() {}

    // ==================== ADMIN - EKSPOR ====================
    
    /**
     * @OA\Get(
     *     path="/api/admin/export/students",
     *     summary="Ekspor data mahasiswa",
     *     tags={"Admin - Ekspor"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="File Excel berhasil diunduh",
     *         @OA\MediaType(
     *             mediaType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
     *         )
     *     )
     * )
     */
    private function exportStudents() {}

    // ==================== ADMIN - PENGGUNA ====================
    
    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     summary="Daftar pengguna",
     *     tags={"Admin - Pengguna"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar pengguna",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     )
     * )
     */
    private function getUsers() {}

    // ==================== STATUS KELENGKAPAN PROFIL ====================
    
    /**
     * @OA\Get(
     *     path="/api/mahasiswa/profile/completion",
     *     summary="Status kelengkapan profil",
     *     tags={"Mahasiswa - Profil"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Status kelengkapan profil",
     *         @OA\JsonContent(ref="#/components/schemas/ProfileCompletion")
     *     )
     * )
     */
    private function getProfileCompletion() {}

    // ==================== AKTIVITAS (LOGS) ====================
    
    /**
     * @OA\Get(
     *     path="/api/admin/activity-logs",
     *     summary="Daftar log aktivitas",
     *     tags={"Admin - Logs"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar log aktivitas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ActivityLog"))
     *     )
     * )
     */
    private function getActivityLogs() {}

    // ==================== PENCARIAN ====================
    
    /**
     * @OA\Get(
     *     path="/api/search",
     *     summary="Pencarian global",
     *     tags={"Pencarian"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         description="Kata kunci pencarian",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hasil pencarian",
     *         @OA\JsonContent(ref="#/components/schemas/SearchResults")
     *     )
     * )
     */
    private function search() {}

    // ==================== UMUM ====================
    
    /**
     * @OA\Get(
     *     path="/api/general/info",
     *     summary="Informasi umum sistem",
     *     tags={"Umum"},
     *     @OA\Response(
     *         response=200,
     *         description="Informasi sistem",
     *         @OA\JsonContent(ref="#/components/schemas/SystemInfo")
     *     )
     * )
     */
    private function getSystemInfo() {}

    // ==================== KELUARGA ====================
    
    /**
     * @OA\Get(
     *     path="/api/mahasiswa/family-members",
     *     summary="Daftar anggota keluarga",
     *     tags={"Keluarga"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar anggota keluarga",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/FamilyMember"))
     *     )
     * )
     */
    private function getFamilyMembers() {}
    
    /**
     * @OA\Post(
     *     path="/api/mahasiswa/family-members",
     *     summary="Tambah anggota keluarga",
     *     tags={"Keluarga"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FamilyMemberRequest")
     *     ),
     *     @OA\Response(response=201, description="Anggota keluarga berhasil ditambahkan")
     * )
     */
    private function addFamilyMember() {}

    /**
     * @OA\Get(
     *     path="/api/mahasiswa/family-info",
     *     summary="Dapatkan informasi keluarga",
     *     tags={"Keluarga"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informasi keluarga",
     *         @OA\JsonContent(ref="#/components/schemas/FamilyInfo")
     *     )
     * )
     */
    private function getFamilyInfo() {}

    // ==================== PENGATURAN ====================
    
    /**
     * @OA\Get(
     *     path="/api/admin/settings",
     *     summary="Dapatkan pengaturan sistem",
     *     tags={"Pengaturan"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar pengaturan",
     *         @OA\JsonContent(type="object", additionalProperties=true)
     *     )
     * )
     */
    private function getSettings() {}
    
    /**
     * @OA\Put(
     *     path="/api/admin/settings",
     *     summary="Update pengaturan sistem",
     *     tags={"Pengaturan"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(type="object", additionalProperties=true)
     *     ),
     *     @OA\Response(response=200, description="Pengaturan berhasil diupdate")
     * )
     */
    private function updateSettings() {}

    // ==================== SCHEMAS ====================
    
    /**
     * @OA\Schema(
     *     schema="StudentProfile",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Nama Mahasiswa"),
     *     @OA\Property(property="email", type="string", format="email", example="mahasiswa@example.com"),
     *     @OA\Property(property="nim", type="string", example="12345678"),
     *     @OA\Property(property="phone", type="string", example="081234567890"),
     *     @OA\Property(property="address", type="string", example="Alamat lengkap")
     * )
     */
    private function schemaStudentProfile() {}

    /**
     * @OA\Schema(
     *     schema="Application",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="status", type="string", example="pending"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="student", ref="#/components/schemas/StudentProfile")
     * )
     */
    private function schemaApplication() {}

    /**
     * @OA\Schema(
     *     schema="Issue",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="title", type="string", example="Judul Masalah"),
     *     @OA\Property(property="description", type="string", example="Deskripsi masalah"),
     *     @OA\Property(property="status", type="string", example="open"),
     *     @OA\Property(property="created_at", type="string", format="date-time")
     * )
     */
    private function schemaIssue() {}

    /**
     * @OA\Schema(
     *     schema="CertificateRequest",
     *     required={"student_id", "internship_id", "issue_date"},
     *     @OA\Property(property="student_id", type="integer", example=1),
     *     @OA\Property(property="internship_id", type="integer", example=1),
     *     @OA\Property(property="issue_date", type="string", format="date"),
     *     @OA\Property(property="additional_notes", type="string")
     * )
     */
    private function schemaCertificateRequest() {}

    /**
     * @OA\Schema(
     *     schema="DashboardStats",
     *     @OA\Property(property="total_students", type="integer", example=150),
     *     @OA\Property(property="total_internships", type="integer", example=25),
     *     @OA\Property(property="pending_applications", type="integer", example=12),
     *     @OA\Property(property="active_internships", type="integer", example=45)
     * )
     */
    private function schemaDashboardStats() {}

    /**
     * @OA\Schema(
     *     schema="ProfileCompletion",
     *     @OA\Property(property="percentage", type="number", format="float", example=75.5),
     *     @OA\Property(property="missing_fields", type="array", @OA\Items(type="string")),
     *     @OA\Property(property="is_complete", type="boolean", example=false)
     * )
     */
    private function schemaProfileCompletion() {}

    /**
     * @OA\Schema(
     *     schema="SystemInfo",
     *     @OA\Property(property="app_name", type="string", example="SPMT"),
     *     @OA\Property(property="version", type="string", example="1.0.0"),
     *     @OA\Property(property="environment", type="string", example="production"),
     *     @OA\Property(property="maintenance_mode", type="boolean", example=false)
     * )
     */
    private function schemaSystemInfo() {}

    /**
     * @OA\Schema(
     *     schema="FamilyMember",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Nama Anggota Keluarga"),
     *     @OA\Property(property="relationship", type="string", example="Ayah"),
     *     @OA\Property(property="phone", type="string", example="081234567890"),
     *     @OA\Property(property="occupation", type="string", example="PNS")
     * )
     */
    private function schemaFamilyMember() {}

    /**
     * @OA\Schema(
     *     schema="FamilyMemberRequest",
     *     required={"name", "relationship"},
     *     @OA\Property(property="name", type="string", example="Nama Lengkap"),
     *     @OA\Property(property="relationship", type="string", example="Ayah"),
     *     @OA\Property(property="phone", type="string", example="081234567890"),
     *     @OA\Property(property="occupation", type="string", example="PNS")
     * )
     */
    private function schemaFamilyMemberRequest() {}

    /**
     * @OA\Schema(
     *     schema="FamilyInfo",
     *     @OA\Property(property="family_card_number", type="string", example="1234567890123456"),
     *     @OA\Property(property="address", type="string", example="Alamat lengkap keluarga"),
     *     @OA\Property(property="members_count", type="integer", example=4)
     * )
     */
    private function schemaFamilyInfo() {}

    /**
     * @OA\Schema(
     *     schema="Division",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="IT Department"),
     *     @OA\Property(property="description", type="string", example="Divisi Teknologi Informasi"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */
    private function schemaDivision() {}

    /**
     * @OA\Schema(
     *     schema="User",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="John Doe"),
     *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *     @OA\Property(property="role", type="string", example="admin|user|student"),
     *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */
    private function schemaUser() {}

    /**
     * @OA\Schema(
     *     schema="ActivityLog",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="log_name", type="string", example="default"),
     *     @OA\Property(property="description", type="string", example="User logged in"),
     *     @OA\Property(property="subject_type", type="string", example="App\\Models\\User"),
     *     @OA\Property(property="subject_id", type="integer", example=1),
     *     @OA\Property(property="causer_type", type="string", example="App\\Models\\User"),
     *     @OA\Property(property="causer_id", type="integer", example=1),
     *     @OA\Property(property="properties", type="object", example={"ip":"127.0.0.1","user_agent":"Mozilla/5.0"}),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */
    private function schemaActivityLog() {}

    /**
     * @OA\Schema(
     *     schema="SearchResults",
     *     @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="users|internships|applications"),
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Contoh Hasil Pencarian"),
     *             @OA\Property(property="description", type="string", example="Deskripsi singkat hasil pencarian"),
     *             @OA\Property(property="url", type="string", example="/path/to/resource/1")
     *         )
     *     ),
     *     @OA\Property(property="total", type="integer", example=15),
     *     @OA\Property(property="per_page", type="integer", example=10),
     *     @OA\Property(property="current_page", type="integer", example=1),
     *     @OA\Property(property="last_page", type="integer", example=2)
     * )
     */
    private function schemaSearchResults() {}
}
