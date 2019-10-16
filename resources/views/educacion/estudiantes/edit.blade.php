@extends('layouts.app')
@section('content')

<div class="vue-estudiantes vue-estudiantes-detail">
    @include('layouts.headerpage', array('titulo'=>'Editar estudiantes','parent'=>array('href'=>'../','title'=>'Estudiantes')))

    <form action="{{ route('estudiantes.update',[$estudiante->id], false) }}" method="POST">
        @include('educacion.estudiantes.partials.form', array('estudiante' => $estudiante,'edit'=>'true'))
    </form>
</div>
@endsection
