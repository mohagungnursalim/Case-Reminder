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
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id(); // id_perusahaan sebagai Primary Key
            $table->string('nama_perusahaan'); // Nama perusahaan
            $table->string('gambar_perusahaan'); //Gambar atau Logo perusahaan
            $table->text('alamat'); // Alamat perusahaan
            $table->string('kontak', 100); // Nomor telepon perusahaan
            $table->string('email', 100); // Email perusahaan
            $table->string('website')->nullable(); // Website perusahaan (opsional)
            $table->string('jenis_perusahaan'); // Jenis perusahaan
            $table->date('tanggal_terdaftar'); // Tanggal perusahaan didaftarkan
            $table->string('status', 50); // Status perusahaan
            $table->text('deskripsi')->nullable(); // Deskripsi perusahaan (opsional)
            $table->timestamps(); // Created_at dan Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
