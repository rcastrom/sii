<!DOCTYPE html>
<html lang="es-MX">
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
        }
        body{
            font-family: Montserrat, sans-serif;
            font-size: 8pt;
            background: url({{asset('img/tecnm.jpg')}}) no-repeat center fixed;
            background-size: cover;
            opacity: 0.12;
        }
        @page { margin: 100px 50px;}
        .header { position: fixed; left: 0; top: -100px; right: 0; height: 100px; text-align: center; color: #2b3035; margin-bottom: 10px; }
        .footer { position: fixed; left: 0; bottom: -2.5cm; right: 0; height: 50px;text-align: center;}
        .footer .pagenum:before { content: counter(page); }

        .tabla{
            font-family: Helvetica, sans-serif;
            font-size: 7px;
            padding: 10px 0;
            margin-top: 0.20cm !important;
        }
        .tabla table{
            width: min-content;
            height: min-content;
        }
        .imagen{
            float: left;
            display: inline-block;
            vertical-align: top;
        }
        .texto{
            display: inline-block;
            padding-top: 5rem;
        }
    </style>
    <title>Lista</title>
</head>
<body>
<div class="header">
    <table width="100%" align="center">
        <tr>
            <td width="20%"><img src="{{asset('img/tecnm.jpg')}}" width="65px" height="55px" ></td>
            <td width="65%" align="center"><strong>Tecnológico Nacional de México<br>Instituto Tecnológico de Ensenada</strong></td>
            <td width="15%"><img src="{{asset('img/escudo.jpg')}}" width="55px" height="50px" ></td>
        </tr>
    </table>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <span style="alignment: center; color: #2b3035;" >HISTORIAL ACADÉMICO</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Número de control {{$control}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Carrera {{$carrera->nombre_carrera}} Clave {{$carrera->clave_oficial}}
            </div>
        </div>
    </div>
</div>
<div class="footer">
    Pag <span class="pagenum"></span>
</div>

<?php $suma_total=0; $calificaciones_totales=0; $j=1; $tipos_mat=array("O2","R1","R2","RO","RP"); $tipos_aprob=array('AC','RC','RU','PG'); ?>
@foreach($calificaciones as $key=>$value)
    @if(!empty($value))
        <table class="tabla table-striped">
            <thead>
            <tr>
                <th colspan="6">{{$nperiodos[$key]->identificacion_larga}}</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Clave oficial</th>
                <th>Materia</th>
                <th>Calificación</th>
                <th>Tipo evaluación</th>
                <th>Observaciones</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i=1;
            $suma_creditos=0;
            $promedio_semestre=0;
            $suma_semestre=0;
            $cal_sem=0;
            $materias=1;
            ?>
            @foreach($value as $data)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$data->clave}}</td>
                    <td>{{$data->nombre_completo_materia}}</td>
                    <td>{{$data->calificacion <= 70 && in_array($data->tipo_evaluacion,$tipos_aprob)?'AC':($data->calificacion < 70?"NA":$data->calificacion)}}</td>
                    <td>{{$data->descripcion_corta_evaluacion}}</td>
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
                if($data->calificacion>=70||in_array($data->tipo_evaluacion,$tipos_aprob)){
                    $suma_creditos+=$data->creditos_materia;
                    if(!in_array($data->tipo_evaluacion,$tipos_aprob)){
                        $cal_sem+=$data->calificacion;
                        $calificaciones_totales+=$data->calificacion;
                        $materias+=1;
                        $j++;
                    }
                    $suma_total+=$data->creditos_materia;

                }elseif($data->calificacion<70&&!in_array($data->tipo_evaluacion,$tipos_aprob)){
                    $materias+=1;
                }
                $suma_semestre+=$data->creditos_materia;
                $i++;
                ?>
            @endforeach
            <?php $promedio=($materias-1)==0?0:round($cal_sem/($materias-1),2); ?>
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
    @endif
@endforeach
<table class="tabla">
    <thead>
    <tr>
        <th>Porcentaje de avance</th>
        <th>Promedio General</th>
    </tr>
    <tr>
        <td align="center"><?php $avance=$suma_total==0?0:round(($suma_total/$carrera->creditos_totales)*100,2); ?>{{$avance."%"}}</td>
        <td align="center"><?php $prom_tot=($j-1)==0?0:round($calificaciones_totales/($j-1),2); ?>{{$prom_tot}}</td>
    </tr>
    </thead>
</table>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Información no válida sin sello y firma</div>
                <div class="card_body">
                    La información aquí señalada está sujeta a revisión.
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <img src="{{asset('img/sello.jpg')}}" width="125px" height="105px" class="imagen" >
        </div>
        <div class="col-md-4">
            <span class="texto">______________ <br>Jefa del Departamento de Servicios Escolares</span>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}" type="text/js"></script>
</body>
</html>
