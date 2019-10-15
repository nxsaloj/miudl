@extends('layouts.app')
@section('content')

<div class="vue-usuarios vue-usuarios-detail">
    @include('layouts.headerpage', array('titulo'=>'Detalle de usuario','parent'=>array('href'=>'./','title'=>'Usuarios')))
    @if(isset($edit))
    <input name="_method" type="hidden" value="PUT">
    @endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input v-if="showextra" type="hidden" name="session_keep" id="extra-action">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Usuario:</label>
                    <div class="col-sm-4">
                        {{$usuario->Usuario}}
                    </div>
                </div>
                @if($usuario->Trabajador)
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Trabajador</label>
                    <div class="col-sm-8">
                        {{$usuario->Trabajador->Nombre}} {{$usuario->Trabajador->Apellidos}}
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Activo:</label>
                    <div class="col-sm-8">
                        @if(!$usuario->Deactivated_at)
                        <i class="mdi mdi-check-circle" style="color: rgb(0,150,250)"></i>
                        @else
                        <i class="mdi mdi-close-circle" style="color: rgb(250,50,50)"></i>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" @click.prevent="confirmReset({{$usuario}},$event)" class="btn btn-primary submit">Reasignar contraseña</button>
                <button type="button" @click.prevent="confirmDeactivate({{$usuario}},$event)" class="btn btn-primary submit">
                    @if($usuario->Deactivated_at)
                        Reactivar
                    @else
                        Desactivar
                    @endif
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
