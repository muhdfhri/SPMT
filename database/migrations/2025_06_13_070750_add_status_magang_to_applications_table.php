<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('status_magang', ['menunggu', 'diterima', 'ditolak', 'in_progress', 'completed'])
                  ->default('menunggu')
                  ->after('status');
        });
    }
    
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('status_magang');
        });
    }
};
