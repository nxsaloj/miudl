<?php

namespace miudl\PuestoTrabajo;

interface PuestoTrabajoValidatorInterface
{
    public function isValid(array $params);
    public function isValidUpdate(array $params, $id);
}
