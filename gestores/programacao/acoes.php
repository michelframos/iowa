<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Nomes_Produtos::find($dados['id']);

/*
if($dados['acao'] == 'novo'):

    $registro = new Nomes_Produtos();
    $registro->nome_material = 'Novo Nome de Produto';
    $registro->programacao = 'n';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;
*/


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Programação e Conteúdo de Aulas', 'a');

    $numero_aulas = ceil($dados['horas_estagio'] / $registro->horas_semanais);

    if($registro->numero_aulas != $numero_aulas):
        /*Salvando Alterações*/
        $registro->horas_estagio = $dados['horas_estagio'];
        $registro->numero_aulas = $numero_aulas;
        $registro->programacao = 's';
        //dadosAlteracao($registro);
        $registro->save();

        /*gerando campos para conteúdo*/
        $programacao = Programa_Aulas::find_all_by_id_nome_produto($registro->id);

        if(empty($programacao)):

            /*Se não existir programação ainda*/
            if(!empty($numero_aulas)):
                for($i=1;$i <= $numero_aulas; $i++):
                    $conteudo = new Programa_Aulas();
                    $conteudo->id_nome_produto = $registro->id;
                    $conteudo->aula = $i;
                    $conteudo->save();
                endfor;
            endif;

        else:

            /*Se já existir*/
            foreach($programacao as $conteudo):
                $conteudo->delete();
            endforeach;

            if(!empty($numero_aulas)):
                for($i=1;$i <= $numero_aulas; $i++):
                    $conteudo = new Programa_Aulas();
                    $conteudo->id_nome_produto = $registro->id;
                    $conteudo->aula = $i;
                    $conteudo->save();
                endfor;
            endif;

        endif;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Programação e Conteúdo de Aulas', 'Alteração', 'A Programação e Conteúdo de Aulas do Produto '.$registro->nome_material.' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'salvar-conteudo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Programação e Conteúdo de Aulas', 'a');

    if(is_array($dados['conteudo'])):
        foreach($dados['conteudo'] as $id_conteudo => $conteudo):
            $programacao = Programa_Aulas::find($id_conteudo);
            $programacao->conteudo = $conteudo;
            $programacao->save();
        endforeach;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Programação e Conteúdo de Aulas - Conteúdo', 'Alteração', 'A Programação e Conteúdo de Aulas do Produto '.$registro->nome_material.' teve seu contúdo alterado..');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Programação e Conteúdo de Aulas', 'e');

    if($registro->programacao_utilizada == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta programação não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    $programacao = Programa_Aulas::find_all_by_id_nome_produto($registro->id);
    if(!empty($programacao)):
        foreach($programacao as $conteudo):
            $conteudo->delete();
        endforeach;
    endif;

    $registro->horas_estagio = null;
    $registro->numero_aulas = null;
    $registro->programacao = 'n';
    $registro->programacao_utilizada = 'n';
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Programação e Conteúdo de Aulas', 'Exclusão', 'A Programação e Conteúdo de Aulas do Produto '.$registro->nome_material.' foi excluída.');

    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Programação e Conteúdo de Aulas', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Programação e Conteúdo de Aulas', 'Inativação', 'A Programação e Conteúdo de Aulas do Produto '.$registro->nome_material.' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Programação e Conteúdo de Aulas', 'Ativação', 'A Programação e Conteúdo de Aulas do Produto '.$registro->nome_material.' foi ativada.');
    endif;

endif;
