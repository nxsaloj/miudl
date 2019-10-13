<?php

namespace miudl\Providers;

use \Illuminate\Support\ServiceProvider;

class EloquentRepositoryProvider extends ServiceProvider{
    public function register()
    {
        $this->app->bind(
            'miudl\Logging\LogRepositoryInterface',
            'miudl\Logging\LogRepository'
        );
        $this->app->bind(
            'miudl\Seccion\SeccionRepositoryInterface',
            'miudl\Seccion\SeccionRepository'
        );
        $this->app->bind(
            'miudl\PuestoTrabajo\PuestoTrabajoRepositoryInterface',
            'miudl\PuestoTrabajo\PuestoTrabajoRepository'
        );
        $this->app->bind(
            'miudl\PuestoTrabajo\PuestoTrabajoValidatorInterface',
            'miudl\PuestoTrabajo\PuestoTrabajoValidator'
        );
    }
}
