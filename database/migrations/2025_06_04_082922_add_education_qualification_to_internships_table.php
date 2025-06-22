<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->enum('education_qualification', ['SMA/SMK', 'Vokasi', 'S1'])
                  ->after('division')
                  ->nullable()
                  ->comment('Kualifikasi pendidikan yang dibutuhkan (SMA/SMK, Vokasi, atau Sarjana)');
        });
    }

    public function down()
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropColumn('education_qualification');
        });
    }
};