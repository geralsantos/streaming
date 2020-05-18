<?php

namespace App\Modules\Mantenimiento\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $fillable = [
                            'nombre',
                            'codigo',
                            'estado',
                            'usuario_creacion',
                            'usuario_edicion',
                            'fecha_creacion',
                            'fecha_modificacion'
                        ];
    protected $table = 'perfil';
    public $timestamps = false;
}
