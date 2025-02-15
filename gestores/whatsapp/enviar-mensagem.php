<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$codigo_pais = filter_input(INPUT_POST, 'codigo_pais', FILTER_SANITIZE_STRING);
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
$mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
$arquivo = $_FILES['arquivo']['name'];

$apiURL = 'http://eu91.chat-api.com/instance87130/';
$tokenApi = 'n599nsn91juqeyfk';

if(!empty($_FILES['arquivo']['name'])):
    $arquivo = $_FILES['arquivo']['name'];
    $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
    $destino = 'arquivos/'.$arquivo;
    move_uploaded_file($arquivo_tmp, $destino);
endif;

$alunos = explode('|', filter_input(INPUT_POST, 'alunos', FILTER_SANITIZE_STRING));
if(!empty($alunos)):
    foreach (array_filter($alunos) as $id_aluno):
        $aluno = Alunos::find($id_aluno);

        if($tipo == ''):

            /*enviando mensagem de texto*/
            !empty($aluno->celular && !empty($mensagem)) ? EnviaMensagem($codigo_pais.$aluno->celular, $mensagem, $apiURL, $tokenApi) : '';
            !empty($aluno->celular_responsavel && !empty($mensagem)) ? EnviaMensagem('55'.$aluno->celular_responsavel, $mensagem, $apiURL, $tokenApi) : '';

            /*enviando arquivo se houve*/
            !empty($aluno->celular) && !empty($arquivo) ? EnviaArquivo($codigo_pais.$aluno->celular, $arquivo, $apiURL, $tokenApi) : '';
            !empty($aluno->celular_responsavel) && !empty($arquivo) ? EnviaArquivo('55'.$aluno->celular_responsavel, $arquivo, $apiURL, $tokenApi) : '';

            $historico->id_aluno = 'Aluno e Responsável';
            $historico->numero = 'Aluno e Responsável';

        endif;

        if($tipo == 'aluno'):
            !empty($aluno->celular && !empty($mensagem)) ? EnviaMensagem($codigo_pais.$aluno->celular, $mensagem, $apiURL, $tokenApi) : '';

            /*enviando arquivo se houve*/
            !empty($aluno->celular) && !empty($arquivo) ? EnviaArquivo($codigo_pais.$aluno->celular, $arquivo, $apiURL, $tokenApi) : '';
        endif;

        if($tipo == 'responsavel'):
            !empty($aluno->celular_responsavel && !empty($mensagem)) ? EnviaMensagem($codigo_pais.$aluno->celular_responsavel, $mensagem, $apiURL, $tokenApi) : '';

            /*enviando arquivo se houve*/
            !empty($aluno->celular_responsavel) && !empty($arquivo) ? EnviaArquivo($codigo_pais.$aluno->celular_responsavel, $arquivo, $apiURL, $tokenApi) : '';
        endif;

    endforeach;
endif;

echo json_encode(['status' => 'ok']);




function EnviaMensagem($celular, $mensagem, $apiURL, $token_api)
{

    $data = [
        'phone' => $celular.'@c.us', // Receivers phone
        'body' => $mensagem, // Message
    ];
    $json = json_encode($data); // Encode data to JSON
    // URL for request POST /message
    $url = $apiURL.'sendMessage?token='.$token_api;
    // Make a POST request
    $options = stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/json',
        'content' => $json
    ]
    ]);

    // Send a request
    $result = file_get_contents($url, false, $options);

}


function EnviaArquivo($celular, $arquivo, $apiURL, $token_api)
{

    $data = [
        'phone'     => $celular, // Receivers phone
        //'body'      => base64_encode(file_get_contents(HOME.'/gestores/whatsapp/arquivos/'.$arquivo)), // Message
        'body'      => HOME.'/gestores/whatsapp/arquivos/'.$arquivo, // Message
        'filename'  => $arquivo,
        'caption'   => $arquivo
    ];

    $json = json_encode($data); // Encode data to JSON

    $url = $apiURL.'sendFile?token='.$token_api;

    $options = stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/json',
        'content' => $json
    ]
    ]);

    $response = file_get_contents($url,false,$options);

}
