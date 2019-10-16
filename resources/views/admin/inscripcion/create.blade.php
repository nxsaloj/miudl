@extends('layouts.app')
@section('content')

<div class="vue-inscripcion vue-inscripcion-create">
    @include('layouts.headerpage', array('titulo'=>'Inscribir estudiante','parent'=>array('href'=>'./','title'=>'Inscripciones')))

    <form action="{{ route('inscripcion.store',null, false) }}" method="POST">
        @include('admin.inscripcion.partials.form')
    </form>
</div>
@endsection
