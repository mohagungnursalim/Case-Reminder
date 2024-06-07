<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_waktu' => 'datetime',
    ];
}
