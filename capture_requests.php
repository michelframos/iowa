<?php
// Captura os dados da requisição
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$query = $_SERVER['QUERY_STRING'];
$body = file_get_contents('php://input');
$headers = getallheaders();

// Registra em um arquivo de log
$log = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $method,
    'uri' => $uri,
    'query' => $query,
    'headers' => json_encode($headers),
    'body' => json_encode($body),
    'usuario' => json_encode($_SESSION['usuario']),
];

//file_put_contents('requisicoes.log', capture_requests . phpjson_encode($log) . PHP_EOL, FILE_APPEND);

LogsModel::novo($log);

// Se precisar continuar o processamento normal para arquivos existentes
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $uri) && !is_dir($_SERVER['DOCUMENT_ROOT'] . $uri)) {
    return false; // Serve o arquivo normalmente
}