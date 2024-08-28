@extends('template')

@section('title', 'Registrar Cliente')

@push('css')
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active">Editar Clientes</li>
    </ol>

    <!--Tipo de persona--->
    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{ route('clientes.update', ['cliente' =>$cliente]) }}" method="post">
            @method('PATCH')
            @csrf
            <div class="row g-3">
                <div class="col-md-6 mb-2">
                    <label for="tipo_persona" class="form-label">Tipo de cliente: <span class="fw-bold">{{strtoupper ($cliente->persona->tipo_persona) }}</span></label>
                </div>

                <!--Razon Social--->
                <div class="col-md-12 mb-2" id="box-razon-social">
                    @if ($cliente->persona->tipo_persona == 'natural')
                        <label id="label-natural" for="" class="form-label">Nombre y Apellido</label>
                    @else
                        <label id="label-juridica" for="form-label">Nombre de la empresa</label>
                    @endif
                    <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social', $cliente->persona->razon_social) }}">

                    @error('razon_social')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <!--Direccion--->
                <div class="col-md-12">
                    <label for="direccion" class="form-label">Direccion</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $cliente->persona->direccion) }}">
                    @error('direccion')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <!-- Seleccion de documento -->
                <div class="col-md-6 mb-2">
                    <label for="documento_id" class="form-label">Tipo de documento</label>
                    <select class="form-select" name="documento_id" id="documento_id">
                        @foreach ($documentos as $item)
                        @if ($cliente->persona->documento_id == $item->id)
                            <option selected value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>
                        @else
                            <option value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>
                        @endif
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
                    <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento',$cliente->persona->numero_documento) }}">
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
    @endpush