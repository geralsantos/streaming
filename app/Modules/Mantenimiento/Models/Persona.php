<?php

namespace App\Modules\Mantenimiento\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Persona extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'sexo_id',
        'pais_id',
        'tipo_documento_id',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'numero_documento',
        'fecha_nacimiento',
        'direccion_principal',
        'telefono_principal',
        'telefono_secundario',
        'correo',
        'estado',
        'usuario_creacion',
        'usuario_edicion',
        'fecha_creacion',
        'fecha_modificacion'
    ];
    protected $table = 'persona';
    public $timestamps = false;
    /*public function routeNotificationForMail($notification)
    {
        return $this->correo;
    }*/
    
    /*public function estudiante()
    {
        return $this->hasOne('App\Modules\Mantenimiento\Models\Estudiante','persona_id','id');
    }*/
}
