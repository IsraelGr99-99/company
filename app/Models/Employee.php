<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    //Guardamos los campos que serán llenados
    protected $fillable = ['name','email','phone','department_id'];
}
