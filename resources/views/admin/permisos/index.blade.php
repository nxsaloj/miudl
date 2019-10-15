@extends('layouts.app') 
@section('content')

<div class="vue-permisos">
    @include('layouts.headerpage', array('titulo'=>'Permisos'))

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
                    <div class="col-lg-7 col-sm-12">
                        <ul class="nav nav-tabs" id="Demo-tab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#puesto_trabajo" @click.prevent="cambioTab('puesto')" role="tab">Puesto de trabajo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#user" @click.prevent="cambioTab('usuario')" role="tab">Usuario</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        
                        <div class="tab-content">
                            <div class="tab-pane pt-4 active" id="puesto_trabajo" role="tabpanel">
                                <div class="container">
                                    <div class="col-sm-12">
                                        <v-select label="Nombre" :filterable="false" :options="puestos" @search="getPuestosTrabajo" v-model="puesto" :placeholder="'Puesto de trabajo'">
                                            <slot name="spinner">
                                                <div class="spinner">Espere...</div>
                                            </slot>
                                            <template slot="no-options">
                                                Buscar puesto...
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div class="d-center">
                                                    @{{ option.Nombre }}
                                                </div>
                                            </template>
                                            <template slot="selected-option" scope="option">
                                                <div class="selected d-center">
                                                    @{{ option.Nombre }}
                                                </div>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane pt-4" id="user" role="tabpanel">
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
                                                    @{{ (option.Empleado)? option.Empleado.Nombre+' '+option.Empleado.Apellidos:((option.Estudiante)? option.Estudiante.Nombre+" "+option.Estudiante.Apellidos:"") }} (@{{ option.Usuario }})
                                                </div>
                                            </template>
                                            <template slot="selected-option" scope="option">
                                                <div class="selected d-center">
                                                    @{{ (option.Empleado)? option.Empleado.Nombre+' '+option.Empleado.Apellidos:((option.Estudiante)? option.Estudiante.Nombre+" "+option.Estudiante.Apellidos:"") }} (@{{ option.Usuario }})
                                                </div>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            <button type="submit" class="btn btn-primary submit float-right" @click.prevent="guardarPermisos()">Cambiar permisos</button>            
        </div>
    </div>
</div>
@endsection