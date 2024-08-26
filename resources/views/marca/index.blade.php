@extends('template')

@section('title', 'Marcas')

@push('css')
<!-- Incluye CSS necesario aquí -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Importa Bootstrap CSS primero -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">

<!-- Luego, importa los estilos de Simple DataTables -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')

@if (session('success'))
<script>
    let message = "{{ session('success') }}";
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: message
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Marcas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Panel</a></li>
        <li class="breadcrumb-item active">Marcas</li>
    </ol>

    <div class="mb-4">
        <a href="{{ route('marcas.create') }}">
            <button type="button" class="btn btn-primary">Añadir nueva marca</button>
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla Marcas
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marcas as $marca)
                        <tr>
                            <td>{{ $marca->caracteristica->nombre }}</td>
                            <td>{{ $marca->caracteristica->descripcion }}</td>
                            <td>
                                @if ($marca->caracteristica->estado == 1)
                                <span class="fw-bolder p-1 rounded bg-success text-white">Activo</span>
                                @else
                                <span class="fw-bolder p-1 rounded bg-danger text-white">Eliminado</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <form action="{{ route('marcas.edit', ['marca' => $marca->id]) }}" method="get">
                                        <button type="submit" class="btn btn-warning">Editar</button>
                                    </form>

                                    @if ($marca->caracteristica->estado == 1)
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmodal-{{ $marca->id }}">
                                        Eliminar
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmodal-{{ $marca->id }}">
                                        Restaurar
                                    </button>
                                    @endif

                                    <!-- Modal de confirmación -->
                                    <div class="modal fade" id="confirmodal-{{ $marca->id }}" tabindex="-1" aria-labelledby="exampleModalLabel-{{ $marca->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel-{{ $marca->id }}">
                                                        {{ $marca->caracteristica->estado == 1 ? 'Confirmar Eliminación' : 'Confirmar Restauración' }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ $marca->caracteristica->estado == 1 ? '¿Estás seguro de que deseas eliminar esta marca?' : '¿Estás seguro de que deseas restaurar esta marca?' }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <form action="{{ route('marcas.destroy', ['marca' => $marca->id]) }}" method="post" style="display: inline;">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Incluye JavaScript necesario aquí -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/umd/simple-datatables.min.js" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
