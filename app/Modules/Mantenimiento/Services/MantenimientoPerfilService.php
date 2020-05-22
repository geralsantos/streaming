<?php

namespace App\Modules\Mantenimiento\Services;

use App\Modules\Mantenimiento\Models\Perfil;

class MantenimientoPerfilService
{

    public function __construct()
    {
        $this->model = new Perfil();

    }

    public function cargarAll(){

        /*if(auth()->user()["perfil_id"]==1){
            //es super administrador
            $datos = $this->model->select('*')
            ->where('estado',1)
            ->where('id','!=',4)
            ->get();
          
        }elseif (auth()->user()["perfil_id"]==2) {
            //es sub administrador
            $datos = $this->model->select('*')
            ->where('estado',1)
            ->where('id','!=',1)
            ->get();
        }else{
            //no puedo crear usuarios
            $datos = [];
        }*/
        $datos = $this->model->select('*')
        ->where('estado',1)
        ->get();
        return $datos;
    }


    public function guardar($data)
    {
        $data["usuario_creacion"] = auth()->user()["id"];
        $data["usuario_edicion"] = auth()->user()["id"];
        $save = $this->model->create($data);
        return $save;

    }

    public function buscar($data){

        $datos =  $this->model->all()->where('id', $data["id"])->first();
        return $datos;
    }

    public function editar($data){

        $data["usuario_edicion"] = auth()->user()->id;
        $data["fecha_modificacion"] = now()->format('Y-m-d H:i:s');
        $update = $this->model->find($data['id'])->update($data);
        return $update?1:$update;
    }

    public function eliminar($data){

        $update = $this->model->find($data['id'])->update([
            'estado' => 0,
            'usuario_edicion' => auth()->user()["id"],
            'fecha_modificacion' => now()->format('Y-m-d H:i:s')
        ]);
        return $update?1:$update;
    }



}
