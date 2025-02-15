<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$codigo_pais = filter_input(INPUT_POST, 'codigo_pais', FILTER_SANITIZE_STRING);
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
$mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
$id_promocao = filter_input(INPUT_POST, 'id_promocao', FILTER_SANITIZE_NUMBER_INT);
//$arquivo = $_FILES['arquivo']['name'];

$apiURL = 'http://eu91.chat-api.com/instance87130/';
$tokenApi = 'n599nsn91juqeyfk';

/*
if(!empty($_FILES['arquivo']['name'])):
    $arquivo = $_FILES['arquivo']['name'];
    $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
    $destino = 'arquivos/'.$arquivo;
    move_uploaded_file($arquivo_tmp, $destino);
endif;
*/

$promocao = Promocoes::find($id_promocao);
$alunos = explode('|', filter_input(INPUT_POST, 'alunos', FILTER_SANITIZE_STRING));

$status_envio = true;
if(!empty($alunos)):
    foreach (array_filter($alunos) as $id_aluno):

        /*Verificando se existe numero de cupons*/

        $numero_envios = $promocao->numero_envios;
        if($promocao->numero_cupons > 0 && !empty($promocao->numero_cupons)):
            if(($promocao->numero_cupons == $numero_envios) || $numero_envios > $promocao->numero_cupons):
                echo json_encode(['status' => 'erro-cupons']);
                exit();
            else:
                $numero_envios = $promocao->numero_envios;
                $promocao->numero_envios = $numero_envios+1;
                $promocao->save();
            endif;
        else:
            $numero_envios = $promocao->numero_envios;
            $promocao->numero_envios = $numero_envios+1;
            $promocao->save();
        endif;

        if($status_envio == true):

            $aluno = Alunos::find($id_aluno);

            $codigo = md5(date('Y-m-d H:i:s').$aluno->id.$promocao->id);
            $link = HOME.'/promocao/'.$codigo;

            $envio_promocao = new EnviosPromocoes();
            $envio_promocao->id_promocao = $promocao->id;
            $envio_promocao->id_aluno = $aluno->id;
            $envio_promocao->id_unidade = $aluno->id_unidade;
            $envio_promocao->mensagem = $mensagem.' '.$link;
            $envio_promocao->data = date('Y-m-d');
            $envio_promocao->codigo = $codigo;
            $envio_promocao->utilizado = 'n';
            dadosCriacao($envio_promocao);
            $envio_promocao->save();

            !empty($aluno->celular && !empty($mensagem)) ? EnviaMensagem($codigo_pais.$aluno->celular, $mensagem.' '.$link, $apiURL, $tokenApi) : '';
            //!empty($aluno->celular && !empty($mensagem)) ? EnviaLink($codigo_pais.$aluno->celular, $link, $apiURL, $tokenApi) : '';

            /*enviando arquivo se houve*/
            //!empty($aluno->celular) && !empty($arquivo) ? EnviaArquivo($codigo_pais.$aluno->celular, $arquivo, $apiURL, $tokenApi) : '';

        endif;

    endforeach;

    echo json_encode(['status' => 'ok']);

endif;




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

function EnviaLink($celular, $link, $apiURL, $token_api)
{

    $data = [
        'phone' => $celular.'@c.us', // Receivers phone
        'body' => $link, // Message
        'title ' => 'Confira agora mesmo!'
    ];
    $json = json_encode($data); // Encode data to JSON
    // URL for request POST /message
    $url = $apiURL.'sendLink?token='.$token_api;
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
