@extends('layouts.app')
@section('content')

<div class="vue-usuarios">
    @include('layouts.headerpage', array('titulo'=>'Usuarios'))

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
                    <input type="text" class="form-control " v-model="filtro" name="usuario" placeholder="Busca un usuario" v-on:keyup.enter="getUsuarios()">
                    <button class="btn btn-primary ml-3" @click.prevent="getUsuarios()">Buscar</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--<a href=" {{ route('usuarios.create') }}  " class="btn btn-success" style="margin: 10px 0; ">Agregar nuevo usuario</a>-->
    <a href="#" @click.prevent="show = !show" class="btn btn-primary" style="font-size:18px; padding: .4rem 1rem;"><span class="mdi mdi-magnify"></span></a>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Todos los usuarios
                </div>
                <div class="card-body">
                    <template>
                        <div class="tablavue tablavue-header">
                            <div class="item-row">
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Usuario')">Usuario <i v-if="orderby.field == 'Usuario'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Trabajador')">Trabajador <i v-if="orderby.field == 'Trabajador'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col th-pointer" @click.prevent="toggleOrderBy('Deactivated_at')">Activo <i v-if="orderby.field == 'Deactivated_at'" class="mdi position-absolute ml-1" v-bind:class="{ 'mdi-sort-ascending': orderby.type, 'mdi-sort-descending': !orderby.type }"></i></div>
                                <div class="item-col"><span class="item-tag"></span></div>
                            </div>
                        </div>

                        <div class="tablavue">
                            <div class="item-row" v-for="(usuario, index) in usuarios">
                                <div class="item-col">
                                    <span class="item-title">Usuario: </span>
                                    <span class="item-tag">@{{usuario.Usuario}}</span>
                                </div>
                                <div class="item-col">
                                    <span class="item-title">Trabajador: </span>
                                    <span class="item-tag">@{{(usuario.Trabajador)? usuario.Trabajador.Nombre:'-'}}</span>
                                </div>
                                <div class="item-col">
                                    <span class="item-tag">
                                        <i v-if="!usuario.Deactivated_at" class="mdi mdi-check-circle" style="color: rgb(0,150,250)"></i>
                                        <i v-else class="mdi mdi-close-circle" style="color: rgb(250,50,50)"></i>
                                    </span>
                                </div>
                                <div class="item-col item-col-options white">
                                    <span class="item-tag">
                                        <a href="#"class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acciones </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" :href="url+usuario.id">Ver detalles</a>
                                            <a class="dropdown-item" @click.prevent="confirmReset(usuario)">Reasignar contraseña</a>
                                            <a v-if="usuario.Deactivated_at" class="dropdown-item" @click.prevent="confirmDeactivate(usuario)">Reactivar</a>
                                            <a v-else class="dropdown-item" @click.prevent="confirmDeactivate(usuario)">Desactivar</a>
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
