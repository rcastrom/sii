@extends('adminlte::page')

@section('title', 'Gpo Propedéutico')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-header">
            <div class="card-title">
                Aquí podrá editar la información del grupo propedéutico; o bien, asignarle un horario
            </div>
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
            <form action="{{route('desarrollo.grupos_editar')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $grupo->id }}">

                <div class="form-section datos-principales">
                    <div class="form-group">
                        <label for="materia">Materia</label>
                        <input type="text"
                               name="materia"
                               id="materia"
                               required
                               value="{{ $grupo->materia }}"
                               class="form-control uppercase-input">
                    </div>

                    <div class="form-group">
                        <label for="nombre_corto">Nombre corto</label>
                        <input type="text"
                               name="nombre_corto"
                               id="nombre_corto"
                               maxlength="20"
                               required
                               value="{{ $grupo->nombre_corto }}"
                               class="form-control uppercase-input">
                    </div>

                    <div class="form-group">
                        <label for="grupo">Grupo</label>
                        <input type="text"
                               name="grupo"
                               id="grupo"
                               maxlength="2"
                               required
                               value="{{ $grupo->grupo }}"
                               class="form-control uppercase-input">
                    </div>
                </div>

                <div class="form-section horarios">
                    @php
                        $dias = [
                            1 => 'lunes',
                            2 => 'martes',
                            3 => 'miércoles',
                            4 => 'jueves',
                            5 => 'viernes'
                        ];
                    @endphp

                    @foreach($dias as $num => $dia)
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="entrada_{{ $num }}">Entrada {{ $dia }}</label>
                                    <input type="time"
                                           class="form-control"
                                           name="entrada_{{ $num }}"
        Y                                   id="entrada_{{ $num }}"
                                           value="{{ formatHora($grupo->{"entrada_$num"}) }}">
                                </div>
                                <div class="col-6">
                                    <label for="salida_{{ $num }}">Salida {{ $dia }}</label>
                                    <input type="time"
                                           class="form-control"
                                           name="salida_{{ $num }}"
                                           id="salida_{{ $num }}"
                                           value="{{ formatHora($grupo->{"salida_$num"}) }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary">Continuar</button>
            </form>
        </div>
    </x-information>
@stop

@push('scripts')
<script>
document.querySelectorAll('.uppercase-input').forEach(input => {
    input.addEventListener('change', e => e.target.value = e.target.value.toUpperCase());
});
</script>
@endpush

@php
function formatHora($hora) {
    return is_null($hora) ? null : date("H:i", strtotime($hora));
}
@endphp
