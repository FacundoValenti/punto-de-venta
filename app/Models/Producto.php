<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public function compras(){
        return $this->belongsToMany(Compra::class)->withTimestamps()->wherePivot('cantidad', 'precio_compra', 'precio_venta');
    }

    public function ventas(){
        return $this->belongsToMany(Venta::class)->withTimestamps()->wherePivot('cantidad', 'precio_venta', 'descuento');
    }

    public function categorias(){
        return $this->belongsToMany(categoria::class)->withTimestamps();
    }
    public function marca(){
        return $this->belongsTo(marca::class);
    }

    public function presentacione (){
        return $this->belongsTo(Presentacione::class);
    }
}
