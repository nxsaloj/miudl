@extends('layouts.app')
@section('content')

<div class="vue-centrosuniversitarios vue-centrosuniversitarios-create">
    @include('layouts.headerpage', array('titulo'=>'Crear centro universitario','parent'=>array('href'=>'./','title'=>'Centros universitarios')))

    <form action="{{ route('centrosuniversitarios.store',null, false) }}" method="POST">
        @include('educacion.centrosuniversitarios.partials.form')
    </form>
</div>
@endsection
