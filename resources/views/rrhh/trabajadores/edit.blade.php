@extends('layouts.app')
@section('content')

<div class="vue-trabajadores vue-trabajadores-detail">
    @include('layouts.headerpage', array('titulo'=>'Editar trabajadores','parent'=>array('href'=>'../','title'=>'Trabajadores')))

    <form action="{{ route('trabajadores.update',[$trabajador->id], false) }}" method="POST">
        @include('rrhh.trabajadores.partials.form', array('trabajador' => $trabajador,'edit'=>'true'))
    </form>
</div>
@endsection
