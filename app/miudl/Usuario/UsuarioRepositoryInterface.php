<?php

namespace miudl\Usuario;

interface UsuarioRepositoryInterface
{
    public function findOrFail($id);
    public function search(array $params = array(), $paginate = false);
    public function create(array $params);
    public function reactivate(array $params);
    public function update($id, array $data);
    public function delete($id);
    public function isDeleted($entity);
    public function change($userid, array $params = array());
}
