@extends('adminlte::page')

@section('title', 'Materias')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4>Carrera: {{$ncarrera->nombre_carrera}}</h4>
        <form method="post" action="{{route('escolares.materia_editar')}}" role="form">
            @csrf
            <div class="form-group">
                <label for="materia">Materia por modificar</label>
                <select name='materia' id="materia" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    @foreach($materias as $materia)
                        <option value="{{$materia->materia}}">{{($materia->materia)." ".$materia->nombre_abreviado_materia}}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="carrera" value="{{$carrera}}">
            <input type="hidden" name="reticula" value="{{$reticula}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop

