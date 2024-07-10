@extends('adminlte::page')

@section('title', 'Evaluación Docente')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        Seleccione el período correspondiente así como tipo de encuesta, para visualizar
        los resultados correspondientes de la evaluación al docente.
        <form action="{{route('desarrollo.resultados_evaluacion')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="periodo">Período de consulta</label>
                <select name="periodo" id="periodo" required class="form-control">
                    @foreach($periodos as $periodo)
                        <option value="{{$periodo->periodo}}"
                                {{$periodo->periodo==$periodo_actual?'selected':''}}
                        >
                            {{$periodo->identificacion_larga}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="encuesta">Tipo de encuesta</label>
                <select name="encuesta" id="encuesta" class="form-control" required>
                    <option value="A" selected>Licenciatura</option>
                    <option value="D">Postgrado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="busqueda">Indique el tipo de búsqueda</label>
                <select name="busqueda" id="busqueda" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    <option value="CE">Por Carreras</option>
                    <option value="DE">Por Departamentos</option>
                    <option value="DO">Por Docentes</option>
                    <option value="LA">Listado de alumnos que no han evaluado</option>
                    <option value="LM">Listado de docentes que no cuentan con evaluación</option>
                    <!--<option value="MA">Por Materias</option>-->
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@endsection


