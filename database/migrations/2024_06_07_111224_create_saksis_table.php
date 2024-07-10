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
        Schema::create('saksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('lokasi');
            $table->string('nama');
            $table->string('alamat');
            $table->string('nomor_wa')->nullable();
            $table->string('pekerjaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saksi');
    }
};
