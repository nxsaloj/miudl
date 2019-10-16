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
        $this->app->bind(
            'miudl\Usuario\UsuarioRepositoryInterface',
            'miudl\Usuario\UsuarioRepository'
        );
        $this->app->bind(
            'miudl\Usuario\UsuarioValidatorInterface',
            'miudl\Usuario\UsuarioValidator'
        );
        $this->app->bind(
            'miudl\Trabajador\TrabajadorRepositoryInterface',
            'miudl\Trabajador\TrabajadorRepository'
        );
        $this->app->bind(
            'miudl\Trabajador\TrabajadorValidatorInterface',
            'miudl\Trabajador\TrabajadorValidator'
        );
        $this->app->bind(
            'miudl\CentroUniversitario\CentroUniversitarioRepositoryInterface',
            'miudl\CentroUniversitario\CentroUniversitarioRepository'
        );
        $this->app->bind(
            'miudl\CentroUniversitario\CentroUniversitarioValidatorInterface',
            'miudl\CentroUniversitario\CentroUniversitarioValidator'
        );
        $this->app->bind(
            'miudl\Facultad\FacultadRepositoryInterface',
            'miudl\Facultad\FacultadRepository'
        );
        $this->app->bind(
            'miudl\Facultad\FacultadValidatorInterface',
            'miudl\Facultad\FacultadValidator'
        );
        $this->app->bind(
            'miudl\Carrera\CarreraRepositoryInterface',
            'miudl\Carrera\CarreraRepository'
        );
        $this->app->bind(
            'miudl\Carrera\CarreraValidatorInterface',
            'miudl\Carrera\CarreraValidator'
        );
        $this->app->bind(
            'miudl\Curso\CursoRepositoryInterface',
            'miudl\Curso\CursoRepository'
        );
        $this->app->bind(
            'miudl\Curso\CursoValidatorInterface',
            'miudl\Curso\CursoValidator'
        );
        $this->app->bind(
            'miudl\Estudiante\EstudianteRepositoryInterface',
            'miudl\Estudiante\EstudianteRepository'
        );
        $this->app->bind(
            'miudl\Estudiante\EstudianteValidatorInterface',
            'miudl\Estudiante\EstudianteValidator'
        );
    }
}
