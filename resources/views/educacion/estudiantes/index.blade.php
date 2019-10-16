@extends('layouts.app')
@section('content')

<div class="vue-estudiantes">
    @include('layouts.headerpage', array('titulo'=>'Estudiantes'))

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
                    <input type="text" class="form-control " v-model="filtro" name="estudiante" placeholder="Busca un estudiante" v-on:keyup.enter="getEstudiantes()">
                    <button class="btn btn-primary ml-3" @click.prevent="getEstudiantes()">Buscar</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="form-group">
        <a href=" {{ route('inscripcion.create') }}  " class="btn btn-success" style="margin: 10px 0; ">Inscribir estudiante</a>
        <a href="#" @click.prevent="show = !show" class="btn btn-primary mdi-reduce-padding"><i class="mdi mdi-magnify mdi-18px"></i></a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Todos los estudiantes
                </div>
                <div class="card-body">
                    <template>
                        <div class="tablavue tablavue-header">
                            <div class="item-row">
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Carne')">No. Carné <i v-if="orderby.field == 'Carne'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Nombre')">Nombre <i v-if="orderby.field == 'Nombre'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Apellidos')">Apellidos <i v-if="orderby.field == 'Apellidos'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('FechaNacimiento')">Fecha de nacimiento <i v-if="orderby.field == 'FechaNacimiento'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col"><span class="item-tag"></span></div>
                            </div>
                        </div>

                        <div class="tablavue">
                            <div class="item-row" v-for="(estudiante, index) in estudiantes">
                                <div class="item-col">
                                    <span class="item-title">No. Carné: </span>
                                    <span class="item-tag">@{{estudiante.Carne}}</span></div>
                                <div class="item-col">
                                    <span class="item-title">Nombre: </span>
                                    <span class="item-tag">@{{estudiante.Nombre}}</span>
                                </div>
                                <div class="item-col">
                                    <span class="item-title">Apellidos: </span>
                                    <span class="item-tag">@{{estudiante.Apellidos}}</span>
                                </div>
                                <div class="item-col">
                                    <span class="item-title">Fecha de nacimiento: </span>
                                    <span class="item-tag">@{{estudiante.FechaNacimiento | formatDate('DD/MM/YYYY')}}</span>
                                </div>
                                <div class="item-col item-col-options white">
                                    <span class="item-tag">
                                        <a href="#"class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acciones </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" :href="url+estudiante.id+'/edit'">Editar</a>
                                            <a class="dropdown-item" @click.prevent="confirmDialog(estudiante)">Eliminar</a>
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
