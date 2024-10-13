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
                    <div class="card-header">Módulo Jefaturas Académicas</div>
                    <div class="card-body">
                        <h4 class="card-title">Período {{$nperiodo->identificacion_larga}}</h4>
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
                                        <?php $suma_semana2=0;?>
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
                                                    $suma_semana2+=$horas;
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
                                            $hadm+=$suma_semana2;
                                            ?>
                                        <td align="center">{{$suma_semana2}}</td>
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
                                    <td align="center">{{$hl+$hlad}}</td>
                                    <td align="center">{{$hm+$hmad}}</td>
                                    <td align="center">{{$hmm+$hmmad}}</td>
                                    <td align="center">{{$hj+$hjad}}</td>
                                    <td align="center">{{$hv+$hvad}}</td>
                                    <td align="center">{{$hs+$hsad}}</td>
                                    <td align="center">{{$hdoc+$hadm}}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('academicos.altaadmin')}}" method="post" role="form">
            @csrf
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Asignación de actividad de horario administrativo</div>
                            <div class="card-body">
                                <h4 class="card-title">Catálogo de puestos</h4><br>
                                <div class="form-group">
                                    <label for="puesto">Seleccione el puesto a realizar del siguiente listado</label>
                                    <select name="puesto" id="puesto" required class="form-control">
                                        <option value="" selected>--Seleccione--</option>
                                        @foreach($puestos as $puesto)
                                            <option value="{{$puesto->clave_puesto}}">{{$puesto->descripcion_puesto}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Lunes
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="elunes" id="elunes">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="slunes" id="slunes">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Martes
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="emartes" id="emartes">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="smartes" id="smartes">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Miércoles
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="emiercoles" id="emiercoles">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="smiercoles" id="smiercoles">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Jueves
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="ejueves" id="ejueves">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="sjueves" id="sjueves">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Viernes
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="eviernes" id="eviernes">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="sviernes" id="sviernes">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Sábado
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="esabado" id="esabado">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="ssabado" id="ssabado">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="hl" value="{{$hl+$hlad}}">
                                    <input type="hidden" name="hm" value="{{$hm+$hmad}}">
                                    <input type="hidden" name="hmm" value="{{$hmm+$hmmad}}">
                                    <input type="hidden" name="hj" value="{{$hj+$hjad}}">
                                    <input type="hidden" name="hv" value="{{$hv+$hvad}}">
                                    <input type="hidden" name="hs" value="{{$hs+$hsad}}">
                                    <input type="hidden" name="periodo" value="{{$periodo}}">
                                    <input type="hidden" name="docente" value="{{$docente}}">
                                    <button type="submit" class="btn btn-primary">Continuar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-information>
@stop
