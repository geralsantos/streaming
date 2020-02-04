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

        $datos["salas"] = $this->model
        ->where('sala.estado', 1)
        ->leftJoin('sala_detalle', function ($leftJoin) {
            $leftJoin->on('sala.id', '=', 'sala_detalle.sala_id')
            ->where('sala_detalle.admin', '=', 1)
            ->where('sala_detalle.usuario_id', '=', auth()->user()["id"]);
        })
        ->select('sala.*','sala_detalle.*')
        ->orderBy('sala.id', 'desc')
        ->get();
        /*$datos["sala_detalle"] = $this->model_sala_detalle
        ->where('estado', 1)
        ->where('usuario_id', auth()->user()["id"])
        ->where('admin', 1)
        ->select('*')
        ->orderBy('id', 'desc')
        ->get();*/
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
