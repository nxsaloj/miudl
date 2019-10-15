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
                    $trabajador = new \miudl\Base\BaseModel;
                    $trabajador->Codigo = null;
                    $trabajador->Nombre = null;
                    $trabajador->Apellidos = null;
                    $trabajador->FechaNacimiento = null;
                    $trabajador->PuestoTrabajo_id = null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Código de trabajador:</label>
                <div class="col-sm-4">
                    <input type="text" name="codigo" class="form-control p-input" value="{{ old('codigo', $trabajador->Codigo) }}" placeholder="Código">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $trabajador->Nombre) }}" placeholder="Nombre">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Apellidos</label>
                <div class="col-sm-8">
                    <input type="text" name="apellidos" class="form-control p-input" value="{{ old('apellidos', $trabajador->Apellidos) }}" placeholder="Apellidos">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Puesto de Trabajo</label>
                <div class="col-sm-8">
                    @php
                        $id = (old('puesto') != null)? old('puesto'):$trabajador->PuestoTrabajo_id;
                        //if($id != null) $puesto = new \App\Http\Controllers\PuestoTrabajoController()->getPuestoTrabajo($id);
                    @endphp
                    @if(isset($puesto))
                        <input type="hidden" id="puestohidden" value="{{ $puesto->Nombre }}">
                    @endif
                    <input type="hidden" v-if="puesto" v-model="puesto.id" name="puesto" value="{{ old('puesto', $trabajador->PuestoTrabajo_id) }}">
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
        <div class="card-footer">
            <button type="submit" class="btn btn-primary submit">Guardar</button>
            @if(isset($edit))
                <a href="" @click.prevent="confirmDialog({{$trabajador}}, $event)" class="btn btn-danger">Eliminar</a>
                <a href="../" class="btn btn-primary">Cancelar</a>
            @else
                <a href="./" class="btn btn-primary">Cancelar</a>
            @endif
        </div>
    </div>
</div>
