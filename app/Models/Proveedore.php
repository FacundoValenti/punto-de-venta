<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
    use HasFactory;

    public function persona(){
        return $this->belongsTo(persona::class);
    }

    public function compras(){
        return $this->hasMany(compra::class);
    }

    protected $fillable = ['persona_id'];
}
