@extends('adminlte::page')

@section('title', 'Retícula')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-additional>
        @slot('header','Retícula de la carrera - especialidad')
        <br>
        <h4 class="card-title">{{$ncarrera->nombre_reducido}} Ret {{$ncarrera->reticula}}</h4><br>
        <h4 class="card-title">Especialidad {{$espe->nombre_especialidad}}</h4><br>
        <table align="center" border="1" bordercolor="#000000">
            <tr>
                @for($i=1; $i<=10; $i++)
                    <th class="medium_center">Semestre<br>{{$i}}</th>
                @endfor
            </tr>
            @for($renglon=1; $renglon<=8; $renglon++)
                <tr>
                    @for($semestre=1; $semestre<=10; $semestre++)
                        @if(isset($array_reticula[$renglon][$semestre]))
                          @php
                            $materia = $array_reticula[$renglon][$semestre]['materia'];
                            $clave = $array_reticula[$renglon][$semestre]['clave'];
                            $horas_teoricas = $array_reticula[$renglon][$semestre]['horas_teoricas'];
                            $horas_practicas = $array_reticula[$renglon][$semestre]['horas_practicas'];
                            $creditos_materia = $array_reticula[$renglon][$semestre]['creditos_materia'];
                            $bandera=1;
                          @endphp
                        @else
                            @php
                                $bandera=0;
                            @endphp
                        @endif
                        @if($bandera)
                            <td align="center" height="80" width="90" class="small_center azul">
                                {{$materia}}<br>{{$clave}}<br>
                                {{$horas_teoricas}}-{{$horas_practicas}}-{{$creditos_materia}}
                            </td>
                        @else
                            <td align="center" height="80" width="90" class="small_center"></td>
                        @endif
                    @endfor
                </tr>
            @endfor
        </table>
    </x-additional>
@stop
@section('css')
    <link href="{{ asset('css/reticula.css') }}" rel="stylesheet">
@stop
