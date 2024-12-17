<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // nombre_tipo
        'description', // descripcion
    ];

    /**
     * Relación: Un tipo de usuario puede tener muchos usuarios.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id');
    }
}
