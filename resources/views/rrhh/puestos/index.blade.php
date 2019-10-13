@extends('layouts.app')
@section('content')

<div class="vue-puestos">
    @include('layouts.headerpage', array('titulo'=>'Puestos de trabajo'))

    @if(Session::get('info'))
        @php
            $toast = "'".Session::get('info')."'";
            Session::forget('info');
        @endphp
        <toastel :mensaje="{{$toast}}"></toastel>
    @endif

    <div class="row grid-margin" v-show="show">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group form-group-search d-flex search-field">
                    <input type="text" class="form-control " v-model="filtro" name="puesto" placeholder="Busca un puesto de trabajo" v-on:keyup.enter="getPuestosTrabajo()">
                    <button class="btn btn-primary ml-3" @click.prevent="getPuestosTrabajo()">Buscar</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <a href=" {{ route('puestos.create') }}  " class="btn btn-success" style="margin: 10px 0; ">Agregar nuevo puesto de trabajo</a>
    <a href="#" @click.prevent="show = !show" class="btn btn-primary" style="font-size:18px; padding: .4rem 1rem;"><span class="mdi mdi-magnify"></span></a>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Todos los Puestos de Trabajo</div>
                <div class="card-body">
                    <template>
                        <div class="tablavue tablavue-header">
                            <div class="item-row">
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Codigo')">Código <i v-if="orderby.field == 'Codigo'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Nombre')">Nombre del puesto <i v-if="orderby.field == 'Nombre'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col"><span class="item-tag"></span></div>
                            </div>
                        </div>

                        <div class="tablavue">
                            <div class="item-row" v-for="(puesto, index) in puestos">
                                <div class="item-col">
                                    <span class="item-title">Código: </span>
                                    <span class="item-tag">@{{puesto.Codigo}}</span></div>
                                <div class="item-col">
                                    <span class="item-title">Nombre: </span>
                                    <span class="item-tag">@{{puesto.Nombre}}</span>
                                </div>
                                <div class="item-col item-col-options white">
                                    <span class="item-tag">
                                        <a href="#"class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acciones </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" :href="url+puesto.id+'/edit'">Editar</a>
                                            <a class="dropdown-item" @click.prevent="confirmDialog(puesto)">Eliminar</a>
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
</div>
@endsection
