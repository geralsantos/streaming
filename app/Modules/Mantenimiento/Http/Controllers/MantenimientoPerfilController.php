<?php

namespace App\Modules\Mantenimiento\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Mantenimiento\Http\Requests\MantenimientoPerfilRequest as MainRequest;
use App\Modules\Mantenimiento\Services\MantenimientoPerfilService as MainService;

class MantenimientoPerfilController extends Controller
{
    public function __construct()
    {
        $this->service = new MainService();
    }
    public function index()
    {
        $menu =getMenu();
        return view('dashboard::admin.home',compact('menu'));
    }

    public function guardar(MainRequest $request)
    {
        $form = $request->validated();
        $save = $this->service->guardar($form);
        return $save;
    }

    public function cargarAll()
    {
        $data = $this->service->cargarAll();
        return $data;
    }

    public function buscar(Request $request)
    {
        $data = $this->service->buscar($request);
        return $data;
    }


    public function editar(MainRequest $request)
    {
        $form = $request->validated();
        $data = $this->service->editar($form);
        return $data;       
    }
    public function eliminar(Request $request){
        $data = $this->service->eliminar($request);
        return $data;
    }

}
