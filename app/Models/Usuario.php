<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    public $timestamps = false; // Activa timestamps si tu tabla los incluye

    protected $fillable = [
        'nombre',
        'apellidoP',
        'apellidoM',
        'correo',
        'password',
        'telefono',
        'rol_id',
    ];
}