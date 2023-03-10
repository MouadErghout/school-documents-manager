<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function Module()
    {
        return $this->hasMany(Module::class);
    }

    public function Eleve()
    {
        return $this->hasMany(Eleve::class);
    }
}
