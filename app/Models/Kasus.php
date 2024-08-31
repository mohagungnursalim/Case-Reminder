<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasus extends Model
{
    use HasFactory;

    protected $table = 'kasus'; // Nama tabel sesuai dengan yang Anda definisikan
    protected $fillable = ['nama','status']; // Daftar atribut yang dapat diisi secara massal

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
