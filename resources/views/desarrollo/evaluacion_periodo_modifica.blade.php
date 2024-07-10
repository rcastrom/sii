@extends('adminlte::page')

@section('title', 'Evaluación Docente')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        <h5>Período: {{$datos_periodo->identificacion_corta}}</h5>
        Indique los datos necesarios para dar de alta el período de evaluación al docente
        <form action="{{route('periodos.update',$datos->id)}}" method="post" role="form">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="encuesta">Indique el nivel escolar a evaluar</label>
                <select name="encuesta" id="encuesta" class="form-control" required>
                    <option value="A" {{$datos->encuesta=="A"?'selected':''}}>Licenciatura</option>
                    <option value="D" {{$datos->encuesta=="D"?'selected':''}}>Posgrado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="inicio">Fecha de inicio</label>
                <input type="date" name="inicio" id="inicio"
                       class="form-control" value="{{$datos->fecha_inicio}}" required>
            </div>
            <div class="form-group">
                <label for="final">Fecha de término</label>
                <input type="date" name="final" id="final"
                       class="form-control" value="{{$datos->fecha_final}}" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@endsection



