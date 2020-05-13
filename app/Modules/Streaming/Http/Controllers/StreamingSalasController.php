<?php

namespace App\Modules\Streaming\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Streaming\Services\StreamingSalasService as MainService;
class StreamingSalasController extends Controller
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
    public function cargarAll()
    {
        $data = $this->service->cargarAll();
        return $data;
    }
    public function guardar(Request $request)
    {
        //$form = $request->validated();
        $save = $this->service->guardar($request);
        return $save;
    }
    public function iniciarStream(Request $request)
    {
        $save = $this->service->iniciarStream($request);
        return $save;
    }
    public function encriptarStream(Request $request)
    {
        $save = $this->service->encriptarStream($request);
        return $save;
    }
    
    
}
