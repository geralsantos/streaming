<?php

namespace App\Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Models\Menu;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function modulos()
    {
        $modules = Menu::where('estado',1)->orderBy('id','asc')->get();
        $tree = $this->buildTree($modules);
        $treeHtml = $this->buildTreeHtml($tree);
        return $treeHtml;
        //return $tree;
    }
    public function buildTree($elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['padre_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
    public function buildTreeHtml($elements, $opt="")
    {
        $branch = array();
        $li = '';

        foreach ($elements as $element) {
            $li = $li . (isset($element['children'])

            ? ('
            <li class="treeview">
                <a href="#">
                <i class="material-icons">'.$element["icon"].'</i>
                    <span style="position: absolute; margin: 5px;">

                    '.(mb_strtolower($element['nombre'])=="rrhh"?"RRHH":(ucwords(mb_strtolower(($element['nombre']))))).'</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    '. $this->buildTreeHtml($element['children'], 'childs').'
                </ul>
           '.' </li>')
            :
                            ('<li >
                            <a href="/'.$element["url"].'">
                                <i class="material-icons">'.$element["icon"].'</i>'.($element["url"]=="tracking"?('<span class="v-alert__border v-alert__border--left cyan v-alert__border--has-color"></span>'):'').' <span  style="position: absolute; margin: 5px;">'.$element['nombre'].'</span>
                            </a>
                        </li> ') );
        }
        return $li;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = $this->modulos();
        /*
        <li>
          <a href="pages/widgets.html">
            <span>Widgets</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
        </li>
        */
      /**/
        return view('dashboard::admin.home',compact('menu'));
    //    return 'mysticthemes::themes.' . $current_theme . '.pages.' . $page;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

    }
}
