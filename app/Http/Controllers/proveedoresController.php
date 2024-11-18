<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProveedorRequest;
use App\Http\Requests\UpdateproveedoresRequest;
use App\Models\Documento;
use App\Models\Persona;
use App\Models\Proveedore;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class proveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedore::with('persona.documento')->get();
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentos = Documento::all();
        return view('proveedores.create', compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProveedorRequest $request)
    {
        try {
            DB::beginTransaction();

            $persona = Persona::create($request->all());
            $persona->proveedore()->create([
                'persona_id' => $persona->id
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            // Registra o muestra el error para depuración
            logger()->error($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al agregar el cliente.']);
        }

        return redirect()->route('proveedores.index')->with('success', 'Proveedor agregado con exito');
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
    public function edit(Proveedore $proveedore)
    {
        $proveedore->load('persona.documento');
        $documentos = Documento::all();
        return view('proveedores.edit', compact('proveedore', 'documentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateproveedoresRequest $request, Proveedore $proveedore)
    {
        try {
            DB::beginTransaction();
            
            // Asegúrate de que el proveedor y su relación persona están cargados
            $proveedore->load('persona');
            
            // Actualizar la información de la persona relacionada con el proveedor
            Persona::where('id', $proveedore->persona->id)
                ->update($request->validated());
     
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('proveedores.index')->with('error', 'Hubo un problema al editar el proveedor.');
        }
     
        return redirect()->route('proveedores.index')->with('success', 'Proveedor editado correctamente');
    }
    
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $persona = Persona::find($id);
        if($persona->estado == 1){
            Persona::where('id',$persona->id)->update([
                'estado' => 0
            ]);
            $message = 'La persona ha sido eliminada correctamente';
        }
        else{
            Persona::where('id',$persona->id)->update([
                'estado' => 1
            ]);
            $message = 'La persona ha sido restaurada correctamente';
        }

        return redirect()->route('proveedores.index')->with('success', $message);
    }
}
