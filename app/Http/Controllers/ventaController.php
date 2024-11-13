<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Venta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ventaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventas = Venta::with(['comprobante','cliente.persona','user'])
        ->where('estado',1)
        ->latest()
        ->get();
        return view('venta.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subquery = DB::table('compra_producto')
            ->select('producto_id', DB::raw('MAX(created_at) as max_created_at'))
            ->groupBy('producto_id');

        $productos = Producto::join('compra_producto as cpr', function ($join) use ($subquery) {
            $join->on('cpr.producto_id', '=', 'productos.id')
                ->whereIn('cpr.created_at', function ($query) use ($subquery) {
                    $query->select('max_created_at')
                        ->fromSub($subquery, 'subquery')
                        ->whereRaw('subquery.producto_id = cpr.producto_id');
                });
        })
            ->select('productos.nombre', 'productos.id', 'productos.stock', 'cpr.precio_venta')
            ->where('productos.estado', 1)
            ->where('productos.stock', '>', 0)
            ->get();

        $clientes = Cliente::whereHas('persona', function ($query) {
            $query->where('estado', 1);
        })->get();

        $comprobantes = Comprobante::all();

        return view('venta.create', compact('productos', 'clientes', 'comprobantes'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVentaRequest $request)
{
    try {
        DB::beginTransaction();
    
        // Crea la venta
        $venta = Venta::create($request->validated());
    
        // Recupera los arrays y convierte los valores a numéricos
        $arrayProducto_id = $request->get('arrayidproducto');
        $arrayCantidad = array_map('intval', $request->get('arraycantidad'));  // Convertir cantidades a enteros
        $arrayPrecioVenta = array_map('floatval', $request->get('arrayprecioventa'));  // Convertir precios a flotantes
        $arrayDescuento = array_map('floatval', $request->get('arraydescuento'));  // Convertir descuentos a flotantes
    
        $sizeArray = count($arrayProducto_id);
    
        for ($cont = 0; $cont < $sizeArray; $cont++) {
            // Agregar el producto a la venta
            $venta->productos()->attach($arrayProducto_id[$cont], [
                'cantidad' => $arrayCantidad[$cont],
                'precio_venta' => $arrayPrecioVenta[$cont],
                'descuento' => $arrayDescuento[$cont]
            ]);
    
            // Actualizar el stock del producto
            $producto = Producto::find($arrayProducto_id[$cont]);
            if ($producto) {
                $producto->decrement('stock', $arrayCantidad[$cont]);
            }
        }
    
        DB::commit();
    } catch (Exception $e) {
        DB::rollBack();
        dd($e);  // Verifica el error aquí si sigue habiendo algún problema
    }
        
    

    return redirect()->route('ventas.index')->with('success', 'Venta exitosa');
}



    


    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
{
    // Asegúrate de cargar la relación productos
    $venta->load('productos');

    // Verifica el contenido de la venta y sus productos
    //dd($venta);

    return view('venta.show', compact('venta'));
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
        Venta::where('id',$id)
        ->update([
            'estado' => 0
        ]);

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente');
    }
}
