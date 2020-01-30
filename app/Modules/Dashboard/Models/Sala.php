<?php

namespace App\Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
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
protected $table = 'sala';
public $timestamps = false;
}
