<?php
include_once('../config.php');

$observaoes_alunos = Alunos_Observacoes::all(['conditions' => ['locate("DO FINANCEIRO: CANCELAMENTO DE PARCELA - corona virus", observacao)']]);
if(!empty($observaoes_alunos)):
    foreach ($observaoes_alunos as $observacao):
        $matriculas = Matriculas::all(['conditions' => ['id_aluno = ? and status = ?', $observacao->id_aluno, 'a']]);

        if(!empty($matriculas)):
            foreach ($matriculas as $matricula):
                echo $matricula->id.'<br>';
            endforeach;
        endif;

    endforeach;
endif;