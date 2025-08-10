@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
@stop

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card">
            <div class="card-title">
                Del siguiente listado, seleccione al aspirante que vaya a marcar como aceptado.<br>
                <strong>Si no hay grupos activos, no podrá marcar seleccionar aspirantes</strong>
            </div>
            <div class="card-body">
                <h6>Carrera: {{$nombre_carrera}}</h6>
                @if($bandera)
                    <form action="{{route('desarrollo.grupo_aspirante')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-8">
                                <select name="id" id="id" class="form-control" required>
                                    <option value="" selected>--Seleccione--</option>
                                    @foreach($aspirantes as $aspirante)
                                        <option value="{{$aspirante->id}}">{{$aspirante->apellido_paterno.' '.$aspirante->apellido_materno.' '.$aspirante->nombre_aspirante}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="grupo" id="grupo" class="form-control" required>
                                    <option value="" selected>--Seleccione--</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{$grupo->grupo}}">Gpo {{$grupo->grupo}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary mt-2">Marcar como seleccionado</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </x-information>
    <x-additional>
        @slot('header','Estudiantes aceptados')
        <table id="aspirantes" class="table display responsive nowrap">
            <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Nombre</th>
                <th>¿Aceptado?</th>
                <th>Grupo</th>
            </tr>
            </thead>
            <tbody>
            @php
                $i=1;
            @endphp
            @foreach($aspirantes as $aspirante)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$aspirante->apellido_paterno.' '.$aspirante->apellido_materno.' '.$aspirante->nombre_aspirante}}</td>
                    <td>{{$aspirante->aceptado?"Sí":"No"}}</td>
                    <td>{{$aspirante->grupo}}</td>
                    @php
                        $i++;
                    @endphp
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-additional>
@stop
@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
        new DataTable('#aspirantes', { layout: { topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print'] } } });
    </script>

@stop

