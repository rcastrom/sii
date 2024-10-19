@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <form action="{{route('academicos.imprimir_horario')}}" method="post" target="_blank">
            @csrf
            <div class="form-group">
                <input type="hidden" name="personal" value="{{$personal->id}}">
                <input type="hidden" name="periodo" value="{{$periodo}}">
                <input type="hidden" name="descripcion_area" value="{{$descripcion_area->descripcion_area}}">
                <input type="hidden" name="ruta_escudo" value="{{asset('img/escudo.jpg')}}">
                <input type="hidden" name="ruta_sep" value="{{asset('img/educacion.jpg')}}">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
