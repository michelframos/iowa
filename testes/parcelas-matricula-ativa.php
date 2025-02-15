<?php
include_once('../config.php');

$matriculas = Matriculas::all(array('conditions' => array('status = ? and (responsavel_pedagogico = ? or responsavel_pedagogico = ?) and (responsavel_financeiro = ? or responsavel_financeiro = ?)', 'a', 1,3,1,3)));
if(!empty($matriculas)):
    foreach ($matriculas as $matricula):

        try{

            $parcelas = Parcelas::find_all_by_id_matricula($matricula->id);
            if(empty($parcelas)):
                try{
                    $aluno = Alunos::find($matricula->id_aluno);
                    $turma = Turmas::find($matricula->id_turma);
                    echo 'Matricula: '.$matricula->id.' - Aluno: '.$aluno->id.' - '.$aluno->nome.' - Turma: '.$turma->nome.' - Numero parcelas: '.count($parcelas).'<br>';
                } catch (Exception $e){

                }
            endif;

        } catch (Exception $e){

        }

    endforeach;
endif;