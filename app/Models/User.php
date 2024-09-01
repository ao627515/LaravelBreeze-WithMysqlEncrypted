<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = DB::raw("AES_ENCRYPT('{$value}', '" . env('DB_ENCRYPTION_KEY') . "')");
    }

    // Chiffrer le mot de passe avant de sauvegarder
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = DB::raw("AES_ENCRYPT('{$value}', '" . env('DB_ENCRYPTION_KEY') . "')");
    }

    public function getEmailAttribute($value)
    {
        return DB::selectOne("SELECT AES_DECRYPT('{$value}', '" . env('DB_ENCRYPTION_KEY') . "') AS email")->email;
    }

    // Déchiffrer le mot de passe lors de la récupération
    public function getPasswordAttribute($value)
    {
        return DB::selectOne("SELECT AES_DECRYPT('{$value}', '" . env('DB_ENCRYPTION_KEY') . "') AS password")->password;
    }
}