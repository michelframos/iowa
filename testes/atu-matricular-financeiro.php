<?php
include_once('../config.php');

/*Dados de criação*/
function dadosCriacao($variavel){
    $variavel->criado_por = $_SESSION['usuario']['id'];
    $variavel->data_criacao = date('Y-m-d H:i:s');
    $variavel->alterado_por = $_SESSION['usuario']['id'];
    $variavel->data_alteracao = date('Y-m-d H:i:s');
}

//$turma = 403;

echo 'iniciando processo...';

$turmas = Turmas::all(array('conditions' => array('status = ?', 'a')));

if(!empty($turmas)):
    foreach ($turmas as $turma_selecionada):

        $turma = $turma_selecionada->id;
        $alunos_turma = Alunos_Turmas::find_by_sql('select alunos_turmas.id_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula as matricula_aluno_turma, matriculas.id_turma, matriculas.id_aluno, matriculas.`status` from alunos_turmas inner join matriculas on alunos_turmas.id_matricula = matriculas.id where matriculas.`status` = "a" and alunos_turmas.id_turma = '.$turma);

        if(!empty($alunos_turma)):
            foreach ($alunos_turma as $aluno_turma):

                if(!Matriculas::find(array('conditions' => array('id_turma = ? and id_aluno = ?', $turma, $aluno_turma->id_aluno)))):
                    //echo $aluno_turma->id_aluno.'<br>';;

                    $id_aluno = $aluno_turma->id_aluno;
                    $id_matricula_original = $aluno_turma->matricula_aluno_turma.'<br>';

                    //$aluno_turma = Alunos_Turmas::find($aluno_turma->id_aluno);
                    $matricula = Matriculas::find($id_matricula_original);
                    $turma_destino = Turmas::find($turma);
                    $turma_origem = Turmas::find($aluno_turma->id_turma);

                    /*marcando matricula de origem como transferida*/
                    $matricula->status = 't';
                    $matricula->save();

                    $numero_parcelas = Parcelas::find_all_by_id_matricula_and_pago($matricula->id, 'n');

                    /*criando nova matricula*/
                    $nova_matricula = new Matriculas();
                    $nova_matricula->id_turma = $turma_destino->id;
                    $nova_matricula->id_aluno = $id_aluno;
                    $nova_matricula->numero_parcelas = count($numero_parcelas);

                    $nova_matricula->valor_parcela = $matricula->valor_parcela;

                    $nova_matricula->data_vencimento = $matricula->data_vencimento;
                    $nova_matricula->responsavel_financeiro = $matricula->responsavel_financeiro;
                    $nova_matricula->id_empresa_financeiro = $matricula->id_empresa_financeiro;

                    $nova_matricula->porcentagem_empresa = $matricula->porcentagem_empresa;

                    $nova_matricula->responsavel_pedagogico = $matricula->responsavel_pedagogico;
                    $nova_matricula->id_empresa_pedagogico = $matricula->id_empresa_pedagogico;
                    $nova_matricula->data_matricula = date('Y-m-d');
                    $nova_matricula->id_situacao_aluno_turma = 1;
                    $nova_matricula->status = 'a';
                    dadosCriacao($nova_matricula);
                    $nova_matricula->save();

                    $id_nova_matricula = $nova_matricula->id;

                    /*atualizando matricula em alunos_turmas*/

                    //echo $id_nova_matricula;
                    try{
                        $atualiza_aluno_turma = Alunos_Turmas::find(array('conditions' => array('id_aluno = ? and id_turma = ?', $id_aluno, $turma)));
                        if(!empty($atualiza_aluno_turma)):
                            $atualiza_aluno_turma->id_matricula = $id_nova_matricula;
                            $atualiza_aluno_turma->save();
                        endif;
                    } catch (Exception $e){

                    }


                    /*Alterando parcelas não pagas para nova matricula*/
                    if(!empty($numero_parcelas)):
                        foreach($numero_parcelas as $parcela):
                            $parcela->id_matricula = $id_nova_matricula;
                            $parcela->id_turma = $turma_destino->id;
                            $parcela->id_idioma = $turma_destino->id_idioma;
                            $parcela->save();
                        endforeach;
                    endif;

                endif;

            endforeach;
        endif;

    endforeach;;
endif;


echo 'processo finalizado...';

/*
$turma = 403;
$alunos_turma = Alunos_Turmas::find_by_sql('select alunos_turmas.id_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula as matricula_aluno_turma, matriculas.id_turma, matriculas.id_aluno, matriculas.`status` from alunos_turmas inner join matriculas on alunos_turmas.id_matricula = matriculas.id where matriculas.`status` = "a" and alunos_turmas.id_turma = '.$turma);

if(!empty($alunos_turma)):
    foreach ($alunos_turma as $aluno_turma):

        if(!Matriculas::find(array('conditions' => array('id_turma = ? and id_aluno = ?', $aluno_turma->id_turma, $aluno_turma->id_aluno)))):
            echo $aluno_turma->id_turma.'<br>';
            $aluno = Alunos::find($aluno_turma->id_aluno);
            $turma = Turmas::find($aluno_turma->id_turma);
            echo 'O alunos '.$aluno->nome.' não tem matricula para a turma '.$turma->nome.'<br>';
        endif;

    endforeach;
endif;
 */