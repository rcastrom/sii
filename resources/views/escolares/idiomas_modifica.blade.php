@extends('adminlte::page')

@section('title', 'Idioma Extranjero')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Módulo para agregar un idioma extranjero
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{route('escolares.idioma_editar',['idioma'=>$idioma->id])}}" role="form">
            @csrf
            <div class="form-group">
                <label for="idioma">Nombre completo del idioma extranjero </label>
                <input type="text" name="idioma" id="idioma" value="{{$idioma->idioma}}"
                       required onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <div class="form-group">
                <label for="siglas">Nombre abreviado (siglas) del idioma extranjero </label>
                <input type="text" name="siglas" id="siglas" value="{{$idioma->abrev}}"
                       required onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>

@stop

