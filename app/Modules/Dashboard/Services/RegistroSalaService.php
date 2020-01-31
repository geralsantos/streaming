<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Dashboard\Models\Sala;
use App\Modules\Dashboard\Models\SalaDetalle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class RegistroSalaService
{

    public function __construct()
    {
        $this->model = new Sala();
        $this->model_sala_detalle = new SalaDetalle();

    }

    public function cargarAll(){

        $datos = $this->model
        ->where('estado', 1)
        ->select('id','nombre','capacidad','expira_sala','estado','token','fecha_creacion')
        ->orderBy('id', 'desc')
        ->get();
        return $datos;
    }

    public function guardar($data)
    {
        try {
            DB::beginTransaction();
            $data["usuario_creacion"] = auth()->user()["id"];
            $data["usuario_edicion"] = auth()->user()["id"];
            $save = $this->model->create($data);
            $save_detalle = $this->model_sala_detalle->create(array("usuario_id"=>auth()->user()["id"],"sala_id"=>$save->id,"admin"=>1,"usuario_creacion"=>auth()->user()["id"],"usuario_edicion"=>auth()->user()["id"]));
            if($save && $save_detalle){
                DB::commit();
                return json_encode(array("estado"=>"success","mensaje"=>"Sala Registrada", "data"=> $save));
            }else{
                DB::rollBack();
                return json_encode(array("estado"=>"error","mensaje"=>"Ha ocurrido un error al registrar la sala."));
            }

        } catch (Exception $th) {
            DB::rollBack();
            return json_encode(array("estado"=>"error","mensaje"=>("Ha ocurrido un error: ".$th)));
            //throw $th;
        }
        
    }

  /*  public function editar($data){

        $data["usuario_edicion"] = auth()->user()->id;
        $data["fecha_modificacion"] = now()->format('Y-m-d H:i:s');
        $datos = $this->model->find($data['id'])->update($data);
        if($datos){
            return json_encode(array("estado"=>"success","mensaje"=>"Empleado Modificado", "data"=> $datos));
        }else{
            return json_encode(array("estado"=>"error","mensaje"=>"Ha ocurrido un error al modificar el empleado."));
        }
    }

    public function buscar($data){

        $datos =  $this->model->all()->where('id', $data["id"])->first();
        return $datos;
    }*/


}
