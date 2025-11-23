<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_type_id', // id_tipo_sensor
        'container_id', // id_contenedor
        'sensor_status_id', // id_estado
        'model', // modelo
        'installation_date', // fecha_instalacion
        'last_reading', // ultima_lectura
    ];

    /**
     * Relación: Un sensor pertenece a un tipo de sensor.
     */
    public function sensorType()
    {
        return $this->belongsTo(SensorType::class, 'sensor_type_id');
    }

    /**
     * Relación: Un sensor pertenece a un estado de sensor.
     */
    public function sensorStatus()
    {
        return $this->belongsTo(SensorStatus::class, 'sensor_status_id');
    }

    /**
     * Relación: Un sensor pertenece a un contenedor.
     */
    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }

    /**
     * Relación: Un sensor tiene muchas lecturas.
     */
    public function readings()
    {
        return $this->hasMany(Reading::class, 'sensor_id');
    }

    /**
     * Funcion para obtener el nombre del tipo sensor y modelo
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->sensorType->name} - {$this->model}";
    }
}