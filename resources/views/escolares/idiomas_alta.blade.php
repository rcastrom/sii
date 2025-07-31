@extends('adminlte::page')

@section('title', 'Idioma Extranjero')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Módulo para agregar un idioma extranjero
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{route('escolares.idioma_alta')}}" role="form">
            @csrf
            <div class="form-group">
                <label for="idioma">Nombre completo del idioma extranjero </label>
                <input type="text" name="idioma" id="idioma"
                       required onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <div class="form-group">
                <label for="siglas">Nombre abreviado (siglas) del idioma extranjero </label>
                <input type="text" name="siglas" id="siglas"
                       required onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
    <x-additional>
        @slot('header','Idiomas hasta el momento')
        @if($bandera)
            <table class="table table-responsive table-striped">
               <thead>
                <tr>
                    <th>Idioma</th>
                    <th colspan="2">Acción</th>
                </tr>
               </thead>
                <tbody>
                    @foreach($idiomas as $idioma)
                        <tr>
                            <td>
                                {{$idioma->idioma}}
                            </td>
                            <td>
                                <a href="{{route('escolares.idioma_modifica',['idioma'=>$idioma->id])}}">
                                    <i class="fa fa-edit"></i>Editar
                                </a>
                            </td>
                            <td>
                                Eliminar
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            No hay ningún idioma registrado hasta el momento
        @endif
    </x-additional>
@stop

