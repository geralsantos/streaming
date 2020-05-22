<?php

namespace App\Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Models\Menu;
use App\Modules\Mantenimiento\Models\Usuario;
use App\Modules\Mantenimiento\Models\Estudiante;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    private $perfil_id;
    public function modulos()
    {
        $Usuario = Usuario::where('estado',1)->where('id',auth()->user()->id)->orderBy('id','asc')->first();
   
        /*if(auth()->user()["perfil_id"]==4){
            $estudiante = Estudiante::where('estado',1)->where('persona_id',auth()->user()["persona_id"])->orderBy('id','asc')->first();
            auth()->user()["estudiante"]=$estudiante;
        }*/

        $this->perfil_id = $Usuario["perfil_id"];
        $modules = Menu::where('estado',1)->orderBy('id','asc')->get();
        $tree = $this->buildTree($modules);
        $treeHtml = $this->buildTreeHtml($tree);
        return $treeHtml;
    }

    public function buildTree($elements, $parentId = 0)
    {
        $branch = array();
        $permitido = array();
        foreach ($elements as $element) {
            $permisos = explode(",",$element['perfil']);
            if (in_array($this->perfil_id,$permisos)) {
                $permitido[] = $element['id'];
            }
            
            if ($element['padre_id'] == $parentId && in_array($element['id'],$permitido) ) {
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
        $p='';
        $active='';
        foreach ($elements as $element) {
        $newMenu = $element["padre_id"]==0 ;
     
          $li =  $li . ( $newMenu ? '<li class="app-sidebar__heading">'.ucwords(mb_strtolower(($element['nombre']))).'</li>':''). (isset($element['children'])
            ? ('
            <li class="'.(((explode("/",Request()->path())[0]==explode("/",$element["url"])[0])) ? 'mm-active': '').'" >
                <a href="#" class="'.((explode("/",Request()->path())[0]==explode("/",$element["url"])[0]) ?'mm-active':'').'">
                    <i class="metismenu-icon material-icons">'.$element["icon"].'</i>
                    '.strtoupper((($element['nombre']))).'
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul>
                    '.$this->buildTreeHtml($element['children'], 'childs').' 
                </ul>
            </li> ')
            :
                            ('<li>
                            <a href="/'.$element["url"].'" class="'.((Request()->path()==$element["url"]) ?'mm-active':'').'">
                                <i class="metismenu-icon material-icons">'.$element["icon"].'</i>
                                '.$element['nombre'].'
                            </a>
                        </li>   ') );
        }
        return $li;
        /*$branch = array();
        $li = '';

        foreach ($elements as $element) {
            $li =  $li . (isset($element['children'])

            ? ('
            <li class="treeview">
                <a href="#">
                <i class="material-icons">'.$element["icon"].'</i>
                    <span style="position: absolute; margin: 2px 5px 5px 10px;">

                    '.((ucwords(mb_strtolower(($element['nombre']))))).'</span>
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
                            <a href="/'.session('colegio').'/'.$element["url"].'">
                                <i class="material-icons">'.$element["icon"].'</i> <span  style="position: absolute; margin: 2px 5px 5px 10px;">'.$element['nombre'].'</span>
                            </a>
                        </li> ') );
        }
        return $li;*/
    }

    public function index()
    {
        $menu = $this->modulos();
        return view('dashboard::admin.home',compact('menu'));
    }

}
