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
        Schema::create('applicant_department', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            
            $table->integer('choice_order'); // 1 untuk pilihan pertama, 2 untuk pilihan kedua
            
            // Menyimpan path file ZIP portofolio. Nullable jika departemen tidak mewajibkan.
            $table->string('portfolio_path')->nullable(); 
            
            $table->timestamps();
            
            // Mencegah user memilih departemen yang sama 2 kali
            $table->unique(['user_id', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_department');
    }
};
