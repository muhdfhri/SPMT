<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
 
    public function up()
    {
    // Ubah ke VARCHAR sementara
    DB::statement("ALTER TABLE monthly_reports MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'belum_terisi'");
    
    // Update nilai yang tidak valid
    DB::table('monthly_reports')
        ->whereNotIn('status', ['belum_terisi', 'submitted', 'pending', 'approved', 'rejected'])
        ->update(['status' => 'belum_terisi']);
    
    // Ubah ke ENUM
    DB::statement("ALTER TABLE monthly_reports 
        MODIFY COLUMN status 
        ENUM('belum_terisi', 'submitted', 'pending', 'approved', 'rejected') 
        NOT NULL DEFAULT 'belum_terisi'");
    }

    public function down()
    {
    DB::statement("ALTER TABLE monthly_reports MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'belum_terisi'");
    }
};
