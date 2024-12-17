<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id', // id_contenedor
        'type', // tipo_notificacion
        'description', // descripcion
        'notification_date', // fecha_notificacion
    ];

    /**
     * Relación: Una notificación pertenece a un contenedor.
     */
    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
}
