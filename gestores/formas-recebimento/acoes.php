<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Formas_Pagamento::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Formas de Recebimento/Pagamento', 'i');

    $registro = new Formas_Pagamento();
    $registro->forma_pagamento = 'Nova Forma de Recebimento/Pagamento';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Formas de Recebimento/Pagamento', 'Inclusão', 'Uma nova forma de recebimento/pagamento foi incluída.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Formas de Recebimento/Pagamento', 'a');

    if($registro->forma_pagamento != $dados['forma_pagamento']):
        /*Verificando duplicidade*/
        if(Formas_Pagamento::find_by_forma_pagamento($dados['forma_pagamento'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->forma_pagamento = $dados['forma_pagamento'];

    $taxa = str_replace('.', '', $dados['taxa']);
    $taxa = str_replace(',', '.', $taxa);
    $registro->taxa = $taxa;

    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Formas de Recebimento/Pagamento', 'Alteração', 'A forma de recebimento/pagamento '.$dados['forma_pagamento'].' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Formas de Recebimento/Pagamento', 'e');

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Forma de Recebimento/Pagamento não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Formas de Recebimento/Pagamento', 'Exclusão', 'A forma de recebimento/pagamento '.$registro['forma_pagamento'].' foi excluída.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Formas de Recebimento/Pagamento', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Formas de Recebimento/Pagamento', 'Inativação', 'A forma de recebimento/pagamento '.$registro['forma_pagamento'].' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Formas de Recebimento/Pagamento', 'Ativação', 'A forma de recebimento/pagamento '.$registro['forma_pagamento'].' foi ativada.');
    endif;

endif;
