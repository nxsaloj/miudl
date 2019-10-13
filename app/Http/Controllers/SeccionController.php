<?php

namespace App\Http\Controllers;

use miudl\Seccion\SeccionRepositoryInterface;
use Illuminate\Http\Request;
use Auth;

class SeccionController extends Controller
{
    protected $repository;
    public function __construct(SeccionRepositoryInterface $_seccionRepository)
    {
        $this->seccionRepository = $_seccionRepository;
        if($this->seccionRepository == null) throw new \Exception('Repo not recognized');
    }

    public function getSeccionesByModel()
    {
        $usuario = Auth::user();
        if($usuario)
        {
            $data = $this->seccionRepository->getSeccionesByModel(true,($puesto = $usuario->PuestoTrabajo())? $puesto:$usuario);
            $serialized = json_encode($data);
        }
        return [];
    }

    public static function getSeccionesHTML($secciones, $url_parent=null)
    {
        $html = "";
        foreach($secciones as $seccion)
        {
            $url = ($url_parent)? $url_parent.(($seccion["Url"])? '/'.$seccion["Url"]:''):$seccion["Url"];
            $icono = $seccion["Icono"];
            $id = $seccion["id"];
            $nombre = $seccion["Nombre"];
            $prioridad = $seccion["Prioridad"];
            $childs = ($seccion["secciones"] != null)? $seccion["secciones"]:[];

            if($prioridad == '0') $prioridad = 1;

            /*$section = '
            <li class="nav-item '.(($icono == null)? "nav-category":"").'">';
            if($icono == null) $section .= '<span class="nav-link">'.$nombre.'</span> </li>';*/
            $section = "";
            if($prioridad >= 0)
            {
                $section = '
                <li class="nav-item '.(($icono == null)? " nav-category":"").'">';
            }
            if($icono == null) $section .= ($prioridad >= 0)? '<span class="nav-link">'.$nombre.'</span> </li>':"";
            else
            {
                $section .= '<a class="nav-link"';

                if(sizeof($childs) > 0 && $icono != null) $section .= ' data-toggle="collapse" href="#'.str_replace(' ','',$id).'Submenu" aria-expanded="false" aria-controls="collapseExample">';
                else $section .= ' href="'.$url.'">';

                $section .= '<i class="'.$icono.'"></i>
                    <span class="menu-title">'.$nombre.'</span>';
                if(sizeof($childs) > 0) $section .= '<i class="mdi mdi-chevron-down"></i>';
                $section .= '</a>';
                if(sizeof($childs) <= 0) $section .='</li>';
            }
            if(sizeof($childs) > 0)
            {
                if($icono != null) $section .= '<div class="collapse" id="'.str_replace(' ','',$id).'Submenu"><ul class="nav flex-column sub-menu">';
                $section .= self::getSeccionesHTML($childs, $url);
                if($icono != null) $section .= '</ul></div>';
            }
            if(sizeof($childs) > 0) $section .='</li>';

            $html .= $section;
        }
        return $html;
    }
}
