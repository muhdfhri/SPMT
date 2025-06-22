<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('status')->default('dikirim');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('report_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('users_report_id');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();

            $table->foreign('users_report_id')->references('id')->on('users_reports')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_attachments');
        Schema::dropIfExists('users_reports');
    }
}; 