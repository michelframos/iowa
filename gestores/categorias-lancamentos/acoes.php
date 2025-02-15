<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Categorias_Lancamentos::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Categorias de Lançamentos', 'i');

    $registro = new Categorias_Lancamentos();
    $registro->categoria = 'Nova Categoria de Lançamento';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Categorias de Lançamentos', 'Inclusão', 'Uma nova categoria de lançamentos foi criada.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Categorias de Lançamentos', 'a');

    if($registro->categoria != $dados['categoria']):
        /*Verificando duplicidade*/
        if(Categorias_Lancamentos::find_by_categoria($dados['categoria'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/

    adicionaHistorico(idUsuario(), idColega(), 'Categorias de Lançamentos', 'Alteração', 'A categoria de lançamentos '.$registro->categoria.' foi alterada para '.$dados['categoria'].'.');

    $registro->categoria = $dados['categoria'];
    dadosAlteracao($registro);
    $registro->save();

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Categorias de Lançamentos', 'e');

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Categoria de Lançamento não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Categorias de Lançamentos', 'Exclusão', 'A categoria de lançamentos '.$registro->categoria.' foi excluída.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Categorias de Lançamentos', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Categorias de Lançamentos', 'Inativação', 'A categoria de lançamentos '.$registro->categoria.' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Categorias de Lançamentos', 'Ativação', 'A categoria de lançamentos '.$registro->categoria.' foi ativada.');
    endif;

endif;
