@extends('adminlte::page')

@section('title', 'Aulas')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p class="card-text">Período {{$nperiodo->identificacion_larga}} <br>
            Aula: {{$aula}} </p>
        <div class="card-header">Lunes</div>
        @foreach($lunes as $monday)
            @if(\Illuminate\Support\Facades\DB::table('grupos')->where('periodo',$periodo)->where('materia',$monday->materia)->where('grupo',$monday->grupo)->whereNull('paralelo_de')->count()>0)
                <div class="row">
                    <div class="col-md-4">
                        @php
                            $hora1=strtotime($monday->hora_inicial);
                            $hora2=strtotime($monday->hora_final);
                        @endphp
                        {{date("H:i",$hora1)."-".date("H:i",$hora2)}}
                    </div>
                    <div class="col-md-4">
                        <span style="font-size: small;">{{$monday->nombre_abreviado_materia}}</span>
                    </div>
                    <div class="col-md-4">
                                    <span style="font-size: small;">
                                        @if(!empty($monday->docente))
                                            <?php $nombre=\Illuminate\Support\Facades\DB::table('personal')->where('id',$monday->docente)->first(); ?>
                                            {{$nombre->apellidos_empleado." ".$nombre->nombre_empleado}}
                                        @else
                                            PENDIENTE POR ASIGNAR
                                        @endif
                                    </span>
                    </div>
                </div>
            @endif
        @endforeach
        <div class="card-header">Martes</div>
            @foreach($martes as $tuesday)
                @if(\Illuminate\Support\Facades\DB::table('grupos')->where('periodo',$periodo)->where('materia',$tuesday->materia)->where('grupo',$tuesday->grupo)->whereNull('paralelo_de')->count()>0)
                    <div class="row">
                        <div class="col-md-4">
                            @php
                                $hora1=strtotime($tuesday->hora_inicial);
                                $hora2=strtotime($tuesday->hora_final);
                            @endphp
                            {{date("H:i",$hora1)."-".date("H:i",$hora2)}}
                        </div>
                        <div class="col-md-4">
                            <span style="font-size: small;">{{$tuesday->nombre_abreviado_materia}}</span>
                        </div>
                        <div class="col-md-4">
                                    <span style="font-size: small;">
                                        @if(!empty($tuesday->docente))
                                            <?php $nombre=\Illuminate\Support\Facades\DB::table('personal')->where('id',$tuesday->docente)->first(); ?>
                                            {{$nombre->apellidos_empleado." ".$nombre->nombre_empleado}}
                                        @else
                                            PENDIENTE POR ASIGNAR
                                        @endif
                                    </span>
                        </div>
                    </div>
                @endif
            @endforeach
        <div class="card-header">Miércoles</div>
            @foreach($miercoles as $wed)
                @if(\Illuminate\Support\Facades\DB::table('grupos')->where('periodo',$periodo)->where('materia',$wed->materia)->where('grupo',$wed->grupo)->whereNull('paralelo_de')->count()>0)
                    <div class="row">
                        <div class="col-md-4">
                            @php
                                $hora1=strtotime($wed->hora_inicial);
                                $hora2=strtotime($wed->hora_final);
                            @endphp
                            {{date("H:i",$hora1)."-".date("H:i",$hora2)}}
                        </div>
                        <div class="col-md-4">
                            <span style="font-size: small;">{{$wed->nombre_abreviado_materia}}</span>
                        </div>
                        <div class="col-md-4">
                                    <span style="font-size: small;">
                                        @if(!empty($wed->docente))
                                            <?php $nombre=\Illuminate\Support\Facades\DB::table('personal')->where('id',$wed->docente)->first(); ?>
                                            {{$nombre->apellidos_empleado." ".$nombre->nombre_empleado}}
                                        @else
                                            PENDIENTE POR ASIGNAR
                                        @endif
                                    </span>
                        </div>
                    </div>
                @endif
            @endforeach
        <div class="card-header">Jueves</div>
            @foreach($jueves as $thu)
                @if(\Illuminate\Support\Facades\DB::table('grupos')->where('periodo',$periodo)->where('materia',$thu->materia)->where('grupo',$thu->grupo)->whereNull('paralelo_de')->count()>0)
                    <div class="row">
                        <div class="col-md-4">
                            @php
                                $hora1=strtotime($thu->hora_inicial);
                                $hora2=strtotime($thu->hora_final);
                            @endphp
                            {{date("H:i",$hora1)."-".date("H:i",$hora2)}}
                        </div>
                        <div class="col-md-4">
                            <span style="font-size: small;">{{$thu->nombre_abreviado_materia}}</span>
                        </div>
                        <div class="col-md-4">
                                    <span style="font-size: small;">
                                        @if(!empty($thu->docente))
                                            <?php $nombre=\Illuminate\Support\Facades\DB::table('personal')->where('id',$thu->docente)->first(); ?>
                                            {{$nombre->apellidos_empleado." ".$nombre->nombre_empleado}}
                                        @else
                                            PENDIENTE POR ASIGNAR
                                        @endif
                                    </span>
                        </div>
                    </div>
                @endif
            @endforeach
        <div class="card-header">Viernes</div>
            @foreach($viernes as $fri)
                @if(\Illuminate\Support\Facades\DB::table('grupos')->where('periodo',$periodo)->where('materia',$fri->materia)->where('grupo',$fri->grupo)->whereNull('paralelo_de')->count()>0)
                    <div class="row">
                        <div class="col-md-4">
                            @php
                                $hora1=strtotime($fri->hora_inicial);
                                $hora2=strtotime($fri->hora_final);
                            @endphp
                            {{date("H:i",$hora1)."-".date("H:i",$hora2)}}
                        </div>
                        <div class="col-md-4">
                            <span style="font-size: small;">{{$fri->nombre_abreviado_materia}}</span>
                        </div>
                        <div class="col-md-4">
                                    <span style="font-size: small;">
                                        @if(!empty($fri->docente))
                                            <?php $nombre=\Illuminate\Support\Facades\DB::table('personal')->where('id',$fri->docente)->first(); ?>
                                            {{$nombre->apellidos_empleado." ".$nombre->nombre_empleado}}
                                        @else
                                            PENDIENTE POR ASIGNAR
                                        @endif
                                    </span>
                        </div>
                    </div>
                @endif
            @endforeach
        <div class="card-header">Sábado</div>
            @foreach($sabado as $sat)
                @if(\Illuminate\Support\Facades\DB::table('grupos')->where('periodo',$periodo)->where('materia',$sat->materia)->where('grupo',$sat->grupo)->whereNull('paralelo_de')->count()>0)
                    <div class="row">
                        <div class="col-md-4">
                            @php
                                $hora1=strtotime($sat->hora_inicial);
                                $hora2=strtotime($sat->hora_final);
                            @endphp
                            {{date("H:i",$hora1)."-".date("H:i",$hora2)}}
                        </div>
                        <div class="col-md-4">
                            <span style="font-size: small;">{{$sat->nombre_abreviado_materia}}</span>
                        </div>
                        <div class="col-md-4">
                                    <span style="font-size: small;">
                                        @if(!empty($sat->docente))
                                            <?php $nombre=\Illuminate\Support\Facades\DB::table('personal')->where('id',$sat->docente)->first(); ?>
                                            {{$nombre->apellidos_empleado." ".$nombre->nombre_empleado}}
                                        @else
                                            PENDIENTE POR ASIGNAR
                                        @endif
                                    </span>
                        </div>
                    </div>
                @endif
            @endforeach
    </x-information>

@stop
