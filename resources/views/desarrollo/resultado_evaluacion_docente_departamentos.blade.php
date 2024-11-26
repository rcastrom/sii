@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-body">
            <div class="card-header">
                <h4>Periodo: {{$nombre_periodo->identificacion_corta}}</h4>
            </div>
            <div class="row">
                <div class="col-12">
                    <p>En base al listado de departamentos, seleccione aquella para obtener el concentrado de
                        la evaluación al docente correspondiente</p>
                </div>
            </div>
            <form action="{{route('desarrollo.resultados_departamento')}}" method="post" role="form"
                  class="form" target="_blank">
                @csrf
                <div class="form-group">
                    <label for="departamento">Seleccione el departamento académico por buscar</label>
                    <select name="departamento" id="departamento" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{$departamento->clave_area}}">{{$departamento->descripcion_area}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
                <input type="hidden" name="periodo" value="{{$periodo}}">
            </form>
        </div>
    </x-information>
@stop
