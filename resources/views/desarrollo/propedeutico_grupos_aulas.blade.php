@extends('adminlte::page')

@section('title', 'Gpo Propedéutico')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-header">
            <div class="card-title">
                Asignación de aula a grupo
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
                    <form action="{{route('desarrollo.asignar_aula_propedeutico')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <select name="aula" id="aula" class="form-control" required>
                                <option value="" selected>--Seleccione el aula</option>
                                @foreach($aulas as $aula)
                                    <option value="{{$aula->id}}">Aula {{$aula->aula}}</option>
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
    <x-additional>
        @slot('header','Aulas asignadas para grupos propedéuticos existentes en el período')
        @if($bandera)
            <table class="table table-responsive table-bordered table-sm table-hover">
                <thead>
                <tr>
                    <th colspan="2"></th>
                    <th colspan="5">Horario</th>
                </tr>
                <tr>
                    <th>Materia</th>
                    <th>Aula</th>
                    <td>L</td>
                    <td>M</td>
                    <td>M</td>
                    <td>J</td>
                    <td>V</td>
                </tr>
                </thead>
                <tbody>
                @foreach($salones as $salon)
                    <tr>
                        <td>Materia: {{ $salon->materia }}, grupo: {{ $salon->grupo }}</td>
                        <td>Aula: {{$salon->aula}}</td>
                        @for($i=1;$i<=5;$i++)
                            <td>{{ formatHora($salon->{"entrada_$i"})."-".formatHora($salon->{"salida_$i"})}}</td>
                        @endfor
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            No hay aulas asignadas al momento
        @endif
    </x-additional>
@stop

@php
    function formatHora($hora) {
        return is_null($hora) ? null : date("H:i", strtotime($hora));
    }
@endphp
