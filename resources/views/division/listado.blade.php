@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>En base al listado de carreras - reticulas, seleccione para ver los grupos existentes</p>
        <form action="{{route('dep_lista')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="carrera">Seleccione la carrera por buscar</label>
                <select name="carrera" id="carrera" required class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    @foreach($carreras as $carrera)
                        <option value="{{$carrera->carrera.'_'.$carrera->reticula}}">{{$carrera->nombre_reducido.' RET('.$carrera->reticula.')'}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="periodo">Periodo</label>
                <select name="periodo" id="periodo" required class="form-control">
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}"{{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
