@extends('template')

@section('title', 'Editar Proveedor')

@push('css')
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Proveedor</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
        <li class="breadcrumb-item active">Editar Proveedor</li>
    </ol>

    <!-- Tipo de persona -->
    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{ route('proveedores.update', ['proveedore' => $proveedore->id]) }}" method="post">
            @method('PATCH')
            @csrf
            <div class="row g-3">
                <div class="col-md-6 mb-2">
                    <label for="tipo_persona" class="form-label">Tipo de proveedor: <span class="fw-bold">{{ strtoupper($proveedore->persona->tipo_persona) }}</span></label>
                </div>

                <!-- Razón Social -->
                <div class="col-md-12 mb-2" id="box-razon-social">
                    @if ($proveedore->persona->tipo_persona == 'natural')
                    <label id="label-natural" for="" class="form-label">Nombre y Apellido</label>
                    @else
                    <label id="label-juridica" for="form-label">Nombre de la empresa</label>
                    @endif
                    <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social', $proveedore->persona->razon_social) }}">

                    @error('razon_social')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <!-- Dirección -->
                <div class="col-md-12">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $proveedore->persona->direccion) }}">
                    @error('direccion')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <!-- Selección de documento -->
                <div class="col-md-6 mb-2">
                    <label for="documento_id" class="form-label">Tipo de documento</label>
                    <select class="form-select" name="documento_id" id="documento_id">
                        @foreach ($documentos as $item)
                        <option value="{{ $item->id }}" {{ $proveedore->persona->documento_id == $item->id ? 'selected' : '' }}>
                            {{ $item->tipo_documento }}
                        </option>
                        @endforeach
                    </select>
                    @error('documento_id')
                    <small class="text-danger">{{ '*'. $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-2">
                    <label for="numero_documento" class="form-label">Número de documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento', $proveedore->persona->numero_documento) }}">
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