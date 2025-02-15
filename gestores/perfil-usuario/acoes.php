<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Usuarios::find(idUsuario());


if($dados['acao'] == 'atualizar-dados'):

    $registro->nome = $dados['nome'];
    $registro->email = $dados['email'];
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Perfil do Usuário', 'Alteração', 'O perfil do usuário '.$registro->nome.' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'salvar-senha'):

    if(empty($dados['nova_senha'])):
        echo json_encode(array('status' => 'senha em branco'));
        exit();
    endif;

    if(md5($dados['senha_atual']) == $registro->senha):

        if($dados['nova_senha'] != $dados['confirma_senha']):
            echo json_encode(array('status' => 'senhas não correspondem'));
            exit();

        else:
            $registro->senha = md5($dados['nova_senha']);
            $registro->save();

            adicionaHistorico(idUsuario(), idColega(), 'Perfil do Usuário', 'Alteração', 'A senha do usuário '.$registro->nome.' foi alterada.');

            echo json_encode(array('status' => 'ok'));

        endif;

    else:

        echo json_encode(array('status' => 'senha atual incorreta'));

    endif;

endif;
