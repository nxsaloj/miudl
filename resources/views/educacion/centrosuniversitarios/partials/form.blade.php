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
                    $centrouniversitario = new \miudl\Base\BaseModel();
                    $centrouniversitario->Codigo = null;
                    $centrouniversitario->Nombre = null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Codigo del centro universitario:</label>
                <div class="col-sm-4">
                    <input type="text" name="codigo" class="form-control p-input" value="{{ old('codigo', $centrouniversitario->Codigo) }}" placeholder="Código">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre del centro universitario:</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $centrouniversitario->Nombre) }}" placeholder="Nombre">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Direccion:</label>
                <div class="col-sm-8">
                    <input type="text" name="direccion" class="form-control p-input" value="{{ old('direccion', $centrouniversitario->Direccion) }}" placeholder="Direccion">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary submit">Guardar</button>
            @if(isset($edit))
                <a href="" @click.prevent="confirmDialog({{$centrouniversitario}}, $event)" class="btn btn-danger">Eliminar</a>
                <a href="../" class="btn btn-primary">Cancelar</a>
            @else
                <a href="./" class="btn btn-primary">Cancelar</a>
            @endif
        </div>
    </div>
</div>
