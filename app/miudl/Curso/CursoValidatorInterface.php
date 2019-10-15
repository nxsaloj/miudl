<?php

namespace miudl\Curso;

interface CursoValidatorInterface
{
    public function isValid(array $params);
    public function isValidUpdate(array $params, $id);
}
