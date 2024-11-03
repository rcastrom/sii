@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Materia {{$nmateria->nombre_completo_materia}}</h4><br>
        <h3>Unidad: {{$unidad}}</h3><br>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('parcial.update',['parcial'=>$id])}}" method="post" class="form">
            @csrf
            @method('PATCH')
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Control</th>
                        <th>Alumno</th>
                        <th>Evaluación</th>
                        <th>No presentó</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i=1;
                    @endphp
                    @foreach($alumnos as $alumno)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$alumno->no_de_control}}</td>
                            <td>{{$alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno}}</td>
                            <td><input type="number" name="{{"al".$alumno->id}}"
                                       id="{{"al".$alumno->id}}" class="form-control"
                                size="3" min="0" max="100" required value="{{$alumno->calificacion}}"></td>
                            <td style="text-align: center;"><input type="checkbox" name="{{"d".$alumno->no_de_control}}"
                                                                   id="{{"al".$alumno->no_de_control}}"
                                                                   class="form-check-input" value="1"
                                    {{$alumno->desertor?" checked":""}}></td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <button type="submit" class="btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
