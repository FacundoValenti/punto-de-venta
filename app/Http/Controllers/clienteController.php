<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Persona;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Return_;

class clienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('persona.documento')->get();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentos = Documento::all();
        return view('clientes.create', compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try {
            DB::beginTransaction();
    
            $persona = Persona::create($request->all());
            $persona->cliente()->create([
                'persona_id' => $persona->id
            ]);
    
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            // Registra o muestra el error para depuración
            logger()->error($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al agregar el cliente.']);
        }
    
        return redirect()->route('clientes.index')->with('success', 'Cliente agregado con éxito');
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
    public function edit(Cliente $cliente)
    {
        $cliente->load('persona.documento');
        $documentos = Documento::all();
        return view('clientes.edit', compact('cliente', 'documentos'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        try {
            DB::beginTransaction();
            
            // Obtener los datos validados
            $validatedData = $request->validated();
    
            // Actualizar la información de la persona relacionada con el cliente
            Persona::where('id', $cliente->persona->id)
                ->update($validatedData);
    
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('clientes.index')->with('error', 'Hubo un problema al editar el cliente.');
        }
    
        return redirect()->route('clientes.index')->with('success', 'Cliente editado correctamente');
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

        return redirect()->route('clientes.index')->with('success', $message);
    }
}
