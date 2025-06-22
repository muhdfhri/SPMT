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
        Schema::dropIfExists('student_certifications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika perlu restore tabel di kemudian hari
        Schema::create('student_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('issuer');
            $table->date('issue_date');
            $table->string('certificate_file')->nullable();
            $table->timestamps();
        });
    }
};
