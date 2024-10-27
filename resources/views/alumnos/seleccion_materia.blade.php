@extends('adminlte::page')

@section('title', 'Reinscripción')

@section('content_header')
    <h1>Estudiantes</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-header">Materia {{ $nmateria->nombre_abreviado_materia }}</div>
        <div class="card-body">
            <form action="{{route('alumnos.seleccion')}}" method="post" role="form">
                @csrf
                <table class="table table-responsive">
                    <thead class="thead-light">
                    <tr>
                        <th>Grupo</th>
                        <th>Global</th>
                        <th>Estatus</th>
                        <th>Docente</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                        <th>Sábado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($info_grupos as $value)
                        <tr>
                            <td>{{$value->grupo}}</td>
                            <td>
                                <select name="{{'op_'.$materia.'_'.$value->grupo}}" id="{{'op_'.$materia.'_'.$value->grupo}}">
                                    <option value="S">Si</option>
                                    <option value="N" selected>No</option>
                                </select>
                            </td>
                            <td>
                                @if($value->capacidad_grupo>0)
                                    <input type="radio" name="materia" value="{{$materia.'_'.$value->grupo}}">Cursar
                                @else
                                    Cerrado
                                @endif
                            </td>
                            <td>
                                @if($value->docente=='' || $value->docente == null)
                                    Sin profesor asignado
                                @else
                                        <?php
                                        $doc=\Illuminate\Support\Facades\DB::table('personal')
                                        ->select('apellidos_empleado','nombre_empleado')->where('id',$value->docente)->first();
                                        echo $doc->apellidos_empleado.' '.$doc->nombre_empleado;
                                        ?>
                                @endif
                            </td>
                            @for($i=2;$i<=7;$i++)
                                <td>
                                        <?php
                                        $gpo=$value->grupo;
                                        $hora=\Illuminate\Support\Facades\DB::table('horarios')->where('periodo',$periodo)
                                            ->where('materia',$materia)->where('grupo',$gpo)->where('dia_semana',$i)->first();
                                        if(!empty($hora)){
                                            $horario=$hora->hora_inicial."-".$hora->hora_final."/".$hora->aula;
                                        }else{
                                            $horario='';
                                        }
                                        ?>
                                    {{$horario}}
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <input type="hidden" name="periodo" value="{{$periodo}}">
                <input type="hidden" name="repeticion" value="{{$repeticion}}">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </form>
        </div>
        </div>
    </x-information>
@stop
