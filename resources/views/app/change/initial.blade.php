<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>MiUDL | Cambiar contraseña!</title>
      <!-- plugins:css -->
      <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" />
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
    </head>
   <body>
      <div class="container-scroller">
         <div class="container-fluid page-body-wrapper">
            <div class="row">
               <div class="content-wrapper full-page-wrapper d-flex align-items-center auth-pages">
                  <div class="card col-lg-4 mx-auto">
                     <div class="card-body px-5 py-5">
                        <h3 class="card-title text-left mb-3">¡Cambia tu contraseña!</h3>
                        <p>Por motivos de seguridad es necesario que cambies tu contraseña actual</p>
                        @if(Session::get('no_login'))
                            <div style="margin:5px 0; font-size: 13px; letter-spacing: normal;" class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                <strong>¡</strong> {{Session::get('no_login')}} <strong>!</strong>

                                @foreach($errors->all() as $key => $error)
                                    <div><strong> {{$error}}</strong></div>
                                @endforeach
                            </div>
                        @endif
                        @include('app.change.partials.form')
                     </div>
                  </div>
               </div>
               <!-- content-wrapper ends -->
            </div>
            <!-- row ends -->
         </div>
         <!-- page-body-wrapper ends -->
      </div>
      <!-- container-scroller -->
      <!-- plugins:js -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/compiled.js') }}"></script>
</body>
</html>
