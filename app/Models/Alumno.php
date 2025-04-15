<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';

    protected $fillable = [
        'email_institucional',
        'apellido_paterno',
        'apellido_materno',
        'nombre',
        'edad',
        'sexo',
        'telefono',
        'calle',
        'cp',
        'colonia',
        'localidad',
        'municipio',
        'ciudad',
        'estado'
    ];
}
