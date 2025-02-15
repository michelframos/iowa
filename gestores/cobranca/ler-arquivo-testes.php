<?php
use Sicoob\Retorno\CNAB240\Arquivo;
use Sicoob\Retorno\CNAB240\Boleto;
include_once('../../classes/Sicoob/Retorno/CNAB240/LineAbstract.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/Boleto.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/Arquivo.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/Header.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/HeaderLote.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/Trailer.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/TrailerLote.php');

include_once('../../classes/Sicoob/Retorno/CNAB240/SegmentT.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/SegmentU.php');

include_once('../../classes/Sicoob/Retorno/Fields/Field.php');
include_once('../../classes/Sicoob/Helpers/Helper.php');

include_once('../../config.php');
include_once('../funcoes_painel.php');

//$caixa = Caixas::find(array('conditions' => array('situacao = ? and id_colega = ?', 'aberto', idUsuario()), 'order' => 'data_abertura desc', 'limit' => 1));

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

/*Array com numeros dos numero dos boletos e ids das parcelas*/
$umeros_boletos = '';
$ids_parcelas = '';
/*--------*/

$boletos = [];
$boletos_recebidos = [];
$num_boletos_recebidos = 0;
$num_boletos_n_recebidos = 0;


$arquivoObj = new \Sicoob\Retorno\CNAB240\Arquivo('retorno/'.$arquivo);

foreach($arquivoObj->boletos as $boleto):

    //echo (string)$boleto->segmentT->fields['numero_documento']->value.'<br>';

    $boleto->codigoMov = $boleto->segmentT->fields['codigo_movimento_retorno']->value;
    $boleto->numeroInscricao = $boleto->segmentT->fields['numero_inscricao']->value;
    $boleto->vencimento = $boleto->segmentT->fields['data_vencimento']->value;
    $numero_documento = $boleto->segmentT->fields['numero_documento']->value;

    //Busca o boleto registrado no banco de dados e dar baixa

    //echo $boleto->segmentT->fields['codigo_movimento_retorno']->value;

    //if((int)$boleto->segmentT->fields['codigo_movimento_retorno']->value == 1 || (int)$boleto->segmentT->fields['codigo_movimento_retorno']->value == 2):
    if(((int)$boleto->segmentT->fields['codigo_movimento_retorno']->value == 06) || ((int)$boleto->segmentT->fields['codigo_movimento_retorno']->value == 9)):

        $data_pagamento = implode('-', array_reverse(explode('/', (string)$boleto->segmentU->fields['data_evento']->value)));
        $data_credito = implode('-', array_reverse(explode('/', (string)$boleto->segmentU->fields['data_credito']->value)));
        $valor_pago = $boleto->segmentU->fields['valor_pago']->value;
        echo $valor_pago.'<br>';

    else:

        $num_boletos_n_recebidos = $num_boletos_n_recebidos+1;

    endif;

endforeach;
