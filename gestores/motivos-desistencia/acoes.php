<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Motivos_Desistencia::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Motivos de Desistência', 'i');

    $registro = new Motivos_Desistencia();
    $registro->motivo = 'Novo Motivo de Desistência';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Motivos de Desistência', 'Inclusão', 'Um novo Motivo de Desistência foi cadastrado.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Motivos de Desistência', 'a');

    if($registro->motivo != $dados['motivo']):
        /*Verificando duplicidade*/
        if(Motivos_Desistencia::find_by_motivo($dados['motivo'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->motivo = $dados['motivo'];
    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Motivos de Desistência', 'Alteração', 'O Motivo de Desistência '.$registro->motivo.' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Motivos de Desistência', 'e');

    if(Matriculas::find_by_id_motivo_desistencia($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este Motivo de Desistência não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Motivos de Desistência', 'Exclusão', 'O Motivo de Desistência '.$registro->motivo.' foi exclusão.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Motivos de Desistência', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Motivos de Desistência', 'Inativação', 'O Motivo de Desistência '.$registro->motivo.' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Motivos de Desistência', 'Ativação', 'O Motivo de Desistência '.$registro->motivo.' foi ativado.');
    endif;

endif;
