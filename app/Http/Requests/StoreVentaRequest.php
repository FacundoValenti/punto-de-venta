<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
{
    return [
        'fecha_hora' => 'required',
        'impuesto' => 'required',
        'numero_comprobante' => 'required|unique:ventas,numero_comprobante|max:255',
        'total' => 'required|numeric',
        'cliente_id' => 'required|exists:clientes,id',
        'user_id' => 'required|exists:users,id',
        'comprobante_id' => 'required|exists:comprobantes,id',
        
        // Validación para los arrays
        'arrayprecioventa' => 'required|array',
        'arrayprecioventa.*' => 'required|numeric|min:0', // Asegura que todos los precios sean numéricos y no negativos

        'arraycantidad' => 'required|array',
        'arraycantidad.*' => 'required|integer|min:1', // Asegura que todas las cantidades sean enteros y mayores a 0
        
        'arraydescuento' => 'required|array',
        'arraydescuento.*' => 'nullable|numeric|min:0', // Permite que el descuento sea un número, incluso si es 0 o vacío
    ];
}

}