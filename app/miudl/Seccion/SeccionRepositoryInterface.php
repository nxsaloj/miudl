<?php

namespace miudl\Seccion;

use miudl\Base\BaseModel;

interface SeccionRepositoryInterface
{
    public function findOrFail($id);
    public function delete($entity);
    public function getSeccionesByModel($justParentsSection, $model, $parentid=null);
}
