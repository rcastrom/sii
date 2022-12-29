@extends('adminlte::page')

@section('title', 'Consulta o modificación personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h3>Datos del personal</h3>
                En caso de querer realizar una modificación, seleccione la casilla correspondiente
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Campo</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $id=base64_encode($personal_info->id);
                    @endphp
                    @foreach($datos as $dato)
                        <tr>
                            <td>{{$dato[2]}}</td>
                            <td>{{$dato[1]}}</td>
                            <td><a href="/rechumanos/personal/edicion/{{$dato[0]}}/{{ $id }}"><i class="fas fa-edit"></i>Editar</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-information>
    <x-additional>
        @slot('header','Datos Adicionales')
        <div class="row">
            <ul>
                <li>Estatus del personal</li>
                <li><a href="/rechumanos/personal/estudios/{{$id}}">Estudios</a></li>
                <li>Plazas</li>
            </ul>
        </div>
    </x-additional>
@stop
