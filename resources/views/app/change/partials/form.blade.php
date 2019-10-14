<form action="{{ route('change',null, false) }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
    
    <div class="form-group">
        <label>Contraseña actual*</label>
        <input type="password" name="password" placeholder="Contraseña actual" class="form-control p_input">
    </div>

    <div class="form-group">
        <label>Contraseña nueva*</label>
        <input type="password" name="newpassword" placeholder="Contraseña nueva" class="form-control p_input">
    </div>

    <div class="form-group">
        <label>Repite la contraseña nueva*</label>
        <input type="password" name="newpasswordr" placeholder="Contraseña nueva" class="form-control p_input">
    </div>

    <div class="form-group d-flex align-items-center justify-content-between">
    
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-block enter-btn">Guardar</button>
        @if(isset($profile))
        <a href="/" class="btn btn-info btn-block enter-btn">Ir a página principal</a>
        @endif
        <a href="/logout" class="btn btn-danger btn-block enter-btn">Cerrar sesión</a>
    </div>
</form>