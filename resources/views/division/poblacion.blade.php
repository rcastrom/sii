@extends('adminlte::page')

@section('title', 'Estadística')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Período {{$nperiodo->identificacion_corta}}</h4>
        <table class="table table-responsive">
            <thead class="thead-light">
            <tr>
                <th>Carrera</th>
                <th>Retícula</th>
                <th>Población</th>
                <th>Desglose</th>
            </tr>
            </thead>
            <tbody>
            @php $suma=0; @endphp
            @foreach($inscritos as $cantidad)
                <tr>
                    <td>{{$cantidad->ncarrera}}</td>
                    <td>{{$cantidad->reticula}}</td>
                    <td>{{$cantidad->cantidad}}</td>
                    <td><i class="far fa-question-circle"></i>
                        <a href="/division/estadistica/desglose/{{$periodo}}/{{$cantidad->carrera}}/{{$cantidad->reticula}}"
                           title="Desglosar">Mayor información</a></td>
                    @php $suma+=$cantidad->cantidad; @endphp
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td>{{$suma}}</td>
            </tr>
            </tfoot>
        </table>
    </x-information>
@stop
