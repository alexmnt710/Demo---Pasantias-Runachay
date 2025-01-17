<?php

use Illuminate\Support\Facades\View;
class BotWhatsappController extends BaseController {

    private $apiUrl = "https://7105.api.greenapi.com";
    private $apiToken = "0db43261f91842ce93ba41da208e5244427d70eb590b4642ae";
    private $idInstance = "7105176459";

    public function getIndex()
    {
        return View::make('bot-whatsapp');
    }

    public function sendMensage()
    {
        // Reemplaza con un número válido en formato internacional
        $telefono = Input::get('telefono');
        $mensaje = "Hello from Runachay!";

        $status = $this->send_whatsapp_image($telefono, $mensaje);
        
        return Redirect::to('/demo-whs')->with('success', 'Mensaje enviado correctamente.'.$telefono);
    }

    public function sendMensageImage()
    {
        $telefono = Input::get('telefono');
        $imageUrl = "https://play-lh.googleusercontent.com/KY14QQixa96W5rFbQzZo9laeWHq9hZmIcPZ0EPECGMhDmyY6AjcSCt5Guv6xJQqQ-bk";
        $caption = "Aquí tienes una imagen de Runachay!";

        $status = $this->send_whatsapp_image($telefono, $imageUrl, $caption);

        return Redirect::to('/demo-whs')->with('success', 'Mensaje enviado correctamente.'.$telefono);
    }



    private function send_whatsapp($telefono, $mensaje)
    {
        $url = "{$this->apiUrl}/waInstance{$this->idInstance}/sendMessage/{$this->apiToken}";

        $data = [
            "chatId" => "{$telefono}@c.us", // Formato internacional: número@c.us
            "message" => $mensaje
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function send_whatsapp_image($telefono, $imageUrl, $caption = "Mira esta imagen!")
    {
        $url = "https://7105.api.greenapi.com/waInstance7105176459/sendFileByUrl/0db43261f91842ce93ba41da208e5244427d70eb590b4642ae";
    
        $data = [
            "chatId" => "{$telefono}@c.us",  // Número de WhatsApp en formato internacional
            "urlFile" => $imageUrl,          // URL pública de la imagen
            "fileName" => "imagen.jpg",      // Nombre del archivo (opcional)
            "caption" => $caption            // Texto que acompañará la imagen
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return json_decode($response, true);
    }
    

}
