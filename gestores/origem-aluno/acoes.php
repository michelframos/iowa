<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Origem_Aluno::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Origem do Aluno', 'i');

    $registro = new Origem_Aluno();
    $registro->origem = 'Nova Origem do Aluno';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Origem do Aluno', 'Inclusão', 'Uma nova Origem do Aluno foi cadastrada.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Origem do Aluno', 'a');

    if($registro->origem != $dados['origem']):
        /*Verificando duplicidade*/
        if(Origem_Aluno::find_by_origem($dados['origem'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->origem = $dados['origem'];
    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Origem do Aluno', 'Alteração', 'A Origem do Aluno '.$registro->origem.' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Origem do Aluno', 'e');

    /*
    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Origem do Aluno não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;
    */

    if(Alunos::find_by_id_origem($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Origem do Aluno não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Origem do Aluno', 'Exclusão', 'A Origem do Aluno '.$registro->origem.' foi excluída.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Origem do Aluno', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Origem do Aluno', 'Inativação', 'A Origem do Aluno '.$registro->origem.' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Origem do Aluno', 'Ativação', 'A Origem do Aluno '.$registro->origem.' foi ativada.');
    endif;

endif;
