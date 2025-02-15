<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

//$caixa = Caixas::find(array('conditions' => array('situacao = ? and id_colega = ?', 'aberto', idUsuario()), 'order' => 'data_abertura desc', 'limit' => 1));

$banco = filter_input(INPUT_POST, 'codigo_banco', FILTER_SANITIZE_STRING);
$caixa = Caixas::find(filter_input(INPUT_POST, 'caixa', FILTER_VALIDATE_INT));

if(empty($caixa)):

    echo json_encode(array('status' => 'erro-caixa'));
    exit();

endif;

if(!file_exists('retorno')):
    mkdir('retorno', 0777, true);
endif;

$arquivo = $_FILES['retorno']['name'];
$arquivo_tmp = $_FILES['retorno']['tmp_name'];
move_uploaded_file($arquivo_tmp, 'retorno/'.$arquivo);

switch ($banco):
    case '756':
        \IowaPainel\RetornoBancoController::retornoSicoob($caixa, $arquivo);
        break;
    case '001':
        \IowaPainel\RetornoBancoController::retornoBancoBrasil($caixa, $arquivo);
        break;
endswitch;

echo json_encode(array('status' => 'ok'));

//return $boletos;
//var_dump($boletos);
