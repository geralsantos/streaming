<?php

namespace App\Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Usuario extends Authenticatable
{
    protected $fillable = [
                            'perfil_id',
                            'usuario',
                            'password',
                            'estado',
                            'usuario_creacion',
                            'usuario_edicion',
                            'fecha_creacion',
                            'fecha_modificacion'
                        ];
    protected $table = 'usuario';
    public $timestamps = false;

    /*public function empleado()
    {
        return $this->hasOne('App\Modules\Mantenimiento\Models\Empleado','id');
    }*/
}
