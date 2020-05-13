<?php

namespace App\Modules\Streaming\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Streaming\Services\StreamingOnlineService as MainService;
class StreamingOnlineController extends Controller
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
    public function entrarStreaming(Request $request)
    {
        $save = $this->service->entrarStreaming($request);
        return $save;
    }
}
