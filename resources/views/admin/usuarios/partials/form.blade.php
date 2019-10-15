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
                    $usuario = new \App\Generic;
                    $usuario->Codigo = null;
                    $usuario->Nombre = null;
                    $usuario->Apellidos = null;
                    $usuario->PuestoTrabajo_id = null;
                }
                if(session()->has('usuario'))
                {
                    $usuario_s = session()->get('usuario');
                    $usuario = new \App\Generic;
                    $usuario->Usuario_id = $usuario_s['id'];
                    $usuario->Codigo = $usuario_s['codigo'];
                    $usuario->Nombre = $usuario_s['nombre'];
                    $usuario->Apellidos = $usuario_s['apellidos'];
                    $usuario->PuestoTrabajo_id = isset($usuario_s['puesto'])? $usuario_s['puesto']:null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Código de usuario:</label>
                <div class="col-sm-4">
                    <input type="text" name="codigo" class="form-control p-input" value="{{ old('codigo', $usuario->Codigo) }}" placeholder="Código">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $usuario->Nombre) }}" placeholder="Nombre">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Apellidos</label>
                <div class="col-sm-8">
                    <input type="text" name="apellidos" class="form-control p-input" value="{{ old('apellidos', $usuario->Apellidos) }}" placeholder="Apellidos">
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
                    <a href="{{ route('usuarios.destroy',[$usuario->Usuario_id], false) }}/delete" @click.prevent="confirmDialog({{$usuario}}, $event)" class="btn btn-danger">Eliminar</a>
                    <a href="../" class="btn btn-primary">Cancelar</a>
                @else
                    <a href="./" class="btn btn-primary">Cancelar</a>
                @endif             
            @endif
        </div>
    </div>
</div>