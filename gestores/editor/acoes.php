<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Textos::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Editor de Documentos', 'i');

    $registro = new Textos();
    $registro->titulo = 'Novo Texto';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Editor de Documentos', 'Inclusão', 'Um novo documento foi incluído');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Editor de Documentos', 'a');

    if($registro->titulo != $dados['titulo']):
        /*Verificando duplicidade*/
        if(Textos::find_by_titulo($dados['titulo'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->titulo = $dados['titulo'];
    $registro->texto = $dados['texto'];
    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Editor de Documentos', 'Alteração', 'O documento '.$dados['titulo']. ' foi alterado');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Editor de Documentos', 'e');

    /*
    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este idioma não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;
    */
    adicionaHistorico(idUsuario(), idColega(), 'Editor de Documentos', 'Exclusão', 'O documento '.$registro->titulo. ' foi excluído.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    /*verificaPermissaoPost(idUsuario(), 'Editor de Documentos', 'ai');*/

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Editor de Documentos', 'Inativação', 'O documento '.$registro->titulo. ' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Editor de Documentos', 'Ativação', 'O documento '.$registro->titulo. ' foi ativado.');
    endif;

endif;
