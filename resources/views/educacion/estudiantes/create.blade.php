@extends('layouts.app')
@section('content')

<div class="vue-estudiantes vue-estudiantes-create">
    @include('layouts.headerpage', array('titulo'=>'Crear estudiantes','parent'=>array('href'=>'./','title'=>'Estudiantes')))

    <form action="{{ route('estudiantes.store',null, false) }}" method="POST">
        @include('educacion.estudiantes.partials.form')
    </form>
</div>
@endsection
