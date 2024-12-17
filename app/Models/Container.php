<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // nombre
        'latitude', // ubicacion_latitud
        'longitude', // ubicacion_longitud
        'installation_date', // fecha_instalacion
    ];

    /**
     * Relación: Un contenedor puede tener muchos sensores.
     */
    public function sensors()
    {
        return $this->hasMany(Sensor::class, 'container_id');
    }

    /**
     * Relación: Un contenedor puede tener muchas notificaciones.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'container_id');
    }
}
