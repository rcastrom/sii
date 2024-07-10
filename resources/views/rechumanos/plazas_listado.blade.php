@extends('adminlte::page')

@section('title', 'Plazas del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h5>Registro de plazas de {{$nombre}}</h5>
                @if($bandera)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Plaza</th>
                                <th>Horas</th>
                                <th>Efectos</th>
                                <th>Estatus </th>
                                <th>Motivo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plazas as $plaza)
                                <tr>
                                    <td>{{$plaza->unidad.$plaza->subunidad}} {{$plaza->categoria}}/{{$plaza->diagonal}}</td>
                                    <td>{{$plaza->horas}}</td>
                                    <td>{{$plaza->efectos_iniciales}} - {{$plaza->efectos_finales}}</td>
                                    <td>{{$plaza->estatus_plaza}}</td>
                                    <td>{{$plaza->motivo}}</td>
                                    <td>
                                        <a href="{{route('personalPlaza.edit',$plaza->id)}}"><i class="fas fa-edit"></i> Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">Las plazas que visualiza, tienen el estatus de "activas", por
                                lo que serán las que se tomen en cuenta para cualquier tipo de cálculo.
                                    De ser incorrecto, se sugiere la modifique como "enviar a histórico".
                                </td>
                            </tr>
                        </tfoot>
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
        @slot('header','Adicionar plaza')
        <div class="row">
            <ul>
                <li><a href="{{route('personalPlaza.create')}}">Asignarle una nueva plaza</a></li>
                <li><a href="/rechumanos/personal/nuevo_estudio/{{base64_encode($id)}}">Ver histórico de plazas</a></li>
            </ul>
        </div>
    </x-additional>
@stop
