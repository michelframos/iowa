<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Nome_Provas::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Nomes de Provas', 'i');

    $registro = new Nome_Provas();
    $registro->nome = 'Novo Nome de Prova';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Nomes de Provas', 'Inclusão', 'Um novo Nome de Prova foi cadastrado.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Nomes de Provas', 'a');

    if($registro->nome != $dados['nome-prova'] || $registro->id_idioma != $dados['idioma']):
        /*Verificando duplicidade*/
        if(Nome_Provas::find_by_nome_and_id_idioma($dados['nome-prova'], $dados['idioma'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->id_idioma = $dados['idioma'];
    $registro->nome = $dados['nome-prova'];
    //dadosAlteracao($registro);
    $registro->save();

    /*Marcando idioma como utilizado*/
    $idioma = Idiomas::find($registro->id_idioma);
    $idioma->utilizado = 's';
    $idioma->save();

    adicionaHistorico(idUsuario(), idColega(), 'Nomes de Provas', 'Alteração', 'O Nome de Prova '.$registro->nome.' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Nomes de Provas', 'e');

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este idioma não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Nomes de Provas', 'Exclusão', 'O Nome de Prova '.$registro->nome.' foi excluído.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Nomes de Provas', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Nomes de Provas', 'Inativação', 'O Nome de Prova '.$registro->nome.' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Nomes de Provas', 'Ativação', 'O Nome de Prova '.$registro->nome.' foi ativado.');
    endif;

endif;
