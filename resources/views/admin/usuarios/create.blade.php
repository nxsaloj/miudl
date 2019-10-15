@extends('layouts.app') 
@section('content')

<div class="vue-usuarios vue-usuarios-create">
    @include('layouts.headerpage', array('titulo'=>'Crear miembro de equipo','parent'=>array('href'=>'./','title'=>'Usuarios')))
    
    <form action="{{ route('usuarios.store',null, false) }}" method="POST">
        @include('admin.usuarios.partials.form')
    </form>
</div>
@endsection