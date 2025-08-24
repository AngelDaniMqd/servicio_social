<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class FormatosController extends Controller
{
    public function index()
    {
        // Obtener el primer formato (solo debe haber uno)
        $formato = DB::table('formatos')->first();
        
        return view('formatos-upload', compact('formato'));
    }

    public function store(Request $request)
    {
        // Validar archivos
        $rules = [
            'formato_word' => 'nullable|mimes:doc,docx|max:5120',
            'formato_reporte' => 'nullable|mimes:doc,docx|max:5120',
            'formato_reporte_final' => 'nullable|mimes:doc,docx|max:5120',
        ];

        // Verificar si existe un formato
        $formatoExistente = DB::table('formatos')->first();
        
        if (!$formatoExistente) {
            // Si no existe, formato_word es obligatorio
            $rules['formato_word'] = 'required|mimes:doc,docx|max:5120';
        }

        $request->validate($rules);

        try {
            // Obtener el ID del usuario actual (puedes cambiarlo por el método que uses para autenticación)
            $usuarioId = 1; // Por ahora usamos 1, pero deberías usar Auth::id() cuando tengas autenticación

            if ($formatoExistente) {
                // ACTUALIZAR formato existente
                return $this->actualizarFormato($request, $formatoExistente, $usuarioId);
            } else {
                // CREAR nuevo formato
                return $this->crearFormato($request, $usuarioId);
            }

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al procesar los formatos: ' . $e->getMessage())
                           ->withInput();
        }
    }

    private function crearFormato(Request $request, $usuarioId)
    {
        $data = [
            'usuario_id' => $usuarioId,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Procesar archivos
        if ($request->hasFile('formato_word')) {
            $data['formato_word'] = file_get_contents($request->file('formato_word')->getRealPath());
        }

        if ($request->hasFile('formato_reporte')) {
            $data['formato_reporte'] = file_get_contents($request->file('formato_reporte')->getRealPath());
        }

        if ($request->hasFile('formato_reporte_final')) {
            $data['formato_reporte_final'] = file_get_contents($request->file('formato_reporte_final')->getRealPath());
        }

        // Insertar en la base de datos
        DB::table('formatos')->insert($data);

        return redirect()->route('dashboard', ['table' => 'formatos'])
                       ->with('success', 'Formatos creados exitosamente');
    }

    private function actualizarFormato(Request $request, $formatoExistente, $usuarioId)
    {
        // Verificar que al menos un archivo sea subido
        if (!$request->hasFile('formato_word') && 
            !$request->hasFile('formato_reporte') && 
            !$request->hasFile('formato_reporte_final')) {
            return redirect()->back()->with('error', 'Debe subir al menos un archivo para actualizar.');
        }

        $data = [
            'usuario_id' => $usuarioId, // Actualizar el usuario que modificó
            'updated_at' => now()
        ];

        // Actualizar solo los archivos que se suban
        if ($request->hasFile('formato_word')) {
            $data['formato_word'] = file_get_contents($request->file('formato_word')->getRealPath());
        }

        if ($request->hasFile('formato_reporte')) {
            $data['formato_reporte'] = file_get_contents($request->file('formato_reporte')->getRealPath());
        }

        if ($request->hasFile('formato_reporte_final')) {
            $data['formato_reporte_final'] = file_get_contents($request->file('formato_reporte_final')->getRealPath());
        }

        // Actualizar en la base de datos
        DB::table('formatos')->where('id', $formatoExistente->id)->update($data);

        return redirect()->route('dashboard', ['table' => 'formatos'])
                       ->with('success', 'Formatos actualizados exitosamente');
    }

    public function descargarFormato(int $id)
    {
        try {
            // Ejemplo: pon tu ruta real del .docx (asegúrate que el archivo esté en el repo)
            $templatePath = resource_path('plantillas/cartas/formato.docx'); // usa /, no \

            if (!file_exists($templatePath)) {
                Log::error('Template no encontrado', ['path' => $templatePath]);
                abort(404, 'Template no encontrado');
            }

            // Genera el documento en memoria (sin escribir a disco)
            $template = new TemplateProcessor($templatePath);
            // ... rellena variables ...
            // $template->setValue('alumno', $alumno->nombre);

            $tmpFile = tempnam(sys_get_temp_dir(), 'docx_');
            $template->saveAs($tmpFile);

            return response()->download($tmpFile, 'carta.docx')->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            Log::error('Error generando Word', [
                'msg' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500, 'Error al generar el documento');
        }
    }
}
