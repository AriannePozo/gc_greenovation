<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // nombre_tipo
        'description', // descripcion
    ];

    /**
     * Relación: Un tipo de sensor tiene muchos sensores.
     */
    public function sensors()
    {
        return $this->hasMany(Sensor::class, 'sensor_type_id');
    }
}
