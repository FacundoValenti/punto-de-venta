@extends('template')

@section('title', 'Registrar Cliente')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<style>
    #box-razon-social {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Registrar cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active">Registrar Cliente</li>
    </ol>

    <!--Tipo de persona--->
    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{ route('clientes.store') }}" method="post">
            @csrf
            <div class="row g-3">
                <div class="col-md-6 mb-2">
                    <label for="tipo_persona" class="form-label">Tipo de cliente</label>
                    <select class="form-select" name="tipo_persona" id="tipo_persona">
                        <option value="" selected>Seleccione una opcion</option>
                        <option value="natural" {{ old('tipo_persona') == 'natural' ? 'selected' : ''}}>Persona Normal</option>
                        <option value="juridica" {{ old('tipo_persona') == 'juridica' ? 'selected' : ''}}>Persona juridica</option>
                    </select>
                    @error('tipo_persona')
                    <small class="text-danger">{{ '*'. $message }}</small>
                    @enderror
                </div>

                <!--Razon Social--->
                <div class="col-md-12 mb-2" id="box-razon-social">
                    <label id="label-natural" for="" class="form-label">Nombre y Apellido</label>
                    <label id="label-juridica" for="form-label">Nombre de la empresa</label>

                    <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}">

                    @error('razon_social')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <!--Direccion--->
                <div class="col-md-12">
                    <label for="direccion" class="form-label">Direccion</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}">
                    @error('direccion')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <!-- Seleccion de documento -->
                <div class="col-md-6 mb-2">
                    <label for="documento_id" class="form-label">Tipo de documento</label>
                    <select class="form-select" name="documento_id" id="documento_id">
                        <option value="" selected>Seleccione una opción</option>
                        @foreach ($documentos as $item)
                        <option value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->tipo_documento }}
                        </option>
                        @endforeach
                    </select>
                    @error('documento_id')
                    <small class="text-danger">{{ '*'. $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-2">
                    <label for="numero_documento" class="form-label">Numero de documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}">
                    @error('numero_documento')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>


                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
    @endsection

    @push('js')
    <script>
        $(document).ready(function() {
            $('#tipo_persona').on('change', function() {
                let selectValue = $(this).val();
                // natural // empresa
                if (selectValue == 'natural') {
                    $('#label-juridica').hide();
                    $('#label-natural').show();
                } else {
                    $('#label-natural').hide();
                    $('#label-juridica').show();
                }

                $('#box-razon-social').show();
            });
        });
    </script>
    @endpush