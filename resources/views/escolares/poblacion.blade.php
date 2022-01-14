@extends('adminlte::page')

@section('title', 'Población Escolar')

@section('content_header')
    <h1>Servicios Escolares</h1>
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
            <?php $suma=0; ?>
            @foreach($inscritos as $cantidad)
                <tr>
                    <td>{{$cantidad->ncarrera}}</td>
                    <td>{{$cantidad->reticula}}</td>
                    <td>{{$cantidad->cantidad}}</td>
                    <td><i class="far fa-question-circle"></i>
                        <a href="/escolares/estadistica/detalle/{{$periodo}}/{{$cantidad->carrera}}/{{$cantidad->reticula}}"
                           title="Desglosar">Mayor información</a></td>
                    <?php $suma+=$cantidad->cantidad; ?>
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

