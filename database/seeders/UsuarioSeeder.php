<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuario')->insert([
            'nombre'     => 'Angel',
            'apellidoP'  => 'Martinez',
            'apellidoM'  => 'Maqueda',
            'correo'     => 'angel@gmail.com',
            'password'   => Hash::make('Maqueda820'),
            'telefono'   => 4461362961,
            'rol_id'     => 2,
        ]);
    }
}