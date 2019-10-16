@extends('layouts.app')
@section('content')

<div class="vue-cursos vue-cursos-create">
    @include('layouts.headerpage', array('titulo'=>'Crear cursos','parent'=>array('href'=>'./','title'=>'Cursos')))

    <form action="{{ route('cursos.store',null, false) }}" method="POST">
        @include('educacion.cursos.partials.form')
    </form>
</div>
@endsection
