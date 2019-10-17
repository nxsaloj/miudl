@extends('layouts.app')
@section('content')

<div class="vue-inscripcion vue-inscripcion-registrar">
    @include('layouts.headerpage', array('titulo'=>'Registrar estudiante','parent'=>array('href'=>'./','title'=>'Inscripciones')))

    <form action="{{ route('inscripcion.registrar',null, false) }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input v-if="showextra" type="hidden" name="session_keep" id="extra-action">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @php
                        if(!isset($edit))
                        {
                            $estudiante = new \miudl\Base\BaseModel;
                            $estudiante->Codigo = null;
                            $estudiante->Nombre = null;
                            $estudiante->Apellidos = null;
                        }
                    @endphp
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Centro universitario:</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-12 col-lg-12 col-sm-12">
                                        @php
                                            $id = (old('centrouniversitario') != null)? old('centrouniversitario'):null;
                                            if($id != null) $centrouniversitario = resolve('\App\Http\Controllers\CentroUniversitarioController')->getCentroUniversitarioAPI($id);
                                        @endphp
                                        @if(isset($centrouniversitario))
                                            <input type="hidden" id="centrouniversitariohidden" value="{{ $centrouniversitario }}">
                                        @endif
                                        <input type="hidden" v-if="centrouniversitario" v-model="centrouniversitario.id" name="centrouniversitario" value="{{ old('centrouniversitario') }}">
                                        <v-select label="Nombre" :filterable="false" :options="centrosuniversitarios_options" @search="getCentrosUniversitarios" v-model="centrouniversitario" :placeholder="'Centro Universitario'">
                                            <slot name="spinner">
                                                <div class="spinner">Espere...</div>
                                            </slot>
                                            <template slot="no-options">
                                                Buscar carrera...
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div class="d-center">
                                                    @{{ option.Nombre }} (@{{ option.Codigo }})
                                                </div>
                                            </template>
                                            <template slot="selected-option" scope="option">
                                                <div class="selected d-center" v-if="option.Nombre">
                                                    @{{ option.Nombre }}  (@{{ option.Codigo }})
                                                </div>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Carrera:</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-12 col-lg-12 col-sm-12">
                                        @php
                                            $id = (old('carrera') != null)? old('carrera'):null;
                                            if($id != null) $carrera = resolve('\App\Http\Controllers\CarreraController')->getCarreraAPI($id);
                                        @endphp
                                        @if(isset($carrera))
                                            <input type="hidden" id="carrerahidden" value="{{ $carrera }}">
                                        @endif
                                        <input type="hidden" v-if="carrera" v-model="carrera.id" name="carrera" value="{{ old('carrera') }}">
                                        <v-select label="Nombre" :filterable="false" :options="carreras_options" @search="getCarreras" v-model="carrera" :placeholder="'Carrera'">
                                            <slot name="spinner">
                                                <div class="spinner">Espere...</div>
                                            </slot>
                                            <template slot="no-options">
                                                Buscar carrera...
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div class="d-center">
                                                    @{{ option.Nombre }} (@{{ option.Codigo }})
                                                </div>
                                            </template>
                                            <template slot="selected-option" scope="option">
                                                <div class="selected d-center" v-if="option.Nombre">
                                                    @{{ option.Nombre }}  (@{{ option.Codigo }})
                                                </div>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Nombre:</label>
                        <div class="col-sm-8">
                            <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $estudiante->Nombre) }}" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Apellidos:</label>
                        <div class="col-sm-8">
                            <input type="text" name="apellidos" class="form-control p-input" value="{{ old('apellidos', $estudiante->Apellidos) }}" placeholder="Apellidos">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Fecha de nacimiento</label>
                        <div class="col-sm-8">
                            <input type="date" name="fechanacimiento" class="form-control p-input" value="{{ old('fechanacimiento', \Carbon\Carbon::parse($estudiante->FechaNacimiento)->format('Y-m-d')) }}" placeholder="Fecha de nacimiento">
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary submit">Guardar</button>
                    <a href="./" class="btn btn-primary">Cancelar</a>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
