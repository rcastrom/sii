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
        <form action="{{route('fechas.update',$fecha->id)}}" method="post" role="form">
            @method('PUT')
            @csrf
            <fieldset>
                <div class="form-group">
                    <label for="carrera">Carrera</label>
                        <select name="carrera" id="carrera" required class="form-control">
                            @foreach($carreras as $carrera)
                                <option value="{{trim($carrera->carrera)}}" {{trim($carrera->carrera==$fecha->carrera?' selected':'')}}>{{$carrera->nombre_carrera}}</option>
                            @endforeach
                        </select>
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha para el examen de admisión</label>
                    <input type="date" name="fecha" id="fecha" required class="form-control" value="{{Carbon\Carbon::parse($fecha->fecha)->format('Y-m-d')}}">
                </div>
                <div class="form-group">
                    <label for="hora">Hora para el examen de admisión</label>
                    <input type="time" name="hora" id="hora" required class="form-control" value="{{Carbon\Carbon::parse($fecha->hora)->format('H:i')}}">
                </div>
                <div class="form-group">
                    <label for="indicaciones">Indicaciones que aparecerán en la ficha, correspondientes al examen de admisión</label>
                    <textarea name="indicaciones" id="indicaciones" rows="5" cols="15" class="form-control" required onchange="this.value=this.value.toUpperCase();">
                        {{trim($fecha->indicaciones)}}
                    </textarea>
                </div>
                <input type="hidden" name="periodo" value="{{$periodo}}">
            </fieldset>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@endsection
