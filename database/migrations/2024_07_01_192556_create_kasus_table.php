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
        Schema::create('kasus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('status', ['Pending', 'Proses', 'Selesai'])->default('Proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasus');
    }
};
