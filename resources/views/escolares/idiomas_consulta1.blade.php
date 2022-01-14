@extends('adminlte::page')

@section('title', 'Idioma Extranjero')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>El siguiente módulo, es para consultar el listado de cursos de lengua
            extranjera impartidos y registrados en la Institución.
        </p>
        <form method="post" action="{{route('escolares.cursos_idiomas')}}" role="form">
            @csrf
            <legend>Cursos impartidos Lengua Extranjera</legend>
            <div class="form-group">
                <label for="periodo"> Indique el período de consulta </label>
                <select name="periodo" id="periodo" class="form-control" required>
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}" {{$per->periodo==$periodo_actual[0]->periodo?" selected":""}}>{{$per->identificacion_corta}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="idioma"> Seleccione la lengua extranjera de consulta </label>
                <select name="idioma" id="idioma" class="form-control" required>
                    @foreach($idiomas as $leng_ext)
                        <option value="{{$leng_ext->id}}">{{$leng_ext->idiomas}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop

