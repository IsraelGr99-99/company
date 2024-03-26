<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //Guardamos los campos que serán llenados
    protected $fillable = ['name'];
    use HasFactory;
    
}
