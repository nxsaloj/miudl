@extends('layouts.app')
@section('content')

<div class="vue-trabajadores vue-trabajadores-create">
    @include('layouts.headerpage', array('titulo'=>'Crear trabajador','parent'=>array('href'=>'./','title'=>'Trabajadores')))

    <form action="{{ route('trabajadores.store',null, false) }}" method="POST">
        @include('rrhh.trabajadores.partials.form')
    </form>
</div>
@endsection
