<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Marca;
use App\Models\Presentacione;
use App\Models\Producto;
use App\Models\Proveedore;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class panelController extends Controller
{
    public function panel()
    {
        $clientes = Cliente::count();
        $categorias = Categoria::count();
        $compras = Compra::count();
        $marcas = Marca::count();
        $presentaciones = Presentacione::count();
        $productos = Producto::count();
        $proveedores = Proveedore::count();
    
        // Ventas diarias
        $fechaInicio = now()->subDays(30);
        $fechaFin = now();
    
        $ventasDiarias = Venta::select(
            DB::raw("DATE_FORMAT(created_at, '%d/%m/%Y') as labels"),
            DB::raw('COUNT(*) as data')
        )
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d/%m/%Y')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y%m%d')"), 'DESC')
            ->get();
    
        // Preparar datos para Chart.js
        $graficoVentas = [
            'labels' => $ventasDiarias->pluck('labels'),
            'data' => $ventasDiarias->pluck('data')
        ];
    
        return view('panel', compact(
            'clientes',
            'categorias',
            'compras',
            'marcas',
            'presentaciones',
            'productos',
            'proveedores',
            'graficoVentas'
        ));
    }
    
}
