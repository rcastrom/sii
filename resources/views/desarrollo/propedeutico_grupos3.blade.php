@extends('adminlte::page')

@section('title', 'Gpo Propedéutico')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-header">
            <div class="card-title">Baja de grupo propedéutico</div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p>
                Está usted a punto de eliminar a la materia {{$grupo->materia}} grupo {{$grupo->grupo}}.
                Confirme si desea continuar.<br>
                En caso de contar con alumnos inscritos, el sistema los dará de baja.
            </p>

            <form action="{{route('desarrollo.eliminar_grupo')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="validar" required>
                            <label class="form-check-label" for="validar">
                                Eliminar
                            </label>
                            <div class="invalid-feedback">
                                You must agree before submitting.
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$grupo->id}}">
                <input type="hidden" name="periodo" value="{{$periodo}}">
                <button type="submit" class="btn btn-danger">Continuar</button>
            </form>
        </div>
    </x-information>

@stop
