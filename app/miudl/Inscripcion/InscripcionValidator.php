<?php

namespace miudl\Inscripcion;

use Validator;

class InscripcionValidator implements InscripcionValidatorInterface
{
    protected $mensajes = array(
        'Estudiante_id.required' => 'El campo Estudiante no debe estar vacío',
        'Carrera_id.required' => 'El campo Carrera no debe estar vacío',
        'CentroUniversitario_id.required' => 'El campo Centro universitario no debe estar vacío',
    );

    public function isValid(array $params= array())
    {
        $reglas =  array(
            'Carrera_id' => 'required',
            'CentroUniversitario_id' => 'required',
        );
        //\Log::debug('Puesto P ' . print_r($params['puesto'], true));
        //$puesto = \App\Models\Utils::getVueParam($params,"puesto","PuestoTrabajo_id");
        //\Log::debug('Puesto ' . $puesto);
        $datos = array(
            'CentroUniversitario_id' => (isset($params['centrouniversitario']))? $params['centrouniversitario']:null,
            'Carrera_id' => (isset($params['carrera']))? $params['carrera']:null,
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
