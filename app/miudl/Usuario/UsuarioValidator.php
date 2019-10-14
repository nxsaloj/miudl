<?php

namespace miudl\Usuario;

use Validator;

class UsuarioValidator implements UsuarioValidatorInterface
{
    public $mensajes = array(
        'user.required' => 'El campo usuario no debe estar vacío',
        'password.required' => 'El campo contraseña no debe estar vacío',
        'password.min' => 'El campo contraseña debe contener al menos 5 caracteres',
        'newpassword.min' => 'El campo contraseña debe contener al menos 5 caracteres',
        'newpassword.required' => 'El campo contraseña nueva no debe estar vacío',
        'newpassword.confirmed' => 'Las contraseñas no coinciden',
        'newpassword_confirmation.required' => 'El campo repetir contraseña nueva no debe estar vacío',
        'Usuario.required' => 'El campo usuario no debe estar vacío'
    );

    public function isValid(array $params= array())
    {
        $reglas =  array(
            'Usuario' => 'required|unique:TB_Usuario',
            'password' => 'required'
        );
        $datos = array(
            'Usuario' => $params['Usuario'],
            'password' => Hash::make($params['password'])
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

    public function isValidLogin(array $params= array())
    {
        $reglas =  array(
            'Usuario' => 'required',
            'password' => 'required'
        );

        $datos = array(
            'Usuario' => $params['user'],
            'password' => $params['password'],
            'Recordar' => $params['recordar']
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
            'Codigo' => 'required|unique:TB_Usuario,Codigo,'.$id.',Usuario_id',
            'Nombre' => 'required',
            'PuestoTrabajo_id' => 'required'
        );

        $datos = array(
            'Codigo' => $params['codigo'],
            'Nombre' => $params['nombre'],
            'Apellidos' => $params['apellidos']
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
