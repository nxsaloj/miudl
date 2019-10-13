@extends('layouts.app')
@section('content')

<div class="vue-puestos vue-puestos-detail">
    @include('layouts.headerpage', array('titulo'=>'Editar puesto de trabajo','parent'=>array('href'=>'../','title'=>'Puestos de trabajo')))

    <form action="{{ route('puestos.update',[$puesto->id], false) }}" method="POST">
        @include('rrhh.puestos.partials.form', array('puesto' => $puesto,'edit'=>'true'))
    </form>
</div>
@endsection
