@extends('adminlte::page')

@section('title', 'Gpo Propedéutico')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-header">
            <div class="card-title">
                Asignación de docente a grupo
            </div>
        </div>

        <div class="card-body">
            <table class="table table-responsive table-bordered table-sm table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th colspan="5">Horario</th>
                </tr>
                <tr>
                    <th>Materia</th>
                        <td>L</td>
                        <td>M</td>
                        <td>M</td>
                        <td>J</td>
                        <td>V</td>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Materia: {{ $grupo->materia }}, grupo: {{ $grupo->grupo }}</td>
                        @for($i=1;$i<=5;$i++)
                            <td>{{ formatHora($grupo->{"entrada_$i"})."-".formatHora($grupo->{"salida_$i"})}}</td>
                        @endfor
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-8">
                    <form action="{{route('desarrollo.asignar_maestro_propedeutico')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <select name="docente" id="docente" class="form-control" required>
                                <option value="" selected>--Seleccione al maestro</option>
                                @foreach($maestros as $maestro)
                                    <option value="{{$maestro->id}}">{{$maestro->apellidos_empleado." ".$maestro->nombre_empleado}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="id" value="{{ $grupo->id }}">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </form>
                </div>
            </div>
        </div>
    </x-information>
@stop

@php
    function formatHora($hora) {
        return is_null($hora) ? null : date("H:i", strtotime($hora));
    }
@endphp
