@if(isset($edit))
<input name="_method" type="hidden" value="PUT">
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            @php
                $current_url = Request::url();
                if(!isset($edit))
                {
                    $puesto = new \miudl\Base\BaseModel();
                    $puesto->Codigo = null;
                    $puesto->Nombre = null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Codigo del puesto:</label>
                <div class="col-sm-4">
                    <input type="text" name="codigo" class="form-control p-input" value="{{ old('codigo', $puesto->Codigo) }}" placeholder="Código">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre del puesto:</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $puesto->Nombre) }}" placeholder="Nombre">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary submit">Guardar</button>
            @if(isset($edit))
                <a href="" @click.prevent="confirmDialog({{$puesto}}, $event)" class="btn btn-danger">Eliminar</a>
                <a href="../" class="btn btn-primary">Cancelar</a>
            @else
                <a href="./" class="btn btn-primary">Cancelar</a>
            @endif
        </div>
    </div>
</div>
