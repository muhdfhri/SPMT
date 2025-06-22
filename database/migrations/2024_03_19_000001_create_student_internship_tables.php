<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('position');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('supervisor_feedback')->nullable();
            $table->timestamps();
        });

        Schema::create('internship_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('internship_application_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->date('report_date');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

        Schema::create('internship_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_report_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('file_size');
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_attachments');
        Schema::dropIfExists('internship_reports');
        Schema::dropIfExists('internship_applications');
    }
}; 