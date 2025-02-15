<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Nomes_Produtos::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Nomes de Produtos e Horas Semanais', 'i');

    $registro = new Nomes_Produtos();
    $registro->nome_material = 'Novo Nome de Produto';
    $registro->programacao = 'n';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Nomes de Produtos e Horas Semanais', 'Inclusão', 'Um novo Nome de Produto e Horas Semanais foi cadastrado.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Nomes de Produtos e Horas Semanais', 'a');

    if($registro->nome_material != $dados['nome_material'] || $registro->nome_pacote_horas != $dados['nome_pacote_horas']):
        /*Verificando duplicidade*/
        if(Nomes_Produtos::find_by_nome_material_and_nome_pacote_horas($dados['nome_material'], $dados['nome_pacote_horas'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->nome_material = $dados['nome_material'];
    $registro->nome_pacote_horas = $dados['nome_pacote_horas'];

    $horas_semanais = str_replace(',','.', $dados['horas_semanais']);
    $registro->horas_semanais = $horas_semanais;
    //dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Nomes de Produtos e Horas Semanais', 'Alteração', 'O Nome de Produto e Horas Semanais '.$registro->nome_material.' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Nomes de Produtos e Horas Semanais', 'e');

    /*
    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este idioma não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;
    */

    if(Programa_Aulas::find_by_id_nome_produto($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este Nome de Produto não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    if(Turmas::find_by_id_produto($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este Nome de Produto não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Nomes de Produtos e Horas Semanais', 'Exclusão', 'O Nome de Produto e Horas Semanais '.$registro->nome_material.' foi excluído.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Nomes de Produtos e Horas Semanais', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Nomes de Produtos e Horas Semanais', 'Inativação', 'O Nome de Produto e Horas Semanais '.$registro->nome_material.' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Nomes de Produtos e Horas Semanais', 'Ativação', 'O Nome de Produto e Horas Semanais '.$registro->nome_material.' foi ativado.');
    endif;

endif;
