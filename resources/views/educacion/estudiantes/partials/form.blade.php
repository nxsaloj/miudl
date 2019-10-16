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
                    $estudiante->Carne = null;
                    $estudiante->Nombre = null;
                    $estudiante->Apellidos = null;
                }
            @endphp
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">No. de carné:</label>
                <div class="col-sm-4">
                    <input type="text" name="Carne" class="form-control p-input" value="{{ old('Carne', $estudiante->Carne) }}" placeholder="No. de carné" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nombre:</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" class="form-control p-input" value="{{ old('nombre', $estudiante->Nombre) }}" placeholder="Nombre">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Apellidos:</label>
                <div class="col-sm-8">
                    <input type="text" name="apellidos" class="form-control p-input" value="{{ old('apellidos', $estudiante->Apellidos) }}" placeholder="Apellidos">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Fecha de nacimiento</label>
                <div class="col-sm-8">
                    <input type="date" name="fechanacimiento" class="form-control p-input" value="{{ old('fechanacimiento', \Carbon\Carbon::parse($estudiante->FechaNacimiento)->format('Y-m-d')) }}" placeholder="Fecha de nacimiento">
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
