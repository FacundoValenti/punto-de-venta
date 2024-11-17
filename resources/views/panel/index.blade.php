@extends('template')

@section('title', 'Panel')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
@endpush

@section('content')

@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let message = "{{ session('success') }}";
    Swal.fire({
        position: "center",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif

<div class="container-fluid px-4" style="margin-top: 20px !important;">
    <h1 class="mt-4 text-left">Panel</h1>
    <div class="row justify-content-center">
        <!-- Clientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <i class="fa-solid fa-people-group"></i><span class="m-1">Clientes</span>
                        </div>
                        <div class="col-4 text-center">
                            <?php
                            use App\Models\Cliente;
                            $clientes = count(Cliente::all());
                            ?>
                            <p class="fw-bold fs-4">{{$clientes}}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('clientes.index') }}">Agregar nuevo cliente</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Producto -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <i class="fa-brands fa-shopify"></i><span class="m-1">Productos</span>
                        </div>
                        <div class="col-4 text-center">
                            <?php
                            use App\Models\Producto;
                            use Illuminate\Support\Facades\DB;
                            $productos = count(Producto::all());
                            ?>
                            <p class="fw-bold fs-4">{{$productos}}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('productos.index') }}">Agregar nuevo producto</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <?php
        // Obtener las ventas totales por día
        $ventasPorFecha = DB::table('ventas')
            ->selectRaw("DATE(created_at) as fecha, COUNT(*) as total")
            ->whereDate('created_at', now())
            ->groupByRaw("DATE(created_at)")
            ->orderBy('fecha')
            ->get();

        $labels = [];
        $datos = [];

        foreach ($ventasPorFecha as $venta) {
            $labels[] = $venta->fecha;
            $datos[] = $venta->total;
        }
        ?>

        <style>
            /* Estilo para centrar el gráfico */
            .col-xl-12 {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 60vh;
            }
        </style>

        <!-- Gráfico de Ventas -->
        <div class="col-xl-12">
            <div class="card" style="width: 60%; margin: 0 auto;">
                <div class="card-header" style="font-size: 14px; padding: 10px;">
                    <i class="fas fa-chart-bar me-1"></i>
                    Ventas del Día
                </div>
                <div class="card-body" style="padding: 10px;">
                    <div id="chart-container" style="position: center; height: 200px; width: 100%; margin: 0 auto;">
                        <canvas id="ventasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

@endsection

@push('js')
<!-- Solo incluir Chart.js una vez -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ventasChart');

        if (!ctx) {
            console.error('No se encontró el elemento canvas');
            return;
        }

        // Datos para el gráfico
        const chartData = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Total de ventas',
                data: <?php echo json_encode($datos); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Configuración del gráfico
        const config = {
            type: 'bar',
            data: chartData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Ventas Totales del Día'
                    }
                }
            }
        };

        // Crear el gráfico
        try {
            new Chart(ctx, config);
            console.log('Datos del gráfico:', {
                labels: chartData.labels,
                datos: chartData.datasets[0].data
            });
        } catch (error) {
            console.error('Error al crear el gráfico:', error);
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="{{ asset ('js/datatables-simple-demo.js')}}"></script>
@endpush
