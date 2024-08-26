<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresentacionRequest;
use App\Http\Requests\UpdatePresentacionRequest;
use App\Models\Caracteristica;
use App\Models\Presentacion;
use App\Models\Presentacione;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cargar la relación 'caracteristica' en lugar de 'Presentacione'
        $presentaciones = Presentacione::with('caracteristica')->latest()->get();
    
        // Verificar los datos si es necesario
        // dd($presentaciones);
    
        return view('presentacion.index', ['presentaciones' => $presentaciones]);
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentacion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePresentacionRequest $request)
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

            // Crear la relación de presentación con la característica recién creada
            $presentacion = Presentacione::create([
                'caracteristica_id' => $caracteristica->id
            ]);

            // Verificar si la creación de la presentación fue exitosa
            if (!$presentacion) {
                throw new Exception('Error al crear la presentación.');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            // Registrar el error en el log para su análisis
            Log::error('Error al guardar la presentación: ' . $e->getMessage());

            // Lanzar una excepción con el mensaje del error
            throw $e;
        }

        return redirect()->route('presentaciones.index')->with('success', 'Presentación guardada correctamente');
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
    public function edit(Presentacione $presentacione)
    {
        return view('presentacion.edit', compact('presentacione'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresentacionRequest $request, Presentacione $presentacione)
    {
        // Verificar los datos y autorización
        //*dd($request->all(), $presentacione, $this->authorize('update', $presentacione));
    
        $caracteristicaId = $presentacione->caracteristica->id;
        Caracteristica::where('id', $caracteristicaId)->update($request->validated());
    
        return redirect()->route('presentaciones.index')->with('success', 'Presentación editada correctamente');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $presentacion = Presentacione::find($id);
        if ($presentacion->caracteristica->estado == 1) {
            Caracteristica::where('id', $presentacion->caracteristica->id)->update([
                'estado' => 0
            ]);
            $message = 'La presentación ha sido eliminada correctamente';
        } else {
            Caracteristica::where('id', $presentacion->caracteristica->id)->update([
                'estado' => 1
            ]);
            $message = 'La presentación ha sido restaurada correctamente';
        }

        return redirect()->route('presentaciones.index')->with('success', $message);
    }
}
