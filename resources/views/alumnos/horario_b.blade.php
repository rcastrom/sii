@extends('layouts.estudianhambre')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Módulo Estudiantes</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
                        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Horario del periodo {{ $nombre_periodo[0]->identificacion_larga }}</div>
                <div class="card-body">
                   <table class="table table-responsive">
                       <thead class="thead-light">
                       <tr>
                           <th>Mat/Gpo</th>
                           <th>Nombre</th>
                           <th>Docente</th>
                           <th>Créditos</th>
                       </tr>
                       </thead>
                       <tbody>
                       @foreach($datos_horario as $value)
                           <tr>
                               <td>{{$value->mat}}/{{$value->gpo}}</td>
                               <td>{{$value->nmateria}}</td>
                               <td>{{$value->ndocente}}</td>
                               <td>{{$value->creditos}}</td>
                           </tr>
                       @endforeach
                       </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
    <form action="{{route('alumno.baja_m')}}" method="post">
        @csrf
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header">Horario del periodo {{ $nombre_periodo[0]->identificacion_larga }}</div>
                    <div class="card-body">
                        <table class="table table-responsive">
                            <thead class="thead-light">
                            <tr>
                                <th>Nombre</th>
                                <th>¿Cancelar?</th>
                                <th>L</th>
                                <th>M</th>
                                <th>M</th>
                                <th>J</th>
                                <th>V</th>
                                <th>S</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $periodo=$periodo_actual[0]->periodo;?>
                            @foreach($datos_horario as $value)
                                <tr>
                                    <td>{{$value->nmateria}}</td>
                                    <td><input type="radio" name="materia" value="{{$value->mat.'_'.$value->gpo}}" required></td>
                                    <?php
                                    for ($i=2;$i<=7;$i++){
                                        $hora=\Illuminate\Support\Facades\DB::table('horarios')
                                            ->select('hora_inicial','hora_final','aula')
                                            ->where('periodo',$periodo)
                                            ->where('materia',$value->mat)
                                            ->where('grupo',$value->gpo)
                                            ->where('dia_semana',$i)
                                            ->first();
                                        echo empty($hora->hora_inicial)?
                                            "<td></td>":
                                            "<td>".$hora->hora_inicial."/".$hora->hora_final."<br>(".$hora->aula.")</td>";
                                    }
                                    ;?>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
