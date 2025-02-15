<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Usuarios::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Usuários', 'i');

    $registro = new Usuarios();
    $registro->nome = 'Novo Usuário';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Usuários', 'Inclusão', 'Um novo Usuário foi cadastrado.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Usuários', 'a');

    /*Verificando duplicidade*/
    if($registro->nome != $dados['nome']):
        if(Usuarios::find_by_nome($dados['nome'])):
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Já existe um Usuário com este nome cadastrado.'));
            exit();
        endif;
    endif;

    if($registro->login != $dados['login']):
        if(Usuarios::find_by_login($dados['login'])):
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Já existe um Usuário com este login cadastrado.'));
            exit();
        endif;
    endif;

    if($registro->email != $dados['email']):
        if(Usuarios::find_by_email($dados['email'])):
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Já existe um Usuário com este email cadastrado.'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->id_perfil = $dados['id_perfil'];
    $registro->id_colega = $dados['id_colega'];
    $registro->nome = $dados['nome'];
    $registro->login = $dados['login'];
    $registro->email = $dados['email'];

    if(!empty($dados['senha'])):
        $registro->senha = md5($dados['senha']);
    endif;

    dadosAlteracao($registro);
    $registro->save();

    /*Pegando permissões da categoria de usuários*/
    $permissoes_perfil = Permissoes_Perfil::all(array('conditions' => array('id_perfil = ?', $registro->id_perfil), 'order' => 'ordem asc'));
    if(!empty($permissoes_perfil)):
        foreach($permissoes_perfil as $permissao_perfil):

            if(!Permissoes::find_by_tela_and_id_usuario($permissao_perfil->tela, $registro->id)):

                $permissao = new Permissoes();
                $permissao->id_usuario = $registro->id;
                $permissao->ordem = $permissao_perfil->ordem;
                $permissao->tela = $permissao_perfil->tela;
                $permissao->opcoes = $permissao_perfil->opcoes;
                $permissao->p = $permissao_perfil->p;
                $permissao->i = $permissao_perfil->i;
                $permissao->a = $permissao_perfil->a;
                $permissao->e = $permissao_perfil->e;
                $permissao->c = $permissao_perfil->c;
                $permissao->ai = $permissao_perfil->ai;
                $permissao->imp = $permissao_perfil->imp;
                $permissao->save();

            else:

                $permissao = Permissoes::find_by_tela_and_id_usuario($permissao_perfil->tela, $registro->id);
                $permissao->ordem = $permissao_perfil->ordem;
                $permissao->tela = $permissao_perfil->tela;
                $permissao->opcoes = $permissao_perfil->opcoes;
                $permissao->p = $permissao_perfil->p;
                $permissao->i = $permissao_perfil->i;
                $permissao->a = $permissao_perfil->a;
                $permissao->e = $permissao_perfil->e;
                $permissao->c = $permissao_perfil->c;
                $permissao->ai = $permissao_perfil->ai;
                $permissao->imp = $permissao_perfil->imp;
                $permissao->save();

            endif;

        endforeach;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Usuários', 'Alteração', 'O usuário '.$registro->nome.' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Usuários', 'e');

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este Usuário não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Usuários', 'Exclusão', 'O usuário '.$registro->nome.' foi excluído.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Usuários', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Usuários', 'Inativação', 'O usuário '.$registro->nome.' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Usuários', 'Ativação', 'O usuário '.$registro->nome.' foi ativado.');
    endif;

endif;
