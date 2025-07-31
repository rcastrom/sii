<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <style type="text/css">
        @font-face {
            font-family: 'Montserrat';
            src: url({{ storage_path('fonts\Montserrat-Medium.ttf') }}) format("truetype");
        }
        body{
            font-family: Montserrat, sans-serif;
            font-size: 9pt;
        }
        h3,h4{
            font-family: Nunito, sans-serif;
            font-size: large;
        }

    </style>
    <title>Lista</title>
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
    <table width="100%" align="center">
        <tr>
            <td width="20%"><img src="{{$logo_tecnm}}" width="125px" height="60px" alt="Logo TecNM" ></td>
            <td width="60%" align="center"><h4>{{$tec}}</h4></td>
            <td width="20%"><img src="{{$logo_tec}}" width="55px" height="50px" alt="Logo Tec" ></td>
        </tr>
    </table>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span style="alignment: center">ACTA DE CALIFICACIONES</span>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            Período {{$nombre_periodo->identificacion_larga}}
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            Materia {{$nmateria->nombre_completo_materia}} {{$materia}} Grupo: {{$grupo}}
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            Docente {{$docente->nombre_empleado}} {{$docente->apellidos_empleado}}
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div style="float: right">
                Folio: {{$datos->folio_acta}}
            </div>
        </div>
    </div>
</div>
<span style="margin-bottom: 2cm;">&nbsp;</span>
<div class="container">
    <div class="row">
        <div class="col-12">
            <font size="6pt" face="Helvetica">
                <table width="100%" border="1">
                    <thead>
                    <tr>
                        <th width="7%">No</th>
                        <th width="15%">No Control</th>
                        <th width="37%">Nombre</th>
                        <td colspan="16"></td>
                        <td width="15%">Calificación</td>
                        <td width="15%">Oportunidad</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $j=1; ?>
                    @foreach($alumnos as $alumno)
                        <tr>
                            <td>{{$j}}</td>
                            <td>{{$alumno->no_de_control}}</td>
                            <td>{{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}</td>
                            <td colspan="16"></td>
                            <td align="center">NA</td>
                            <td></td>
                        </tr>
                        <?php $j++;?>
                    @endforeach
                    </tbody>
                </table>
            </font>
        </div>
    </div>
</div>
<span style="margin-bottom: 2cm;">&nbsp;</span>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            {{$ciudad}} a {{date('d')}} de {{$mes}} del {{date('Y')}}
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <table align="right">
        <tr>
            <td>____________________________</td>
        </tr>
        <tr>
            <td>{{$docente->nombre_empleado}} {{$docente->apellidos_empleado}}</td>
        </tr>
    </table>
</div>
<script src="{{ asset('js/app.js') }}" type="text/js"></script>
</body>
</html>



