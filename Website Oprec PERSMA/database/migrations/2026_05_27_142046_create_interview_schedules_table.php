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
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time'); // Atau waktu_mulai jika kamu masih pakai nama itu
            $table->dateTime('end_time');   // Atau waktu_selesai
            
            // Kolom Baru
            $table->enum('tipe', ['online', 'offline'])->default('offline');
            $table->string('lokasi')->nullable();
            $table->string('pewawancara')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
    }
};
