<?php

namespace App\Modules\Streaming\Services;

use App\Modules\Dashboard\Models\Sala;
use App\Modules\Dashboard\Models\SalaDetalle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class StreamingService
{
    public function __construct()
    {
        $this->model_sala_detalle = new SalaDetalle();
        $this->model_sala = new Sala();

    }
    public function cargarAll(){

        $datos["sala_detalle"] = $this->model_sala_detalle
        ->where('estado', 1)
        ->where('usuario_id',auth()->user()["id"])
        ->select('*')
        ->orderBy('id', 'desc')
        ->first();

        $datos["sala"] = $this->model_sala
        ->where('estado', 1)
        ->where('id',$datos["sala_detalle"]["sala_id"])
        ->select('*')
        ->orderBy('id', 'desc')
        ->get();
        return $datos;
    }

}