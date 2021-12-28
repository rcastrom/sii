<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url({{ storage_path('fonts\Montserrat-Medium.ttf') }}) format("truetype");
            font-weight: 200;
            font-style: normal;
        }
        body{
            background-image: url({{asset('img/naguila.jpeg')}});
            font-family: Montserrat, sans-serif;
            font-size: 9pt;
        }
        h3,h4{
            font-family: Nunito, sans-serif;
            font-size: large;
        }
    </style>
    <title>Boleta</title>
</head>
<?php
$hoy=date('m');
switch ($hoy){
    case "01": $mes="enero"; break;
    case "02": $mes="febrero"; break;
    case "03": $mes="marzo"; break;
    case "04": $mes="abril"; break;
    case "05": $mes="mayo"; break;
    case "06": $mes="junio"; break;
    case "07": $mes="julio"; break;
    case "08": $mes="agosto"; break;
    case "09": $mes="septiembre"; break;
    case "10": $mes="octubre"; break;
    case "11": $mes="noviembreo"; break;
    case "12": $mes="diciembre"; break;
}
?>
<body>
<div class="container">
    <div class="row">
        <table width="100%" align="center">
            <tr>
                <td width="20%"><img src="{{asset('img/tecnm.jpg')}}" width="175px" height="85px" ></td>
                <td width="75%" align="center"><strong>Tecnológico Nacional de México<br>Instituto Tecnológico de Ensenada</strong></td>
                <td width="15%"><img src="{{asset('img/escudo.jpg')}}" width="55px" height="50px" ></td>
            </tr>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Boleta del período {{$nombre_periodo->identificacion_larga}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h5>
                    <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php
                    $tipos_mat=array("O2","R1","R2","RO","RP","2");
                    $i=1;
                    $suma_creditos=0;
                    $promedio_semestre=0;
                    $suma_semestre=0;
                    $cal_sem=0;
                    ?>
                    <font size="6pt" face="Helvetica">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Materia</th>
                                <th>Calificación</th>
                                <th>Oportunidad</th>
                                <th>Créditos</th>
                                <th>Observaciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cal_periodo as $data)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$data->nombre_completo_materia}}</td>
                                    <td>{{$data->calificacion<60?"NA":($data->tipo_evaluacion=='AC'?'AC':$data->calificacion)}}</td>
                                    <td>{{$data->descripcion_corta_evaluacion}}</td>
                                    <td>{{$data->creditos_materia}}</td>
                                    @if(($data->calificacion < 70 && in_array($data->tipo_evaluacion,$tipos_mat)) || ($data->calificacion < 70 && $data->tipo_evaluacion == 'EA')){
                                    @if($alumno->plan_de_estudios==3||$alumno->plan_de_estudios==4){
                                    <td>A curso especial</td>
                                    }
                                    @else{
                                    <td></td>
                                    }
                                    @endif
                                    @endif
                                </tr>
                                <?php
                                if($data->calificacion>=70||($data->tipo_evaluacion=='AC')){
                                    $suma_creditos+=$data->creditos_materia;
                                    $cal_sem+=$data->calificacion;
                                }
                                $suma_semestre+=$data->creditos_materia;
                                $i++;
                                ?>
                            @endforeach
                            <?php $promedio=round($cal_sem/($i-1),2); ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>Créditos Aprobados/Solicitados</td>
                                <td>{{$suma_creditos}}/{{$suma_semestre}}</td>
                                <td>Promedio del semestre</td>
                                <td>{{$promedio}}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </font>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p>Documento no válido sin sello y firma</p>
        </div>
    </div>
    <div class="row">
        <table>
            <tr>
                <td>
                    <img src="{{asset('img/sello.jpg')}}" width="125px" height="105px">
                </td>
                <td>
                    <table>
                        <tr>
                            <td>___________________________________</td>
                        </tr>
                        <tr>
                            <td>Julia E. Chávez Remigio</td>
                        </tr>
                        <tr>
                            <td>JEFA DEL DEPARTAMENTO DE SERVICIOS ESCOLARES</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            Ensenada B.C. a {{date('d')}} de {{$mes}} del {{date('Y')}} <br>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}" type="text/js"></script>
</body>
</html>



