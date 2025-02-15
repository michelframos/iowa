<?php
//header("Access-Control-Allow-Origin: *");
include_once('../gestores/funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}

if($dados['acao'] == 'busca-cep'):

    $cep = str_replace('.', '', $dados['cep']);
    $cep = str_replace('-', '', $cep);

    function webClient ($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    $url = sprintf('http://viacep.com.br/ws/%s/json', $cep);
    $result = json_decode(webClient($url));

    echo json_encode(array(
        'status' => 'ok',
        'cidade' => tirarAcentos($result->localidade),
        'uf' => $result->uf,
        'endereco' => $result->logradouro,
        'bairro' => $result->bairro,
        'complemento' => $result->complemento
    ));

endif;
