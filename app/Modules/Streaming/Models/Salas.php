<?php

namespace App\Modules\Streaming\Models;

use Illuminate\Database\Eloquent\Model;

class Salas extends Model
{
    //
    protected $fillable = [
        'nombre',
        'capacidad',
        'expira_sala',
        'token',
        'estado',
        'usuario_creacion',
        'usuario_edicion',
        'fecha_creacion',
        'fecha_modificacion'
    ];
protected $table = 'salas';
public $timestamps = false;
}
