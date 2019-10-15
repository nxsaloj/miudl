@extends('layouts.app') 
@section('content')

<div class="vue-permisos vue-permisos-self">
    @include('layouts.headerpage', array('titulo'=>'Delegar permisos'))

    @if(Session::get('info'))
        @php
            $toast = "'".Session::get('info')."'";
            Session::forget('info');
        @endphp
        <toastel :mensaje="{{$toast}}"></toastel>
    @endif
    
    <div class="card">
        <div class="card-body">
            <div class="col-lg-12">
                <div class="card bg-light">      
                    @php
                        $delegado = \App\Models\App\Usuario::getDelegado(Auth::user());
                    @endphp              
                    @if(!$delegado)
                    <div class="col-lg-7 col-sm-12">
                        <ul class="nav nav-tabs" id="Demo-tab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#user" @click.prevent="cambioTab('usuario')" role="tab">Usuario</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        
                        <div class="tab-content">
                            <div class="tab-pane pt-4 active" id="user" role="tabpanel">
                                <div class="container">
                                    <div class="col-sm-12">
                                        <v-select label="Usuario" :filterable="false" :options="usuarios" @search="getUsuarios" v-model="usuario" :placeholder="'Usuarios'">
                                            <slot name="spinner">
                                                <div class="spinner">Espere...</div>
                                            </slot>
                                            <template slot="no-options">
                                                Buscar usuario...
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div class="d-center">
                                                    @{{ (option.Empleado)? option.Empleado.Nombre+' '+option.Empleado.Apellidos:((option.Estudiante)? option.Estudiante.Nombre+" "+option.Estudiante.Apellidos:"") }} 
                                                </div>
                                            </template>
                                            <template slot="selected-option" scope="option">
                                                <div class="selected d-center">
                                                    @{{ (option.Empleado)? option.Empleado.Nombre+' '+option.Empleado.Apellidos:((option.Estudiante)? option.Estudiante.Nombre+" "+option.Estudiante.Apellidos:"") }} 
                                                </div>
                                            </template>
                                        </v-select>
                                    </div>
                                    <div class="col-lg-12 row">
                                        <div class="col-lg-6">
                                            <label for="exampleTextarea" class="col-form-label">Desde</label>
                                            <div class="form-inline">
                                                <input type="date" class="form-control" placeholder="Fecha" v-model="desde.Fecha">
                                                <div class="form-inline">
                                                    <input type="time" @change="cambiarHora(desde)" class="form-control" placeholder="Hora" v-model="desde.Hora">
                                                    <select class="form-control" v-model="desde.Tipo" @change="cambiarHora(desde)">
                                                        <option value="0">AM</option>
                                                        <option value="1">PM</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="exampleTextarea" class="col-form-label">Hasta</label>
                                            <div class="form-inline">
                                                <input type="date" class="form-control" placeholder="Fecha" v-model="hasta.Fecha">
                                                <div class="form-inline">
                                                    <input type="time" @change="cambiarHora(hasta)" class="form-control" placeholder="Hora" v-model="hasta.Hora">
                                                    <select class="form-control" v-model="hasta.Tipo" @change="cambiarHora(hasta)">
                                                        <option value="0">AM</option>
                                                        <option value="1">PM</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                        <label>
                            <strong>Delegado:</strong> {{ $delegado->Delegado->Empleado->Nombre }} {{ $delegado->Delegado->Empleado->Apellidos }}
                        </label>
                        <label>
                            <strong>Fecha de inicio:</strong> {{ \Carbon\Carbon::parse($delegado->FechaInicio)->format('d/m/Y H:i') }}
                        </label>
                        <label>
                            <strong>Fecha de finalización:</strong> {{ \Carbon\Carbon::parse($delegado->FechaFinal)->format('d/m/Y H:i') }}
                        </label>
                        <input type="hidden" value="{{$delegado->Delegado->Usuario_id}}" name="delegado"/>
                        <input type="hidden" value="{{$delegado->Delegacion_id}}" name="delegacion"/>
                    @endif
                </div>

                <div class="card bg-light">
                    <div class="card-body row user-profile">
                    
                        <div class="col-lg-6 side-left">
                        
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h2 class="card-title">Secciones</h2>
                                    <template>
                                        <div class="section-permissions" v-for="seccion in seccionesSidebar">
                                            <div class="form-check section-permission">
                                                <input type="checkbox" @change="addSelect(seccion)" :checked="( secciones_permitidas.all || secciones_permitidas.active.includes(seccion.SeccionApp_id) ) && !secciones_permitidas.ignore.includes(seccion.SeccionApp_id)"/>
                                                @{{ seccion.Nombre }}
                                            </div>
                                            <div v-for="child in seccion.secciones">
                                                <div class="form-check section-permission child-section-permission">
                                                    <input type="checkbox" @change="addSelect(child)" :checked="( secciones_permitidas.all || secciones_permitidas.active.includes(child.SeccionApp_id) ) && !secciones_permitidas.ignore.includes(child.SeccionApp_id)"/>
                                                    @{{ child.Nombre }}
                                                </div>
                                                <div v-for="child2 in child.secciones">
                                                    <div class="form-check section-permission child2-section-permission">
                                                        <input type="checkbox" @change="addSelect(child2)" :checked="( secciones_permitidas.all || secciones_permitidas.active.includes(child2.SeccionApp_id) ) && !secciones_permitidas.ignore.includes(child2.SeccionApp_id)"/>
                                                        @{{ child2.Nombre }}
                                                    </div>
                                                    <div v-for="child3 in child2.secciones">
                                                        <div class="form-check section-permission child3-section-permission">
                                                            <input type="checkbox" @change="addSelect(child3)" :checked="( secciones_permitidas.all || secciones_permitidas.active.includes(child3.SeccionApp_id) ) && !secciones_permitidas.ignore.includes(child3.SeccionApp_id)"/>
                                                            @{{ child3.Nombre }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 side-right">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h2 class="card-title">Notificaciones</h2>
                                    <template>
                                        <div class="section-permissions" v-for="seccion in notificaciones">
                                            <div class="form-check section-permission">                                            
                                                <input type="checkbox" @change="addSelect(seccion)" :checked="( secciones_permitidas.all || secciones_permitidas.active.includes(seccion.SeccionApp_id) ) && !secciones_permitidas.ignore.includes(seccion.SeccionApp_id)"/>
                                                @{{ seccion.Nombre }}
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="card bg-light">
                                <div class="card-body">
                                    <h2 class="card-title">Permisos</h2>
                                    <template>
                                        <div class="section-permissions" v-for="seccion in seccionesPermisos">
                                            <div class="form-check section-permission">
                                                <input type="checkbox" @change="addSelect(seccion)" :checked="( secciones_permitidas.all || secciones_permitidas.active.includes(seccion.SeccionApp_id) ) && !secciones_permitidas.ignore.includes(seccion.SeccionApp_id)"/>
                                                @{{ seccion.Nombre }}
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                             <div class="card bg-light">
                                <div class="card-body">
                                    <h2 class="card-title">Informes</h2>
                                    <template>
                                        <div class="section-permissions" v-for="seccion in informesPermisos">
                                            <div class="form-check section-permission">
                                                <input type="checkbox" @change="addSelect(seccion)" :checked="( secciones_permitidas.all || secciones_permitidas.active.includes(seccion.SeccionApp_id) ) && !secciones_permitidas.ignore.includes(seccion.SeccionApp_id)"/>
                                                @{{ seccion.Nombre }}
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <input type="checkbox" @change="addSelect(null,true)" :checked="( secciones_permitidas.all )"><label>Marcar todo</label>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
        <div class="card-footer">
            @if($delegado)
            <div class="float-left">
                <a href="" class="btn btn-danger submit" @click.prevent="revocarPermisos()">Finalizar delegación</a>
            </div>
            @else
            <a href="" class="btn btn-primary submit float-right" @click.prevent="guardarDelegacion()">Delegar permisos</a>
            @endif
        </div>
    </div>
</div>
@endsection