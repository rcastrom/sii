@extends('adminlte::page')

@section('title', 'Evaluación docente')

@section('content_header')
    <h1>Estudiantes</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-body">
            <h4 class="card-title">Evaluación docente del período {{$nombre_periodo->identificacion_corta}}</h4>
            <br>
            <h5>Docente: {{$nombre_docente}}</h5>
            <h6>Materia: {{$nmat->nombre_abreviado_materia}}</h6>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    Para cada una de las preguntas que se te presentan a continuación, evalúa en la escala
                    del 1 al 5 de acuerdo a lo siguiente:
                    <table class="table table-responsive">
                        <tr>
                            <td>1.- Altamente en desacuerdo</td>
                            <td>2.- En desacuerdo</td>
                            <td>3.- Indiferente</td>
                            <td>4.- De acuerdo</td>
                            <td>5.- Totalmente de acuerdo</td>
                        </tr>
                    </table>
                </div>
            </div>
            <form method="post" action="{{route('alumnos.eval_docente')}}" role="form">
                @csrf
                @foreach($preguntas as $pregunta)
                    <div class="row mb-3">
                        <label for="{{$pregunta->no_pregunta}}" class="col-sm-6 col-md-8 col-form-label">
                            {{$pregunta->pregunta}}</label>
                        <input type="number" class="form-control col-sm-6 col-md-4"
                               name="{{$pregunta->no_pregunta}}" id="{{$pregunta->no_pregunta}}"
                               required min="1" max="5">
                    </div>
                @endforeach
                <input type="hidden" name="materia" value="{{$mat}}">
                <input type="hidden" name="gpo" value="{{$gpo}}">
                <input type="hidden" name="docente" value="{{$docente}}">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </form>
        </div>
    </x-information>
@stop


