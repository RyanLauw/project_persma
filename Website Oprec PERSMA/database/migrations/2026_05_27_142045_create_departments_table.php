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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_name')->nullable(); // Menyimpan atas nama siapa
            $table->string('contact_wa')->nullable();   // Menyimpan nomor WA (bisa berupa string agar aman dengan karakter seperti +62)
            $table->boolean('requires_portfolio')->default(false); // <-- Pastikan kolom ini ada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};