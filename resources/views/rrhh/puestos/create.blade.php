@extends('layouts.app')
@section('content')

<div class="vue-puestos vue-puestos-create">
    @include('layouts.headerpage', array('titulo'=>'Crear puesto de trabajo','parent'=>array('href'=>'./','title'=>'Puestos de trabajo')))

    <form action="{{ route('puestos.store',null, false) }}" method="POST">
        @include('rrhh.puestos.partials.form')
    </form>
</div>
@endsection
