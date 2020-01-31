<?php

namespace App\Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;

class SalaDetalle extends Model
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
protected $table = 'sala_detalle';
public $timestamps = false;
}
