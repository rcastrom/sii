@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Horario Apoyo</h4>
                        <table class="table table-responsive">
                            <thead class="thead-light">
                            <tr>
                                <th>Actividad</th>
                                <th>L</th>
                                <th>M</th>
                                <th>M</th>
                                <th>J</th>
                                <th>V</th>
                                <th>S</th>
                                <th>Hrs/semana</th>
                                <th colspan="2">Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $hlap=0; $hmap=0; $hmmap=0; $hjap=0; $hvap=0; $hsap=0; $hadp=0;?>
                            @foreach($apoyo as $admvo)
                                    <?php $suma_semana3=0;?>
                                <tr>
                                    <td>{{$admvo->descripcion_actividad}}</td>
                                        <?php
                                        for($i=2;$i<=7;$i++){
                                            $dias=\Illuminate\Support\Facades\DB::table('horarios')->where('periodo',$periodo)
                                                ->where('docente',$docente)->where('consecutivo',$admvo->consecutivo)
                                                ->where('tipo_horario','Y')
                                                ->where('dia_semana',$i)->select('hora_inicial','hora_final')->first();
                                            if(!empty($dias)){
                                                $entrada=\Carbon\Carbon::parse($dias->hora_inicial);
                                                $salida=\Carbon\Carbon::parse($dias->hora_final);
                                                $horas=$entrada->diff($salida)->format('%h');
                                                $suma_semana3+=$horas;
                                                switch ($i){
                                                    case 2: $hlap+=$horas; break;
                                                    case 3: $hmap+=$horas; break;
                                                    case 4: $hmmap+=$horas; break;
                                                    case 5: $hjap+=$horas; break;
                                                    case 6: $hvap+=$horas; break;
                                                    case 7: $hsap+=$horas; break;
                                                }
                                                echo "<td>".$dias->hora_inicial."-".$dias->hora_final."</td>";
                                            }else{
                                                echo "<td>"."</td>";
                                            }
                                        }
                                        $hadp+=$suma_semana3;
                                        ?>
                                    <td align="center">{{$suma_semana3}}</td>
                                    <td><i class="fas fa-wrench"></i>
                                        <a href="{{route('academicos.modhapoyo',['periodo'=>$periodo,'docente'=>$docente,'consecutivo'=>$admvo->consecutivo])}}">Modificar</a></td>
                                    <td><i class="fas fa-trash-alt"></i>
                                        <a href="{{route('academicos.delhapoyo',['periodo'=>$periodo,'docente'=>$docente,'consecutivo'=>$admvo->consecutivo])}}">Eliminar</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                                <td align="center">{{$hlap}}</td>
                                <td align="center">{{$hmap}}</td>
                                <td align="center">{{$hmmap}}</td>
                                <td align="center">{{$hjap}}</td>
                                <td align="center">{{$hvap}}</td>
                                <td align="center">{{$hsap}}</td>
                                <td align="center">{{$hadp}}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-information>
@stop
