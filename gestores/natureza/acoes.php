<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Natureza_Conta::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Natureza de Contas a Pagar', 'i');

    $registro = new Natureza_Conta();
    $registro->natureza = 'Nova Natureza de Conta a Pagar';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Natureza de Contas a Pagar', 'Inclusão', 'Uma nova Natureza de Contas a Pagar foi cadastrada.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Natureza de Contas a Pagar', 'a');

    if($registro->natureza != $dados['natureza']):
        /*Verificando duplicidade*/
        if(Natureza_Conta::find_by_natureza($dados['natureza'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->natureza = $dados['natureza'];
    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Natureza de Contas a Pagar', 'Alteração', 'A Natureza de Contas a Pagar '.$registro->natureza.' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Natureza de Contas a Pagar', 'e');

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Natureza de Conta a Pagar não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Natureza de Contas a Pagar', 'Exclusão', 'A Natureza de Contas a Pagar '.$registro->natureza.' foi excluída.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Natureza de Contas a Pagar', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Natureza de Contas a Pagar', 'Inativação', 'A Natureza de Contas a Pagar '.$registro->natureza.' foi inativação.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Natureza de Contas a Pagar', 'Ativação', 'A Natureza de Contas a Pagar '.$registro->natureza.' foi ativação.');
    endif;

endif;
