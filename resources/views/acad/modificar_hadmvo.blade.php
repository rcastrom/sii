@extends('layouts.academicos')

@section('content')

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
                            <th colspan="2">Acción</th>
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
                                        ->where('rfc',$rfc)->where('consecutivo_admvo',$admvo->consecutivo_admvo)
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
                                <td><i class="fas fa-wrench"></i>
                                    <a href="/acad/modificar/admvo/{{$periodo}}/{{$rfc}}/{{$admvo->consecutivo_admvo}}" title="Modificar">
                                        Modificar</a></td>
                                <td><i class="fas fa-trash-alt"></i>
                                    <a href="/acad/eliminar/admvo/{{$periodo}}/{{$rfc}}/{{$admvo->consecutivo_admvo}}" title="Eliminar">
                                        Eliminar</a></td>
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
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
