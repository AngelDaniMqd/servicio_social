<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/solicitud', function () {
    return view('solicitud');
});

Route::get('/datos-alumno', function () {
    return view('datosalumno');
});

Route::get('/escolaridad', function () {
    return view('escolaridad');
});

Route::get('/programa', function () {
    return view('programa');
});

Route::get('/final', function () {
    return view('final');
});
