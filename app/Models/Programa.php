<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';

    protected $fillable = [
        'alumno_id',
        'dependencia',
        'otra_institucion',
        'programa',
        'encargado',
        'titulo_encargado',
        'otro_titulo',
        'puesto_encargado',
        'telefono_institucion',
        'metodo',
        'inicio',
        'fin',
        'tipo_programa',
        'tipo_otro',
    ];
}
