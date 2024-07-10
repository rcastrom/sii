@extends('adminlte::page')

@section('title', 'Evaluación Docente')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        Seleccione el período para indicar los parámetros correspondientes a la
        evaluación al docente.
        <form action="{{route('desarrollo.periodo_evaluacion')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="periodo">Período a establecer</label>
                <select name="periodo" id="periodo" required class="form-control">
                    @foreach($periodos as $periodo)
                        <option value="{{$periodo->periodo}}" {{$periodo->periodo==$periodo_actual?' selected':''}}>{{$periodo->identificacion_larga}}</option>
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
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@endsection


