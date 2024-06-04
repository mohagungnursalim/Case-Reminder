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
        Schema::create('proyek', function (Blueprint $table) {
            $table->id(); // id_proyek sebagai Primary Key
            $table->string('nama_proyek'); // Nama proyek
            $table->text('deskripsi_proyek'); // Deskripsi proyek
            $table->date('tanggal_mulai'); // Tanggal mulai proyek
            $table->date('tanggal_selesai')->nullable(); // Tanggal selesai proyek 
            $table->date('tanggal_diselesaikan')->nullable(); //Tanggal diselesaikan proyek jika molor dari tanggal selesai
            $table->enum('status', ['pemeriksaan', 'berlangsung', 'selesai', 'ditunda', 'dibatalkan'])->default('pemeriksaan');  // Status proyek Enum default pemeriksaan
            $table->string('lokasi'); // Lokasi proyek
            $table->decimal('anggaran', 15, 2); // Anggaran proyek
            $table->timestamps(); // Created_at dan Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
