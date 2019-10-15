@extends('layouts.app')
@section('content')

<div class="vue-facultades vue-facultades-detail">
    @include('layouts.headerpage', array('titulo'=>'Editar facultades','parent'=>array('href'=>'../','title'=>'Facultades')))

    <form action="{{ route('facultades.update',[$facultad->id], false) }}" method="POST">
        @include('educacion.facultades.partials.form', array('facultad' => $facultad,'edit'=>'true'))
    </form>
</div>
@endsection
