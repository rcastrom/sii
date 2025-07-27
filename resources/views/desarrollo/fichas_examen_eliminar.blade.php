@extends('adminlte::page')

@section('title', 'Fecha examen selección')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        <h5>Periodo: {{$nombre_periodo}}</h5>
        <form action="{{route('fechas.destroy',$fecha->id)}}" method="post">
            @method('DELETE')
            @csrf
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="col-sm-12 col-md-6">Carrera</div>
                    <div class="col-sm-12 col-md-6">{{$carrera->nombre_reducido}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="col-sm-12 col-md-6">Fecha</div>
                    <div class="col-sm-12 col-md-6">{{Carbon\Carbon::parse($fecha->fecha)->format('Y-m-d')}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="col-sm-12 col-md-6">Hora</div>
                    <div class="col-sm-12 col-md-6">{{Carbon\Carbon::parse($fecha->hora)->format('H:i')}}</div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Eliminar registro</button>
            </div>

        </form>
        <x-alert>
            @slot("mensaje","Este proceso es irreversible")
        </x-alert>
    </x-information>
@endsection

