<?php

namespace miudl\Carrera;

interface CarreraValidatorInterface
{
    public function isValid(array $params);
    public function isValidUpdate(array $params, $id);
}
