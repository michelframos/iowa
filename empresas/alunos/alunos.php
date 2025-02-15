<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$empresa = Empresas::find(idEmpresa());

$id = filter_input(INPUT_POST, 'id_turma', FILTER_VALIDATE_INT);
$turma = Turmas::find($id);
$aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id, 0)));
$numero_aulas = count($aulas);

if(!empty($turma->limite_faltas)):
    $limite_faltas = $turma->limite_faltas;
else:
    $limite_faltas = 0;
endif;

//$alunos = Alunos_Turmas::find_by_sql("select alunos_turmas.id as id_aluno_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula, alunos_turmas.id_turma, matriculas.status, alunos.nome from aulas_turmas INNER JOIN alunos_turmas ON aulas_turmas.id_turma = alunos_turmas.id_turma INNER JOIN matriculas ON matriculas.id = alunos_turmas.id_matricula	INNER JOIN alunos ON matriculas.id_aluno = alunos.id WHERE aulas_turmas.id_turma like '{$turma->id}' AND ( aulas_turmas.id_situacao_aula <> 0 AND aulas_turmas.id_situacao_aula <> 2 AND aulas_turmas.id_situacao_aula <> 3 AND id_situacao_aula <> 10 ) and (matriculas.status = 'a' || matriculas.status = 't') group by alunos_turmas.id_aluno");
$alunos = Alunos_Turmas::find_by_sql("select alunos_turmas.id as id_aluno_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula, alunos_turmas.id_turma, matriculas.status, alunos.nome from aulas_turmas INNER JOIN alunos_turmas ON aulas_turmas.id_turma = alunos_turmas.id_turma INNER JOIN matriculas ON matriculas.id = alunos_turmas.id_matricula	INNER JOIN alunos ON matriculas.id_aluno = alunos.id WHERE aulas_turmas.id_turma like '{$turma->id}' AND ( aulas_turmas.id_situacao_aula <> 0 AND aulas_turmas.id_situacao_aula <> 2 AND aulas_turmas.id_situacao_aula <> 3 AND id_situacao_aula <> 10 ) and (matriculas.status = 'a') group by alunos_turmas.id_aluno");

/*
$alunos_turma = Alunos::find_by_sql("
                    select 
                    * 
                    from 
                    matriculas 
                    inner join empresas on matriculas.id_empresa_pedagogico = empresas.id 
                    inner join turmas on matriculas.id_turma = turmas.id
                    where
                    matriculas.id_empresa_pedagogico = '".idEmpresa()."'
                    and turmas.id = '{$turma->id}'
                ");
*/
?>

<!-- Start Content -->
<section class="padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>

    <h1 class="headline">Turma: <?php echo $turma->nome ?></h1>
    <h1 class="headline">Aulas Dadas: <?php echo count($aulas) ?></h1>
    <h1 class="headline">Limite de Faltas: <?php echo $limite_faltas; ?></h1>
    <div class="espaco20"></div>


    <section class="pmd-card pmd-z-depth padding-10">

        <div class="pmd-card">
            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th width="150">Data Matrícula</th>
                        <th>Aluno</th>
                        <th>Número de Faltas</th>
                        <th>Abonos</th>
                        <th>Frequencia</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(!empty($alunos)):
                        foreach ($alunos as $aluno):
                            $dados_aluno = Alunos::find($aluno->id_aluno);
                            $matricula = Matriculas::find_by_id_aluno_and_id_turma($aluno->id_aluno, $turma->id);

                            $abonos = 0;
                            if(!empty($aulas)):
                                foreach($aulas as $aula):
                                    if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_abonada($aula->id, $aluno->id_aluno, 's')):
                                        $abonos++;
                                    endif;
                                endforeach;
                            endif;

                            $frequencia = 0;
                            if(!empty($aulas)):
                                foreach($aulas as $aula):
                                    if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $aluno->id_aluno, 's')):
                                    //if(Aulas_Alunos::find(array('conditions' => array('id_aula = ? and id_aluno = ? and presente = ? or (presente = ? and abonada = ?)', $aula->id, $aluno->id_aluno, 's', 'n', 's')))):
                                        $frequencia++;
                                    endif;
                                endforeach;
                            endif;

                            $numero_faltas = 0;
                            if(!empty($aulas)):
                                foreach($aulas as $aula):
                                    if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $aluno->id_aluno, 'n')):
                                    //if(Aulas_Alunos::find(array('conditions' => array('id_aula = ? and id_aluno = ? and presente = ? and coalesce(abonada, "n") = ? ', $aula->id, $aluno->id_aluno, 'n', 'n')))):
                                        $numero_faltas++;
                                    endif;
                                endforeach;
                            endif;

                            $numero_faltas = $numero_faltas-$abonos;
                            $aproveitamento = (($frequencia+$abonos)/$numero_aulas)*100;

                            if(!empty($matricula->data_matricula)):
                                $data_matricula = $matricula->data_matricula->format('d/m/Y');
                            else:
                                $data_matricula;
                            endif;

                            echo "
                                <tr>
                                    <td>{$data_matricula}</td>
                                    <td>{$dados_aluno->nome}</td>
                                    <td class='texto-center'>{$numero_faltas}</td>
                                    <td class='texto-center'>{$abonos}</td>
                                    <td class='texto-center'>".number_format($aproveitamento, 2, '.', '')."%</td>
                                    <td class='texto-center'><button class=\"btn pmd-btn-raised pmd-ripple-effect btn-danger ver-dados\" id_aluno='{$dados_aluno->id}' id_turma='{$turma->id}' type=\"button\" turma='{$turma->id}'>Ver Datas de Faltas, Abonos e Justificativas</button></td>
                                </tr>
                            ";
                        endforeach;
                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>

    </section>

</section>
<div class="pmd-sidebar-overlay"></div>
<script src="js/alunos.js"></script>