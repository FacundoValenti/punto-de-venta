<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Documento extends Model
{
    use HasFactory;

    public function persona(){
        return $this->hasMany(Persona::class);
    }

}

