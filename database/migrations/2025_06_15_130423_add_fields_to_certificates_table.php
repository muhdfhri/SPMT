<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->string('certificate_number')->unique()->after('id');
            $table->enum('status', ['pending', 'generated', 'published', 'revoked'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('revoked_at')->nullable();
            $table->text('revoked_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('application_id')->constrained('applications')->after('user_id');
            
            // Tambahkan index
            $table->index('certificate_number');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['application_id']);
            
            // Hapus index
            $table->dropIndex(['certificate_number']);
            $table->dropIndex(['status']);
            
            // Hapus kolom yang ditambahkan
            $table->dropColumn([
                'certificate_number',
                'status',
                'verified_at',
                'verified_by',
                'revoked_at',
                'revoked_reason',
                'metadata',
                'application_id'
            ]);
        });
    }
};
