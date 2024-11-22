@extends('adminlte::page')

@section('title', 'Evaluación docente')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3>Evaluación al docente</h3>
        <form action="{{route('personal.evaldocente')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="periodo">Señale el período por consultar</label>
                <select name="periodo" id="periodo" required class="form-control">
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}" {{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_larga}}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="personal" value="{{base64_encode($doc->id)}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
