<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Sistema_Notas::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Sistemade Notas', 'i');

    $registro = new Sistema_Notas();
    $registro->nome = 'Novo Sistema de Notas';
    $registro->id_idioma = 0;
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Sistema de Notas', 'Inclusão', 'Um novo Sistemade Notas foi cadastrado.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Sistemade Notas', 'a');

    if($registro->nome != $dados['nome']):
        /*Verificando duplicidade*/
        if(Sistema_Notas::find_by_nome($dados['nome'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->id_idioma = $dados['idioma'];
    $registro->nome = $dados['nome'];
    $registro->id_nome_prova_oral = $dados['prova-oral'];
    $registro->id_nome_prova1 = $dados['prova1'];
    $registro->id_nome_prova2 = $dados['prova2'];
    $registro->id_nome_prova3 = $dados['prova3'];
    $registro->id_nome_prova4 = $dados['prova4'];
    $registro->id_nome_prova5 = $dados['prova5'];
    $registro->id_nome_prova6 = $dados['prova6'];
    //dadosAlteracao($registro);
    $registro->save();

    /*Marcando idioma como utilizado*/
    $idioma = Idiomas::find($registro->id_idioma);
    $idioma->utilizado = 's';
    $idioma->save();

    /*Marcando Nomes de Provas como utilizado*/
    if(!empty($dados['prova-oral'])):
        $nome = Nome_Provas::find($dados['prova-oral']);
        $nome->utilizado = 's';
        $nome->save();
    endif;

    if(!empty($dados['prova1'])):
        $nome = Nome_Provas::find($dados['prova1']);
        $nome->utilizado = 's';
        $nome->save();
    endif;

    if(!empty($dados['prova2'])):
        $nome = Nome_Provas::find($dados['prova2']);
        $nome->utilizado = 's';
        $nome->save();
    endif;

    if(!empty($dados['prova3'])):
        $nome = Nome_Provas::find($dados['prova3']);
        $nome->utilizado = 's';
        $nome->save();
    endif;

    if(!empty($dados['prova4'])):
        $nome = Nome_Provas::find($dados['prova4']);
        $nome->utilizado = 's';
        $nome->save();
    endif;

    if(!empty($dados['prova5'])):
        $nome = Nome_Provas::find($dados['prova5']);
        $nome->utilizado = 's';
        $nome->save();
    endif;

    if(!empty($dados['prova6'])):
        $nome = Nome_Provas::find($dados['prova6']);
        $nome->utilizado = 's';
        $nome->save();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Sistema de Notas', 'Alteração', 'O Sistema de Notas '.$registro->nome.' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'simular-nota'):

    $num_notas = 0;

    if(!empty($dados['prova-oral'])):
        $po = str_replace(',', '.', $dados['prova-oral']);
    endif;

    if(!empty($dados['prova1'])):
        $p1 = str_replace(',', '.', $dados['prova1']);
        $num_notas++;
    endif;

    if(!empty($dados['prova2'])):
        $p2 = str_replace(',', '.', $dados['prova2']);
        $num_notas++;
    endif;;

    if(!empty($dados['prova3'])):
        $p3 = str_replace(',', '.', $dados['prova3']);
        $num_notas++;
    endif;;

    if(!empty($dados['prova4'])):
        $p4 = str_replace(',', '.', $dados['prova4']);
        $num_notas++;
    endif;;

    if(!empty($dados['prova5'])):
        $p5 = str_replace(',', '.', $dados['prova5']);
        $num_notas++;
    endif;;

    if(!empty($dados['prova6'])):
        $p6 = str_replace(',', '.', $dados['prova6']);
        $num_notas++;
    endif;

    $nota = (($p1+$p2+$p3+$p4+$p5+$p6)/$num_notas+$po)/2;

    echo json_encode(array('resultado' => number_format($nota, 1, ',', '.')));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Sistemade Notas', 'e');

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este idioma não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Sistema de Notas', 'Exclusão', 'O Sistema de Notas '.$registro->nome.' foi excluído.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Sistemade Notas', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Sistema de Notas', 'Inativação', 'O Sistema de Notas '.$registro->nome.' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Sistema de Notas', 'Ativação', 'O Sistema de Notas '.$registro->nome.' foi ativado.');
    endif;

endif;
