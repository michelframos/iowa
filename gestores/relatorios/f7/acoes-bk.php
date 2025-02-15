<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'busca-turmas'):

    if(!empty($dados['id_unidade'])):

        $turmas = Turmas::all(array('conditions' => array('id_unidade = ?', $dados['id_unidade']), 'order' => 'nome asc'));
        if(!empty($turmas)):
            echo '<option value="">Todas</option>';
            foreach($turmas as $turma):
                echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
            endforeach;
        endif;

    else:

        $turmas = Turmas::all(array('order' => 'nome asc'));
        if(!empty($turmas)):
            echo '<option value="">Todas</option>';
            foreach($turmas as $turma):
                echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
            endforeach;
        endif;

    endif;

endif;

if($dados['acao'] == 'gerar-relatorio'):

    if(empty($dados['id_unidade'])):
        $id_unidade = '%';
    else:
        $id_unidade = $dados['id_unidade'];
    endif;

    if($dados['turma'] == ''):
        $id_turma = '%';
    else:
        $id_turma = $dados['turma'];
    endif;

    /*
    if($dados['nome'] == ''):
        $nome = '%';
    else:
        $nome = $dados['nome'].'%';
    endif;
    */

    /*Data*/
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

    /*
    if(!empty($dados['considerar_abono'])):
        $considerar_abono = $dados['considerar_abono'];
    else:
        $considerar_abono = 'n';
    endif;
    */

    //$turmas = Turmas::all(array('conditions' => array('id_unidade like ? and id like ?', $id_unidade, $id_turma), 'order' => 'nome asc'));

    if(!empty($data_inicial) && empty($data_final)):
        $data_final = $data_inicial;
    endif;

    if(!empty($data_inicial)):
        /*
        $alunos = VAulasDadasModel::find_by_sql("
            select 
            id_aluno,
            nome,
            nome_turma,
            nome_fantasia,
            count(case when situacao = 'CC' then 1 end) as aulas_cc,
            count(case when situacao = 'CCOA' then 1 end) as aulas_ccoa,
            count(case when situacao = 'PC' then 1 end) as aulas_pc,
            count(case when presente = 'n' then 1 end) as faltas
            from 
            v_aulas_dadas
            where data between '{$data_inicial}' and '{$data_final}'
            and id_turma like '{$id_turma}'
            and id_unidade like '{$id_unidade}'
            GROUP BY
            id_aluno, nome, nome_turma, nome_fantasia
            having 
            aulas_cc > 1
            or aulas_ccoa > 1
            or aulas_pc > 1
            or faltas > 1
        ");
        */

        $alunos = VAulasDadasModel::find_by_sql("
            select 
            row_number ( ) OVER ( PARTITION BY id_unidade, id_turma, id_aluno ) AS `sequencia_aula`,
            id_aluno,
            nome,
            id_unidade,
            nome_turma,
            id_turma,
            nome_fantasia,
            situacao,
            data,
            numero_aula,
            presente
            from 
            v_aulas_dadas
            where data between '{$data_inicial}' and '{$data_final}'
            and id_turma like '{$id_turma}'
            and id_unidade like '{$id_unidade}'
            order by
            data,
            nome asc
        ");

    else:
        /*
        $alunos = VAulasDadasModel::find_by_sql("
            select 
            id_aluno,
            nome,
            nome_turma,
            nome_fantasia,
            count(case when situacao = 'CC' then 1 end) as aulas_cc,
            count(case when situacao = 'CCOA' then 1 end) as aulas_ccoa,
            count(case when situacao = 'PC' then 1 end) as aulas_pc,
            count(case when presente = 'n' then 1 end) as faltas
            from 
            v_aulas_dadas
            where id_turma like '{$id_turma}'
            and id_unidade like '{$id_unidade}'
            GROUP BY
            id_aluno, nome, nome_turma, nome_fantasia
            having 
            aulas_cc > 1
            or aulas_ccoa > 1
            or aulas_pc > 1
            or faltas > 1
        ");
        */

        $alunos = VAulasDadasModel::find_by_sql("
            select 
            id_aluno,
            nome,
            id_unidade,
            id_turma,
            nome_turma,
            nome_fantasia,
            situacao,
            data,
            numero_aula,
            presente,
            sequencia_aula
            from 
            v_aulas_dadas
            where id_turma like '{$id_turma}'
            and id_unidade like '{$id_unidade}'
            order by
            data,
            nome asc
        ");

    endif;

    $cont = 1;
    $alunos_filtrados = [];
    if(!empty($alunos)):
        foreach ($alunos as $aluno):

            if($aluno->id_aluno == 999):
                echo $aluno->sequencia_aula.' | '.$aluno->nome.' | '.$aluno->data->format('d/m/Y').' | '.$aluno->situacao.' | '.$aluno->presente.'<br>';
            endif;

            if(
                $aluno->situacao == 'CC'
                || $aluno->situacao == 'CCOA'
                || $aluno->situacao == 'PC'
                //|| ($aluno->presente == 'n' && $aluno->situacao != 'CC' && $aluno->situacao != 'CCOA' && $aluno->situacao != 'PC')
                || ($aluno->presente == 'n' && $aluno->situacao == 'OK')
            ):
                if(isset($alunos_filtrados[$id_unidade][$aluno->id_turma][$aluno->id_aluno])):
                    echo 'exite';
                    if(
                        $aluno->sequencia_aula > 1 &&
                        $aluno->sequencia_aula == ($alunos_filtrados[$id_unidade][$aluno->id_turma][$aluno->id_aluno]['sequencia_aula'] + 1)
                    ):
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['id_aluno'] = $aluno->id_aluno;
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['numero_ocorrencia']++;
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['sequencia_aula'] = $aluno->sequencia_aula;
                        $aluno->situacao == 'CC' ? $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['CC']++ : '';
                        $aluno->situacao == 'CCOA' ? $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['CCOA']++ : '';
                        $aluno->situacao == 'PC' ? $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['PC']++ : '';
                        ($aluno->presente == 'n' && $aluno->situacao == 'OK') ? $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['Faltas']++ : '';
                    else:
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['numero_ocorrencia'] = 1;
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['sequencia_aula'] = $aluno->sequencia_aula;
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['id_aluno'] = $aluno->id_aluno;
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['CC'] = $aluno->situacao == 'CC' ? 1 : 0;
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['CCOA'] = $aluno->situacao == 'CCOA' ? 1 : 0;
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['PC'] = $aluno->situacao == 'PC' ? 1 : 0;
                        $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['Faltas'] = ($aluno->presente == 'n' && $aluno->situacao == 'OK') ? 1 : 0;
                    endif;
                else:
                    $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['numero_ocorrencia'] = 1;
                    $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['sequencia_aula'] = $aluno->sequencia_aula;
                    $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['id_aluno'] = $aluno->id_aluno;
                    $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['CC'] = $aluno->situacao == 'CC' ? 1 : 0;
                    $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['CCOA'] = $aluno->situacao == 'CCOA' ? 1 : 0;
                    $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['PC'] = $aluno->situacao == 'PC' ? 1 : 0;
                    $alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['Faltas'] = ($aluno->presente == 'n' && $aluno->situacao == 'OK') ? 1 : 0;
                endif;
            endif;

            //echo $cont.'<br>';
            //echo 'Aluno '.$aluno->nome.'<br>';
            //echo 'ID '.$alunos_filtrados[$aluno->id_unidade][$aluno->id_turma][$aluno->id_aluno]['id_aluno'] .'<br>';
            //echo 'sequencia aula '.$aluno->sequencia_aula.'<br>';
            //echo 'sequencia seguinte '.(($alunos_filtrados[$id_unidade][$aluno->id_turma][$aluno->id_aluno]['sequencia_aula']) + 1).'<br>';
            //echo 'sequencia anterior '.(($alunos_filtrados[$id_unidade][$aluno->id_turma][$aluno->id_aluno]['sequencia_aula']) - 1).'<br>';
            //echo 'Situação '.( $aluno->situacao).'<br>';
            //echo 'CCOA '.(($alunos_filtrados[$id_unidade][$aluno->id_turma][$aluno->id_aluno]['CCOA'])).'<br>';
            //echo 'Numero Ocorrencia '.(($alunos_filtrados[$id_unidade][$aluno->id_turma][$aluno->id_aluno]['numero_ocorrencia'])).'<br>';
            $cont++;

        endforeach;
    endif;

    print_r($alunos_filtrados);

    if(!empty($alunos)):

        echo '<h2 class="titulo">RELATÓRIO F7</h2>';


            ?>
                <?php
                if(!empty($alunos_filtrados)):
                foreach($alunos_filtrados as $id_unidade => $dados_unidade):

                    $unidade = Unidades::find_by_id($id_unidade);

                    echo "
                        <div class='titulo'>$unidade->nome_fantasia</div>
                        <div class='espaco20'></div>
                        ";

                    if(!empty($dados_unidade)):
                        foreach ($dados_unidade as $id_turma => $dados_turma):

                            $turma = Turmas::find_by_id($id_turma);

                            echo "
                                <div class='table-responsive'>
                                   <table class='table table-striped'>
                                        <thead>
                                            <tr>
                                                <th colspan='5' class='titulo'>{$turma->nome}</th>
                                            </tr>
                                            <tr>
                                                <th>Aluno</th>
                                                <th class='texto-centro' width='10%'>CC</th>
                                                <th class='texto-centro' width='10%'>CCOA</th>
                                                <th class='texto-centro' width='10%'>PC</th>
                                                <th class='texto-centro' width='10%'>FALTAS</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                       ";

                                    if(!empty($dados_turma)):
                                        foreach ($dados_turma as $dados_aluno):

                                            if($dados_aluno['numero_ocorrencia'] >= 2):

                                                $aluno = Alunos::find_by_id($dados_aluno['id_aluno']);

                                                echo "
                                                    <tr>
                                                        <td>$aluno->nome</td>
                                                        <td class='text-center'>".$dados_aluno['CC']."</td>
                                                        <td class='text-center'>".$dados_aluno['CCOA']."</td>
                                                        <td class='text-center'>".$dados_aluno['PC']."</td>
                                                        <td class='text-center'>".$dados_aluno['Faltas']."</td>
                                                    </tr>
                                                ";

                                            endif;

                                        endforeach;
                                    endif;

                                        "
                                        </tbody>
                                    </table>
                                </div>
                                <div class='espaco20'></div>
                                ";

                        endforeach;
                    endif;


                endforeach;
                endif;
                ?>

                <?php

    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUMA AULA ENCONTRADA.</div>';

    endif;


endif;
