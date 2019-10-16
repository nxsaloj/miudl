<?php

namespace miudl\Inscripcion;

class InscripcionTransformer extends \App\Utils\BaseSerializer
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
            'CentroUniversitario'      => ($data->CentroUniversitario_id)? new miudl\CentroUniversitario\CentroUniversitario(array("id"=>$data->CentroUniversitario_id,"Nombre"=>$data->CentroUniversitario_Nombre)):null,
            'Estudiante'      => ($data->Estudiante_id)? new miudl\Estudiante\Estudiante(array("id"=>$data->Estudiante_id,"Nombre"=>$data->Estudiante_Nombre)):null,
            'Carrera'      => ($data->Carrera_id)? new miudl\Carrera\Carrera(array("id"=>$data->Carrera_id,"Nombre"=>$data->Carrera_Nombre)):null,
            'Fecha'      => $data->Fecha,
            'Created_at'   => $data->Created_at,
            'Updated_at'   => $data->Updated_at
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
