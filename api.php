<?php
require 'vendor/autoload.php';

use Orhanerday\OpenAi\OpenAi;


ini_set('display_errors', 0);
error_reporting(0);
// Recebe o texto do usuário do frontend

$input = json_decode(file_get_contents('php://input'));

$textoDoUsuario = $input->texto ?? exit;

// Função para enviar uma solicitação HTTP POST
function enviarRequisicaoPOST($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);
    var_dump($response, curl_error($ch));
    return $response;
}

// Chave de API do Chat GPT (substitua pela sua chave)
$chatGPTApiKey = getenv('APIKEY');

$open_ai = new OpenAi($chatGPTApiKey);

$chat = $open_ai->chat([
   'model' => 'gpt-3.5-turbo',
   'messages' => [
       [
           "role" => "system",
           "content" => "Você é um assistente para conversas sobre assuntos gerais"
       ],
       [
           "role" => "user",
           "content" => $textoDoUsuario
       ],
   ],
   'temperature' => 1.0,
   'max_tokens' => 4000,
   'frequency_penalty' => 0,
   'presence_penalty' => 0,
]);


// decode response
$d = json_decode($chat);

echo($d->choices[0]->message->content);