@if(isset($edit))
<input name="_method" type="hidden" value="PUT">
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input v-if="showextra" type="hidden" name="session_keep" id="extra-action">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            @php
                $current_url = Request::url();
                if($session = Session::get('keep_session')) if($origin = $session['dest']) if(strpos($current_url, $origin) !== false) $gyc = true;
                if(!isset($edit))
                {
                    $empleado = new \App\Generic;
                    $empleado->Codigo = null;
                    $empleado->Nombre = null;
                    $empleado->Apellidos = null;
                    $empleado->PuestoTrabajo_id = null;
                }
                if(session()->has('empleado'))
                {
                    $empleado_s = session()->get('empleado');
                    $empleado = new \App\Generic;
                    $empleado->Empleado_id = $empleado_s['id'];
                    $empleado->Codigo = $empleado_s['codigo'];
                    $empleado->Nombre = $empleado_s['nombre'];
                    $empleado->Apellidos = $empleado_s['apellidos'];
                    $empleado->PuestoTrabajo_id = isset($empleado_s['puesto'])? $empleado_s['puesto']:null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Código de empleado:</label>
                <div class="col-sm-4">
                    <input type="text" name="codigo" class="form-control p-input" value="{{ old('codigo', $empleado->Codigo) }}" placeholder="Código">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $empleado->Nombre) }}" placeholder="Nombre">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Apellidos</label>
                <div class="col-sm-8">
                    <input type="text" name="apellidos" class="form-control p-input" value="{{ old('apellidos', $empleado->Apellidos) }}" placeholder="Apellidos">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Puesto de Trabajo</label>
                <div class="col-sm-8">
                    @php 
                        $id = (old('puesto') != null)? old('puesto'):$empleado->PuestoTrabajo_id; 
                        if($id != null) $puesto = \App\Models\RRHH\PuestoTrabajo::getPuestoTrabajo($id); 
                    @endphp
                    @if(isset($puesto))
                        <input type="hidden" id="puestohidden" value="{{ $puesto->Nombre }}">
                    @endif
                    <input type="hidden" v-if="puesto" v-model="puesto.PuestoTrabajo_id" name="puesto" value="{{ old('puesto', $empleado->PuestoTrabajo_id) }}">
                    <v-select label="Nombre" :filterable="false" :options="puestos" @search="getPuestosTrabajo" v-model="puesto" :placeholder="'Puesto de trabajo'">
                        <slot name="spinner">
                            <div class="spinner">Espere...</div>
                        </slot>
                        @if(!isset($gyc))
                            <div slot="no-options" @click.prevent="session_keep('/rrhh/puestos')">
                                <span>Crear puesto...</span>
                            </div>
                        @else
                            <template slot="no-options">
                                Buscar puesto...
                            </template>
                        @endif
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
            @if(isset($gyc))
                <button type="submit" class="btn btn-primary submit">Guardar y continuar</button>
                <a href="{{ url('cancellsession') }}" class="btn btn-primary">Cancelar</a>
            @else
                <button type="submit" class="btn btn-primary submit">Guardar</button>
                @if(isset($edit))
                    <a href="" @click.prevent="confirmDialog({{$empleado}}, $event)" class="btn btn-danger">Eliminar</a>
                    <a href="../" class="btn btn-primary">Cancelar</a>
                @else
                    <a href="./" class="btn btn-primary">Cancelar</a>
                @endif             
            @endif
        </div>
    </div>
</div>