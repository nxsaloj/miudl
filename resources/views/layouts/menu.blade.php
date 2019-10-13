<li class="nav-item">
    <a class="nav-link" href="/">
        <i class="mdi mdi-atom"></i>
        <span class="menu-title">Pagina Principal</span>
    </a>
</li>

@if(!Auth::check())
{!! $html = \App\Http\Controllers\SeccionController::getSeccionesHTML($secciones) !!}
@endif
