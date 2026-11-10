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
        Schema::create('applicant_interview', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('interview_schedule_id')->constrained()->cascadeOnDelete();
            
            // Untuk menandai ini jadwal wawancara untuk pilihan 1 atau pilihan 2
            $table->integer('for_choice_order'); 
            
            $table->timestamps();
            
            // Mencegah user memilih 2 jadwal untuk pilihan departemen yang sama
            $table->unique(['user_id', 'for_choice_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_interview');
    }
};
