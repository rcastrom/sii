@extends('adminlte::page')

@section('title', 'Fichas')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        <h4>Periodo: {{$nperiodo->identificacion_corta}}</h4>
        <form action="{{route('aulas.update',$aula->id)}}" method="post">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="carrera">Carrera</label>
                    <select name="carrera" id="carrera" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($carreras as $carrera)
                            <option value="{{$carrera->carrera}}" {{trim($carrera->carrera)==trim($aula->carrera)?" selected":""}}>{{$carrera->nombre_reducido}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="salon">Aula</label>
                    <select name="salon" id="salon" required class="form-control" readonly="true" disabled>
                        <option value="" selected>--Seleccione--</option>
                        @foreach($salones as $salon)
                            <option value="{{$salon->id}}" {{$salon->id==$aula->id?" selected":""}}>{{$salon->aula}} Capacidad real: {{$salon->capacidad}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="cupo">Programados</label>
                    <input type="number" class="form-control" name="cupo" id="cupo" required value="{{$aula->capacidad}}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="disponibles">Disponibles:</label>
                    <input type="number" name="disponibles" id="disponibles" class="form-control" readonly value="{{$aula->disponibles}}">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
            <input type="hidden" name="capacidad" value="{{$aula->capacidad}}">
        </form>
        El espacio disponible se incrementará en base a la cantidad en que se incremente
        al número de espacios programados; es decir, si en éste momento usted programó al salón
        con 15 espacios y ahora lo modifica a 20, entonces se incrementarán 5 espacios.
    </x-information>
@endsection
