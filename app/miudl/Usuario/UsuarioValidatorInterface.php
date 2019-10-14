<?php

namespace miudl\Usuario;

interface UsuarioValidatorInterface
{
    public function isValid(array $params);
    public function isValidUpdate(array $params, $id);
}
