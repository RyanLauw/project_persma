<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update tabel users (Tambah department_id untuk role KWK)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('email');
        });

        // Modifikasi enum role menggunakan Raw Query agar aman di MySQL
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('bph', 'kwk', 'applicant') DEFAULT 'applicant'");

        // 2. Update tabel applicant_department (Tambah hasil wawancara & status BPH)
        Schema::table('applicant_department', function (Blueprint $table) {
            $table->text('interview_notes')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
        });
        
        // Bagian 3 (penambahan batch_id) sudah dihapus karena sudah digabung di file utama
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};