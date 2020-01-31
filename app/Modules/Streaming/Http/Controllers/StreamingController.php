<?php

namespace App\Modules\Streaming\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Streaming\Services\StreamingService as MainService;
use App\Http\Controllers\Controller;

class StreamingController extends Controller
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
}
