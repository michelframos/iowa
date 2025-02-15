<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Fornecedores::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Fornecedores', 'i');

    $registro = new Fornecedores();
    $registro->fornecedor = 'Novo Fornecedor';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Fornecedores', 'Inclusão', 'Um novo fornecedor foi incluído.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Fornecedores', 'a');

    if($registro->fornecedor != $dados['fornecedor']):
        /*Verificando duplicidade*/
        if(Fornecedores::find_by_fornecedor($dados['fornecedor'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->fornecedor = $dados['fornecedor'];
    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Fornecedores', 'Alteração', 'O fornecedor '.$dados['fornecedor']. ' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Fornecedores', 'e');

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este Fornecedor não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Fornecedores', 'Exclusão', 'O fornecedor '.$registro->fornecedor. ' foi excluído.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Fornecedores', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Fornecedores', 'Inativação', 'O fornecedor '.$registro->fornecedor. ' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Fornecedores', 'Ativação', 'O fornecedor '.$registro->fornecedor. ' foi ativado.');
    endif;

endif;
