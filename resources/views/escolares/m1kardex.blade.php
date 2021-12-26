@extends('adminlte::page')

@section('title', 'Mov en Kardex')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}</h4>
        <br>
        <h4 class="card-title">Número de control {{$control}}</h4><br>
        <div class="form-group">
            <form action="{{route('escolares.accion_kardex_modificar1')}}" method="post" role="form">
                @csrf
                <label for="pbusqueda">Señale el período correspondiente para realizar la modificación a la materia</label>
                <select name="pbusqueda" id="pbusqueda" required class="form-control">
                    <option value="" selected>--Seleccione</option>
                    @foreach($periodos as $periodo)
                        <option value="{{$periodo->periodo}}">{{$periodo->identificacion_corta}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="control" value="{{$control}}">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </form>
        </div>
    </x-information>
@stop
