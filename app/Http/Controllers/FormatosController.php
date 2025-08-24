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

    public function descargarFormato(Request $request, int $id)
    {
        try {
            $tipo = $request->query('tipo', 'word'); // default si no viene ?tipo=
            if (!in_array($tipo, ['word', 'docx'])) {
                Log::warning('Tipo inválido', ['tipo' => $tipo, 'id' => $id]);
                return abort(422, 'Tipo de formato inválido');
            }

            // IMPORTANTE: usa / (no \) y respeta mayúsculas/minúsculas reales del archivo
            $templatePath = resource_path('plantillas/cartas/formato.docx');
            Log::info('Generando formato', ['id' => $id, 'tipo' => $tipo, 'template' => $templatePath]);

            if (!file_exists($templatePath)) {
                Log::error('Template no encontrado', ['path' => $templatePath]);
                return abort(404, 'Template no encontrado');
            }

            if (!class_exists(TemplateProcessor::class)) {
                Log::error('phpoffice/phpword no está instalado');
                return abort(500, 'Dependencia faltante: phpoffice/phpword');
            }

            // Carga de datos (ajusta a tu modelo)
            // $alumno = Alumno::findOrFail($id);

            $tpl = new TemplateProcessor($templatePath);
            // $tpl->setValue('alumno', $alumno->nombre); // ... rellena tus variables

            $tmp = tempnam(sys_get_temp_dir(), 'docx_');
            $tpl->saveAs($tmp);

            $filename = "carta_{$id}.docx";
            return response()->download($tmp, $filename)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            Log::error('Error generando formato', [
                'id' => $id,
                'msg' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return abort(500, 'Error al generar el documento');
        }
    }
}
