@extends('adminlte::page')

@section('title', 'Fichas')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        Seleccione las carreras que serán ofertadas para el período señalado
        <form action="{{route('desarrollo.actualizar_carreras')}}" method="post" role="form">
            @csrf
            <table class="table table-active">
                <thead>
                    <tr>
                        <th>Carrera</th>
                        <th>Retícula</th>
                        <th>¿Se oferta?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carreras as $carrera)
                        <tr>
                            <td>{{$carrera->nombre_reducido}}</td>
                            <td>{{$carrera->reticula}}</td>
                            <td>
                                <input type="checkbox" name="carreras[]"
                                       class="form-check-input"
                                       value="{{trim($carrera->carrera).'_'.trim($carrera->reticula)}}"
                                @if($carrera->ofertar==1){{'checked'}} @endif>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@endsection

