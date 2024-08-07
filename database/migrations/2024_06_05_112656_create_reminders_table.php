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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('lokasi');
            $table->string('nama_kasus');
            $table->text('pesan');
            $table->json('nama_atasan');
            $table->json('nomor_atasan');
            $table->json('nama_jaksa');
            $table->json('nomor_jaksa');
            $table->json('nama_saksi');
            $table->json('nomor_saksi');
            $table->boolean('is_sent')->default(0);
            $table->dateTime('tanggal_waktu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
