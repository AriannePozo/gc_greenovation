<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name', // nombre_tipo
        'description', // descripcion
    ];

    /**
     * Relación: Un estado de usuario tiene muchos usuarios.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'user_status_id');
    }
}
