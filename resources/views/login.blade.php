<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MiUDL | Identificate!</title>
    <link rel="stylesheet" href="{{ mix('css/styles.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        <div class="row">
            <div class="content-wrapper full-page-wrapper d-flex align-items-center auth-pages">
                <div class="card col-lg-4 mx-auto">
                    <div class="card-body px-5 py-5">

                        @if(Session::get('no_login'))
                            <div style="margin:5px 0; font-size: 13px; letter-spacing: normal;" class="alert alert-info">
                                <strong>¡</strong> {{Session::get('no_login')}} <strong>!</strong>
                            </div>
                        @endif
                        <form action="{{ route('login',null, false) }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group text-center" >
                                <img class="img-fluid mb-3" src="{{ asset('images/logo.png') }}" alt="" width="140">
                                <h3 class="card-title mb-3">¡Identificate por favor!</h3>
                            </div>
                            <div class="form-group">
                                <label>Usuario *</label>
                                <input type="text" name="user" value="{{old('user')}}" class="form-control p_input">
                            </div>
                            <div class="form-group">
                                <label>Contraseña *</label>
                                <input type="password" name="password" class="form-control p_input">
                            </div>
                            <div class="form-group d-flex align-items-center justify-content-between">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="remember" class="form-check-input">
                                        Recordar sesión
                                    </label>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" tabindex="-1" class="btn btn-primary btn-block enter-btn">Entrar</button>
                            </div>
                            <p class="sign-up">Olvidé mi contraseña! <a href="/recuperar">Solicitar contraseña</a></p>
                        </form>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
</body>
</html>
