<?php
namespace App\Http\Composers;

use Illuminate\Contracts\View\View;

class AppComposer {
    protected $controller;
    public function __construct(\App\Http\Controllers\SeccionController $_controller)
    {
        $this->controller = $_controller;
    }

    public function compose(View $view)
    {
        $secciones = $this->controller->getSeccionesByModel();
        $view->with('secciones', $secciones);
    }
}
