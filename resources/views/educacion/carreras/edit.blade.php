@extends('layouts.app')
@section('content')

<div class="vue-carreras vue-carreras-detail">
    @include('layouts.headerpage', array('titulo'=>'Editar carreras','parent'=>array('href'=>'../','title'=>'Carreras')))

    <form action="{{ route('carreras.update',[$carrera->id], false) }}" method="POST">
        @include('educacion.carreras.partials.form', array('carrera' => $carrera,'edit'=>'true'))
    </form>
</div>
@endsection
