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
                    <p>Seleccione al personal docente de quien imprimirá el resultado</p>
                </div>
            </div>
            <form action="{{route('desarrollo.resultados_docente')}}" method="post" role="form"
                  class="form" target="_blank">
                @csrf
                <div class="form-group">
                    <label for="docente">Seleccione al docente</label>
                    <select name="docente" id="docente" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($docentes as $docente)
                            <option value="{{$docente->docente}}">{{$docente->apellidos_empleado.' '.$docente->nombre_empleado}}</option>
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
