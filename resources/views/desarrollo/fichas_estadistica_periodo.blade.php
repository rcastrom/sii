@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        Indique el período correspondiente para la obtención del concentrado de fichas hasta el momento
        <form action="{{route('desarrollo.fichas_concentrado_estadistico')}}" method="post" class="form-inline" role="form">
            @csrf
            <label class="sr-only" for="periodo">Periodo</label>
            <select name="periodo" id="periodo" class="form-control mb-2 mr-sm-2">
                @foreach($periodos as $per)
                    <option value="{{$per->periodo}}"{{$per->periodo==$periodo_actual->periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary mb-2">Continuar</button>
        </form>
    </x-information>
@stop
