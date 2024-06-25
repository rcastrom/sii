@extends('adminlte::page')

@section('title', 'Fichas')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        Selección de aulas por carrera para el examen de nuevo ingreso
        <form action="{{route('desarrollo.alta_salon')}}" method="post" role="form">
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="carrera">Carrera</label>
                    <select name="carrera" id="carrera" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($carreras as $carrera)
                            <option value="{{$carrera->carrera}}">{{$carrera->nombre_reducido}} Ret {{$carrera->reticula}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="salon">Aula</label>
                    <select name="salon" id="salon" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($salones as $salon)
                            <option value="{{$salon->id}}">{{$salon->aula}} Cap: {{$salon->capacidad}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="cupo">Cupo</label>
                    <input type="number" class="form-control" name="cupo" id="cupo" required>
                </div>
            </div>
            <input type="hidden" name="periodo" value="{{$periodo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
    <x-additional>
        @slot('header','Aulas capturadas')
        @if($bandera==0)
            No hay salones registrados por el momento
        @else
            <h4>Salones registrados</h4>
            <div class="row">
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Carrera</th>
                        <th>Aula</th>
                        <th>Cupo</th>
                        <th>Disponibles</th>
                        <th>Editar</th>
                        <th>Quitar aula</th>
                        <th>Impresión por aula</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($registros as $registro)
                        <tr>
                            <td>{{$registro->nombre_reducido}}</td>
                            <td>{{$registro->aula}}</td>
                            <td>{{$registro->capacidad}}</td>
                            <td>{{$registro->disponibles}}</td>
                            <td><a href="{{route('aulas.edit',$registro->id)}}"><i class="far fa-edit"></i></a></td>
                            <td><a href="{{route('aulas.show',$registro->id)}}"><i class="fa fa-trash"></i></a></td>
                            <td><a href="/desarrollo/fichas/aula/editar?aula={{$registro->id}}&accion=3"><i class="fa fa-print"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-additional>
@endsection


