@extends('adminlte::page')

@section('title', 'Fecha examen admisión')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        Indique los parámetros necesarios para el examen de admisión
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('fechas.store')}}" method="post" role="form">
            @csrf
            <fieldset>
                <div class="form-group">
                    <label for="carrera">Carrera</label>
                        <select name="carrera" id="carrera" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($carreras as $carrera)
                                <option value="{{trim($carrera->carrera)}}">{{$carrera->nombre_carrera}}</option>
                            @endforeach
                        </select>
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha para el examen de admisión</label>
                    <input type="date" name="fecha" id="fecha" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="hora">Hora para el examen de admisión</label>
                    <input type="time" name="hora" id="hora" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="indicaciones">Indicaciones que aparecerán en la ficha, correspondientes al examen de admisión</label>
                    <textarea name="indicaciones" id="indicaciones" rows="5" cols="15" class="form-control" required onchange="this.value=this.value.toUpperCase();">
                ESTA FICHA DEBERÁ DE TRAERLA CONSIGO EL DÍA DE LA APLICACIÓN DEL EXAMEN DE SELECCIÓN, ASÍ COMO UNA IDENTIFICACIÓN.
                TRAER HOJAS Y LÁPIZ.
                ACUDIR MEDIA HORA ANTES, A FIN DE VERIFICAR EL LUGAR (AULA) EN DONDE REALIZARÁ
                EL EXAMEN DE ADMISIÓN.
                    </textarea>
                </div>
                <input type="hidden" name="periodo" value="{{$periodo}}">
            </fieldset>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
    <x-additional>
        @slot('header','Fechas para examen de admisión capturadas')
        @if($bandera==0)
            No hay registro de fechas para algún examen de admisión
        @else
            <h4>Fechas programadas</h4>
            <div class="row">
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Carrera</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Editar</th>
                        <th>Quitar registro</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($registros as $registro)
                        <tr>
                            <td>{{$registro->nombre_reducido}}</td>
                            <td>{{Carbon\Carbon::parse($registro->fecha)->format('Y-m-d')}}</td>
                            <td>{{Carbon\Carbon::parse($registro->hora)->format('H:i')}}</td>
                            <td><a href="{{route('fechas.edit',$registro->id)}}"><i class="far fa-edit"></i></a></td>
                            <td><a href="{{route('fechas.show',$registro->id)}}"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-additional>
@endsection
