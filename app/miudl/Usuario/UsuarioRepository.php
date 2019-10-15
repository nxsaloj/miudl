<?php

namespace miudl\Usuario;

use App\Utils\Utils;
use miudl\Base\BaseRepository;

class UsuarioRepository extends BaseRepository implements UsuarioRepositoryInterface
{
    public function getModel($params=[])
    {
        return new Usuario($params);
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
        $orderby = (isset($params['orderby']))?  array('field'=>$params['orderby'][0],'type'=>$params['orderby'][1])  :   array('field'=>'Usuario','type'=>true);
        //Utilidad para validar campos y ordenamiento
        $sortby = \App\Utils\Utils::getSortBy($orderby['field']  ,$orderby['type']  ,$this->getModel());
        //Validar con filtro de búsqueda
        $filtro = isset($params['filtro'])? $params['filtro']:null;
        //Query

        $data = $this->getModel()->whereNull($this->getModel()->getTable().'.Deleted_at')->join('TB_Trabajador','TB_Trabajador.Usuario_id',$this->getModel()->getTable().'.id');
        //Filtrar por like
        if($filtro)
        {
            $data = $data->where( function ( $query ) use ($filtro)
            {
                $query->where($this->getModel()->getTable().'.Usuario','like','%'.$filtro.'%')
                ->orWhere(\DB::RAW('CONCAT(TB_Trabajador.Nombre," ",TB_Trabajador.Apellidos)'),'LIKE', "%".str_replace(" ", "%", $filtro)."%")
                ->orWhere(\DB::RAW('CONCAT(TB_Trabajador.Nombre," ",TB_Trabajador.Apellidos)'),'LIKE', "%".$filtro."%");
            });
        }

        //Ordenar por campo validado en UTILS
        if($sortby) $data = $data->orderBy($sortby['field'],$sortby['type']);
        //Paginar por X cantidad de registros por página
        $extrafields = ["TB_Trabajador.id as Trabajador_id",\DB::raw("CONCAT(TB_Trabajador.Nombre,' ',TB_Trabajador.Apellidos) as Trabajador_Nombre")];
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

    public function reactivate($id)
    {
        $data = $this->getModel()->findOrFail($id);
        $data->Deactivated_at = null;
        return $data->save();
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


    public function change($userid, array $params = array())
    {
        $model = $this->getModel()->findOrFail($userid);
        $model->password = \Hash::make($params['newpassword']);
        $model->Changed_at = \Carbon\Carbon::now();
        if($model->save()) return $model;

        return false;
    }

    public function deactivate($id)
    {
        $data = $this->getModel()->findOrFail($id);
        $data->Deactivated_at = \Carbon\Carbon::now();
        if($data->save()) return $data;

        return false;
    }

    public function reasign($id)
    {
        $data = $this->getModel()->findOrFail($id);
        $data->password = \Hash::make(\miudl\Usuario\Usuario::getDefault());
        $data->Changed_at = null;
        if($data->save()) return $data;

        return false;
    }
}
