@extends('adminlte::page')

@section('title', 'Carrera - Retícula')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Carrera {{$ncarrera->nombre_reducido}}</h4><br>
        <form method="post" action="{{route('escolares.vista_reticula')}}" role="form">
            @csrf
            <div class="form-group">
                <label for="espe">Seleccione la vista reticular, en base a la especialidad asignada</label>
                <select name="espe" id="espe" required class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    @foreach($espe as $especialidades)
                        <option value="{{$especialidades->especialidad}}">{{$especialidades->nombre_especialidad}}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="carrera" value="{{$carrera}}">
            <input type="hidden" name="reticula" value="{{$reticula}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
