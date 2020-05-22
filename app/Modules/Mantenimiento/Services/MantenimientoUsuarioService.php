<?php

namespace App\Modules\Mantenimiento\Services;

use App\Modules\Mantenimiento\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class MantenimientoUsuarioService
{

    public function __construct()
    {
        $this->model = new Usuario();

    }

    public function cargarAll(){
       
            $datos = $this->model->where('usuario.estado',1)
            ->where('usuario.usuario_creacion',auth()->user()["id"])
                    ->leftJoin('perfil', 'usuario.perfil_id', '=', 'perfil.id')
                    ->select('usuario.*',
                    DB::raw('CONCAT(IFNULL((usuario.primer_apellido),"")," " ,IFNULL((usuario.segundo_apellido),"")," " ,IFNULL((usuario.primer_nombre),"")) as nombre_completo'),
                    'perfil.nombre as perfil_nombre')
                    ->orderby("id","desc")
                    ->get();
        return $datos;
    }

    
    public function verificar($data){

        $datos =  $this->model::select('*')
        ->where('usuario', $data["usuario"]);
        if ($data["tipo"]=="modificar") {
            $datos->where('id','!=',$data["id"]) ;
        }
        $datos = $datos->first();
        return $datos?1:$datos;
    }

    public function guardar($data)
    {
        $data = $data->all(); 
        $data["usuario_creacion"] = auth()->user()["id"];
        $data["usuario_edicion"] = auth()->user()["id"];
        $data['password']=  Hash::make( $data['password']);
        $save = $this->model->create($data);
        return $save;

    }

    public function buscar($data){

        $datos =  $this->model
        ->select('usuario.*')
        ->where('usuario.id', $data["id"])
        ->first();
        return $datos;
    }

    public function editar($data){

        
            $datos = array(
                "primer_nombre" =>$data["primer_nombre"],
                "segundo_nombre" =>$data["segundo_nombre"],
                "primer_apellido" =>$data["primer_apellido"],
                "segundo_apellido" =>$data["segundo_apellido"],
                "correo" =>$data["correo"],
                "dni" =>$data["dni"],
                "usuario_edicion" => auth()->user()->id,
                "fecha_modificacion" => now()->format('Y-m-d H:i:s')
            );
        $update = $this->model->find($data['id'])->update($datos);
        return $update?1:$update;
    }

    public function resetClave($data){
   
        $update = $this->model->find($data["id"])->update(array(
            "password" => Hash::make('12345678'),
            "usuario_edicion" => auth()->user()->id,
            "fecha_modificacion" => now()->format('Y-m-d H:i:s')
        ));
        return $update?1:$update;
    }

    public function cambiarClave($data){
   
        $data["password"]=  Hash::make( $data['password']);
 
        $update = $this->model->find($data["id"])->update(array(
            "password" =>$data["password"],
            "usuario_edicion" => auth()->user()->id,
            "fecha_modificacion" => now()->format('Y-m-d H:i:s')
        ));
        return $update?1:$update;
    }

    public function editarPerfil($data){
 
        $update = $this->model->find($data['id'])->update(array(
            "numero_documento" =>$data["numero_documento"],
            "primer_nombre" =>$data["primer_nombre"],
            "segundo_nombre" =>$data["segundo_nombre"],
            "primer_apellido" =>$data["primer_apellido"],
            "segundo_apellido" =>$data["segundo_apellido"],
            "correo" =>$data["correo"],
            "usuario_edicion" => auth()->user()->id,
            "fecha_modificacion" => now()->format('Y-m-d H:i:s')
        ));
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
