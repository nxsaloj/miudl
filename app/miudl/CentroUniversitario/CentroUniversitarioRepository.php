<?php

namespace miudl\CentroUniversitario;

use App\Utils\Utils;
use miudl\Base\BaseRepository;

class CentroUniversitarioRepository extends BaseRepository implements CentroUniversitarioRepositoryInterface
{
    public function getModel($params=[])
    {
        return new CentroUniversitario($params);
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

        $data = $this->getModel()->whereNull($this->getModel()->getTable().'.Deleted_at');
        //Filtrar por like
        if($filtro)
        {
            $data = $data->where( function ( $query ) use ($filtro)
            {
                $query->where($this->getModel()->getTable().'.Nombre','like','%'.$filtro.'%')->orWhere($this->getModel()->getTable().'.Codigo',$filtro);
            });
        }

        //Ordenar por campo validado en UTILS
        if($sortby) $data = $data->orderBy($sortby['field'],$sortby['type']);
        //Paginar por X cantidad de registros por página
        $extrafields = [];
        $fields = Utils::getFieldsJoin($this->getModel(), $extrafields);
        if($pag)
        {
            $data = $data->paginate($pag, $fields);
        }
        else if($filtro) $data =  $data->limit($per_page)->get($fields);
        else $data =  $data->get($fields);

        return $data;
    }

    public function create(array $params)
    {
        $model = $this->getModel($params);
        if($model->save()) return $model;

        return false;
    }

    public function update($id, array $data)
    {
        $model = $this->getModel()->findOrFail($id);
        $model->fill($data);
        if($model->save()) return $model;

        return false;
    }

    public function delete($id)
    {
        $model = $this->getModel()->findOrFail($id);
        if($model->delete()) return $model;

        return false;
    }

    public function isDeleted($params)
    {
        return $this->getModel()->onlyTrashed()->where('Codigo',$params['Codigo'])->first();
    }
}
