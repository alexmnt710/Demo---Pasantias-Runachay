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

            $storagePath = public_path('uploads');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            $filename = time() . '-' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filePath = $storagePath . '/' . $filename;
            $file->move($storagePath, $filename);

            if (!file_exists($filePath)) {
                return Response::json(['error' => 'No se pudo guardar el archivo correctamente.'], 500);
            }

            $response = $this->sendToPythonService($filePath, $userData);

            return Response::json($response);

        } catch (\Exception $e) {
            Log::error("Error en la subida del documento: " . $e->getMessage());
            return Response::json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    private function sendToPythonService($filePath, $userData) {
        $pythonServiceUrl = 'http://python:5000/process';

        $curl = curl_init();

        $postFields = [
            'file' => new \CurlFile($filePath, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', basename($filePath)),
            'user_data' => json_encode($userData)
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => $pythonServiceUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                "Content-Type: multipart/form-data"
            ]
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error("Error al conectar con el servicio Python: " . $error);
            return ['error' => 'No se pudo procesar el documento en el servicio externo.'];
        }

        return json_decode($response, true);
    }
}



