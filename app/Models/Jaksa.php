<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jaksa extends Model
{
    use HasFactory;

    protected $table = 'jaksa'; // Nama tabel sesuai dengan yang Anda definisikan
    protected $guarded = ['id']; // Menjaga kolom 'id' agar tidak diisi secara massal

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
