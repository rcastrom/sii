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
    <title>Nuevo Ingreso</title>
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
    case "11": $mes="noviembre"; break;
    case "12": $mes="diciembre"; break;
}
?>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{$tec}}</h3>
                    <h4 class="card-title">Alta nuevo ingreso</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $appat}} {{$apmat}} {{$nombre}} </h5>
                    <p class="card-text">Número de control: {{ $control }}</p>
                    <p class="card-text">Carrera: {{$ncarrera->nombre_carrera}}</p>
                    <p class="card-text">NIP: {{$nip}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p>El Tecnológico Nacional de México Campus {{$tec}}, te da la más
                        cordial bienvenida a esta institución educativa.</p>
                    <p>Existen algunos puntos que debes conocer:
                    <ol>
                        <li>Aún no cuentas con carga académica activa; se te solicita acudas a la Coordinación
                            correspondiente a tu carrera para la selección de materias</li>
                        <li>Para cualquier trámite en la institución, te solicitarán tu número de control <strong>{{$control}}</strong></li>
                        <li>Tu cuenta de correo se activará en unos días más. Por favor, manténte al tanto de
                        la información que tu coordinación de carrera te indique.</li>
                        <li>Para acceder al sistema de consulta de calificaciones SII, tu usuario es el correo electrónico, pero
                            la contraseña es tu NIP: {{$nip}}</li>
                    </ol>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{$ciudad}} a {{date('d')}} de {{$mes}} del {{date('Y')}}
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}" type="text/js"></script>
</body>
</html>
