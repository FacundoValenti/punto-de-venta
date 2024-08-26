<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarcaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Obtén el ID de la característica asociada a la marca
        $marca = $this->route('marca'); // Asegúrate de que el nombre 'marca' coincide con el nombre del parámetro en la ruta

        if ($marca) {
            $caracteristicaId = $marca->caracteristica->id;

            return [
                'nombre' => 'required|max:60|unique:caracteristicas,nombre,' . $caracteristicaId,
                'descripcion' => 'nullable|max:255',
                // Agrega otras reglas de validación aquí si es necesario
            ];
        }

        // Devuelve un array vacío si $marca es null (para evitar errores en caso de parámetros incorrectos)
        return [];
    }
}

