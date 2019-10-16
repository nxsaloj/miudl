@extends('layouts.app')
@section('content')

<div class="vue-inscripcion">
    @include('layouts.headerpage', array('titulo'=>'Inscripciones'))

    @if(Session::get('info'))
        @php
            $toast = "'".Session::get('info')."'";
            Session::forget('info');
        @endphp
        <toastel :mensaje="{{$toast}}"></toastel>
    @endif

    <div class="row grid-margin" v-if="show">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group form-group-search d-flex search-field">
                    <input type="text" class="form-control " v-model="filtro" name="inscripcion" placeholder="Busca un inscripcion" v-on:keyup.enter="getInscripciones()">
                    <button class="btn btn-primary ml-3" @click.prevent="getInscripciones()">Buscar</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="form-group">
        <a href=" {{ route('inscripcion.generar') }}  " class="btn btn-success" style="margin: 10px 0; ">Generar formulario</a>
        <a href=" {{ route('inscripcion.register') }}  " class="btn btn-success" style="margin: 10px 0; ">Registrar estudiante</a>
        <a href=" {{ route('inscripcion.create') }}  " class="btn btn-success" style="margin: 10px 0; ">Asignar cursos</a>
        <a href="#" @click.prevent="show = !show" class="btn btn-primary mdi-reduce-padding"><i class="mdi mdi-magnify mdi-18px"></i></a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Todas las inscripciones
                </div>
                <div class="card-body">
                    <template>
                        <div class="tablavue tablavue-header">
                            <div class="item-row">
                                <div class="item-col"><span class="item-tag"></span></div>
                            </div>
                        </div>

                        <div class="tablavue">
                            <div class="item-row" v-for="(inscripcion, index) in inscripciones">
                                <div class="item-col item-col-options white">
                                    <span class="item-tag">
                                        <a href="#"class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acciones </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" :href="url+inscripcion.id+'/edit'">Editar</a>
                                            <a class="dropdown-item" @click.prevent="confirmDialog(inscripcion)">Eliminar</a>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>


                </div>
                <div class="card-footer">
                    <paginador :pagination="pagination" v-on:pagechange="cambiarPagina()" v-bind:value="pagination.current_page"></paginador>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
