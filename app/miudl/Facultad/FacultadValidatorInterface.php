<?php

namespace miudl\Facultad;

interface FacultadValidatorInterface
{
    public function isValid(array $params);
    public function isValidUpdate(array $params, $id);
}
