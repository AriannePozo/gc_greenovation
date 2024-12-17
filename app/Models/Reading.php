<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id', // id_sensor
        'value', // valor
        'reading_date', // fecha_lectura
    ];

    /**
     * Relación: Una lectura pertenece a un sensor.
     */
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }
}
