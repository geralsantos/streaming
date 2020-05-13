<?php

namespace App\Modules\Streaming\Models;

use Illuminate\Database\Eloquent\Model;

class SalasDetalle extends Model
{
    //
    protected $fillable = [
        'usuario_id',
        'sala_id',
        'admin',
        'estado',
        'usuario_creacion',
        'usuario_edicion',
        'fecha_creacion',
        'fecha_modificacion'
    ];
protected $table = 'salas_detalle';
public $timestamps = false;
}
