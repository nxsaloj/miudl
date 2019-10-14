<?php

namespace App\Http\Controllers;

use miudl\Seccion\repositoryInterface;
use Illuminate\Http\Request;
use Auth;
use miudl\Seccion\SeccionRepositoryInterface;

class SeccionController extends Controller
{
    protected $repository;
    public function __construct(SeccionRepositoryInterface $_repository)
    {
        $this->repository = $_repository;
        if($this->repository == null) throw new \Exception('Repo not recognized');
    }

    public function getSeccionesByModel($parentid=null)
    {
        $usuario = Auth::user();
        if($usuario)
        {
            if($usuario->id > 1) $data = $this->repository->getSeccionesByModel(!isset($parentid),($puesto = $usuario->PuestoTrabajo())? $puesto:$usuario, $parentid);
            else $data = $this->repository->getSecciones(!isset($parentid), $parentid);
            return $data;
        }
        return [];
    }

    public function getSeccionesHTML($secciones, $url_parent=null)
    {
        $html = "";
        foreach($secciones as $seccion)
        {
            $url = ($url_parent)? $url_parent.(($seccion["Url"])? '/'.$seccion["Url"]:''):$seccion["Url"];
            $icono = $seccion["Icono"];
            $id = $seccion["id"];
            $nombre = $seccion["Nombre"];
            $prioridad = $seccion["Prioridad"];
            $childs = $this->getSeccionesByModel($id);

            if($prioridad == '0') $prioridad = 1;

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
                $section .= $this->getSeccionesHTML($childs, $url);
                if($icono != null) $section .= '</ul></div>';
            }
            if(sizeof($childs) > 0) $section .='</li>';

            $html .= $section;
        }
        return $html;
    }
}
