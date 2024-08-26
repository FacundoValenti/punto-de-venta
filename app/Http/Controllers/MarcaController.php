<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Models\Caracteristica;
use App\Models\Marca;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = Marca::with('caracteristica')->latest()->get();
        return view('marca.index', ['marcas' => $marcas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marca.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request)
    {
        try {
            DB::beginTransaction();
    
            // Crear una nueva marca usando los datos validados del request
            $caracteristica = Caracteristica::create($request->validated());
    
            // Verificar si la creación de la marca fue exitosa
            if (!$caracteristica) {
                throw new Exception('Error al crear la marca.');
            }
            // Crear la relación de presentación con la característica recién creada
            $marca = Marca::create([
                'caracteristica_id' => $caracteristica->id
            ]);
    
            // Verificar si la creación de la presentación fue exitosa
            if (!$marca) {
                throw new Exception('Error al crear la presentación.');
            }
    
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
    
            // Registrar el error en el log para su análisis
            Log::error('Error al guardar la marca: ' . $e->getMessage());
    
            // Mostrar un mensaje de error al usuario
            return redirect()->route('marcas.create')->withErrors(['error' => 'Error al guardar la marca. Por favor, inténtelo de nuevo.']);
        }
    
        return redirect()->route('marcas.index')->with('success', 'Marca guardada correctamente');
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
    public function edit($id)
    {
        // Obtener la marca con la relación 'caracteristica'
        $marca = Marca::with('caracteristica')->find($id);
    
        // Verificar si se encontró la marca
        if (!$marca) {
            return redirect()->route('marcas.index')->withErrors(['error' => 'Marca no encontrada.']);
        }
    
        return view('marca.edit', compact('marca'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarcaRequest $request, Marca $marca)
{
    try {
        //$caracteristicaId = $marca->caracteristica->id;

        // Actualiza la característica asociada a la marca
        $marca->caracteristica->update($request->validated());

        return redirect()->route('marcas.index')->with('success', 'Marca actualizada correctamente.');
    } catch (Exception $e) {
        // Registrar el error en el log para su análisis
        Log::error('Error al actualizar la marca: ' . $e->getMessage());

        // Mostrar un mensaje de error al usuario
        return redirect()->route('marcas.edit', $marca->id)->withErrors(['error' => 'Error al actualizar la marca. Por favor, inténtelo de nuevo.']);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $marca = Marca::find($id);
        if ($marca->caracteristica->estado == 1) {
            Caracteristica::where('id', $marca->caracteristica->id)->update([
                'estado' => 0
            ]);
            $message = 'La marca ha sido eliminada correctamente';
        } else {
            Caracteristica::where('id', $marca->caracteristica->id)->update([
                'estado' => 1
            ]);
            $message = 'La marca ha sido restaurada correctamente';
        }

        return redirect()->route('marcas.index')->with('success', $message);
    }
}
