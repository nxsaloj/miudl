<?php

namespace miudl\Trabajador;

use miudl\PuestoTrabajo\PuestoTrabajo;

class TrabajadorTransformer extends \App\Utils\BaseSerializer
{
    public $permitido = null;
    public $extra = false;
    public $only = [];
    public $ignore = [];
    function __construct($only=null,$ignore=null,$extra=false) {
        parent::__construct($only,$ignore);
        $this->extra = $extra;
        $this->only = ($only)? $only:[];
        $this->ignore = ($ignore)? $ignore:[];
    }
    public function transform($data)
    {
        $values = array(
            'id'      => $data->id,
            'Codigo'      => $data->Codigo,
            'Nombre'      => $data->Nombre,
            'Apellidos'      => $data->Apellidos,
            'FechaNacimiento' => $data->FechaNacimiento,
            'PuestoTrabajo'      => ($data->PuestoTrabajo_id)? new PuestoTrabajo(array("id"=>$data->PuestoTrabajo_id,"Nombre"=>$data->PuestoTrabajo_Nombre)):null,
            'Deleted_at'   => $data->Deleted_at,
            'created_at'   => $data->created_at,
            'updated_at'   => $data->updated_at
        );
        return self::getSerialized($values);
    }

    public function isAllowed($field)
    {
        return (in_array($field,$this->only) || !in_array($field,$this->ignore));
    }
    public function getField($data,$field)
    {
        if(self::isAllowed($field)){
            return $data[$field];
        }
    }
}
