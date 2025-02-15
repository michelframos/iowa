<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);


if($dados['acao'] == 'gerar-relatorio'):

    if(empty($dados['situacao_aluno'])):
        $situacao = '%';
    else:
        $situacao = $dados['situacao_aluno'];
    endif;

    /*Data Matricula*/
    if(!empty($dados['data_inicial_matricula'])):
        $data_inicial_matricula = implode('-', array_reverse(explode('/', $dados['data_inicial_matricula'])));
    else:
        $data_inicial_matricula = '';
    endif;

    if(!empty($dados['data_final_matricula'])):
        $data_final_matricula = implode('-', array_reverse(explode('/', $dados['data_final_matricula'])));
    else:
        $data_final_matricula = '';
    endif;

    /*Data Inativação*/
    if(!empty($dados['data_inicial_nativado'])):
        $data_inicial_nativado = implode('-', array_reverse(explode('/', $dados['data_inicial_nativado'])));
    else:
        $data_inicial_nativado = '';
    endif;

    if(!empty($dados['data_final_inativado'])):
        $data_final_inativado = implode('-', array_reverse(explode('/', $dados['data_final_inativado'])));
    else:
        $data_final_inativado = '';
    endif;

    if(!empty($data_inicial_matricula) && empty($data_final_matricula)):
        $data_final_matricula = $data_inicial_matricula;
    endif;

    if(!empty($data_inicial_nativado) && empty($data_final_inativado)):
        $data_final_inativado = $data_inicial_nativado;
    endif;

    /*Consulta*/
    $sql_data_matricula = !empty($data_inicial_matricula) ? "and matriculas.data_matricula between '{$data_inicial_matricula}' and '{$data_final_matricula}'" : "";
    $sql_data_inativavao = !empty($data_inicial_nativado) ? "and matriculas.data_desistencia between '{$data_inicial_nativado}' and '{$data_final_inativado}' and matriculas.id_situacao_aluno_turma = 2" : "";
    $registros = Matriculas::find_by_sql("
        select 
            alunos.`status` as status_aluno,
            alunos.data_alteracao as data_alteracao_aluno,
            alunos.nome,
            alunos.email1,
            alunos.email2,
            alunos.celular,
            matriculas.id, 
            matriculas.id_turma, 
            matriculas.id_aluno, 
            matriculas.id_situacao_aluno_turma, 
            matriculas.id_motivo_desistencia, 
            matriculas.data_desistencia, 
            matriculas.status,
            matriculas.data_matricula,
            unidades.nome_fantasia as unidade,
            turmas.nome as turma_nome,
            turmas.data_inicio as data_inicio_turma,
            turmas.data_termino as data_termino_turma,
            turmas.`status` as status_turma,
            turmas.data_alteracao as data_alteracao_turma
        from 
            matriculas 
            inner join alunos
            on matriculas.id_aluno = alunos.id
            inner join turmas
            on matriculas.id_turma = turmas.id
            inner join unidades
            on turmas.id_unidade = unidades.id
        where 
            alunos.status like '{$situacao}'
            and matriculas.data_matricula = (select max(data_matricula) from matriculas where id_aluno = alunos.id)
            {$sql_data_matricula}
            {$sql_data_inativavao}
            group by
            matriculas.id_aluno
            order by
            matriculas.id_aluno asc
    ");

    //$turmas = Turmas::all(array('conditions' => array('id_unidade like ? and id like ?', $id_unidade, $id_turma), 'order' => 'nome asc'));

    if(!empty($registros)):

        echo '<h2 class="titulo">RELATÓRIO DE E-MAIL MARKETING</h2>';

            ?>
            <div class="table-responsive">
            <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Nome do Aluno</th>
                <!--<th width="150">Status do Aluno</th>-->
                <th>E-mail</th>
                <th>Celular</th>
                <th>Turma</th>
                <th>Unidade</th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach($registros as $registro):

                echo '<tr>';
                echo '<td class="text-center" width="5%">'.$registro->nome.'</td>';
                //echo '<td class="text-center" width="5%">'.$registro->status_aluno.'</td>';
                echo '<td class="text-center" width="20%">'.(!empty($registro->email1) ? $registro->email1 : $registro->email2).'</td>';
                echo '<td class="text-center" width="5%">'.$registro->celular.'</td>';
                echo '<td class="text-center" width="5%">'.$registro->turma_nome.'</td>';
                echo '<td class="text-center" width="5%">'.$registro->unidade.'</td>';
                echo '</tr>';

            endforeach;
            ?>
                </tbody>
            </table>
            </div>
            <div class="espaco20"></div>
            <?php

    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUM ALUNO ENCONTRADO.</div>';

    endif;


endif;
