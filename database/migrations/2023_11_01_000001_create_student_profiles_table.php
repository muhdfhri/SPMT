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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->text('about_me')->nullable();
            $table->string('full_name');
            $table->string('nik', 16)->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            
            // Profile Photo
            $table->string('profile_photo')->nullable();
            
            // Profile Completion Status
            $table->boolean('is_personal_complete')->default(false);
            $table->boolean('is_academic_complete')->default(false);
            $table->boolean('is_family_complete')->default(false);
            $table->boolean('is_documents_complete')->default(false);
            $table->integer('profile_completion_percentage')->default(0);
            
            $table->timestamps();
        });

        Schema::create('student_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->string('institution_name');
            $table->string('degree');
            $table->string('field_of_study');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->decimal('gpa', 3, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('position');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('issuing_organization');
            $table->date('issue_date');
            $table->date('expiration_date')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->timestamps();
        });

        Schema::create('student_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('issuer');
            $table->date('date_received');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('intermediate');
            $table->timestamps();
        });

        Schema::create('student_family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->enum('relationship', ['father', 'mother', 'sibling', 'spouse', 'child', 'other']);
            $table->string('name');
            $table->string('occupation')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_emergency_contact')->default(false);
            $table->timestamps();
        });

        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['cv', 'transcript', 'id_card', 'certificate', 'recommendation_letter', 'other']);
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('file_size');
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_documents');
        Schema::dropIfExists('student_family_members');
        Schema::dropIfExists('student_skills');
        Schema::dropIfExists('student_awards');
        Schema::dropIfExists('student_certifications');
        Schema::dropIfExists('student_experiences');
        Schema::dropIfExists('student_educations');
        Schema::dropIfExists('student_profiles');
    }
};