@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Observaciones para el horario</h4>
                        <div class="row">
                            <div class="col-sm-8 col-md-8">
                                {{$obs->observaciones}}
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <i class="fas fa-wrench"></i>
                                <a href="{{route('academicos.modobs',['periodo'=>$periodo,'docente'=>$docente,'id'=>$obs->id])}}">Modificar</a>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <i class="fas fa-trash-alt"></i>
                                <a href="{{route('academicos.delobs',['id'=>$obs->id])}}">
                                    Eliminar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-information>
@stop
