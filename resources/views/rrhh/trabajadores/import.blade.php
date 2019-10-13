@extends('layouts.app') 
@section('content')

<div class="vue-empleados vue-empleados-file">
    @include('layouts.headerpage', array('titulo'=>'Importar miembros de equipo','parent'=>array('href'=>'./','title'=>'Miembros de equipo')))
    <div class="row grid-margin">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Seleccione el archivo .csv de miembros de equipo</h2>
                    <form action="{{ route('empleados.import',null, false) }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" class="dropify" name="csv" accept=".csv"/>
                        <button type="submit" class="btn btn-primary submit">Importar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection