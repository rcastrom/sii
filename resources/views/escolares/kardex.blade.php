@extends('adminlte::page')

@section('title', 'Kardex')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <p class="card-text">Número de control: {{ $control }}</p>
        <p class="card-text">{{ $ncarrera->nombre_carrera }} Ret {{ $alumno->reticula }}</p>
        <p class="card-text">Semestre {{ $alumno->semestre }}</p>
        <p class="card-text">Estatus actual: {{ $estatus->descripcion }}</p>
    </x-information>
    <x-additional>
        @slot('header','Kardex de alumno')
            @php
                $suma_total=0; $calificaciones_totales=0; $j=1;
                $tipos_mat=array("E2","3","4","5","R1","R2","RO","RP");
                $tipos_aprob=array('AC','RC','93','92','91','RU','PG');
            @endphp
        @foreach($calificaciones as $key=>$value)
            @if(!empty($value))
                <caption>{{$nombre_periodo[$key]->identificacion_larga}}</caption>
                <table class="table table-responsive table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Materia</th>
                        <th>Calificación</th>
                        <th>Oportunidad</th>
                        <th>Créditos</th>
                        <th>Observaciones</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=1;
                        $suma_creditos=0;
                        $promedio_semestre=0;
                        $suma_semestre=0;
                        $cal_sem=0;
                        $materias=1;
                    @endphp
                    @foreach($value as $data)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $data->nombre_completo_materia }}</td>
                            <td>{{ $data->calificacion <= 70 && in_array($data->tipo_evaluacion,$tipos_aprob)?'AC':($data->calificacion < 70?"NA":$data->calificacion) }}</td>
                            <td>{{ $data->descripcion_corta_evaluacion }}</td>
                            <td>{{ $data->creditos_materia }}</td>
                            @if(($data->calificacion < 70 && in_array($data->tipo_evaluacion,$tipos_mat)) || ($data->calificacion < 70 && $data->tipo_evaluacion == 'EA'))
                                @if($alumno->plan_de_estudios==3||$alumno->plan_de_estudios==4)
                                    <td>A curso especial</td>
                                @else
                                    <td></td>
                                @endif
                            @else
                                <td></td>
                            @endif
                            <td>
                                <a href="{{route('kardex.edit',$data->id)}}"><i class="fas fa-edit"></i>Editar</a>
                                <a href="{{route('kardex.show',$data->id)}}"><i class="fas fa-trash"></i>Eliminar</a>
                            </td>
                        </tr>
                        @if($data->calificacion>=70||in_array($data->tipo_evaluacion,$tipos_aprob))
                            @php
                                $suma_creditos+=$data->creditos_materia;
                            @endphp
                            @if(!in_array($data->tipo_evaluacion,$tipos_aprob))
                                @php
                                    $cal_sem+=$data->calificacion;
                                    $calificaciones_totales+=$data->calificacion;
                                    $materias+=1;
                                    $j++;
                                @endphp
                            @endif
                            @php
                                $suma_total+=$data->creditos_materia;
                            @endphp
                        @else($data->calificacion<70&&!in_array($data->tipo_evaluacion,$tipos_aprob))
                            @php
                                $materias+=1;
                            @endphp
                        @endif
                        @php
                            $suma_semestre+=$data->creditos_materia;
                            $i++;
                        @endphp
                    @endforeach
                    @php
                        $promedio=($materias-1)==0?0:round($cal_sem/($materias-1),2);
                    @endphp
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Créditos Aprobados/Solicitados</td>
                        <td>{{$suma_creditos}}/{{$suma_semestre}}</td>
                        <td>Promedio del semestre</td>
                        <td>{{$promedio}}</td>
                    </tr>
                    </tfoot>
                </table>
            @endif
        @endforeach
        <table class="table table-responsive" id="cssTable">
            <thead>
            <tr>
                <th>Porcentaje de avance</th>
                <th>Promedio General</th>
            </tr>
            <tr>
                <td>
                    @php
                        $avance1=$suma_total==0?0:round(($suma_total/$ncarrera->creditos_totales)*100,2);
                        $avance = ($avance1>=100)?100:$avance1;
                    @endphp
                        {{$avance."%"}}
                </td>
                <td>
                    @php
                        $prom_tot=($j-1)==0?0:round($calificaciones_totales/($j-1),2);
                    @endphp
                        {{$prom_tot}}
                </td>
            </tr>
            </thead>
        </table>
    </x-additional>
    <x-information :encabezado="$encabezado2">
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <ul>
                        <li><a href="{{route('kardex.create',['control'=>$control])}}">Agregar materia</a></li>
                        <li><a href="{{route('escolares.imprimirkardex',['control'=>$control])}}" target="_blank">Imprimir</a></li>
                    </ul>
                </div>
            </div>
    </x-information>

@stop

@section('css')
    #cssTable td
    {
        text-align: center;
        vertical-align: middle;
    }
@endsection
