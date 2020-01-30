<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Dashboard\Models\Sala;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class RegistroSalaService
{

    public function __construct()
    {
        $this->model = new Sala();

    }

   /* public function cargarAll(){

        $datos = $this->model
        ->where('empleado.estado', 1)
        ->leftJoin('persona', 'empleado.persona_id', '=', 'persona.id')
        ->leftJoin('sexo', 'persona.sexo_id', '=', 'sexo.id')
        ->leftJoin('pais', 'persona.pais_id', '=', 'pais.id')
        ->leftJoin('tipo_documento', 'persona.tipo_documento_id', '=', 'tipo_documento.id')
        ->leftJoin('tipo_empleado', 'empleado.tipo_empleado_id', '=', 'tipo_empleado.id')
        ->select(
                    'empleado.id',
                    'empleado.persona_id',
                    'sexo.nombre as sexo',
                    'pais.nombre as pais',
                    'tipo_documento.nombre as tipo_documento',
                    'persona.numero_documento',
                    'persona.primer_apellido',
                    'persona.segundo_apellido',
                    'persona.primer_nombre',
                    'persona.segundo_nombre',
                    'persona.fecha_nacimiento',
                    'persona.direccion_principal',
                    'persona.telefono_principal',
                    'persona.correo',
                    'tipo_empleado.nombre as cargo',
                    DB::raw('date_format(persona.fecha_creacion,"%d-%m-%Y %H:%i:%s") as fecha_registro'),
                    DB::raw('CONCAT(persona.primer_apellido," ",persona.segundo_apellido) as apellidos'),
                    DB::raw('CONCAT(persona.primer_nombre," ",persona.segundo_nombre) as nombres')
        )
        ->orderBy('persona.primer_apellido', 'asc')
        ->get();
        return $datos;
    }*/

    public function guardar($data)
    {
        $data["usuario_creacion"] = auth()->user()["id"];
        $data["usuario_edicion"] = auth()->user()["id"];
        $save = $this->model->create($data);
        if($save){
            return json_encode(array("estado"=>"success","mensaje"=>"Sala Registrada", "data"=> $save));
        }else{
            return json_encode(array("estado"=>"error","mensaje"=>"Ha ocurrido un error al registrar la sala."));
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
