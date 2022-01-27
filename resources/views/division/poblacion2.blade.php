@extends('adminlte::page')

@section('title', 'Estadística')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3 class="card-title">Período {{$nperiodo->identificacion_corta}}</h3><br>
        <h6 class="card-header">Población total carrera {{$ncarrera->nombre_reducido}} ret {{$reticula}}</h6>
        <table class="table table-responsive">
            <thead class="thead-light">
            <tr>
                <th>Semestre</th>
                <th>Hombres</th>
                <th>Mujeres</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @php $suma_h=0; $suma_m=0; $suma=0; @endphp
            @foreach($poblacion as $value)
                <tr>
                    <td>{{$value["semestre"]}}</td>
                    <td>{{$value["hombres"]}}</td>
                    <td>{{$value["mujeres"]}}</td>
                    <td>{{$value["total"]}}</td>
                    @php $suma_h+=$value["hombres"]; $suma_m+=$value["mujeres"]; $suma+=$value["total"]; @endphp
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td>Total</td>
                <td>{{$suma_h}}</td>
                <td>{{$suma_m}}</td>
                <td>{{$suma}}</td>
            </tr>
            </tfoot>
        </table>
    </x-information>
@stop
