<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url({{ storage_path('fonts\Montserrat-Medium.ttf') }}) format("truetype");
            font-weight: 200;
            font-style: normal;
        }
        body{
            background-image: url({{asset('img/aguila.jpg')}});
            font-family: Montserrat, sans-serif;
            font-size: 9pt;
        }
        .encabezado{
            width: 100%;
            align-self: center;
        }
        .encabezado .celda-tecnm{
            width: 15%;
        }
        .encabezado .celda-nombre{
            width: 70%;
        }
        .encabezado .celda-escudo{
            width: 15%;
        }

        .boleta{
            width: auto;
            height: auto;
        }
        .boleta th .boleta td {
            text-align: left;
        }
        .boleta .centrar_informacion{
            text-align: center;
        }
        h3,h4 {
            font-family: Nunito, sans-serif;
            font-size: large;
        }
    </style>
    <title>Boleta</title>
</head>
<?php

?>
<body>
<div class="container">
    <table class="encabezado">
        <tr>
            <td class="celda-tecnm"><img src="{{$imagen_tecnm}}" alt="" width="175px" height="85px" ></td>
            <td class="celda-nombre"><strong>Tecnológico Nacional de México<br>{{$nombre_tec}}</strong></td>
            <td class="celda-escudo"><img src="{{$imagen_escudo}}" alt="" width="55px" height="50px" ></td>
        </tr>
    </table>

    <div class="row">
        <div class="col-md-4">
            <h5>Boleta del período {{$nombre_periodo->identificacion_larga}}</h5>
        </div>
        <div class="col-md-4">
            <h5>{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h5>
        </div>
        <div class="col-md-4">
            <p>Número de control: {{ $alumno->no_de_control }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @php
                    $segunda_oportunidad = '';
                    $tipos_mat=$segunda_oportunidad;
                    $i=1;
                    $suma_creditos=0;
                    $promedio_semestre=0;
                    $suma_semestre=0;
                    $cal_sem=0;
                    @endphp
                    <font size="6pt" face="Helvetica">
                        <table class="table table-striped boleta">
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
                                    <td class="centrar_informacion">{{$i}}</td>
                                    <td>{{$data->nombre_completo_materia}}</td>
                                    <td class="centrar_informacion">{{$data->calificacion<60?"NA":($data->tipo_evaluacion=='AC'?'AC':$data->calificacion)}}</td>
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
            <p>Documento no válido sin sello y sin firma</p>
        </div>
    </div>
    <div class="row">
        <table>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>___________________________________</td>
                        </tr>
                        <tr>
                            <td>{{$nombre_jefe}}</td>
                        </tr>
                        <tr>
                            <td>{{$cargo}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            Ensenada B.C. a {{$fecha}} <br>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}" type="text/js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



