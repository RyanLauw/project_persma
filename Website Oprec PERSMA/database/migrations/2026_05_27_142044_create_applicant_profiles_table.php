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
        Schema::create('applicant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->cascadeOnDelete(); // Sesuaikan jika kamu sudah menambahkan ini sebelumnya
            $table->string('nama_lengkap');
            $table->string('nrp', 15)->unique();
            $table->string('program_studi');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('no_telepon');
            $table->text('alamat_domisili');
            $table->string('agama');
            $table->string('instagram')->nullable();
            $table->string('id_line')->nullable();

            // Kolom dokumen tambahan (ukuran file di-handle di Controller)
            $table->decimal('ipk', 4, 2)->nullable(); 
            $table->string('ktm_path')->nullable();
            $table->string('pas_foto_path')->nullable();
            $table->string('transkrip_path')->nullable();
            $table->string('skkk_path')->nullable();
            
            // TAMBAHAN BARU: Lacak progress pelamar (1 sampai 6)
            $table->integer('progress_step')->default(1); 
            
            $table->string('jadwal_hidup_path')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_profiles');
    }
};
