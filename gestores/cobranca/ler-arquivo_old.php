<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

require_once("../../classes/retorno/RetornoBanco.php");
require_once("../../classes/retorno/RetornoFactory.php");


$arquivo = $_FILES['retorno']['name'];
$arquivo_tmp = $_FILES['retorno']['tmp_name'];
move_uploaded_file($arquivo_tmp, 'retorno/'.$arquivo);

function linhaProcessada($self, $numLn, $vlinha) {
    if($vlinha) {
        if($vlinha["registro"] == $self::HEADER_ARQUIVO)
            echo "<b>".$vlinha['nome_empresa']."</b><br />";
        //O registro detalhe U são dados adicionais do registro de pagamento
        //e não necessariamente precisa ser usado.
        //Pode ser que o arquivo de retorno não tenha o registro detalhe separado em
        //duas linhas (T e U). Assim, nestes casos, pode-se fazer apenas um
        //if($vlinha["registro"] == $self::DETALHE)
        else if($vlinha["registro"] == $self::DETALHE && $vlinha["segmento"] == "T") {
            echo get_class($self) . ": Nosso N&uacute;mero: <b>".$vlinha['nosso_numero']."</b> -
		  Venc: <b>".$vlinha['vencimento']."</b>".
                " Vlr Titulo: <b>R\$ ".number_format($vlinha['valor'], 2, ',', '')."</b> - ".
                " Vlr Tarifa: <b>R\$ ".number_format($vlinha['valor_tarifa'], 2, ',', '')."</b><br/>";
        }
    } else echo "Tipo da linha n&atilde;o identificado<br/>\n";
}

function linhaProcessada1($self, $numLn, $vlinha) {
    printf("%08d - ", $numLn);
    if($vlinha) {

        foreach($vlinha as $nome_indice => $valor):
            $valor_recebido = 0;
            echo "$nome_indice: <b>$valor</b><br/>\n ";

            //salvar no banco aqui;
            if($nome_indice == 'valor_pagamento'):
                $valor_recebido = $valor;
            endif;

            if($nome_indice == 'referencia_sacado'):
                try{
                    $parcela = Parcelas::find($valor);
                    $parcela->pago = 's';
                    $parcela->save();
                } catch(\ActiveRecord\RecordNotFound $e){

                }

            endif;

        endforeach;

    } else echo "Tipo da linha n&atilde;o identificado<br/>\n";
    echo "<br/>\n";

}


//Use uma das duas instrucões abaixo (comente uma e descomente a outra)
//$cnab240 = RetornoFactory::getRetorno($fileName, "linhaProcessada");
$cnab240 = RetornoFactory::getRetorno('retorno/'.$arquivo, "linhaProcessada1");

$retorno = new RetornoBanco($cnab240);

$retorno->processar();