@extends('layouts.app')
@section('content')

<div class="vue-cursos vue-cursos-detail">
    @include('layouts.headerpage', array('titulo'=>'Editar cursos','parent'=>array('href'=>'../','title'=>'Cursos')))

    <form action="{{ route('cursos.update',[$curso->id], false) }}" method="POST">
        @include('educacion.cursos.partials.form', array('curso' => $curso,'edit'=>'true'))
    </form>
</div>
@endsection
