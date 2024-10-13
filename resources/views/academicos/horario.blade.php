@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Período {{$nperiodo->identificacion_larga}}</h4><br>
        <h4 class="card-title">Horario Docente</h4>
        <table class="table table-responsive">
            <thead class="thead-light">
            <tr>
                <th>Materia</th>
                <th>Grupo</th>
                <th>L</th>
                <th>M</th>
                <th>M</th>
                <th>J</th>
                <th>V</th>
                <th>S</th>
                <th>Hrs/semana</th>
            </tr>
            </thead>
            <tbody>
            <?php $hl=0; $hm=0; $hmm=0; $hj=0; $hv=0; $hs=0; $hdoc=0;?>
            @foreach($info as $horario)
                    <?php $suma_semana=0;?>
                <tr>
                    <td>({{$horario->materia}}) {{$horario->nombre_abreviado_materia}}</td>
                    <td>{{$horario->grupo}}</td>
                        <?php
                        for($i=2;$i<=7;$i++){
                            $dias=\Illuminate\Support\Facades\DB::table('horarios')->where('periodo',$periodo)
                                ->where('docente',$docente)->where('materia',$horario->materia)->where('grupo',$horario->grupo)
                                ->where('tipo_horario','D')
                                ->where('dia_semana',$i)->select('hora_inicial','hora_final','aula')->first();
                            if(!empty($dias)){
                                $entrada=\Carbon\Carbon::parse($dias->hora_inicial);
                                $salida=\Carbon\Carbon::parse($dias->hora_final);
                                $horas=$entrada->diff($salida)->format('%h');
                                $suma_semana+=$horas;
                                switch ($i){
                                    case 2: $hl+=$horas; break;
                                    case 3: $hm+=$horas; break;
                                    case 4: $hmm+=$horas; break;
                                    case 5: $hj+=$horas; break;
                                    case 6: $hv+=$horas; break;
                                    case 7: $hs+=$horas; break;
                                }
                                echo "<td>".$dias->hora_inicial."-".$dias->hora_final."/".$dias->aula."</td>";
                            }else{
                                echo "<td>"."</td>";
                            }
                        }
                        $hdoc+=$suma_semana;
                        ?>
                    <td align="center">{{$suma_semana}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td align="center">{{$hl}}</td>
                <td align="center">{{$hm}}</td>
                <td align="center">{{$hmm}}</td>
                <td align="center">{{$hj}}</td>
                <td align="center">{{$hv}}</td>
                <td align="center">{{$hs}}</td>
                <td align="center">{{$hdoc}}</td>
            </tr>
            </tfoot>
        </table>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Horario Actividades de apoyo</h4>
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
                                </tr>
                                </thead>
                                <tbody>
                                <?php $hlap=0; $hmap=0; $hmmap=0; $hjap=0; $hvap=0; $hsap=0; $hadp=0;?>
                                @foreach($apoyo as $ayuda)
                                        <?php $suma_semana2=0;?>
                                    <tr>
                                        <td>{{$ayuda->descripcion_actividad}}</td>
                                            <?php
                                            for($i=2;$i<=7;$i++){
                                                $dias=\Illuminate\Support\Facades\DB::table('horarios')->where('periodo',$periodo)
                                                    ->where('docente',$docente)->where('consecutivo',$ayuda->consecutivo)
                                                    ->where('tipo_horario','Y')
                                                    ->where('dia_semana',$i)->select('hora_inicial','hora_final')->first();
                                                if(!empty($dias)){
                                                    $entrada=\Carbon\Carbon::parse($dias->hora_inicial);
                                                    $salida=\Carbon\Carbon::parse($dias->hora_final);
                                                    $horas=$entrada->diff($salida)->format('%h');
                                                    $suma_semana2+=$horas;
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
                                            $hadp+=$suma_semana2;
                                            ?>
                                        <td align="center">{{$suma_semana2}}</td>
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
                                <tr>
                                    <td></td>
                                    <td align="center">{{$hl+$hlap}}</td>
                                    <td align="center">{{$hm+$hmap}}</td>
                                    <td align="center">{{$hmm+$hmmap}}</td>
                                    <td align="center">{{$hj+$hjap}}</td>
                                    <td align="center">{{$hv+$hvap}}</td>
                                    <td align="center">{{$hs+$hsap}}</td>
                                    <td align="center">{{$hdoc+$hadp}}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Horario Administrativo</h4>
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
                                </tr>
                                </thead>
                                <tbody>
                                <?php $hlad=0; $hmad=0; $hmmad=0; $hjad=0; $hvad=0; $hsad=0; $hadm=0;?>
                                @foreach($admin as $admvo)
                                        <?php $suma_semana3=0;?>
                                    <tr>
                                        <td>{{$admvo->descripcion_puesto}}</td>
                                            <?php
                                            for($i=2;$i<=7;$i++){
                                                $dias=\Illuminate\Support\Facades\DB::table('horarios')->where('periodo',$periodo)
                                                    ->where('docente',$docente)->where('consecutivo_admvo',$admvo->consecutivo_admvo)
                                                    ->where('tipo_horario','A')
                                                    ->where('dia_semana',$i)->select('hora_inicial','hora_final')->first();
                                                if(!empty($dias)){
                                                    $entrada=\Carbon\Carbon::parse($dias->hora_inicial);
                                                    $salida=\Carbon\Carbon::parse($dias->hora_final);
                                                    $horas=$entrada->diff($salida)->format('%h');
                                                    $suma_semana3+=$horas;
                                                    switch ($i){
                                                        case 2: $hlad+=$horas; break;
                                                        case 3: $hmad+=$horas; break;
                                                        case 4: $hmmad+=$horas; break;
                                                        case 5: $hjad+=$horas; break;
                                                        case 6: $hvad+=$horas; break;
                                                        case 7: $hsad+=$horas; break;
                                                    }
                                                    echo "<td>".$dias->hora_inicial."-".$dias->hora_final."</td>";
                                                }else{
                                                    echo "<td>"."</td>";
                                                }
                                            }
                                            $hadm+=$suma_semana3;
                                            ?>
                                        <td align="center">{{$suma_semana3}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td align="center">{{$hlad}}</td>
                                    <td align="center">{{$hmad}}</td>
                                    <td align="center">{{$hmmad}}</td>
                                    <td align="center">{{$hjad}}</td>
                                    <td align="center">{{$hvad}}</td>
                                    <td align="center">{{$hsad}}</td>
                                    <td align="center">{{$hadm}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td align="center">{{$hl+$hlad+$hlap}}</td>
                                    <td align="center">{{$hm+$hmad+$hmap}}</td>
                                    <td align="center">{{$hmm+$hmmad+$hmmap}}</td>
                                    <td align="center">{{$hj+$hjad+$hjap}}</td>
                                    <td align="center">{{$hv+$hvad+$hvap}}</td>
                                    <td align="center">{{$hs+$hsad+$hsap}}</td>
                                    <td align="center">{{$hdoc+$hadm+$hadp}}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-information>
    <x-additional>
        @slot('header','Acciones Adicionales')
        <form action="{{route('academicos.otros_horarios')}}" method="post" role="form" >
            @csrf
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="accion">Acción por realizar</label>
                    <select name="accion" id="accion" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Alta horario administrativo</option>
                        <option value="2">Modificación horario administrativo</option>
                        <option value="3">Alta horario apoyo</option>
                        <option value="4">Modificación horario apoyo</option>
                        <option value="5">Alta observaciones para horario</option>
                        <option value="6">Modificación observaciones para horario</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
            <input type="hidden" name="docente" value="{{$docente}}">
            <input type="hidden" name="periodo" value="{{$periodo}}">
        </form>
    </x-additional>
@stop
