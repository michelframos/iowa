<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

function intervalo( $entrada, $saida ) {
    $entrada = explode( ':', $entrada );
    $saida   = explode( ':', $saida );
    $minutos = ( $saida[0] - $entrada[0] ) * 60 + $saida[1] - $entrada[1];
    if( $minutos < 0 ) $minutos += 24 * 60;
    return sprintf( '%d:%d', $minutos / 60, $minutos % 60 );
}

function converterHora($total_segundos){

    $hora = sprintf("%02s",floor($total_segundos / (60*60)));
    $total_segundos = ($total_segundos % (60*60));

    $minuto = sprintf("%02s",floor ($total_segundos / 60 ));
    $total_segundos = ($total_segundos % 60);

    $hora_minuto = $hora.":".$minuto;
    return $hora_minuto;
}

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

    print_r($dados);

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

    if($dados['nome'] == ''):
        $nome = '%';
    else:
        $nome = $dados['nome'].'%';
    endif;


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

    //$turmas = Turmas::all(array('conditions' => array('id_unidade like ? and id like ?', $id_unidade, $id_turma), 'order' => 'nome asc'));
    $turmas = Turmas::find_by_sql("select turmas.*, turmas.nome as nome_turma, aulas_alunos.*, alunos.*, alunos.nome as nome_aluno from aulas_alunos inner join turmas on aulas_alunos.id_turma = turmas.id inner join alunos on aulas_alunos.id = alunos.id where turmas.id_unidade like '".$id_unidade."' and turmas.id like '".$id_turma."' and alunos.nome like '".$nome."' ");

    if(!empty($turmas)):

        echo '<h2 class="titulo">RELATÓRIO DE FREQUÊNCIA</h2>';

        foreach($turmas as $turma):

            echo $turma->id_turma;

            try{
                $unidade = Unidades::find($turma->id_unidade);
            } catch (\ActiveRecord\RecordNotFound $e){
                $unidade = '';
            }

            if(empty($data_final)):
                $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id_turma, 0)));
            else:
                $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10) and (data between ? and ?)', $turma->id_turma, 0, $data_inicial, $data_final)));
            endif;

            $numero_aulas = count($aulas);
            //$alunos = Alunos_Turmas::find_all_by_id_turma($turma->id);
            //$alunos = Alunos_Turmas::find_by_sql("select alunos_turmas.id as id_aluno_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula, alunos_turmas.id_turma, matriculas.id as id_matricula, matriculas.id_turma, matriculas.id_aluno, matriculas.status, alunos.nome from alunos_turmas inner join matriculas on alunos_turmas.id_aluno = matriculas.id_aluno inner join alunos on matriculas.id_aluno = alunos.id where alunos_turmas.id_turma = ".$turma->id." and matriculas.status = 'a' and alunos.nome like '".$nome."' and matriculas.id_turma = ".$turma->id." group by alunos_turmas.id_aluno order by alunos.nome asc");
            //$alunos = Alunos_Turmas::find_by_sql("select alunos_turmas.id as id_aluno_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula, alunos_turmas.id_turma, matriculas.status, alunos.nome from aulas_turmas INNER JOIN alunos_turmas ON aulas_turmas.id_turma = alunos_turmas.id_turma INNER JOIN matriculas ON matriculas.id = alunos_turmas.id_matricula	INNER JOIN alunos ON matriculas.id_aluno = alunos.id WHERE aulas_turmas.id_turma like '{$id_turma}' AND ( aulas_turmas.id_situacao_aula <> 0 AND aulas_turmas.id_situacao_aula <> 2 AND aulas_turmas.id_situacao_aula <> 3 AND id_situacao_aula <> 10 ) AND aulas_turmas.id_turma like '{$id_turma}' and matriculas.status = 'a' group by alunos_turmas.id_aluno");
            ?>

                <h2 class="titulo">Unidade: <?php echo $unidade->nome_fantasia; ?></h2>
                <h2 class="titulo">Turma: <?php echo $turma->nome_turma; ?></h2>
                <h2 class="titulo">Aulas Dadas: <?php echo $numero_aulas; ?></h2>

                <div class="table-responsive">
                <table class="table pmd-table table-hover">
                <thead>
                <tr>
                    <th width="70%">Aluno</th>
                    <!--<th class="texto-centro" width="10%">Numero de Presenças</th>-->
                    <th class="texto-centro" width="10%">Numero de Faltas</th>
                    <th class="texto-centro" width="10%">Frequencia Estágio</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if(!empty($alunos)):
                foreach($alunos as $aluno):

                    $dados_aluno = Alunos::find($aluno->id_aluno);
                    //$frequencia = Aulas_Alunos::find_all_by_id_turma_and_id_aluno_and_presente($turma->id, $aluno->id_aluno, 's');
                    //$faltas = Aulas_Alunos::find_all_by_id_turma_and_id_aluno_and_presente($turma->id, $aluno->id_aluno, 'n');

                    $frequencia = 0;
                    if(!empty($aulas)):
                        foreach($aulas as $aula):
                            if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $aluno->id_aluno, 's')):
                                $frequencia++;
                            endif;
                        endforeach;
                    endif;

                    $numero_faltas = 0;
                    if(!empty($aulas)):
                        foreach($aulas as $aula):
                            if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $aluno->id_aluno, 'n')):
                                $numero_faltas++;
                            endif;
                        endforeach;
                    endif;

                    if($numero_aulas > 0):
                        $aproveitamento = ($frequencia/$numero_aulas)*100;
                    endif;

                    echo '<tr>';
                    echo '<td class="texto-centro" width="5%">'.$dados_aluno->nome.'</td>';
                    //echo '<td class="texto-centro" width="20%">'.count($frequencia).'</td>';
                    echo '<td class="texto-centro" width="5%">'.$numero_faltas.'</td>';
                    echo '<td class="texto-centro" width="5%">'.number_format($aproveitamento, 2, ',', '').'%</td>';
                    echo '</tr>';

                    echo '<tr>';

                        $numero_faltas = 0;
                        if(!empty($aulas)):
                            foreach($aulas as $aula):
                                if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $aluno->id_aluno, 'n')):
                                    $numero_faltas++;
                                endif;
                            endforeach;
                        endif;

                        if($numero_aulas > 0):

                        echo '<td colspan="3"> Datas das faltas: ';

                        if(!empty($aulas)):
                            foreach($aulas as $aula):
                                if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $aluno->id_aluno, 'n')):
                                    echo $aula->data->format('d/m/Y').' - ';
                                endif;
                            endforeach;
                        endif;

                        echo '</td>';

                        echo '<tr><td colspan="4"></td> </tr>';

                    endif;

                    echo '</tr>';


                endforeach;
                endif;
                ?>
                    </tbody>
                </table>
                </div>
                <div class="espaco20"></div>
                <?php

        endforeach;

    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUMA AULA ENCONTRADA.</div>';

    endif;


endif;
