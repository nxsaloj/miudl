<?php

namespace miudl\Logging;

use miudl\Base\BaseRepository;

class LogRepository extends BaseRepository implements LogRepositoryInterface
{
    public function getModel($params=[])
    {
        return new LogObject($params);
    }

    public function findOrFail($id)
    {
        return $this->getModel()->findOrFail($id);
    }

    /**
     * @param array $params
     * @param bool $paginate
     * @param int $per_page
     * @return \Throwable|void
     */
    public function search(array $params = array(), $paginate = false, $per_page=25)
    {
        //Parametros
        $pag = (isset($params['por_pagina']))? ((intval($params['por_pagina']) > 0)? $params['por_pagina']:null):$per_page;
        $orderby = (isset($params['orderby']))?  array('field'=>$params['orderby'][0],'type'=>$params['orderby'][1])  :   array('field'=>'Nombre','type'=>true);
        //Utilidad para validar campos y ordenamiento
        $sortby = \App\Utils\Utils::getSortBy($orderby['field']  ,$orderby['type']  ,$this->getModel());
        //Validar con filtro de búsqueda
        $filtro = isset($params['filtro'])? $params['filtro']:null;
        //Query

        $data = $this->getModel()->whereNull('TB_PuestoTrabajo.Deleted_at');
        //Filtrar por like
        if($filtro)
        {
            $data = $data->where( function ( $query ) use ($filtro)
            {
                $query->where('TB_PuestoTrabajo.Nombre','like','%'.$filtro.'%')->orWhere('TB_PuestoTrabajo.Codigo',$filtro);
            });
        }

        //Ordenar por campo validado en UTILS
        if($sortby) $data = $data->orderBy($sortby['field'],$sortby['type']);
        //Paginar por X cantidad de registros por página
        if($pag)
        {
            $data = $data->paginate($pag);
        }
        else if($filtro) $data =  $data->limit($per_page)->get();
        else $data =  $data->get();

        return $data;
    }

    public function create(array $params)
    {
        $model = $this->getModel($params);
        if($model->save()) return $model;

        return false;
    }
}
