@extends('adminlte::page')

@section('title', 'Estadística')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css">
@stop

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Período {{$nperiodo->identificacion_corta}}</h4>
        <table id="population" class="display responsive nowrap">
            <thead>
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
                        <a href="{{route('academicos.pob_x_carrera',['periodo'=>$periodo,'carrera'=>$cantidad->carrera,'reticula'=>$cantidad->reticula])}}">Mayor información</a></td>
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

@section('js')
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.min.js"></script>
    <script>
        new DataTable('#population',{
            responsive:true,
        });
    </script>
@stop
