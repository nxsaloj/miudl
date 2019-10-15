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
                    $curso = new \miudl\Base\BaseModel;
                    $curso->Codigo = null;
                    $curso->Nombre = null;
                    $curso->Apellidos = null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Código de curso:</label>
                <div class="col-sm-4">
                    <input type="text" name="codigo" class="form-control p-input" value="{{ old('codigo', $curso->Codigo) }}" placeholder="Código">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $curso->Nombre) }}" placeholder="Nombre">
                </div>
            </div>


        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary submit">Guardar</button>
            @if(isset($edit))
                <a href="" @click.prevent="confirmDialog({{$curso}}, $event)" class="btn btn-danger">Eliminar</a>
                <a href="../" class="btn btn-primary">Cancelar</a>
            @else
                <a href="./" class="btn btn-primary">Cancelar</a>
            @endif
        </div>
    </div>
</div>
