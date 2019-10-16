<?php

namespace miudl\Estudiante;

class EstudianteTransformer extends \App\Utils\BaseSerializer
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
            'Carne'      => $data->Carne,
            'Nombre'      => $data->Nombre,
            'Apellidos'      => $data->Apellidos,
            'FechaNacmiento'      => $data->FechaNacimiento,
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
