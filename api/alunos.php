<?php
/*
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
*/

include_once('../config.php');

$acao = $url[1];

if($acao == 'listar-alunos'):


    $alunos = Alunos::all();
    if(!empty($alunos)):
        $dados[] = '';
        foreach($alunos as $aluno):
            $dados[] = array('nome' => $aluno->nome, 'celular' => $aluno->celular);
        endforeach;
    endif;

    echo json_encode($dados);



endif;