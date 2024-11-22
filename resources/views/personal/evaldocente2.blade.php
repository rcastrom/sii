@extends('adminlte::page')

@section('title', 'Evaluación docente')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-body">
            <div class="card-header">Periodo {{$nperiodo->identificacion_corta}}</div>
            <div class="row">
                <div class="col">Clave</div>
                <div class="col">Grupo</div>
                <div class="col">Materia</div>
                <div class="col">Inscritos</div>
                <div class="col">Evaluaron</div>
                <div class="col">% Eval</div>
            </div>
            @php
                $alumnos_totales=0;
                $alumnos_evaluados=0;
            @endphp
            @foreach($materias as $materia)
                <div class="row">
                    <div class="col">{{$materia->materia}}</div>
                    <div class="col">{{$materia->grupo}}</div>
                    <div class="col">{{$materia->nombre_completo_materia}}</div>
                    @php
                        $alumnos_totales+=$materia->alumnos_inscritos;
                        $alumnos_evaluados+=$materia->evaluaron;
                    @endphp
                    <div class="col">{{$materia->alumnos_inscritos}}</div>
                    <div class="col">{{$materia->evaluaron}}</div>
                    <div class="col">{{round(($materia->evaluaron/$materia->alumnos_inscritos)*100,2)}}%</div>
                </div>
            @endforeach
            <div class="row">
                <div class="col-4">Total alumnos inscritos </div>
                <div class="col-4">Total alumnos que evaluaron </div>
                <div class="col-4">Porcentaje de evaluación </div>
            </div>
            <div class="row">
                <div class="col-4">{{$alumnos_totales}}</div>
                <div class="col-4">{{$alumnos_evaluados}}</div>
                <div class="col-4">{{round(($alumnos_evaluados/$alumnos_totales)*100,2)}}%</div>
            </div>
        </div>
        <div class="card-body">
            <div class="card-header">Aspectos a evaluar</div>
            <div class="row">
                <div class="col">Aspectos evaluados</div>
                <div class="col">Puntaje</div>
                <div class="col">Calificación</div>
            </div>
            @foreach($resultados as $key=>$value)
                <div class="row">
                    <div class="col">{{$value["aspecto"].") ".$value["descripcion"]}}</div>
                    <div class="col">{{$value["porcentaje"]}}</div>
                    <div class="col">{{$value["calificacion"]}}</div>
                </div>
            @endforeach
            <h5>Promedio General</h5>
            <div class="row">
                <div class="col-sm-6 col-md-4">{{$promedio}}</div>
                <div class="col-sm-6 col-md-8">{{$cal}}</div>
            </div>
        </div>
        <div class="card">
            <img src="{{route('personal.grafica_evaluacion_docente',['periodo'=>$periodo,'docente'=>$personal,'promedio'=>$promedio])}}" alt="">
        </div>
    </x-information>
@stop
