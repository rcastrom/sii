<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Evaluación docente</title>
    <style>

        @page  {
            margin: 3cm 0.5cm 1.5cm 0.5cm;
        }
        @font-face {
            font-family: "Montserrat";
            src: url("{{storage_path('fonts/Montserrat-Light.ttf')}}") format('truetype');
            font-weight: 100;
            font-style: italic;
        }
        @font-face {
            font-family: "Montserrat";
            src: url("{{storage_path('fonts/Montserrat-Regular.ttf')}}") format('truetype');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: "Montserrat";
            src: url("{{storage_path('fonts/Montserrat-SemiBoldItalic.ttf')}}") format('truetype');
            font-weight: 600;
            font-style: normal;
        }
        @font-face {
            font-family: "Montserrat";
            src: url("{{storage_path('fonts/Montserrat-Bold.ttf')}}") format('truetype');
            font-weight: 700;
            font-style: normal;
        }
        #header{
            position: fixed;
            top:-3cm;
            left: 0;
            display: inline;
        }
        .imgHeader{
            float: left;
            padding: 0;
            width: 4.15cm;
            height: 2.75cm;
        }
        .espacio{
            float: left;
            clear: both;
            margin: 0 9cm;
        }
        .infoHeader{
            float: right;
            width: 210px;
            margin-left: 5cm;
        }
        .imgHeaderinfo{
            width: 2.5cm;
            height: 2.75cm;
            float: right;
            padding: 0;
        }
        .departamento{
            float: right;
            margin-top: 0.45cm;
            padding-top: 1.75cm;
            font-family: "Montserrat", serif;
            font-weight: 600;
            font-size: 7pt;
            text-align: right;
            width: 6cm;
        }
        #footer{
            position: fixed;
            bottom: -1.5cm;
            left: 0;
            width: 100%;
        }
        .imgFooter{
            width: 100%;
            height: 3cm;
            margin-bottom: 0.5cm;
        }
        .container{
            /* margin: 1.5cm auto;
            margin-top: 30px;
            margin-left: 2cm;
            margin-right: 1cm;*/
            margin: 30px 0.5cm 1cm 1cm;
            font-family: "Montserrat", serif;
            font-weight: 400;
            font-size: 9pt;
            /*width: 100%;*/

        }
        .salto{
            page-break-after: always;
        }

    </style>
</head>
<body>
    <div id="header">
        <img src="{{public_path('img/tecnm.jpg')}}" alt="Logo TecNM" class="imgHeader">
        <div class="espacio"></div>
        <div class="infoHeader">
            <img src="{{public_path('img/mujer_emblema.jpg')}}" alt="Emblema mujer" class="imgHeaderinfo">
            <div class="departamento">
                <p>Instituto Tecnológico de Ensenada</p>
                <p>Departamento de Desarrollo Académico</p>
            </div>
        </div>
    </div>
    <div id="footer">
        <img src="{{public_path('img/logo_pie_pagina.jpg')}}" alt="Pie de página" class="imgFooter">
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Período</th>
                    <th>Carrera</th>
                    <th>Retícula</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$nombre_periodo->identificacion_corta}}</td>
                    <td>{{$nombre_carrera->nombre_reducido}}</td>
                    <td>{{$reticula}}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th colspan="2">Materias de la carrera</th>
                    <th colspan="2">Docentes que imparten a la carrera</th>
                    <th colspan="2">No. de alumnos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Activas</td>
                    <td>Evaluadas</td>
                    <td>Activos</td>
                    <td>Evaluados</td>
                    <td>Inscritos</td>
                    <td>Evaluaron</td>
                </tr>
                <tr>
                    <td>{{$materias_activas}}</td>
                    <td>{{$materias_evaluadas}}</td>
                    <td>{{$docentes_activos}}</td>
                    <td>{{$docentes_evaluados}}</td>
                    <td>{{$alumnos_activos}}</td>
                    <td>{{$alumnos_evaluados}}</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Aspectos a evaluar</th>
                    <th>Puntaje</th>
                    <th>Calificación</th>
                </tr>
            </thead>
            <tbody>
            @foreach($resultados_carrera as $key=>$value)
                <tr>
                    <td>{{$value["aspecto"].") ".$value["descripcion"]}}</td>
                    <td>{{$value["porcentaje"]}}</td>
                    <td>{{$value["calificacion"]}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <p>
            Promedio General: <strong>{{$promedio}}</strong> Descripción  <strong>{{$calificacion}}</strong>
        </p>
        <img src="{{storage_path('img/'.$archivo)}}" alt="">
    </div>
</body>
</html>

