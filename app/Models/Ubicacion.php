<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    public $timestamps = false;

    protected $fillable = [
        'alumno_id',
        'localidad',
        'cp',
        'municipios_id',
    ];

    // Relación con alumno
    public function alumno()
    {
        return $this->belongsTo(\App\Models\Alumno::class, 'alumno_id');
    }

    // Relación con municipio
    public function municipio()
    {
        return $this->belongsTo(\App\Models\Municipio::class, 'municipios_id');
    }
}