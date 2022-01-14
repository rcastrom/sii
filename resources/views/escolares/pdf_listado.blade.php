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
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Instituto Tecnológico de Ensenada</h3>
                    <h4 class="card-title">Orden de reinscripción del período {{$nperiodo->identificacion_corta}}</h4>
                    <h4 class="card-title">Carrera: {{$ncarrera->nombre_reducido}}</h4>
                </div>
            </div>
            <table>
                <thead>
                <tr>
                    <th>Control</th>
                    <th>Nombre</th>
                    <th>Fecha - hora selección</th>
                </tr>
                </thead>
                <tbody>
                @foreach($alumnos as $alumno)
                    <tr>
                        <td>{{$alumno->no_de_control}}</td>
                        <td>{{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}</td>
                        <td>{{$alumno->fecha_hora_seleccion}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" type="text/js"></script>
</body>
</html>
