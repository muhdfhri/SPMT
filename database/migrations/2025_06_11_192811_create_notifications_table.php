<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->string('type')->default('info'); // info, success, warning, error
            $table->string('action_url')->nullable();
            $table->json('data')->nullable(); // Untuk menyimpan data tambahan
            $table->timestamp('read_at')->nullable();
            $table->nullableMorphs('notifiable');
            $table->timestamps();
            
            // Index untuk pencarian yang lebih cepat
            $table->index(['user_id', 'is_read']);
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
