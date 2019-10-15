@extends('layouts.app')
@section('content')

<div class="vue-centrosuniversitarios vue-centrosuniversitarios-detail">
    @include('layouts.headerpage', array('titulo'=>'Editar centro universitario','parent'=>array('href'=>'../','title'=>'Centros universitarios')))

    <form action="{{ route('centrosuniversitarios.update',[$centrouniversitario->id], false) }}" method="POST">
        @include('educacion.centrosuniversitarios.partials.form', array('centrouniversitario' => $centrouniversitario,'edit'=>'true'))
    </form>
</div>
@endsection
