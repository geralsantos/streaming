<?php

namespace App\Modules\Streaming\Services;

use App\Modules\Soporte\Models\Errores;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use App\Modules\Streaming\Models\Salas;
use App\Modules\Streaming\Models\SalasDetalle;
use Illuminate\Support\Facades\Crypt;

class StreamingOnlineService
{
    public function __construct()
    {
        $this->model = new Salas();
        $this->model_sala_detalle = new SalasDetalle();

    }
    public function entrarStreaming($data)
    {
        $response=array();
        $data = $data->all();
        $id = "";
        try {
            $id = Crypt::decryptString($data["room"]);
            if (!$id) {
                return json_encode(array("estado"=>"error","mensaje"=>"El código del stream es incorrecto"));
            }
        } catch (\Throwable $th) {
            return json_encode(array("estado"=>"error","mensaje"=>"El código del stream es incorrecto"));
        }
        $sala = $this->model->where("id",$id)->first();
        $sala_detalle = $this->model_sala_detalle
            ->select(DB::raw('count(id) as capacidad'))
            ->where("sala_id",$id)
            ->where("usuario_id","<>",auth()->user()["id"])
            ->first();

        if ($sala["capacidad"]==$sala_detalle["capacidad"]) {
            return json_encode(array("estado"=>"error","mensaje"=>"El stream ha superado el límite de capacidad"));
        }
        $save_detalle = $this->model_sala_detalle->firstOrCreate(
            ["usuario_id"=>auth()->user()["id"],"sala_id"=>$sala["id"]],
            array("usuario_id"=>auth()->user()["id"],"sala_id"=>$sala["id"],"admin"=>0,"usuario_creacion"=>auth()->user()["id"],"usuario_edicion"=>auth()->user()["id"]));

        $idhashed = explode("#",$sala["token"])[1];
        if ($save_detalle) {
            $response["salatoken"] = $idhashed;
            $response["isadmin"] = auth()->user()["id"]==$sala["usuario_creacion"];
            return json_encode(array("estado"=>"success","mensaje"=>"Ha entrado al stream", "response"=>$response));
        }else{
            return json_encode(array("estado"=>"error","mensaje"=>"No ha podido unirse al stream."));
        }
    }

}
