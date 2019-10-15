@extends('layouts.app') 
@section('content')

<div class="vue-logs-admin">
    @include('layouts.headerpage', array('titulo'=>'Registro de actividad'))

    @if(Session::get('info'))
        @php
            $toast = "'".Session::get('info')."'";
            Session::forget('info');
        @endphp
        <toastel :mensaje="{{$toast}}"></toastel>
    @endif
    
    <template>
    <div class="row grid-margin">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group col-md-12 col-lg-12 col-sm-12">
                        <div class="row">
                            <div class="form-group col-md-12 col-lg-12 col-sm-12">
                                <label class="form-control-label">Buscar</label>                                
                                <div class="form-group form-group-search d-flex search-field">
                                    <input type="text" class="form-control " v-model="filtro" name="recurso" placeholder="Buscar una coincidencia" v-on:keyup.enter="getLogs()">
                                </div>
                            </div>
                        </div>
                    </div>         
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary ml-3" @click.prevent="limpiar()"><i class="mdi mdi-broom btn-label"></i></button>
                    <button class="btn btn-primary ml-3" @click.prevent="getLogs()">Buscar</button>
                </div>
            </div>
        </div>
    </div>    
    
    </template>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    
                </div>
                <div class="card-body">
                        <template>
                            <div class="tablavue tablavue-header">
                                <div class="item-row">
                                    <div class="item-col"><span class="item-tag">Usuario</span></div>
                                    <div class="item-col"><span class="item-tag">Tipo</span></div>
                                    <div class="item-col"><span class="item-tag">Actividad</span></div>
                                    <div class="item-col"><span class="item-tag">Fecha</span></div>
                                </div>                            
                            </div>

                            <div class="tablavue">
                                <div class="item-row" v-for="(log, index) in logs">
                                    <div class="item-col">
                                        <span class="item-title">Usuario: </span>
                                        <span class="item-tag" v-if="log.Usuario"> @{{(log.Usuario.Estudiante)? log.Usuario.Estudiante.Nombre+" "+log.Usuario.Estudiante.Apellidos:((log.Usuario.Empleado)?(log.Usuario.Empleado.Nombre+" "+log.Usuario.Empleado.Apellidos):"-")}}</span>
                                    </div>

                                    <div class="item-col">
                                        <span class="item-title">Tipo: </span>
                                        <span class="item-tag">@{{ log.Tipo }}</span>
                                    </div>

                                    <div class="item-col">
                                        <span class="item-title">Actividad: </span>
                                        <span class="item-tag">@{{ log.Actividad }}</span>
                                    </div>
                                    
                                    <div class="item-col">
                                        <span class="item-title">Fecha: </span>
                                        <span class="item-tag">@{{log.Fecha | formatDate('DD/MM/YYYY HH:mm') }}</span>
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