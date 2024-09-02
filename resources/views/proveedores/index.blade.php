@extends('template')

@section('title', 'Proveedores')

@push('css')
<!-- Importa SweetAlert2 -->
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
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: 'success',
        title: message
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Proveedores</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Panel</a></li>
        <li class="breadcrumb-item active">Proveedores</li>
    </ol>
    <div class="mb-4">
        <a href="{{ route('proveedores.create') }}" class="btn btn-primary">Añadir nuevo Proveedor</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla Proveedores
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Documento</th>
                            <th>Tipo de Proveedor</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $item)
                        <tr>
                            <td>{{ $item->persona->razon_social }}</td>
                            <td>{{ $item->persona->direccion }}</td>
                            <td>
                                <p class="fw-normal mb-1">{{ $item->persona->documento->tipo_documento }}</p>
                                <p class="text-muted mb-0">{{ $item->persona->numero_documento }}</p>
                            </td>
                            <td>{{ $item->persona->tipo_persona }}</td>
                            <td>
                                @if ($item->persona->estado == 1)
                                <span class="badge rounded-pill text-bg-success d-inline">Activo</span>
                                @else
                                <span class="badge rounded-pill text-bg-danger d-inline">Eliminado</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <form action="{{ route('proveedores.edit', ['proveedore' => $item]) }}" method="get">
                                        <button type="submit" class="btn btn-warning">Editar</button>
                                    </form>

                                    @if ($item->persona->estado == 1)
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmodal-{{ $item->id }}">
                                        Eliminar
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmodal-{{ $item->id }}">
                                        Restaurar
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal de confirmación -->
                        <div class="modal fade" id="confirmodal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmodalLabel-{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmodalLabel-{{ $item->id }}">
                                            {{ $item->persona->estado == 1 ? 'Confirmar Eliminación' : 'Confirmar Restauración' }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {{ $item->persona->estado == 1 ? '¿Estás seguro de que deseas eliminar este proveedor?' : '¿Estás seguro de que deseas restaurar este proveedor?' }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <form action="{{ route('proveedores.destroy', ['proveedore' => $item->persona->id]) }}" method="post" style="display: inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                {{ $item->persona->estado == 1 ? 'Confirmar Eliminación' : 'Confirmar Restauración' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<!-- Importa jQuery primero -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Luego, importa el JavaScript de Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/umd/simple-datatables.min.js" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
