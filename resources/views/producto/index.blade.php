@extends('template')

@section('title', 'Productos')

@push('css')
<!-- Importa Bootstrap CSS primero -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<!-- Luego, importa los estilos de Simple DataTables -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')

@if (session('success'))
<!-- Incluye SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let message = "{{ session('success') }}";
        Swal.fire({
            icon: 'success',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Panel</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>
    <div class="mb-4">
        <a href="{{ route('productos.create') }}">
            <button type="button" class="btn btn-primary">Añadir nuevo Producto</button>
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla Productos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Presentación</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producto as $item)
                        <tr>
                            <td>{{ $item->codigo }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->marca->caracteristica->nombre }}</td>
                            <td>{{ $item->presentacione->caracteristica->nombre }}</td>
                            <td>
                                @foreach ($item->categorias as $category)
                                <span class="m-1 rounded-pill p-1 bg-secondary text-white text-center">{{ $category->caracteristica->nombre }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($item->estado == 1)
                                <span class="fw-bolder p-1 rounded bg-success text-white">Activo</span>
                                @else
                                <span class="fw-bolder p-1 rounded bg-danger text-white">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <!-- Botón para editar -->
                                <a href="{{ route('productos.edit',['producto' => $item]) }}" class="btn btn-warning btn-sm">Editar</a>

                                @if ($item->estado == 1)
                                <!-- Botón para eliminar -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmodal-{{ $item->id }}" data-id="{{ $item->id }}">Eliminar</button>
                                @else
                                <!-- Botón para restaurar -->
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmodal-{{ $item->id }}" data-id="{{ $item->id }}">Restaurar</button>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach ($producto as $item)
<!-- Modal para eliminar/restaurar -->
<div class="modal fade" id="confirmodal-{{ $item->id }}" tabindex="-1" aria-labelledby="confirmodalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmodalLabel-{{ $item->id }}">
                    {{ $item->estado == 1 ? 'Confirmar Eliminación' : 'Confirmar Restauración' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $item->estado == 1 ? '¿Estás seguro de que deseas eliminar este producto?' : '¿Estás seguro de que deseas restaurar este producto?' }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <form action="{{ route('productos.destroy', ['producto' => $item->id]) }}" method="post" style="display: inline;">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        {{ $item->estado == 1 ? 'Confirmar Eliminación' : 'Confirmar Restauración' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/umd/simple-datatables.min.js" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>

<!-- Incluye los scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-9/aliU8dGdQsPAxzyapLyyu8OdvbbXTU2F5a/9CkEci7H+JQ7/ieK+zxU5ge+RI5" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9i28NTk32EFL2F6A3K4P6jyY9NT1kG6XY3xqD8HEc+1+WmS4N" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-rbsAq+u7g9G4H6F8Lh5vKhfF5Y1jFzKQ6E5a0xTKr2e72hh3tkY0A7rT1kcNzQVO" crossorigin="anonymous"></script>

<script>
    $('#vermodal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        $.ajax({
            url: '{{ url("productos") }}/' + id,
            method: 'GET',
            success: function(data) {
                $('#vermodal-' + id + ' .modal-body').html(`
                    <p><strong>Código:</strong> ${data.codigo}</p>
                    <p><strong>Nombre:</strong> ${data.nombre}</p>
                    <p><strong>Descripción:</strong> ${data.descripcion}</p>
                    <p><strong>Marca:</strong> ${data.marca}</p>
                    <p><strong>Presentación:</strong> ${data.presentacion}</p>
                    <p><strong>Categoría:</strong> ${data.categorias.map(c => c.nombre).join(', ')}</p>
                    <p><strong>Estado:</strong> ${data.estado == 1 ? 'Activo' : 'Inactivo'}</p>
                    <p><strong>Stock:</strong> ${data.stock}</p>
                    ${data.img_path ? `<img src="{{ Storage::url('public/productos/') }}/${data.img_path}" class="img-fluid" alt="${data.nombre}">` : '<p>No hay imagen disponible</p>'}
                `);
            }
        });
    });
</script>
@endpush