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
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
                
            $table->timestamp('reviewed_at')->nullable();
            $table->text('feedback')->nullable();
            $table->string('status')->default('pending')->comment('pending, approved, rejected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            
            $table->dropColumn([
                'reviewed_by',
                'reviewed_at',
                'feedback',
                'status'
            ]);
        });
    }
};
