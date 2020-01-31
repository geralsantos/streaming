<?php

namespace App\Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Dashboard\Http\Requests\SalaRequest as MainRequest;
use App\Modules\Dashboard\Services\RegistroSalaService as MainService;
use App\Http\Controllers\Controller;

class RegistroSalaController extends Controller
{
    //
    public function __construct()
    {
        $this->service = new MainService();
    }
    public function index()
    {
        $menu =getMenu();
        return view('dashboard::admin.home',compact('menu'));
    }
    public function cargarAll(Request $request)
    {
        $data = $this->service->cargarAll($request);
        return $data;
    }
    public function guardar(MainRequest $request)
    {
        $form = $request->validated();
        $save = $this->service->guardar($form);
        return $save;
    }
}
