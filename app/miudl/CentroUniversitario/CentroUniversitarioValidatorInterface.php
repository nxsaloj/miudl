<?php

namespace miudl\CentroUniversitario;

interface CentroUniversitarioValidatorInterface
{
    public function isValid(array $params);
    public function isValidUpdate(array $params, $id);
}
