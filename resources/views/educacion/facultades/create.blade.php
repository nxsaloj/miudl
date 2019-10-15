@extends('layouts.app')
@section('content')

<div class="vue-facultades vue-facultades-create">
    @include('layouts.headerpage', array('titulo'=>'Crear facultad','parent'=>array('href'=>'./','title'=>'Facultades')))

    <form action="{{ route('facultades.store',null, false) }}" method="POST">
        @include('educacion.facultades.partials.form')
    </form>
</div>
@endsection
