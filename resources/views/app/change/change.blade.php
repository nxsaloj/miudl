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
                        <h3 class="card-title text-left mb-3">Cambiar contraseña</h3>
                        <p>Establecer una nueva contraseña de acceso a tu cuenta</p>
                        @php
                            $shown = array();
                        @endphp
                        @if(Session::get('no_login'))
                            <div class="alert alert-info">
                            <!--Muestra el mensaje flash de si hubo un error o no en la operación-->
                            <div>{{Session::get('no_login')}}</div>

                            @if($errors->has('exception_dev'))
                                <!--Muestra los detalles de la Excepción que capturó Try-->
                                <a href="#" @click.prevent="show = !show">Detalles</a>
                                <div class="exception-dev card card-body" v-if="show">
                                    @foreach($errors->get('exception_dev') as $error)
                                        @php array_push($shown, $error); @endphp
                                        <span> {{var_dump($error)}}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!--Muestra los valores de validación de los campos del objeto-->
                            @foreach($errors->all() as $key => $error)
                                @if(!in_array($error, $shown)) <div><strong> {{$error}}</strong></div> @endif
                            @endforeach
                        </div>
                        @endif
                        @include('app.change.partials.form',array('profile'=>true))
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
