@extends('template')

@section('title', 'Editar Marca')

@push('css')
    <!-- Estilos -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Importa Bootstrap CSS primero -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- Luego, importa los estilos de Simple DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Marca</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('marcas.index') }}">Marca</a></li>
            <li class="breadcrumb-item active">Editar Marca</li>
        </ol>

        @if ($marca && $marca->caracteristica)
            <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
                <form action="{{ route('marcas.update', ['marca' => $marca->id]) }}" method="post">
                    @method('PATCH')
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre de la Característica</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $marca->caracteristica->nombre ?? '') }}">
                            @error('nombre')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion', $marca->caracteristica->descripcion ?? '') }}</textarea>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <button type="reset" class="btn btn-secondary">Reiniciar</button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="alert alert-danger" role="alert">
                La marca o la característica asociada no se encontraron.
            </div>
        @endif
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/umd/simple-datatables.min.js" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
