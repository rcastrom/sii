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
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-title">
            Concentrado de fichas del período {{$nombre_periodo->identificacion_corta}}
        </div>
        <div class="card-body">
            <table id="aspirantes" class="table display responsive nowrap">
                <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Clave</th>
                    <th>Carrera</th>
                    <th>Aspirantes</th>
                    <th>Ficha (*)</th>
                    <th>Prope (*)</th>
                    <th>Inscripción (*)</th>
                    <th>Inscritos</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $i=1;
                    $valores=collect($listados);
                    $total_aspirantes=$valores->pluck("aspirantes")->sum();
                    $total_inscritos=$valores->pluck("inscritos")->sum();
                @endphp
                @foreach($listados as $listado)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$listado->carrera}}</td>
                        <td>{{$listado->nombre_carrera}}</td>
                        <td>{{$listado->aspirantes}}</td>
                        <td>{{$listado->pagaron_ficha}}</td>
                        <td>{{$listado->pagaron_prope}}</td>
                        <td>{{$listado->pagaron_inscripcion}}</td>
                        <td>{{$listado->inscritos}}</td>
                        @php
                            $i++;
                        @endphp
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Total de fichas: {{$total_aspirantes}}, inscritos: {{$total_inscritos}}</td>
                        <td colspan="4">(*) Registros pagados.</td>
                    </tr>
                </tfoot>
            </table>
            <div class="card-footer">
                <a href="{{route('escolares.fichas_concentrado_excel',['periodo'=>$periodo])}}" class="btn btn-primary">
                    Descargar Excel completo</a>
            </div>
        </div>
    </x-information>
    <x-additional>
        @slot('header','Entrega de fichas')
            <canvas id="fichas"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new DataTable('#aspirantes', { layout: { topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print'] } } });
    </script>
    <script>
        const ctx = document.getElementById('fichas');
        const labels = {!! json_encode($valores->pluck('nombre_carrera')) !!};
        const data = {
            labels: labels,
            datasets: [{
                axis: 'y',
                label: 'Aspirantes',
                data: {!! json_encode($valores->pluck('aspirantes')) !!},
                position: 'right',
                fill: false,
                borderWidth: 1,

            }]
        };
        new Chart(ctx, {
            type: 'bar',
            data,
            options: {
                indexAxis: 'y',
                scales: {
                    y: {
                        ticks:{
                          crossAlign: 'far',
                            align: 'inner',
                        },
                    }
                },
                responsive: true,

            }
        });

    </script>

@stop
