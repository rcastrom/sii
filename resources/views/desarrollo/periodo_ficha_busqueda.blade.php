@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        Indique el período para el cuál, desea visualizar los datos de los aspirantes a ingresar
        <form action="{{route('desarrollo.mostrar')}}" method="post" class="form-inline" role="form">
            @csrf
            <label class="sr-only" for="periodo">Periodo</label>
            <select name="periodo" id="periodo" class="form-control mb-2 mr-sm-2">
                @foreach($periodos as $per)
                    <option value="{{$per->periodo}}"{{$per->periodo==$periodo_actual[0]->periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                @endforeach
            </select>
            <label class="sr-only" for="carrera">Carrera</label>
            <select name="carrera" id="carrera" class="form-control mb-2 mr-sm-2">
                <option value="T" selected>Todas</option>
                @foreach($carreras as $carrera)
                    <option value="{{$carrera->carrera."_".$carrera->reticula}}">Carrera {{$carrera->nombre_reducido}} Ret ({{$carrera->reticula}})</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary mb-2">Continuar</button>
        </form>
    </x-information>
@stop
