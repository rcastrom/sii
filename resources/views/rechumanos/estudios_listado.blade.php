@extends('adminlte::page')

@section('title', 'Estudios del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                @if($bandera)
                    <h5>Registros</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nivel</th>
                                <th>Carrera</th>
                                <th>Realizado en </th>
                                <th>Cédula</th>
                                <th colspan="2">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudios as $estudio)
                                <tr>
                                    <td>{{$estudio->nivel}}</td>
                                    <td>{{$estudio->carrera}}</td>
                                    <td>{{$estudio->nombre}}</td>
                                    <td>{{$estudio->cedula}}</td>
                                    <td>
                                        <a href="/rechumanos/personal/estudios_editar/{{ $estudio->id }}"><i class="fas fa-edit"></i>Editar</a>
                                    </td>
                                    <td>
                                        <a href="/rechumanos/personal/estudios_borrar/{{ $estudio->id }}"><i class="fas fa-trash"></i>Borrar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h4>Sin información</h4>
                    <p>De acuerdo a la información que se tiene registrada en el sistema, el
                    personal no cuenta con estudios registrados.</p>
                @endif
            </div>
        </div>
    </x-information>
    <x-additional>
        @slot('header','Información adicional')
        <div class="row">
            <ul>
                <li><a href="/rechumanos/personal/nuevo_estudio/{{base64_encode($id)}}">Registrar un nuevo estudio</a></li>
            </ul>
        </div>
    </x-additional>
@stop
