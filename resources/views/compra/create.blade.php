@extends('template')

@section('title', 'Crear Compra')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Aplica un fondo azul y texto blanco al encabezado de la tabla */
    thead.bg-primary-custom {
        background-color: #007bff;
        color: #ffffff;
    }

    thead.bg-primary-custom th {
        color: #ffffff !important;
    }

    /* Estilo para la línea divisoria */
    .divider {
        border: 0;
        border-top: 2px solid #ddd;
        margin: 20px 0;
    }

    /* Estilo general de los contenedores */
    .container-fluid,
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }

    .bg-success-custom {
        background-color: #28a745;
        color: #ffffff;
    }

    .border-success-custom {
        border-color: #28a745;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Compra</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></li>
        <li class="breadcrumb-item active">Crear Compras</li>
    </ol>
</div>

<form action="{{ route ('compras.store')}}" method="post">
    @csrf

    <div class="container mt-4">
        <div class="row">
            <!-- Detalles de la compra -->
            <div class="col-md-8">
                <div class="text-white bg-primary p-1 text-center">
                    Detalles de la compra
                </div>
                <div class="p-3 border border-3 border-primary">
                    <!-- Contenido de detalles de la compra -->
                    <div class="row">
                        <!-- Producto -->
                        <div class="col-md-4 mb-2">
                            <label for="producto_id" class="form-label">Producto:</label>
                            <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" data-size="1" title="Selecciona tu producto">
                                @foreach ($productos as $item)
                                <option value="{{ $item->id }}">{{ $item->codigo }} {{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cantidad -->
                        <div class="col-md-4 mb-2">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control">
                        </div>

                        <!-- Precio de compra -->
                        <div class="col-md-4 mb-2">
                            <label for="precio_compra" class="form-label">Precio de compra:</label>
                            <input type="number" name="precio_compra" id="precio_compra" class="form-control" step="0.1">
                        </div>

                        <!-- Precio de venta -->
                        <div class="col-md-4 mb-2">
                            <label for="precio_venta" class="form-label">Precio de Venta:</label>
                            <input type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                        </div>

                        <!-- Botón para agregar -->
                        <div class="col-md-12 mb-2 text-end">
                            <button id="btn_agregar" type="button" class="btn btn-primary">Agregar</button>
                        </div>

                        <!-- Tabla para detalles de la compra -->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tabla_detalle" class="table table-hover">
                                    <thead class="bg-primary-custom">
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio compra</th>
                                            <th>Precio Venta</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las filas se agregarán dinámicamente con JavaScript -->
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Sumas</th>
                                            <th colspan="2"><span id="sumas">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">IVA %</th>
                                            <th colspan="2"><span id="iva">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Total</th>
                                            <th colspan="2"> <input type="hidden" name="total" value="0" id="inputTotal"> <span id="total">0</span></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <button id="cancelar" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Cancelar compra
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos generales -->
            <div class="col-md-4">
                <div class="text-white bg-success-custom p-1 text-center">
                    Datos generales
                </div>
                <div class="p-3 border border-3 border-success-custom">
                    <!-- Contenido de datos generales -->
                    <div class="row">
                        <!-- Proveedores -->
                        <div class="col-md-12 mb-2">
                            <label for="proveedore_id" class="form-label">Proveedor:</label>
                            <select name="proveedore_id" id="proveedore_id" class="form-control selectpicker show-tick" data-live-search="true" title="Selecciona" data-size="2">
                                @foreach ($proveedores as $item)
                                <option value="{{ $item->id }}">{{ $item->persona->razon_social }}</option>
                                @endforeach
                            </select>
                            @error('proveedore_id')
                            <small class="text-danger"> {{ '*'. $message }} </small>
                            @enderror
                        </div>

                        <!-- Tipo de comprobante -->
                        <div class="col-md-12 mb-2">
                            <label for="comprobante_id" class="form-label">Comprobante:</label>
                            <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker show-tick" title="Selecciona">
                                @foreach ($comprobantes as $item)
                                <option value="{{ $item->id }}">{{ $item->tipo_comprobante }}</option>
                                @endforeach
                            </select>
                            @error('comprobante_id')
                            <small class="text-danger"> {{ '*'. $message }} </small>
                            @enderror
                        </div>

                        <!-- Número de comprobante -->
                        <div class="col-md-12 mb-2">
                            <label for="numero_comprobante" class="form-label">Número de comprobante:</label>
                            <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control">
                            @error('numero_comprobante')
                            <small class="text-danger"> {{ '*'. $message }} </small>
                            @enderror
                        </div>

                        <!-- Impuesto -->
                        <div class="col-md-12 mb-2">
                            <label for="impuesto" class="form-label">Impuesto:</label>
                            <input readonly type="text" name="impuesto" id="impuesto" class="form-control border-success-custom">
                            @error('impuesto')
                            <small class="text-danger"> {{ '*'. $message }} </small>
                            @enderror
                        </div>

                        <!-- Fecha -->
                        <div class="col-md-12 mb-2">
                            <label for="fecha" class="form-label">Fecha:</label>
                            <input readonly type="date" name="fecha" id="fecha" class="form-control border-success-custom" value="{{ date('Y-m-d') }}">
                            <?php
                            use Carbon\Carbon;
                                $fecha_hora = Carbon::now()->toDateString();
                            ?>
                            <input type="hidden" name="fecha_hora" value="{{ $fecha_hora }}">
                        </div>

                        <!-- Botones -->
                        <div class="col-md-12 mb-2 text-center">
                            <button type="submit" class="btn btn-success" id="guardar">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para cancelar compra-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal de confirmación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cancelar la compra?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btnCancelarCompra" type="button" class="btn btn-danger" data-bs-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
$(document).ready(function() {
    $('#btn_agregar').click(function() {
        console.log('Botón Agregar clicado');
        agregarProducto();
    });

    $('#btnCancelarCompra').click(function() {
        console.log('Botón Cancelar Compra clicado');
        cancelarCompra();
    });

    disableButtons();
    $('#impuesto').val(impuesto + '%');
});

// Variables
let cont = 0;
let subtotal = [];
let sumas = 0;
let iva = 0;
let total = 0;

// Constantes
const impuesto = 21;

function cancelarCompra() {
    console.log('Función cancelarCompra() llamada');

    $('#tabla_detalle > tbody').empty();
    let fila = '<tr><th></th><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
    $('#tabla_detalle').append(fila);

    cont = 0;
    subtotal = [];
    sumas = 0;
    iva = 0;
    total = 0;

    $('#sumas').html(sumas);
    $('#iva').html(iva);
    $('#total').html(total);
    $('#impuesto').val(impuesto + '%');
    $('#inputTotal').val(total);

    limpiarCampos();
    disableButtons();

    console.log('Valores después de cancelar compra:', {
        cont, subtotal, sumas, iva, total
    });
}

function disableButtons() {
    console.log('Función disableButtons() llamada con total:', total);
    if (total == 0) {
        $('#guardar').hide();
        $('#cancelar').hide();
    } else {
        $('#guardar').show();
        $('#cancelar').show();
    }
}

function agregarProducto() {
    console.log('Función agregarProducto() llamada');
    
    let idProducto = $('#producto_id').val();
    let nameProducto = ($('#producto_id option:selected').text()).split(' ')[1];
    let cantidad = $('#cantidad').val();
    let precioCompra = $('#precio_compra').val();
    let precioVenta = $('#precio_venta').val();

    console.log('Datos del producto:', { idProducto, nameProducto, cantidad, precioCompra, precioVenta });

    if (nameProducto != '' && cantidad != '' && precioCompra != '' && precioVenta != '') {
        if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precioCompra) > 0 && parseFloat(precioVenta) > 0) {
            if (parseFloat(precioVenta) > parseFloat(precioCompra)) {
                subtotal[cont] = round(cantidad * precioCompra);
                sumas += subtotal[cont];
                iva = round(sumas / 100 * impuesto);
                total = round(sumas + iva);

                let fila = '<tr id="fila' + cont + '">' +
                    '<th>' + (cont + 1) + '</th>' +
                    '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                    '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                    '<td><input type="hidden" name="arraypreciocompra[]" value="' + precioCompra + '">' + precioCompra + '</td>' +
                    '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
                    '<td>' + subtotal[cont] + '</td>' +
                    '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + cont + ')" ><i class="fa-solid fa-trash"></i></button></td>' +
                    '</tr>';

                $('#tabla_detalle').append(fila);
                limpiarCampos();
                cont++;
                disableButtons();

                $('#sumas').html(sumas);
                $('#iva').html(iva);
                $('#total').html(total);
                $('#impuesto').val(iva);
                $('#inputTotal').val(total);

                console.log('Producto agregado, totales actualizados:', {
                    sumas, iva, total
                });

            } else {
                showModal('Precio de compra incorrecto');
            }
        } else {
            showModal('Valores incorrectos');
        }
    } else {
        showModal('Tenes que llenar todos los campos');
    }
}

function eliminarProducto(indice) {
    console.log('Función eliminarProducto() llamada para índice:', indice);

    sumas -= round(subtotal[indice]);
    iva = round(sumas / 100 * impuesto);
    total = round(sumas + iva);

    $('#sumas').html(sumas);
    $('#iva').html(iva);
    $('#total').html(total);
    $('#impuesto').val(iva);
    $('#inputTotal').val(total);

    $('#fila' + indice).remove();
    disableButtons();

    console.log('Producto eliminado, totales actualizados:', {
        sumas, iva, total
    });
}

function limpiarCampos() {
    let select = $('#producto_id');
    select.selectpicker();
    select.selectpicker('val', '');
    $('#cantidad').val('');
    $('#precio_compra').val('');
    $('#precio_venta').val('');
}

function round(num, decimales = 2) {
    var signo = (num >= 0 ? 1 : -1);
    num = num * signo;
    if (decimales === 0)
        return signo * Math.round(num);
    num = num.toString().split('e');
    num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
    num = num.toString().split('e');
    return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
}

function showModal(message, icon = 'error') {
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
        icon: icon,
        title: message
    });
}

</script>





@endpush