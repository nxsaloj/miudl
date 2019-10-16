<?php

namespace miudl\Estudiante;

use Validator;

class EstudianteValidator implements EstudianteValidatorInterface
{
    protected $mensajes = array(
        'Nombre.required' => 'El campo Nombre no debe estar vacío',
        'Apellidos.required' => 'El campo Apellidos no debe estar vacío',
        'Codigo.required' => 'El campo Codigo no debe estar vacío',
        'Codigo.unique' => 'El codigo especificado ya ha sido utilizado anteriormente',
    );

    public function isValid(array $params= array())
    {
        $reglas =  array(
            'Codigo' => 'required|unique:TB_Estudiante',
            'Nombre' => 'required'
        );
        //\Log::debug('Puesto P ' . print_r($params['puesto'], true));
        //$puesto = \App\Models\Utils::getVueParam($params,"puesto","PuestoTrabajo_id");
        //\Log::debug('Puesto ' . $puesto);
        $datos = array(
            'Codigo' => $params['codigo'],
            'Nombre' => $params['nombre'],
            'Carreras' => (isset($params['carreras']))? $params['carreras']:null,
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
            'Codigo' => 'required|unique:TB_Estudiante,Codigo,'.$id.',id',
            'Nombre' => 'required'
        );

        $datos = array(
            'Codigo' => $params['codigo'],
            'Nombre' => $params['nombre'],
            'Carreras' => $params['carreras'],
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
