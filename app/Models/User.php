<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type_id',   // Añadió el campo user_type_id
        'user_status_id', // Añadió el campo user_status_id
        'ci',             // Añadió el campo ci
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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


    /**
     * Relación: Un usuario pertenece a un tipo de usuario.
     */
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    /**
     * Relación: Un usuario pertenece a un estado.
     */
    public function userStatus()
    {
        return $this->belongsTo(UserStatus::class, 'user_status_id');
    }

    /**
     * Encriptar la contraseña antes de guardarla.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Asegurarse de que la contraseña esté encriptada antes de ser almacenada
            $user->password = Hash::make($user->password);
        });
    }
}
