<?php

namespace miudl\Logging;

interface LogRepositoryInterface
{
    public function findOrFail($id);
    public function search(array $params = array(), $paginate = false);
    public function create(array $params);
}
