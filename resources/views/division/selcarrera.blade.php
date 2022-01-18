@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>En base al listado de carreras - retículas, seleccione la correspondiente a la que
            se le va a crear un grupo</p>
        <form action="{{route('dep_lista2')}}" method="post" role="form">
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
                <label for="periodo">Periodo de alta</label>
                <select name="periodo" id="periodo" required class="form-control">
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}"{{$per->periodo==$periodo_actual[0]->periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
