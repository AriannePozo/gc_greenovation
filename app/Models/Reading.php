<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reading
 *
 * @property $id
 * @property $sensor_id
 * @property $container_id
 * @property $value
 * @property $reading_date
 * @property $created_at
 * @property $updated_at
 *
 * @property Sensor $sensor
 * @property Container $container
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Reading extends Model
{
    use HasFactory;

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['sensor_id', 'container_id', 'value', 'reading_date'];

    /**
     * Relación: Una lectura pertenece a un sensor.
     */
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

    /**
     * Relación: Una lectura pertenece a un contenedor.
     */
    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
}