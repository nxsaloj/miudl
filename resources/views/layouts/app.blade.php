<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MiUDL | @yield('page_title','Panel de administración')</title>
    <link rel="stylesheet" href="{{ mix('css/styles.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
</head>
<body class="navbar-primary sidebar-fixed sidebar-icon-only">
    @if(Auth::check())
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper">
                <a class="navbar-brand brand-logo" href="/">
                    <img src="{{ asset('images/logo_slogan.png') }}" alt="" width="115">
                </a>
                <a class="navbar-brand brand-logo-mini" href="/"><img src="{{ asset('images/logo.png') }}" alt="" width="40"></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <button class="navbar-toggler navbar-toggler align-self-center mr-2" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <div class="nav-profile">
                    <span>Hola, @if(!Auth::guest()) @if(Auth::user()->Trabajador) {{Auth::user()->Trabajador->Nombre." ".Auth::user()->Trabajador->Apellidos}} @else Administrador @endif @else Invitado @endif</span>
                </div>
                <form class="form-inline mt-2 mt-md-0 d-none d-lg-block ml-lg-auto">

                </form>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" data-toggle="dropdown">
                            <i class="mdi mdi-account-settings"></i>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="notificationDropdown">
                            <a href="/cambiarpass" class="dropdown-item align-items-center justify-content-center">
                                Cambiar contraseña
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="/logout" class="dropdown-item align-items-center justify-content-center">
                                Cerrar sesión
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>


        <div class="container-fluid page-body-wrapper">
            <div class="row row-offcanvas row-offcanvas-right">
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        @include('layouts.menu')
                    </ul>
                </nav>
                <div class="content-wrapper" id="content-wrapper">
                    @yield('content')
                </div>

            </div>
            <footer class="footer">
                <div class="container-fluid clearfix">
                    <span class="float-right">
                     <small><a href="mailto:nisaloj@live.com?subject=Contacto">Nelson Saloj</a> &copy; 2019-{{ date("Y") }}</small>
                    </span>
                </div>
            </footer>
        </div>
    </div>
    @else
        @yield('content')
    @endif

    <script src="{{ mix('js/scripts.js') }}"></script>
    <script src="{{ mix('js/compiled.js') }}"></script>
</body>
</html>
