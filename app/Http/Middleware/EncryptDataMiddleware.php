<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\EncryptionHelper;

class EncryptDataMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Encriptar datos en requests POST/PUT
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $data = $request->all();
            
            // Campos a encriptar
            $fieldsToEncrypt = [
                'nombre', 'apellidop', 'apellidom', 'correo', 
                'telefono', 'curp', 'rfc', 'direccion',
                'nombre_institucion', 'contacto', 'telefono_institucion'
            ];
            
            $encryptedData = EncryptionHelper::encryptArray($data, $fieldsToEncrypt);
            $request->merge($encryptedData);
        }

        return $next($request);
    }
}