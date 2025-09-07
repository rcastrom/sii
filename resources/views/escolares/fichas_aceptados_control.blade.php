@extends('adminlte::page')

@section('title', 'Aspirantes')


@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-12">
                <div class="card-body">
                    <p>Modifique la información de así considerarlo necesario</p>
                    <form action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-2">
                                Ficha
                            </div>
                            <div class="col-4">
                                Nombre
                            </div>
                            <div class="col-6">
                                Número de control por asignarse
                            </div>
                        </div>
                        @foreach($datos as $dato)
                        <div class="row">
                            <div class="col-2">
                                {{$dato["ficha"]}}
                            </div>
                            <div class="col-4">
                                {{$dato["nombre"]}}
                            </div>
                            <div class="col-6">
                                <input type="text" name="" id="{{$dato["ficha"]}}"
                                       value="{{$dato["control"]}}"
                                       class="form-control">
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group mt-3">
                            <input type="submit" value="Continuar" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-information>

@stop

