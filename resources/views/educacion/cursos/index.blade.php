@extends('layouts.app')
@section('content')

<div class="vue-cursos">
    @include('layouts.headerpage', array('titulo'=>'Cursos'))

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
                    <input type="text" class="form-control " v-model="filtro" name="curso" placeholder="Busca un curso" v-on:keyup.enter="getCursos()">
                    <button class="btn btn-primary ml-3" @click.prevent="getCursos()">Buscar</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="form-group">
        <a href=" {{ route('cursos.create') }}  " class="btn btn-success" style="margin: 10px 0; ">Agregar nuevo curso</a>
        <a href="#" @click.prevent="show = !show" class="btn btn-primary mdi-reduce-padding"><i class="mdi mdi-magnify mdi-18px"></i></a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Todos los cursos
                </div>
                <div class="card-body">
                    <template>
                        <div class="tablavue tablavue-header">
                            <div class="item-row">
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Codigo')">Código <i v-if="orderby.field == 'Codigo'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Nombre')">Nombre <i v-if="orderby.field == 'Nombre'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col"><span class="item-tag"></span></div>
                            </div>
                        </div>

                        <div class="tablavue">
                            <div class="item-row" v-for="(curso, index) in cursos">
                                <div class="item-col">
                                    <span class="item-title">Código: </span>
                                    <span class="item-tag">@{{curso.Codigo}}</span></div>
                                <div class="item-col">
                                    <span class="item-title">Nombre: </span>
                                    <span class="item-tag">@{{curso.Nombre}}</span>
                                </div>
                                <div class="item-col item-col-options white">
                                    <span class="item-tag">
                                        <a href="#"class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acciones </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" :href="url+curso.id+'/edit'">Editar</a>
                                            <a class="dropdown-item" @click.prevent="confirmDialog(curso)">Eliminar</a>
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
