<?php

namespace miudl\Trabajador;

interface TrabajadorValidatorInterface
{
    public function isValid(array $params);
    public function isValidUpdate(array $params, $id);
}
