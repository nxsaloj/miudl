<?php

namespace miudl\Carrera;

use Validator;

class CarreraValidator implements CarreraValidatorInterface
{
    protected $mensajes = array(
        'Nombre.required' => 'El campo Nombre no debe estar vacío',
        'Apellidos.required' => 'El campo Apellidos no debe estar vacío',
        'Ciclos.required' => 'El campo Ciclo no debe estar vacío',
        'Facultad_id.required' => 'El campo Facultad no debe estar vacío',
        'Codigo.required' => 'El campo Codigo no debe estar vacío',
        'Codigo.unique' => 'El codigo especificado ya ha sido utilizado anteriormente',
    );

    public function isValid(array $params= array())
    {
        $reglas =  array(
            'Codigo' => 'required|unique:TB_Carrera',
            'Nombre' => 'required',
            'Ciclos'=>'required',
            'Facultad_id' => 'required'
        );
        //\Log::debug('Puesto P ' . print_r($params['facultad'], true));
        //$facultad = \App\Models\Utils::getVueParam($params,"facultad","Facultad_id");
        //\Log::debug('Puesto ' . $facultad);
        $datos = array(
            'Codigo' => $params['codigo'],
            'Nombre' => $params['nombre'],
            'Ciclos' => $params['ciclos'],
            'Facultad_id' => isset($params['facultad'])? $params['facultad']:null,
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
            'Codigo' => 'required|unique:TB_Carrera,Codigo,'.$id.',id',
            'Nombre' => 'required',
            'Ciclos'=>'required',
            'Facultad_id' => 'required'
        );
        //\Log::debug('Puesto P ' . print_r($params['facultad'], true));
        //$facultad = \App\Models\Utils::getVueParam($params,"facultad","Facultad_id");
        //\Log::debug('Puesto ' . $facultad);
        $datos = array(
            'Codigo' => $params['codigo'],
            'Nombre' => $params['nombre'],
            'Ciclos' => $params['ciclos'],
            'Facultad_id' => isset($params['facultad'])? $params['facultad']:null,
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
