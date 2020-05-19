<?php

namespace App\Modules\Streaming\Services;

use App\Modules\Soporte\Models\Errores;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use App\Modules\Streaming\Models\Salas;
use App\Modules\Streaming\Models\SalasDetalle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class StreamingSalasService
{
    public function __construct()
    {
        $this->model = new Salas();
        $this->model_sala_detalle = new SalasDetalle();

    }

    public function cargarAll(){

        $datos["salas"] = $this->model
        ->where('salas.estado', 1)
        ->leftJoin('salas_detalle', function ($leftJoin) {
            $leftJoin->on('salas.id', '=', 'salas_detalle.sala_id')
            ->where('salas_detalle.admin', '=', 1)
            ->where('salas_detalle.usuario_id', '=', auth()->user()["id"]);
        })
        ->select('salas.*','salas_detalle.admin')
        ->orderBy('salas.id', 'desc')
        ->get();
        /*$datos["salas_detalle"] = $this->model_sala_detalle
        ->where('estado', 1)
        ->where('usuario_id', auth()->user()["id"])
        ->where('admin', 1)
        ->select('*')
        ->orderBy('id', 'desc')
        ->get();*/
        return $datos;
    }
    public function iniciarStream($data)
    {
        $data = $data->all();

        $save = $this->model->find($data["id"])->update(array("token"=>""));
        $idhashed = (Crypt::encryptString($data["id"]));
        $token = request()->getSchemeAndHttpHost()."/streaming/#".$idhashed;
        $save = $this->model->find($data["id"])->update(array("token"=>$token));
        if ($save) {
            return json_encode(array("estado"=>"success","mensaje"=>"Stream iniciado", "data"=> $save,"salatoken"=>$idhashed));
        }else{
            return json_encode(array("estado"=>"error","mensaje"=>"Ha ocurrido un error al iniciar el stream."));
        }
    }
    public function encriptarStream($data)
    {
        try {
            $data = $data->all();
            $sala = $this->model->where('id',$data["room"])->first();
            if (empty($sala["token"])) {
                throw new \Exception('La videoconferencia aún no ha iniciado, por favor intente nuevamente.');
            }
            $idhashed = explode("#",$sala["token"])[1];

            if ($idhashed) {
                return json_encode(array("estado"=>"success","mensaje"=>"Se ha unido al stream", "salatoken"=>$idhashed));
            }else{
                return json_encode(array("estado"=>"error","mensaje"=>"No ha podido unirse al stream."));
            }
        } catch (\Exception $th) {
            return json_encode(array("estado"=>"warning","mensaje"=>$th->getMessage()));
        }
    }
    public function guardar($data)
    {
        try {
            DB::beginTransaction();
            $data = $data->all();
            $data["usuario_creacion"] = auth()->user()["id"];
            $data["usuario_edicion"] = auth()->user()["id"];
            $save = $this->model->create($data);
            $save_detalle = $this->model_sala_detalle->create(array("usuario_id"=>auth()->user()["id"],"sala_id"=>$save->id,"admin"=>1,"usuario_creacion"=>auth()->user()["id"],"usuario_edicion"=>auth()->user()["id"]));
            if($save && $save_detalle){
                DB::commit();
                return json_encode(array("estado"=>"success","mensaje"=>"Sala Registrada", "data"=> $save));
            }else{
                DB::rollBack();
                return json_encode(array("estado"=>"error","mensaje"=>"Ha ocurrido un error al registrar la salas."));
            }

        } catch (Exception $th) {
            DB::rollBack();
            return json_encode(array("estado"=>"error","mensaje"=>("Ha ocurrido un error: ".$th)));
            //throw $th;
        }
        
    }
}
