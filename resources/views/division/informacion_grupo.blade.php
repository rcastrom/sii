@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Materia: {{$nmateria->nombre_completo_materia}} Grupo: {{$grupo}}</h4><br>
        <h5>Clave: {{$materia}}</h5><br>
        <h4 class="card-title">Docente {{$docente}}</h4><br>
        <table class="table table-responsive">
            <thead class="thead-light">
            <tr>
                <th>L</th>
                <th>M</th>
                <th>M</th>
                <th>J</th>
                <th>V</th>
                <th>S</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                @php
                    $per=$periodo; $mat=$materia; $gpo=$grupo;
                @endphp
                @for ($i=2;$i<=7;$i++)
                    @php
                    $hora=\Illuminate\Support\Facades\DB::table('horarios')
                        ->select('hora_inicial','hora_final','aula')
                        ->where('periodo',$periodo)
                        ->where('materia',$mat)
                        ->where('grupo',$gpo)
                        ->where('dia_semana',$i)
                        ->first();
                    echo empty($hora->hora_inicial)?
                        "<td></td>":
                        "<td>".$hora->hora_inicial."/".$hora->hora_final."<br>(".$hora->aula.")</td>";
                    @endphp
                @endfor
            </tr>
            </tbody>
        </table>
    </x-information>
    <x-additional>
        @slot("header","Estudiantes")
        <div class="row">
            <div class="col-md-1">
                #
            </div>
            <div class="col-md-2">
                No de Control
            </div>
            <div class="col-md-7">
                Nombre
            </div>
            <div class="col-md-2">
                Repetidor
            </div>
        </div>
        @php
            $i=1;
        @endphp
        @foreach($alumnos as $alumno)
            <div class="row">
                <div class="col-md-1">
                    {{$i}}
                </div>
                <div class="col-md-2">
                    {{$alumno->no_de_control}}
                </div>
                <div class="col-md-7">
                    {{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}
                </div>
                <div class="col-md-2">
                    {{$alumno->repeticion}}
                </div>
            </div>
            @php
             $i++;
            @endphp
        @endforeach
        <form action="{{route('dep_acciones')}}" method="post" role="form" class="form-inline">
            @csrf
            <div class="form-group">
                <label for="accion">Seleccione si desea realizar una acción específica</label>
                <select name="accion" id="accion" required class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    <option value="1">Dar de alta un estudiante</option>
                    <option value="2">Dar de baja a un estudiante</option>
                    <option value="3">Modificar horario</option>
                    <option value="4">Modificar capacidad grupo</option>
                    <option value="5">Eliminar grupo</option>
                </select>
            </div>
            <input type="hidden" name="materia" id="materia" value="{{$materia}}">
            <input type="hidden" name="grupo" id="grupo" value="{{$grupo}}">
            <input type="hidden" name="periodo" id="periodo" value="{{$periodo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
        <x-alert>
            @slot("mensaje","No podrá modificar horario si el grupo tiene alumnos inscritos")
        </x-alert>
    </x-additional>
@stop
