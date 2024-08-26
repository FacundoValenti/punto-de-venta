<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePresentacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cambia esto a true para permitir que cualquier usuario haga esta solicitud.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Obtén el ID de la característica asociada a la presentación
        $presentacione = $this->route('presentacione');
        $caracteristicaId = $presentacione->caracteristica->id;
        
        return [
            'nombre' => 'required|max:60|unique:caracteristicas,nombre,' . $caracteristicaId,
            'descripcion' => 'nullable|max:255',
            // Agrega otras reglas de validación aquí si es necesario
        ];
    }
}
