@extends('adminlte::page')

@section('title', 'Fichas')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        <h4>Periodo: {{$nperiodo->identificacion_corta}}</h4>
        <form action="{{route('aulas.destroy',$aula->id)}}" method="post">
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
                    <div class="col-sm-12 col-md-6">Aula</div>
                    <div class="col-sm-12 col-md-6">{{$salon->aula}}</div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>

        </form>
        <x-alert>
            @slot("mensaje","Este proceso es irreversible")
        </x-alert>
    </x-information>
@endsection

