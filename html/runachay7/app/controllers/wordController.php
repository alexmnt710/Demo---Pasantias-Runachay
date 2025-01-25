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

            // Definir la ruta de almacenamiento dentro de 'public'
            $storagePath = public_path('uploads');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            // Guardar el archivo con un nombre único
            $filename = time() . '-' . $file->getClientOriginalName();
            $filePath = $storagePath . '/' . $filename;
            $file->move($storagePath, $filename);

            // Reemplazar variables en la plantilla
            $processedFilePath = $this->replaceVariablesInDocument($filePath, $userData);

            // Generar URL de descarga
            return Response::json([
                'message' => 'Documento generado correctamente.',
                'download_link' => url('uploads/' . basename($processedFilePath))
            ]);

        } catch (\Exception $e) {
            return Response::json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }
    public function replaceVariablesInDocument($filePath, $userData) {
        try {
            $templateProcessor = new TemplateProcessor($filePath);
    
            // Obtener variables detectadas en el documento
            $variables = $templateProcessor->getVariables();
            Log::info('Variables detectadas en la plantilla:', $variables);
    
            // Diccionario de reemplazo basado en variables encontradas
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
                if (isset($replacements[$variable])) {
                    Log::info("Reemplazando: {$variable} → {$replacements[$variable]}");
                    $templateProcessor->setValue($variable, $replacements[$variable]);
                } else {
                    Log::warning("Variable detectada en Word pero no encontrada en PHP: {$variable}");
                }
            }
    
            // Guardar temporalmente el documento
            $processedFilePath = public_path('uploads/' . time() . '-processed.docx');
            $templateProcessor->saveAs($processedFilePath);
    
            // Verificar si el archivo se generó correctamente
            if (!$this->isValidDocx($processedFilePath)) {
                Log::warning("Documento potencialmente corrupto, intentando reemplazo forzado.");
                $this->forceReplaceInDocx($processedFilePath, $replacements);
            }
    
            Log::info("Documento generado correctamente en: {$processedFilePath}");
            return $processedFilePath;
    
        } catch (\Exception $e) {
            Log::error("Error al generar documento: " . $e->getMessage());
            return Response::json(['error' => 'Error al generar documento: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Verifica si un archivo .docx es válido usando ZipArchive.
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
     * Reemplazo forzado de variables en el XML del DOCX.
     */
    private function forceReplaceInDocx($filePath, $replacements) {
        $zip = new \ZipArchive;
        if ($zip->open($filePath) === TRUE) {
            $xml = $zip->getFromName('word/document.xml');
            foreach ($replacements as $key => $value) {
                $pattern = '/\$\{\s*' . preg_quote($key, '/') . '\s*\}/';
                $xml = preg_replace($pattern, $value, $xml);
            }
            $zip->deleteName('word/document.xml');
            $zip->addFromString('word/document.xml', $xml);
            $zip->close();
        }
    }
    
}

