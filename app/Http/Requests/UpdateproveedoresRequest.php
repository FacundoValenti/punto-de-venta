<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateproveedoresRequest extends FormRequest
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
        $proveedor = $this->route('proveedore'); // Usa el nombre correcto para el parámetro de ruta
    
        return [
            'razon_social' => 'required|max:80',
            'direccion' => 'required|max:80',
            'documento_id' => 'required|integer|exists:documentos,id',
            'numero_documento' => [
                'required',
                'max:20',
                'unique:personas,numero_documento,' . $proveedor->persona->id
            ]
        ];
    }
    
}