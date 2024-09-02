<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompraRequest;
use App\Http\Requests\StoreProveedorRequest;
use App\Models\Compra;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Proveedore;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class compraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = compra::with('comprobante', 'proveedore.persona')->where('estado', 1)
        ->latest()
        ->get();
        return view('compra.index', compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedore::whereHas('persona', function($query){
            $query->where('estado', 1);
        })->get();
        $comprobantes = Comprobante::all();
        $productos = Producto::where('estado', 1)->get();
        return view('compra.create', compact('proveedores', 'comprobantes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request)
{
    try {
        DB::beginTransaction();

        // Crear compra
        $compra = Compra::create($request->validated());

        // Obtener datos de los productos
        $arrayProducto_id = $request->get('arrayidproducto');
        $arraycantidad = $request->get('arraycantidad');
        $arrayPrecioCompra = $request->get('arraypreciocompra');
        $arrayPrecioVenta = $request->get('arrayprecioventa');

        $sizeArray = count($arrayProducto_id);
        $cont = 0;

        while($cont < $sizeArray){
            $compra->productos()->syncWithoutDetaching([
                $arrayProducto_id[$cont] => [
                    'cantidad' => $arraycantidad[$cont],
                    'precio_compra' => $arrayPrecioCompra[$cont],
                    'precio_venta' => $arrayPrecioVenta[$cont]
                ]
            ]);

            // Actualizar stock del producto
            $producto = Producto::find($arrayProducto_id[$cont]);
            if ($producto) {
                $stockActual = $producto->stock;
                $stockNuevo = intval($arraycantidad[$cont]);

                DB::table('productos')
                    ->where('id', $producto->id)
                    ->update([
                        'stock' => $stockActual + $stockNuevo
                    ]);
            }

            $cont++;
        }

        DB::commit();

        return redirect()->route('compras.index')->with('success', 'Compra exitosa');

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Error al guardar la compra: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Ocurrió un error al guardar la compra.');
    }
}

    
    

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        return view('compra.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Compra::where('id',$id)
        ->update([
            'estado' => 0
        ]);

        return redirect()->route('compras.index')->with('success', 'Compra eliminada correctamente');
    }
}
