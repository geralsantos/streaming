<?php

namespace App\Modules\Mantenimiento\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Mantenimiento\Models\Usuario;
use App\Modules\Mantenimiento\Http\Requests\MantenimientoUsuarioRequest as MainRequest;
use App\Modules\Mantenimiento\Services\MantenimientoUsuarioService as MainService;

class MantenimientoUsuarioController extends Controller
{
    public function __construct()
    {
        $this->service = new MainService();
    }

    public function index()
    {
        //auth()->user()->authorizeRoles(['superadmin']);
        $menu =getMenu();
        return view('dashboard::admin.home',compact('menu'));

    }

    public function guardar(Request $request)
    {
        $save = $this->service->guardar($request);
        return $save;
    }

    public function cargarAll()
    {
        $data = $this->service->cargarAll();
        return $data;
    }

    public function cargarUbigeo()
    {
        $data = $this->service->cargarUbigeo();
        return $data;
    }
    public function cargarUbigeoDepartamentos()
    {
        $data = $this->service->cargarUbigeoDepartamentos();
        return $data;
    }
    public function cargarUbigeoProvincias(Request $request)
    {
        $data = $this->service->cargarUbigeoProvincias($request);
        return $data;
    }
    public function cargarUbigeoDistritos(Request $request)
    {
        $data = $this->service->cargarUbigeoDistritos($request);
        return $data;
    }

    public function buscar(Request $request)
    {
        $data = $this->service->buscar($request);
        return $data;
    }

    public function verificar(Request $request)
    {
        $data = $this->service->verificar($request);
        return $data;
       
    }

    public function editar(Request $request)
    {
     
        $data = $this->service->editar($request);
         return $data;
       
    }

    public function editarPerfil(Request $request)
    {
        $data = $this->service->editarPerfil($request);
        return $data;
    }
    
    public function cambiarClave(Request $request)
    {
        $data = $this->service->cambiarClave($request);
        return $data;
    }

    public function resetClave(Request $request)
    {
        $data = $this->service->resetClave($request);
        return $data;
    }

    public function eliminar(Request $request){
        $data = $this->service->eliminar($request);
        return $data;
    }
}
