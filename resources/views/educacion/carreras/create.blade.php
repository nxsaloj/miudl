@extends('layouts.app')
@section('content')

<div class="vue-carreras vue-carreras-create">
    @include('layouts.headerpage', array('titulo'=>'Crear carrera','parent'=>array('href'=>'./','title'=>'Carreras')))

    <form action="{{ route('carreras.store',null, false) }}" method="POST">
        @include('educacion.carreras.partials.form')
    </form>
</div>
@endsection
