<?php

namespace miudl\Usuario;

use miudl\Trabajador\Trabajador;

class UsuarioTransformer extends \App\Utils\BaseSerializer
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
            'Usuario'      => $data->Usuario,
            'Trabajador'      => ($data->Trabajador_id)? new Trabajador(array("id"=>$data->Trabajador_id,"Nombre"=>$data->Trabajador_Nombre)):null,
            'Deactivated_at'   => $data->Deactivated_at,
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
