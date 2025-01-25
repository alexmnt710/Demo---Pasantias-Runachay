<?php
require_once __DIR__ . '/../../../vendor/autoload.php'; 

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;

class WordController extends BaseController 
{
    public function upload() {
        try {
            if (!Input::hasFile('template') || !Input::has('user')) {
                return Response::json(['error' => 'Faltan datos. Asegúrate de subir la plantilla y los datos del usuario.'], 400);
            }

            $file = Input::file('template');
            $userData = json_decode(Input::get('user'), true);

            if (!$file->isValid() || $file->getClientOriginalExtension() !== 'docx') {
                return Response::json(['error' => 'Error en la subida del archivo. Asegúrate de que sea un archivo Word válido.'], 400);
            }

            // Crear directorio si no existe
            $storagePath = public_path('uploads');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            // Guardar archivo con un nombre único
            $filename = time() . '-' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filePath = $storagePath . '/' . $filename;
            $file->move($storagePath, $filename);

            if (!file_exists($filePath)) {
                return Response::json(['error' => 'No se pudo guardar el archivo correctamente.'], 500);
            }

            // Reemplazar variables en el documento
            $processedFilePath = $this->replaceVariablesInDocument($filePath, $userData);

            return Response::json([
                'message' => 'Documento generado correctamente.',
                'download_link' => url('uploads/' . basename($processedFilePath))
            ]);

        } catch (\Exception $e) {
            Log::error("Error en la subida del documento: " . $e->getMessage());
            return Response::json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    public function replaceVariablesInDocument($filePath, $userData) {
        try {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filePath);
    
            // Obtener variables detectadas en el documento
            $variables = $templateProcessor->getVariables();
            Log::info('📌 Variables detectadas en la plantilla:', $variables);
    
            // Diccionario de reemplazo
            $replacements = [
                'user.period' => $userData['period'] ?? '',
                'userperiod' => $userData['period'] ?? '',
                'city' => $userData['contract']['city'] ?? '',
                'day' => $userData['contract']['day_today'] ?? '',
                'datetoday' => $userData['contract']['date_today'] ?? '',
                'INSTITUCIONCOOWNERNAME' => $userData['institution']['co_owner_name'] ?? '',
                'CITY' => $userData['contract']['city'] ?? ''
            ];
    
            foreach ($variables as $variable) {
                $normalizedVariable = strtolower(trim($variable));
    
                if (isset($replacements[$normalizedVariable])) {
                    Log::info("🔄 Reemplazando: {$variable} → {$replacements[$normalizedVariable]}");
                    $templateProcessor->setValue($variable, $replacements[$normalizedVariable]);
                } else {
                    Log::warning("⚠️ Variable detectada pero sin reemplazo en PHP: {$variable}");
                }
            }
    
            // ✅ Manejo de tablas
            $this->replaceTableVariables($templateProcessor, $replacements);
    
            // Guardar documento procesado
            $processedFilePath = public_path('uploads/' . time() . '-processed.docx');
            $templateProcessor->saveAs($processedFilePath);
    
            if (!$this->isValidDocx($processedFilePath)) {
                Log::warning("⚠️ Documento potencialmente corrupto, aplicando reemplazo forzado...");
                $this->forceReplaceInDocx($processedFilePath, $replacements);
            }
    
            Log::info("✅ Documento generado correctamente en: {$processedFilePath}");
            return $processedFilePath;
    
        } catch (\Exception $e) {
            Log::error("❌ Error al procesar el documento: " . $e->getMessage());
            return Response::json(['error' => 'Error al procesar el documento: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * ✅ Reemplazo de Variables dentro de Tablas en el DOCX.
     */
    private function replaceTableVariables($filePath, $replacements) {
        try {
            $zip = new \ZipArchive;
    
            if ($zip->open($filePath) === TRUE) {
                $xml = $zip->getFromName('word/document.xml');
    
                if (!$xml) {
                    Log::error("⚠️ No se pudo leer el XML del documento.");
                    return;
                }
    
                // Buscar y reemplazar variables dentro de tablas
                foreach ($replacements as $key => $value) {
                    $pattern = '/\$\{\s*' . preg_quote($key, '/') . '\s*\}/';
                    $xml = preg_replace($pattern, $value, $xml);
                }
    
                // Guardar cambios en el documento
                $zip->deleteName('word/document.xml');
                $zip->addFromString('word/document.xml', $xml);
                $zip->close();
                Log::info("✅ Variables en tablas reemplazadas correctamente.");
    
            } else {
                Log::error("❌ No se pudo abrir el archivo DOCX.");
            }
    
        } catch (\Exception $e) {
            Log::error("⚠️ Error al reemplazar variables en tabla: " . $e->getMessage());
        }
    }
    
    

    /**
     * ✅ Verifica si un archivo .docx es válido usando ZipArchive.
     */
    private function isValidDocx($filePath) {
        $zip = new \ZipArchive;
        if ($zip->open($filePath) === TRUE) {
            $isValid = ($zip->getFromName('word/document.xml') !== false);
            $zip->close();
            return $isValid;
        }
        return false;
    }

    /**
     * ✅ Reemplazo forzado de variables en el XML del DOCX sin dañar la estructura.
     */
    private function forceReplaceInDocx($filePath, $replacements) {
        $zip = new \ZipArchive;
        if ($zip->open($filePath) === TRUE) {
            $xml = $zip->getFromName('word/document.xml');

            // Hacer reemplazo seguro en XML
            foreach ($replacements as $key => $value) {
                $pattern = '/\$\{\s*' . preg_quote($key, '/') . '\s*\}/';
                $xml = preg_replace($pattern, $value, $xml);
            }

            // Actualizar documento sin corromper
            $zip->deleteName('word/document.xml');
            $zip->addFromString('word/document.xml', $xml);
            $zip->close();
        }
    }
}

