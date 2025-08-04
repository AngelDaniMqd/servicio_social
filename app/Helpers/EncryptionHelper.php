<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionHelper
{
    /**
     * Encriptar un valor
     */
    public static function encrypt($value)
    {
        if (is_null($value) || $value === '') {
            return $value;
        }
        
        try {
            return Crypt::encrypt($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Desencriptar un valor
     */
    public static function decrypt($value)
    {
        if (is_null($value) || $value === '') {
            return $value;
        }
        
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            // Si no se puede desencriptar, devolver el valor original
            return $value;
        }
    }

    /**
     * Encriptar un array de datos
     */
    public static function encryptArray($data, $fieldsToEncrypt = [])
    {
        if (empty($fieldsToEncrypt)) {
            // Campos sensibles por defecto
            $fieldsToEncrypt = [
                'nombre', 'apellidop', 'apellidom', 'correo', 
                'telefono', 'curp', 'rfc', 'direccion', 
                'nombre_institucion', 'contacto', 'telefono_institucion'
            ];
        }

        foreach ($data as $key => $value) {
            if (in_array($key, $fieldsToEncrypt) && !is_null($value)) {
                $data[$key] = self::encrypt($value);
            }
        }

        return $data;
    }

    /**
     * Desencriptar un array de datos
     */
    public static function decryptArray($data, $fieldsToDecrypt = [])
    {
        if (empty($fieldsToDecrypt)) {
            // Campos sensibles por defecto
            $fieldsToDecrypt = [
                'nombre', 'apellidop', 'apellidom', 'correo', 
                'telefono', 'curp', 'rfc', 'direccion', 
                'nombre_institucion', 'contacto', 'telefono_institucion'
            ];
        }

        foreach ($data as $key => $value) {
            if (in_array($key, $fieldsToDecrypt) && !is_null($value)) {
                $data[$key] = self::decrypt($value);
            }
        }

        return $data;
    }

    /**
     * Hash para búsquedas (no reversible)
     */
    public static function hashForSearch($value)
    {
        return hash('sha256', strtolower(trim($value)));
    }
}