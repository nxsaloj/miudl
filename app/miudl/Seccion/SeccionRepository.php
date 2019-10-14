<?php

namespace miudl\Seccion;

use miudl\Base\BaseModel;
use miudl\Base\BaseRepository;

class SeccionRepository extends BaseRepository implements SeccionRepositoryInterface
{
    protected $admins = [1];
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

    public function getSecciones($justParentsSection=false, $parentid=null)
    {
        $data = $this->getModel()->whereNotNull('id');
        if($justParentsSection) $data = $data->whereNull('Icono');
        else $data = $data->whereNotNull('Icono');

        if($parentid) $data = $data->where('idPadre',$parentid);

        $data = $data->orderBy('Prioridad','ASC')->get();

        return $data;
    }

    public function getSeccionesByModel($justParentsSection=false, $model, $parentid=null)
    {
        $allowed = $this->getSeccionesPermitidas($model);
        $data = $this->getModel()->whereIn('id',$allowed);
        if($justParentsSection) $data = $data->whereNull('Icono');
        else $data = $data->whereNotNull('Icono');

        if($parentid) $data = $data->where('idPadre',$parentid);

        $data = $data->orderBy('Prioridad','ASC')->get();

        return $data;
    }

    public function getSeccionesPermitidas(BaseModel $model,$field="id")
    {
        return ($model)? $model->SeccionesPermitidas->pluck($field)->toArray():[];
    }

    public function esPermitido(BaseModel $usuario,$url)
    {
        $usuario_id = $usuario->id;

        if(in_array($usuario_id,$this->admins)) return true;

        $current = explode("/", $url);

        $prev = null;
        foreach($current as $url_part)
        {
            if($url_part != "")
            {
                if($curr = $this->getSeccionUser($usuario,$url_part,$prev))
                {
                    if($curr === true) return true;
                    $prev = $curr;
                }
                else return false;
            }
        }

        return true;
    }

    public function getSeccionUser($usuario,$url, $padre=null)
    {
        if(!self::where(function($query) use ($url, $padre){
            $query->where('Url',$url)->orWhere('Url','/'.$url);
            if($padre) $query = $query->orWhere($this->getModel()->getTable().'..Url',$padre->Url."/".$url)->orWhere($this->getModel()->getTable().'.Url','/'.$padre->Url."/".$url);
        })->first()) return true;

        if($usuario->SeccionesPermitidas->count() > 0)
        {
            $data = $usuario->SeccionesPermitidas()->where(function($query) use ($url){
                $query->where('Url',$url)->orWhere('Url','/'.$url);
            });
            if($padre) $data = $data->where('idPadre',$padre->id)->first();
            else $data = $data->first();

            if(!$data && $padre)
            {
                if(!$padre->secciones()->where(function($query) use ($url){
                    $query->where('Url',$url)->orWhere('Url','/'.$url);
                })->first()) return true;
            }
            return $data;
        }
        else if($trabajador = $usuario->Trabajador)
        {
            $puesto = $trabajador->PuestoTrabajo;

            $data = $puesto->SeccionesPermitidas()->where(function($query) use ($url){
                $query->where('Url',$url)->orWhere('Url','/'.$url);
            });
            if($padre) $data = $data->where('idPadre',$padre->id)->first();
            else $data = $data->first();

            if(!$data && $padre)
            {
                if(!$padre->secciones()->where(function($query) use ($url){
                    $query->where('Url',$url)->orWhere('Url','/'.$url);
                })->first())
                {
                    $other = $puesto->SeccionesPermitidas()->leftJoin($this->getModel()->getTable().'. as APP','APP.id','=',$this->getModel()->getTable().$this->getModel()->getTable().'.idPadre')->where(function($query) use ($url, $padre){
                        $query->where($this->getModel()->getTable().'.Url',$url)->orWhere($this->getModel()->getTable().'.Url','/'.$url)->orWhere($this->getModel()->getTable().'.Url',$padre->Url."/".$url)
                            ->orWhere($this->getModel()->getTable().'.Url','/'.$padre->Url."/".$url);
                    })->where('APP.Url','')->first();

                    return ($other)? $other:null;
                }
            }
            return $data;
        }
        return null;
    }
}
