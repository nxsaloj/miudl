<?php

namespace miudl\Estudiante;

interface EstudianteValidatorInterface
{
    public function isValid(array $params);
    public function isValidUpdate(array $params, $id);
}
