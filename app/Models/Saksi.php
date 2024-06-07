<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saksi extends Model
{
    use HasFactory;
    protected $table = 'saksi'; // Nama tabel sesuai dengan yang Anda definisikan
    protected $guarded = ['id']; // Menjaga kolom 'id' agar tidak diisi secara massal

}
