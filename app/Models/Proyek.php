<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;
    protected $table = 'proyek';
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'tanggal_mulai' => 'datetime:d-m-Y',
        'tanggal_selesai' => 'datetime:d-m-Y',
        'tanggal_diselesaikan' => 'datetime:d-m-Y',
    ];

     // Relasi dengan model User
     public function users()
     {
         return $this->belongsTo(User::class, 'user_id');
     }
 
     // Relasi many-to-many dengan model Perusahaan melalui tabel pivot
     public function perusahaans()
     {
         return $this->belongsToMany(Perusahaan::class, 'proyek_perusahaan', 'proyek_id', 'perusahaan_id');
     }

}
