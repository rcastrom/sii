@extends('adminlte::page')

@section('title', 'Calificaciones')

@section('content_header')
    <h1>Estudiantes</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
       @foreach($materias as $materia)
           <div class="card mb-4">
               <div class="card-header" style="background-color: #00AA9E;">
                   {{$materia->nmateria}}
               </div>
           @php
               if(\Illuminate\Support\Facades\DB::table('parciales')
              ->where('periodo',$periodo)
              ->where('materia',$materia->mat)
              ->where('grupo',$materia->gpo)
              ->count()==0){
           @endphp
           <div class="card-body">
               <div class="row">
                   El docente no ha entregado calificaciones parciales
               </div>
           </div>
           @php
           }else{
              $evaluado=\Illuminate\Support\Facades\DB::table('parciales')
              ->where('periodo',$periodo)
              ->where('materia',$materia->mat)
              ->where('grupo',$materia->gpo)
              ->max('unidad');
           @endphp
                    @for($i=1;$i<=$evaluado;$i++)
                            @php
                                $registro=\Illuminate\Support\Facades\DB::table('parciales')
                                    ->where('periodo',$periodo)
                                    ->where('materia',$materia->mat)
                                    ->where('grupo',$materia->gpo)
                                    ->where('unidad',$i)
                                    ->select('id')
                                    ->first();
                                $cal=\Illuminate\Support\Facades\DB::table('calificaciones_parciales')
                                    ->where('parcial',$registro->id)
                                    ->where('no_de_control',$control)
                                    ->select('calificacion')
                                    ->first();
                                $calificacion=empty($cal)?'*':$cal->calificacion;
                            @endphp
                  <div class="row">
                      <div class="col-sm-6">
                          <p class="card-text">Unidad {{$i}}</p>
                      </div>
                      <div class="col-sm-6">
                          <p class="card-text">{{$calificacion}}</p>
                      </div>
                  </div>
                    @endfor
              </div>
        @php } @endphp
       @endforeach

            <div class="row">
                En caso de que aparezca el símbolo "*", significa que no hay registro de
                la calificación para esa materia - unidad
            </div>

    </x-information>
@stop


