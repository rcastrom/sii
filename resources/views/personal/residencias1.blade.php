@extends('adminlte::page')

@section('title', 'Residencias')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3>Evaluación residencias profesionales</h3>
        <form action="{{route('personal_residencias1')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="per_res">Señale el período a evaluar</label>
                <select name="per_res" id="per_res" required class="form-control">
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}" {{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_larga}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
    <x-alert>
        @slot('mensaje','Una vez realizada la evaluación, no podrá realizar cambios')
    </x-alert>
@stop
