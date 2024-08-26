<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    // Relación con el modelo Caracteristica
    public function caracteristica()
    {
        return $this->belongsTo(Caracteristica::class);
    }

    // Columnas que se pueden asignar en masa
    protected $fillable = ['caracteristica_id'];
}
