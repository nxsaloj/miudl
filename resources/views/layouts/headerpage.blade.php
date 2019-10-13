<div class="row">    
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @if(isset($parent)) 
                <li class="breadcrumb-item"><a href="{{ $parent['href'] }}">{{ $parent['title'] }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$titulo}}</li>
            @else
                <li class="breadcrumb-item active" aria-current="page"><h2>{{ $titulo }}</h2></li>
            @endif
            
        </ol>
    </nav>
</div>
<!--$shown variable auxiliar de arrays de mensajes mostrados-->
@php
    $shown = array();
@endphp
@if(Session::get('error'))
    <div class="alert alert-light">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        
        <!--Muestra el mensaje flash de si hubo un error o no en la operación-->
        <div>{{Session::get('error')}}</div>
        
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