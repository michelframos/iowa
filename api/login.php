<?php
include_once('../config.php');

$acao = $url[1];

if($acao == 'logar'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

    if(Alunos::find_by_login_and_senha($login, md5($senha))):
        $aluno = Alunos::find_by_login_and_senha($login, md5($senha));
        echo json_encode(array('status' => 'ok', 'id' => $aluno->id, 'nome' => $aluno->nome, 'login' => $aluno->login, 'senha' => $aluno->senha));
    else:
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Usuário e/ou senha inválido! Tente novamente.', 'login' => $login, 'senha' => $senha));
    endif;

endif;

if($acao == 'teste'):
    echo json_encode(array('status' => 'ok'));
endif;
