@extends('layouts.app')
@section('content')

<div class="error404">
    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="404" width="70">
        <div class="title">
            <h1>Error 404</h1>
        </div>
        <p>Ups! Lo sentimos &#128533;<br>Ésta página no existe</p>
    </div>
</div>
@endsection
