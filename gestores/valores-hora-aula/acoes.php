<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Valores_Hora_Aula::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Valores Hora/Aula', 'i');

    $registro = new Valores_Hora_Aula();
    $registro->nome = 'Novo Valor Hora/Aula';
    $registro->status = 'a';
    $registro->aplicar_categoria = 'n';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Valores Hora/Aula', 'Inclusão', 'Um novo Valor Hora/Aula foi cadastrado.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Valores Hora/Aula', 'a');

    if($registro->nome != $dados['nome']):
        /*Verificando duplicidade*/
        if(Valores_Hora_Aula::find_by_nome($dados['nome'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->nome = $dados['nome'];

    $valor = str_replace(".", "", $dados['valor']);
    $valor = str_replace(",", ".", $valor);

    $registro->valor = $valor;
    //dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Valores Hora/Aula', 'Alteração', 'O Valor Hora/Aula '.$registro->nome.' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Valores Hora/Aula', 'e');

    /*
    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este idioma não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;
    */

    if(Turmas::find_by_id_valor_hora_aula($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este Valor Hora/Aula não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Valores Hora/Aula', 'Exclusão', 'O Valor Hora/Aula '.$registro->nome.' foi excluído.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Valores Hora/Aula', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Valores Hora/Aula', 'Inativação', 'O Valor Hora/Aula '.$registro->nome.' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Valores Hora/Aula', 'Ativação', 'O Valor Hora/Aula '.$registro->nome.' foi ativado.');
    endif;

endif;


if($dados['acao'] == 'aplicar-categoria'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Valores Hora/Aula', 'a');

    if($registro->aplicar_categoria == 'n'):
        $registro->aplicar_categoria = 's';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Valores Hora/Aula', 'Alteração', 'O Valor Hora/Aula '.$registro->nome.' teve a opção Aplicar Categoria marcada como sim.');
    else:
        $registro->aplicar_categoria = 'n';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Valores Hora/Aula', 'Alteração', 'O Valor Hora/Aula '.$registro->nome.' teve a opção Aplicar Categoria marcada como não .');
    endif;

endif;
