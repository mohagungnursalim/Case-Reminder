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
        Schema::create('atasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('lokasi');
            $table->string('nama');
            $table->string('nomor_wa');
            $table->string('jabatan');
            $table->string('pangkat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atasan');
    }
};
