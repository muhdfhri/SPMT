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
        Schema::table('users_reports', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('status');
            $table->foreignId('admin_id')->nullable()->after('admin_notes')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_reports', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['admin_notes', 'admin_id', 'resolved_at']);
        });
    }
};
