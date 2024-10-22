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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('academicos.modadmin')}}" method="post" role="form">
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
                                        @foreach($puestos as $cves)
                                            <option value="{{$cves->clave_puesto}}" {{$cves->clave_puesto==$puesto->descripcion_horario?' selected':''}}>{{$cves->descripcion_puesto}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="unidad">Indique el área de quien dependerá dicho puesto (unidad orgánica de adscripción)
                                        <select name="unidad" id="unidad" required class="form-control">
                                            @foreach($areas as $area)
                                                <option value="{{$area->clave_area}}" {{$area->clave_area==$puesto->area_adscripcion?' selected':''}} >{{$area->descripcion_area}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                for($i=2;$i<=7;$i++){
                                    switch ($i){
                                        case 2: $dia="Lunes"; $entrada="elunes"; $salida="slunes"; break;
                                        case 3: $dia="Martes"; $entrada="emartes"; $salida="smartes"; break;
                                        case 4: $dia="Miércoles"; $entrada="emiercoles"; $salida="smiercoles"; break;
                                        case 5: $dia="Jueves"; $entrada="ejueves"; $salida="sjueves"; break;
                                        case 6: $dia="Viernes"; $entrada="eviernes"; $salida="sviernes"; break;
                                        case 7: $dia="Sábado"; $entrada="esabado"; $salida="ssabado"; break;
                                    } ?>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        {{$dia}}
                                    </div>
                                        <?php
                                        $admin=\Illuminate\Support\Facades\DB::table('horarios')->where('periodo',$periodo)
                                            ->where('docente',$docente)
                                            ->where('consecutivo_admvo',$consecutivo)
                                            ->where('tipo_horario','A')
                                            ->where('dia_semana',$i)
                                            ->first();
                                    if(!empty($admin)){?>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="{{$entrada}}" id="{{$entrada}}" value="{{$admin->hora_inicial}}">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="{{$salida}}" id="{{$salida}}" value="{{$admin->hora_final}}">
                                    </div>
                                    <?php }else{ ?>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="{{$entrada}}" id="{{$entrada}}">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="{{$salida}}" id="{{$salida}}">
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php   } ?>
                                <div class="form-group">
                                    <input type="hidden" name="hl" value="{{$hl}}">
                                    <input type="hidden" name="hm" value="{{$hm}}">
                                    <input type="hidden" name="hmm" value="{{$hmm}}">
                                    <input type="hidden" name="hj" value="{{$hj}}">
                                    <input type="hidden" name="hv" value="{{$hv}}">
                                    <input type="hidden" name="hs" value="{{$hs}}">
                                    <input type="hidden" name="periodo" value="{{$periodo}}">
                                    <input type="hidden" name="docente" value="{{$docente}}">
                                    <input type="hidden" name="consecutivo" value="{{$consecutivo}}">
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
