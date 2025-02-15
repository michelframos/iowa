<?php
include_once ('../config.php');
$turma = Turmas::find(529);
$faltas_aluno = Aulas_Alunos::all(array('conditions' => array('id_turma = ? and id_aluno = ? and presente = ? and coalesce(abonada,"n") <> ?', $turma->id, 851, 'n', 's')));

if(!empty($faltas_aluno)):
    foreach($faltas_aluno as $falta_aluno):

        try{
            $aula_turma = Aulas_Turmas::find($falta_aluno->id_aula);

            if(
                $aula_turma->id_situacao_aula != 0 &&
                $aula_turma->id_situacao_aula != 2 &&
                $aula_turma->id_situacao_aula != 3 &&
                $aula_turma->id_situacao_aula != 5 &&
                $aula_turma->id_situacao_aula != 10
            ):

                $faltas[] = array(
                    'id_aula_turma' => $falta_aluno->id_aula,
                    'id_aula_aluno' => $falta_aluno->id,
                    'data' => $aula_turma->data->format('d/m/Y'),
                    'responsavel_pedagogico' => $matricula->responsavel_pedagogico,
                    'id_empresa_pedagogico' => $matricula->id_empresa_pedagogico,
                    'email_gestor' => $aluno->email_gestor_pedagogico,
                    'id_turma' => $id_turma,
                    'abonada' => $falta_aluno->abonada
                );

            endif;
        } catch (Exception $e){

        }

    endforeach;
endif;

echo json_encode($faltas);