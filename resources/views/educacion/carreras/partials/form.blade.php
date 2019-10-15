@if(isset($edit))
<input name="_method" type="hidden" value="PUT">
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input v-if="showextra" type="hidden" name="session_keep" id="extra-action">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            @php
                if(!isset($edit))
                {
                    $carrera = new \miudl\Base\BaseModel;
                    $carrera->Codigo = null;
                    $carrera->Nombre = null;
                    $carrera->Apellidos = null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Código de carrera:</label>
                <div class="col-sm-4">
                    <input type="text" name="codigo" class="form-control p-input" value="{{ old('codigo', $carrera->Codigo) }}" placeholder="Código">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $carrera->Nombre) }}" placeholder="Nombre">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Facultad</label>
                <div class="col-sm-8">
                    @php
                        $id = (old('facultad') != null)? old('facultad'):$carrera->Facultad_id;
                        if($id != null) $facultad = resolve('\App\Http\Controllers\FacultadController')->getFacultadAPI($id);
                    @endphp
                    @if(isset($facultad))
                        <input type="hidden" id="facultadhidden" value="{{ $facultad->Nombre }}">
                    @endif
                    <input type="hidden" v-if="facultad" v-model="facultad.id" name="facultad" value="{{ old('facultad', $carrera->Facultad_id) }}">
                    <v-select label="Nombre" :filterable="false" :options="facultades" @search="getFacultades" v-model="facultad" :placeholder="'Facultad'">
                        <slot name="spinner">
                            <div class="spinner">Espere...</div>
                        </slot>
                        <template slot="no-options">
                            Buscar facultad...
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
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Ciclos</label>
                <div class="col-sm-8">
                    <input type="number" min="2" max="14" name="ciclos" class="form-control p-input" value="{{ old('ciclos', $carrera->Ciclos) }}" placeholder="Ciclos">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary submit">Guardar</button>
            @if(isset($edit))
                <a href="" @click.prevent="confirmDialog({{$carrera}}, $event)" class="btn btn-danger">Eliminar</a>
                <a href="../" class="btn btn-primary">Cancelar</a>
            @else
                <a href="./" class="btn btn-primary">Cancelar</a>
            @endif
        </div>
    </div>
</div>
