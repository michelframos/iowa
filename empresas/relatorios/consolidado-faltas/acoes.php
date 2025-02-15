<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$empresa = Empresas::find(idEmpresa());

if($dados['acao'] == 'gerar-relatorio'):

    if(empty($dados['turma'])):
        $id_turma = '%';
    else:
        $id_turma = $dados['turma'];
    endif;

    /*
    if(empty($dados['idioma'])):
        $id_idioma = '%';
    else:
        $id_idioma = $dados['idioma'];
    endif;

    if(!empty($dados['data_inicial'])):
        $data_inicial = implode('-', array_reverse(explode('/', $dados['data_inicial'])));
    else:
        $data_inicial = '';
    endif;

    if(!empty($dados['data_final'])):
        $data_final = implode('-', array_reverse(explode('/', $dados['data_final'])));
    else:
        $data_final = '';
    endif;

    if(!empty($data_inicial) && empty($data_final)):
        $data_final = $data_inicial;
    endif;
    */

    $turmas = Turmas::find_by_sql("
        select 
        alu.id,
        alu.nome,
        tur.nome as nome_turma,
        tur.id as id_turma,
        tur.limite_faltas,
        mat.id as id_matricula
        from
        alunos alu
        inner join matriculas mat
        on alu.id = mat.id_aluno
        inner join turmas tur
        on mat.id_turma = tur.id
        where 
        tur.id like '%".$id_turma."%'and
        tur.status = 'a' and
        mat.`status` = 'a' and 
        mat.id_empresa_pedagogico = '".idEmpresa()."'
        order by alu.nome asc;

        /*
        select 
        *,
        matriculas.status as status_matricula
        from 
        matriculas 
        inner join empresas on matriculas.id_empresa_pedagogico = empresas.id 
        inner join turmas on matriculas.id_turma = turmas.id
        where
        matriculas.id_empresa_pedagogico = '".idEmpresa()."'
        and turmas.id_idioma like '{$id_idioma}'
        and turmas.id like '{$id_turma}'
        and turmas.status = 'a'
        and matriculas.status = 'a'
        group by turmas.id;
        */
    ");

    if(!empty($turmas)):

        ?>
        <section class="pmd-card pmd-z-depth padding-10">

        <div class="pmd-card">
            <div class="table-responsive">
            <table class="table pmd-table table-hover">
                <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Turma</th>
                    <th class="texto-centro">Nº Faltas</th>
                </tr>
                </thead>
                <tbody>
        <?php

        foreach ($turmas as $turma):

            if(!empty($turma->limite_faltas) && $turma->limite_faltas > 0):

                $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id_turma, 0)));

                if(!empty($turma->limite_faltas)):
                    $limite_faltas = $turma->limite_faltas;
                else:
                    $limite_faltas = 0;
                endif;

                /*
                echo '<h1 class="headline">Turma: '.$turma->nome.'</h1>';
                echo '<h1 class="headline">Limite de Faltas: '.$turma->limite_faltas.'</h1>';
                echo '<div class="espaco20"></div>';
                */

                //$alunos = Alunos_Turmas::find_by_sql("select alunos_turmas.id as id_aluno_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula, alunos_turmas.id_turma, matriculas.status, alunos.nome from aulas_turmas INNER JOIN alunos_turmas ON aulas_turmas.id_turma = alunos_turmas.id_turma INNER JOIN matriculas ON matriculas.id = alunos_turmas.id_matricula	INNER JOIN alunos ON matriculas.id_aluno = alunos.id WHERE aulas_turmas.id_turma like '{$turma->id_turma}' AND ( aulas_turmas.id_situacao_aula <> 0 AND aulas_turmas.id_situacao_aula <> 2 AND aulas_turmas.id_situacao_aula <> 3 AND id_situacao_aula <> 10 ) and (matriculas.status = 'a') group by alunos_turmas.id_aluno");

                $dados_aluno = Alunos::find($turma->id);

                $faltas = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id_turma} and aulas_alunos.id_aluno = {$turma->id} and aulas_alunos.presente = 'n' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");
                $datas_faltas_ = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id_turma} and aulas_alunos.id_aluno = {$turma->id} and aulas_alunos.presente = 'n' and coalesce(aulas_alunos.abonada, '') <> 's' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");
                $abonos = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id_turma} and aulas_alunos.id_aluno = {$turma->id} and aulas_alunos.presente = 'n' and coalesce(aulas_alunos.abonada, '') = 's' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");

                $numero_faltas = count($faltas)-count($abonos);
                if($numero_faltas > $limite_faltas):
                    echo '<tr>';
                    echo '<td>'.$dados_aluno->nome.'</td>';
                    echo '<td>'.$turma->nome_turma.'</td>';
                    echo '<td width="150" class="texto-centro">'.$numero_faltas.'</td>';
                    echo '</tr>';

                    if(!empty($datas_faltas_)):
                        $datas_faltas = '';
                        foreach ($datas_faltas_ as $falta):
                            echo '<tr>';
                            $aula = Aulas_Turmas::find($falta->id_aula);
                            $datas_faltas .= $aula->data->format('d/m/Y').' - ';
                            echo '</tr>';
                        endforeach;
                        echo '<td colspan="3">'.$datas_faltas.'</td>';
                    endif;
                endif;

            endif;

        endforeach;
        ?>

                </tbody>
            </table>
            </div>
        </div>
        </section>

        <?php

    endif;

endif;
