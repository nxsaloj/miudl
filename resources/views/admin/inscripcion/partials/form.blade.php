@if(isset($edit))
    <input name="_method" type="hidden" value="PUT">
    <input id="estudianteedit" type="hidden" value="{{ $inscripcion }}">
@endif
@if(old('cursos'))
    @foreach(old('cursos') as $product)
        <input id="cursoshidden" type="hidden" name="cursoshidden[]" value="{{ old('cursos')[$loop->index] }}">
    @endforeach
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input v-if="showextra" type="hidden" name="session_keep" id="extra-action">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            @php
                if(!isset($edit))
                {
                    $inscripcion = new \miudl\Base\BaseModel;
                    $inscripcion->Codigo = null;
                    $inscripcion->Nombre = null;
                    $inscripcion->Apellidos = null;
                }
            @endphp


            <div class="form-group row">
                <label class="col-sm-4 col-form-label">No. de boleta:</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('boletano', $inscripcion->boletano) }}" placeholder="No. de boleta">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Estudiante:</label>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="row col-md-12">
                            <div class="form-group col-md-12 col-lg-12 col-sm-12">
                                <v-select label="Carne" :filterable="false" :options="estudiantes_options" @search="getEstudiantes" v-model="estudiante" :placeholder="'Estudiante'">
                                    <slot name="spinner">
                                        <div class="spinner">Espere...</div>
                                    </slot>
                                    <template slot="no-options">
                                        Buscar curso...
                                    </template>
                                    <template slot="option" slot-scope="option">
                                        <div class="d-center">
                                            @{{ option.Nombre }} (@{{ option.Carne }})
                                        </div>
                                    </template>
                                    <template slot="selected-option" scope="option">
                                        <div class="selected d-center" v-if="option.Nombre">
                                            @{{ option.Nombre }}  (@{{ option.Carne }})
                                        </div>
                                    </template>
                                </v-select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Asigar cursos:</label>
                <div class="col-sm-8">
                    <div class="card-header">
                        <div class="row">
                            <div class="row col-md-12">
                                <div class="form-group col-md-10 col-lg-10 col-sm-10">
                                    <v-select label="Codigo" :filterable="false" :options="cursos_options" @search="getCursos" v-model="curso_tmp" :placeholder="'Curso'">
                                        <slot name="spinner">
                                            <div class="spinner">Espere...</div>
                                        </slot>
                                        <template slot="no-options">
                                            Buscar curso...
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
                                <button type="button" class="btn btn-success" @click="agregarCurso()">Asociar</button>
                            </div>

                        </div>
                        <div class="row">
                            <template>
                                <div class="tablavue  col-md-12">
                                    <div class="item-row" v-for="(curso, index) in cursos">
                                        <div class="item-col">
                                            <span class="item-title">Curso: </span>
                                            <span class="item-tag">@{{ curso.Nombre }}</span>
                                        </div>
                                        <input type="hidden" name="cursos[]" :value="JSON.stringify(curso)"/>
                                        <div class="item-col item-col-options white">
                                            <span class="item-tag">
                                                <a href="#"class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acciones </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="" @click.prevent="quitarDetalleDialog(curso)">Quitar</a>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary submit">Guardar</button>
            <a href="./" class="btn btn-primary">Cancelar</a>
        </div>
    </div>
</div>
