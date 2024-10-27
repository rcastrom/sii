@extends('adminlte::page')

@section('title', 'Reinscripción')

@section('css')
    <link rel="stylesheet" href="/css/reticula.css">
@stop

@section('content_header')
    <h1>Estudiantes</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <?php
        for($i=1;$i<=10;$i++){
            for($j=1;$j<=9;$j++){
                $linea[$i.$j] = "";
            }
        }
        $encurso=array();

        if(!empty($carga)){
            foreach($carga as $cursando){
                $encurso[$cursando->materia]=$cursando->materia;
            }
        }
        ?>
        <table class="table table-responsive">
            <tr>
                @for($j=1;$j<=9;$j++)
                    <th class="medium_negrita_center">SEMESTRE {{$j}}</th>
                @endfor
            </tr>
            <?php
            foreach($historial as $historia){
                $tipocur = $historia->tipocur;
                $calificacion = "";
                $renglon = $historia->renglon;
                $semestre_reticula = $historia->semestre_reticula;
                $materia = $historia->materia;
                $nombre_materia = $historia->nombre_abreviado_materia;
                $muestra_materia = $materia."<br>".$nombre_materia;
                if($tipocur == 'AC') // Acreditada
                {
                    $calificacion="<br>";
                    $calificacion.= $historia->tipo_evaluacion=='RU'?'AC':($historia->tipo_evaluacion=='AC'?'ACA':$historia->calificacion);
                    $calificacion.="/".$historia->tipo_evaluacion;
                    $linea[$renglon.$semestre_reticula] =  "<td height='80' width='90' class='small_center verde'>".$muestra_materia.$calificacion."</td>";
                }elseif($tipocur == "AE") // Examen Autodidacta o especial
                {
                    $linea[$renglon.$semestre_reticula] =  "<td height='80' width='90' class='small_center naranja'><a href='/estudiante/reinscripcion/$materia/$tipocur' title='Seleccionar materia'>".$muestra_materia.$calificacion."</a></td>";
                }elseif($tipocur == "ER") // Especial Reprobado
                {
                    $linea[$renglon.$semestre_reticula] =  "<td height='80' width='90' class='small_center rojo'><a href='/estudiante/reinscripcion/$materia/$tipocur' title='Seleccionar materia'>".$muestra_materia.$calificacion."</a></td>";
                }elseif($tipocur == "CR") // Cursada y reprobada
                {
                    $linea[$renglon.$semestre_reticula] =  "<td height='80' width='90' class='small_center amarillo'><a href='/estudiante/reinscripcion/$materia/$tipocur' title='Seleccionar materia'>".$muestra_materia.$calificacion."</a></td>";
                }else{
                    $linea[$renglon.$semestre_reticula] =  "<td height='80' width='90' class='small_center azul'><a href='/estudiante/reinscripcion/$materia/CN' title='Seleccionar materia'><span style='color: white'>".$muestra_materia.$calificacion."</span></a></td>";
                }
                if(!empty($encurso)){
                    if(in_array($materia,$encurso)){
                        $linea[$renglon.$semestre_reticula] =  "<td height='80' width='90' class='small_center lila'>$muestra_materia</td>";
                    }
                }
            }
            for ($i=1;$i<=10;$i++){
                echo "<tr>";
                for ($j=1; $j<=9; $j++){
                    echo strlen($linea[$i.$j]) > 0 ? $linea[$i.$j]: "<td>&nbsp;</td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
    </x-information>
@stop

