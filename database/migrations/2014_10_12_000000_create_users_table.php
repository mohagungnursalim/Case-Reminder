<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('kejari_nama', [
                'Kejari Sulteng',
                'Kejari Palu',
                'Kejari Poso',
                'Kejari Tolitoli',
                'Kejari Banggai',
                'Kejari Parigi',
                'Kejari Donggala',
                'Kejari Buol',
                'Kejari Morowali'
            ])->nullable();
            $table->string('name');
            $table->boolean('is_admin')->default(false);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
