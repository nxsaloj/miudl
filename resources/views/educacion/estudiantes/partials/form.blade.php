@if(isset($edit))
<input name="_method" type="hidden" value="PUT">
<input id="estudianteedit" type="hidden" value="{{ $estudiante }}">
@endif
@if(old('carreras'))
    @foreach(old('carreras') as $product)
        <input id="carrerashidden" type="hidden" name="carrerashidden[]" value="{{ old('carreras')[$loop->index] }}">
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
                    $estudiante = new \miudl\Base\BaseModel;
                    $estudiante->Codigo = null;
                    $estudiante->Nombre = null;
                    $estudiante->Apellidos = null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Código de estudiante:</label>
                <div class="col-sm-4">
                    <input type="text" name="codigo" class="form-control p-input" value="{{ old('codigo', $estudiante->Codigo) }}" placeholder="Código">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre:</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $estudiante->Nombre) }}" placeholder="Nombre">
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Carreras asociadas:</label>
                <div class="col-sm-8">
                    <div class="card-header">
                        <div class="row">
                            <div class="row col-md-12">
                                <div class="form-group col-md-10 col-lg-10 col-sm-10">
                                    <v-select label="Nombre" :filterable="false" :options="carreras_options" @search="getCarreras" v-model="detalle_tmp" :placeholder="'Carrera'">
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
                                <button type="button" class="btn btn-success" @click="agregarCarrera()">Asociar</button>
                            </div>

                        </div>
                        <div class="row">
                            <template>
                                <div class="tablavue  col-md-12">
                                    <div class="item-row" v-for="(carrera, index) in carreras">
                                        <div class="item-col">
                                            <span class="item-title">Carrera: </span>
                                            <span class="item-tag">@{{ carrera.Nombre }}</span>
                                        </div>
                                        <div class="item-col">
                                            <span class="item-title">Ciclo: </span>
                                            <span class="item-tag"><input type="number" class="form-control" min="1" v-model="carrera.Ciclo"></span>
                                        </div>
                                        <input type="hidden" name="carreras[]" :value="JSON.stringify(carrera)"/>
                                        <div class="item-col item-col-options white">
                                            <span class="item-tag">
                                                <a href="#"class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acciones </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="" @click.prevent="quitarDetalleDialog(carrera)">Quitar</a>
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
            @if(isset($edit))
                <a href="" @click.prevent="confirmDialog({{$estudiante}}, $event)" class="btn btn-danger">Eliminar</a>
                <a href="../" class="btn btn-primary">Cancelar</a>
            @else
                <a href="./" class="btn btn-primary">Cancelar</a>
            @endif
        </div>
    </div>
</div>
