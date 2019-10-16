<?php

namespace miudl\Estudiante;

use Validator;

class EstudianteValidator implements EstudianteValidatorInterface
{
    protected $mensajes = array(
        'Nombre.required' => 'El campo Nombre no debe estar vacío',
        'Apellidos.required' => 'El campo Apellidos no debe estar vacío',
        'Carne.required' => 'El campo No. de Carne no debe estar vacío',
        'Carne.unique' => 'El codigo especificado ya ha sido utilizado anteriormente',
    );

    public function isValid(array $params= array())
    {
        $reglas =  array(
            'Carne' => 'required|unique:TB_Estudiante',
            'Nombre' => 'required',
            'Apellidos' => 'required',
        );
        //\Log::debug('Puesto P ' . print_r($params['puesto'], true));
        //$puesto = \App\Models\Utils::getVueParam($params,"puesto","PuestoTrabajo_id");
        //\Log::debug('Puesto ' . $puesto);
        $datos = array(
            'Carne' => $params['Carne'],
            'Nombre' => $params['nombre'],
            'Apellidos' => $params['apellidos'],
            'FechaNacimiento'=>$params['fechanacimiento']

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
            'Carne' => 'required|unique:TB_Estudiante,Carne,'.$id.',id',
            'Nombre' => 'required',
            'Apellidos' => 'required',
        );

        $datos = array(
            'Carne' => $params['Carne'],
            'Nombre' => $params['nombre'],
            'Apellidos' => $params['apellidos'],
            'FechaNacimiento'=>$params['fechanacimiento']
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
