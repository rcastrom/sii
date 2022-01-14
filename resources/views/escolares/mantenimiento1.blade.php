@extends('adminlte::page')

@section('title', 'Mantenimiento')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>El siguiente módulo, es para realizar el mantenimiento a la base de datos con respecto
            al período en curso, que consiste en lo siguiente:
        <ol>
            <li>Dar de baja temporal a los no reinscritos</li>
            <li>Corregir el semestre de los estudiantes</li>
            <li>Actualizar el número de estudiantes inscritos por grupo</li>
        </ol>
        Señale del formulario siguiente, la acción a realizar
        <form method="post" action="{{route('escolares.mantenimiento')}}" role="form">
            @csrf
            <legend>Mantenimiento a realizar</legend>
            <div class="form-group">
                <label for="accion"> Indique la acción a realizar </label>
                <select name="accion" id="accion" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    <option value="1">Realizar baja temporal</option>
                    <option value="2">Corregir el semestre </option>
                    <option value="3">Inscritos por grupo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop

