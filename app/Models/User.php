<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'kejari_nama'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
        'password' => 'hashed',
    ];

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function saksis()
    {
        return $this->hasMany(Saksi::class);
    }

    public function kasuss()
    {
        return $this->hasMany(Kasus::class);
    }

    public function jaksas()
    {
        return $this->hasMany(Jaksa::class);
    }

    public function atasans()
    {
        return $this->hasMany(Atasan::class);
    }

}
