@extends('adminlte::page')

@section('title', 'Idioma Extranjero')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{route('escolares.imprimir_idioma')}}" role="form">
            @csrf
            <legend>Liberación de idioma extranjero</legend>
            <div class="form-group">
                <label for="control"> Indique por favor, el número de control </label>
                <input type="text" name="control" id="control" class="form-control"
                       required maxlength="10" onchange="this.value=this.value.toUpperCase();">
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
