@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Materia {{$nmateria->nombre_completo_materia}}</h4><br>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>No control</th>
                                    <th>Alumno</th>
                                    @for($i=1;$i<=$maximo;$i++)
                                        <th>{{$i}}</th>
                                    @endfor
                                    <th>Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i=1;
                                @endphp
                                @foreach($alumnos as $alumno)
                                    @php
                                        $suma=0;
                                        $acreditadas=0;
                                        $k=0;
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$alumno->no_de_control}}</td>
                                        <td>{{$alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno}}</td>
                                            <?php
                                            for($j=1;$j<=$maximo;$j++){
                                                $parcial=\Illuminate\Support\Facades\DB::table('parciales')
                                                    ->where([
                                                        'periodo' => $periodo,
                                                        'docente' => $doc->id,
                                                        'materia' => $materia,
                                                        'grupo' => $grupo,
                                                        'unidad' => $j
                                                    ])->select('id')
                                                    ->first();
                                                $cal=\Illuminate\Support\Facades\DB::table('calificaciones_parciales')
                                                    ->select('calificacion')
                                                    ->where('parcial',$parcial->id)
                                                    ->where('no_de_control',$alumno->no_de_control)
                                                    ->first();
                                                $calif=empty($cal)?"*":$cal->calificacion;
                                                if(!empty($cal)){
                                                    if($cal->calificacion>=70){
                                                        $suma+=$cal->calificacion;
                                                        $acreditadas++;
                                                        $k++;
                                                    }
                                                }
                                                echo "<td>".$calif."</td>";
                                            }
                                            ?>
                                        @php
                                            $i++;
                                        @endphp
                                        <td>{{$k==$maximo?round($suma/$acreditadas):0}}</td>
                                @endforeach

                                    </tr>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Aviso</th>
                                    <td colspan="2">En caso de aparecer el símbolo "*", significa que no ha registrado
                                        dicha calificación parcial</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-information>
@stop
