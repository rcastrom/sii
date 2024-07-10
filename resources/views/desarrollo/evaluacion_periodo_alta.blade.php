@extends('adminlte::page')

@section('title', 'Evaluación Docente')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        <h5>Período: {{$datos_periodo->identificacion_corta}}</h5>
        Indique los datos necesarios para dar de alta el período de evaluación al docente
        <form action="{{route('periodos.store')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="encuesta">Indique el nivel escolar a evaluar</label>
                <select name="encuesta" id="encuesta" class="form-control">
                    <option value="A" selected>Licenciatura</option>
                    <option value="D">Posgrado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="inicio">Fecha de inicio</label>
                <input type="date" name="inicio" id="inicio" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="final">Fecha de término</label>
                <input type="date" name="final" id="final" class="form-control" required>
            </div>
            <input type="hidden" name="periodo" value="{{$periodo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@endsection



