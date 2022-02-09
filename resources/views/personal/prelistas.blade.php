@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Período {{$nperiodo->identificacion_larga}}</h4><br>
            @foreach($materias as $materia)
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        Clave materia {{$materia->materia}} Grupo {{$materia->grupo}}
                    </div>
                    <div class="col-sm-12 col-md-4">
                        Nombre {{$materia->nombre_completo_materia}}
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="row">
                            <div class="col-sm-3">
                                <i class="fas fa-list-ol"></i>
                                <a href="/personal/periodo/listas/{{base64_encode($periodo)}}/{{base64_encode($materia->materia)}}/{{base64_encode($materia->grupo)}}" title="Listas en PDF">PDF</a>
                            </div>
                            <div class="col-sm-3">
                                <i class="far fa-file-excel"></i>
                                <a href="/personal/periodo/excel/{{base64_encode($periodo)}}/{{base64_encode($materia->materia)}}/{{base64_encode($materia->grupo)}}" title="Listas en Excel">Excel</a>
                            </div>
                            <div class="col-sm-3">
                                <i class="fas fa-sort-numeric-up"></i>
                                <a href="/personal/periodo/evaluar/{{base64_encode($periodo)}}/{{base64_encode($materia->materia)}}/{{base64_encode($materia->grupo)}}" title="Calificación Final">Final</a>
                            </div>
                            <div class="col-sm-3">
                                <i class="fas fa-print"></i>
                                <a href="/personal/periodo/acta/{{base64_encode($periodo)}}/{{base64_encode($materia->materia)}}/{{base64_encode($materia->grupo)}}" title="Acta Final">Acta</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
    </x-information>
@stop
