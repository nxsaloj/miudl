@extends('layouts.app') 
@section('content')

<div class="vue-usuarios vue-usuarios-detail">
    @include('layouts.headerpage', array('titulo'=>'Editar miembro de equipo','parent'=>array('href'=>'../','title'=>'Usuarios')))
    
    <form action="{{ route('usuarios.update',[$usuario->Usuario_id], false) }}" method="POST">
        @include('admin.usuarios.partials.form', array('usuario' => $usuario,'edit'=>'true'))
    </form>
</div>
@endsection