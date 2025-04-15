<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escolaridad extends Model
{
    protected $table = 'escolaridades';

    protected $fillable = [
        'alumno_id',
        'modalidad',
        'carrera',
        'carrera_otro',
        'semestre_actual',
        'grupo',
        'matricula'
    ];
}
