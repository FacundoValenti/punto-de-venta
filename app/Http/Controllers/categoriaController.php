<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Caracteristica;
use App\Models\Categoria;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class categoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::with('caracteristica')->latest()->get();
        return view('categoria.index', ['categorias' => $categorias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        // Mostrar los datos validados para depuración
        // dd($request->validated());
    
        try {
            DB::beginTransaction();
    
            // Crear una nueva característica usando los datos validados del request
            $caracteristica = Caracteristica::create($request->validated());
    
            // Verificar si la creación de la característica fue exitosa
            if (!$caracteristica) {
                throw new Exception('Error al crear la característica.');
            }
    
            // Crear la relación de categoría con la característica recién creada
            $categoria = $caracteristica->categoria()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
    
            // Verificar si la creación de la categoría fue exitosa
            if (!$categoria) {
                throw new Exception('Error al crear la categoría.');
            }
    
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
    
            // Registrar el error en el log para su análisis
            Log::error('Error al guardar la categoría: ' . $e->getMessage());
    
            // Lanzar una excepción con el mensaje del error
            throw $e;
        }
        return redirect()->route('categorias.index')->with('success', 'Categoria guardada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //dd($categoria);

        return view('categoria.edit', ['categoria' => $categoria]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        // Primero obtenemos el ID de la característica asociada a la categoría
        $caracteristicaId = $categoria->caracteristica->id;
    
        // Luego actualizamos la característica con los datos validados del request
        Caracteristica::where('id', $caracteristicaId)->update($request->validated());
    
        // Finalmente redirigimos con un mensaje de éxito
        return redirect()->route('categorias.index')->with('success', 'Categoría editada correctamente');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $categoria = Categoria::find($id);
        if($categoria->caracteristica->estado == 1){
            Caracteristica::where('id',$categoria->caracteristica->id)->update([
                'estado' => 0
            ]);
            $message = 'La categoria ha sido eliminada correctamente';
        }
        else{
            Caracteristica::where('id',$categoria->caracteristica->id)->update([
                'estado' => 1
            ]);
            $message = 'La categoria ha sido restaurada correctamente';
        }

        return redirect()->route('categorias.index')->with('success', $message);
    }
}
