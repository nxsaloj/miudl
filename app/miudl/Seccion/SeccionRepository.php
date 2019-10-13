<?php

namespace miudl\Seccion;

use miudl\Base\BaseModel;
use miudl\Base\BaseRepository;

class SeccionRepository extends BaseRepository implements SeccionRepositoryInterface
{
    public function getModel()
    {
        return new Seccion;
    }

    public function findOrFail($id)
    {
        return $this->getModel()->findOrFail($id);
    }

    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }

    public function getSeccionesByModel($includeParentSection=false, $modelid)
    {
        $allowed = $this->getSeccionesPermitidas($modelid);
        $data = $this->getModel()->whereNull('Extra')->whereIn('SeccionApp_id',$allowed);
        if($includeParentSection) $data = $data->whereNull('Icono');
        $data = $data->orderBy('Prioridad','ASC')->get();

        return $data;
    }

    public function getSeccionesPermitidas(BaseModel $model,$field="Seccion_id")
    {
        return ($model)? $model->SeccionesPermitidas->pluck($field)->toArray():[];
    }
}
