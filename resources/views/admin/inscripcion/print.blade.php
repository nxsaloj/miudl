@extends('layouts.app')
@section('content')

<div class="vue-inscripcion vue-inscripcion-print">
    @include('layouts.headerpage', array('titulo'=>'Formulario de inscripción','parent'=>array('href'=>'./','title'=>'Inscripciones')))

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Tipo:</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="row col-md-12">
                                <div class="form-group col-md-10 col-lg-10 col-sm-10">
                                    <select class="form-control" v-model="tipo_inscripcion">
                                        <option value="inscripcion">Inscripción</option>
                                        <option value="reinscripcion">Reinscripción</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group" v-if="tipo_inscripcion=='inscripcion'">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Centro universitario:</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-10 col-lg-10 col-sm-10">
                                        <v-select label="Nombre" :filterable="false" :options="centrosuniversitarios_options" @search="getCentrosUniversitarios" v-model="centrouniversitario" :placeholder="'Centros Universitarios'">
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
                                    <div class="form-group col-md-10 col-lg-10 col-sm-10">
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
                </div>
                <div class="form-group" v-if="tipo_inscripcion=='reinscripcion'">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Estudiante:</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-10 col-lg-10 col-sm-10">
                                        <v-select label="Carne" :filterable="false" :options="estudiantes_options" @search="getEstudiantes" v-model="estudiante" :placeholder="'Estudiante'">
                                            <slot name="spinner">
                                                <div class="spinner">Espere...</div>
                                            </slot>
                                            <template slot="no-options">
                                                Buscar estudiante...
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div class="d-center">
                                                    @{{ option.Apellidos }}, @{{ option.Nombre }} (@{{ option.Carne }})
                                                </div>
                                            </template>
                                            <template slot="selected-option" scope="option">
                                                <div class="selected d-center" v-if="option.Nombre">
                                                    @{{ option.Apellidos }}, @{{ option.Nombre }} (@{{ option.Carne }})
                                                </div>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="#" @click.prevent="imprimirFormulario()" class="btn btn-primary"><i v-show="loading" class="fa fa-circle-o-notch fa-spin"></i><i v-show="!loading" class="fa fa-print"></i>  Imprimir</a>
                <a href="./" class="btn btn-primary">Cancelar</a>
            </div>
        </div>
    </div>
</div>
@endsection
