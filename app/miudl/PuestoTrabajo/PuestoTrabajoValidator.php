<?php

namespace miudl\PuestoTrabajo;

use Validator;

class PuestoTrabajoValidator implements PuestoTrabajoValidatorInterface
{
    protected $mensajes = array(
        'Nombre.required' => 'El campo Nombre no debe estar vacío',
        'Codigo.required' => 'El campo Codigo no debe estar vacío',
        'Codigo.unique' => 'El codigo especificado ya ha sido utilizado anteriormente',
    );

    public function isValid(array $params= array())
    {
        $reglas =  array(
            'Codigo' => 'required|unique:TB_PuestoTrabajo',
            'Nombre' => 'required'
        );

        $datos = array(
            'Nombre' => $params['nombre'],
            'Codigo' => $params['codigo']
        );

        $validator = Validator::make($datos, $reglas, $this->mensajes);

        if ($validator->fails()){
            $respuesta['mensaje'] = 'Por favor verifique los datos ingresados';
            $respuesta['errors'] = $validator;
            $respuesta['error']   = true;
            return $respuesta;
        }

        return $datos;
    }

    public function isValidUpdate(array $params, $id)
    {
        $reglas =  array(
            'Codigo' => 'required|unique:TB_PuestoTrabajo,Codigo,'.$id.',id',
            'Nombre' => 'required'
        );

        $datos = array(
            'Nombre' => $params['nombre'],
            'Codigo' => $params['codigo']
        );

        $validator = Validator::make($datos, $reglas, $this->mensajes);

        if ($validator->fails()){
            $respuesta['mensaje'] = 'Por favor verifique los datos ingresados';
            $respuesta['errors'] = $validator;
            $respuesta['error']   = true;
            return $respuesta;
        }

        return $datos;
    }
}
