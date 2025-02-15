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

    if($dados['empresa'] == ''):
        $id_empresa = '%';
    else:
        $id_empresa = $dados['empresa'];
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

    $total_matriculas = 0;

    $turmas = Turmas::all(array('conditions' => array('id like ?', $id_turma), 'order' => 'id_unidade, nome asc'));

    if(!empty($turmas)):

        echo '<h2 class="titulo">RELATÓRIO DE MATRÍCULAS</h2>';

        foreach($turmas as $turma):

            try{
                $unidade = Unidades::find($turma->id_unidade);
            } catch(\ActiveRecord\RecordNotFound $e){
                $unidade = '';
            }

            if(empty($data_final)):
                //$matriculas = Matriculas::find_by_sql("select matriculas.*, turmas.id_unidade AS id_unidade, empresas.id AS id_empresa, empresas.nome_fantasia AS nome_fantasia from matriculas inner join turmas on matriculas.id_turma = turmas.id left join empresas on matriculas.id_empresa_financeiro = empresas.id where matriculas.id_turma not in (select transferencias.id_turma_destino from transferencias) and matriculas.id_turma like '{$turma->id}' and turmas.id_unidade like '{$id_unidade}' and matriculas.status = 'a' and COALESCE(matriculas.id_empresa_pedagogico, '') like '{$id_empresa}' ");
                $matriculas = Matriculas::find_by_sql("
                SELECT
                matriculas.*,
                turmas.id_unidade AS id_unidade,
                empresas.id AS id_empresa,
                empresas.nome_fantasia AS nome_fantasia 
                FROM
                matriculas
                INNER JOIN turmas ON matriculas.id_turma = turmas.id
                LEFT JOIN empresas ON matriculas.id_empresa_financeiro = empresas.id 
                WHERE
                COALESCE(matriculas.id_turma, '') LIKE '{$turma->id}' 
                AND COALESCE(turmas.id_unidade, '') LIKE '{$id_unidade}' 
                AND COALESCE(matriculas.nova_matricula, '') = 's'
                AND COALESCE(matriculas.STATUS, '') = 'a' 
                AND COALESCE(matriculas.id_empresa_pedagogico, '') LIKE '{$id_empresa}' ");

            else:

                $matriculas = Matriculas::find_by_sql("
                    SELECT
                    matriculas.*,
                    turmas.id_unidade AS id_unidade,
                    empresas.id AS id_empresa,
                    empresas.nome_fantasia AS nome_fantasia 
                    FROM
                    matriculas
                    INNER JOIN turmas ON matriculas.id_turma = turmas.id
                    LEFT JOIN empresas ON matriculas.id_empresa_financeiro = empresas.id 
                    WHERE
                    COALESCE(matriculas.id_turma, '') LIKE '{$turma->id}' 
                    AND COALESCE(turmas.id_unidade, '') LIKE '{$id_unidade}' 
                    AND COALESCE(matriculas.nova_matricula, '') = 's'
                    /*AND COALESCE(matriculas.STATUS, '') = 'a'*/ 
                    AND COALESCE(matriculas.id_empresa_pedagogico, '' ) LIKE '{$id_empresa}' 
                    AND matriculas.data_matricula BETWEEN '{$data_inicial}' and '{$data_final}' ");
            endif;

            if(!empty($matriculas)):

                echo '<h2 class="titulo">Turma: '.$turma->nome.'</h2>';
                echo '<h2 class="titulo">Unidade: '.$unidade->nome_fantasia.'</h2>';

                $total_matriculas += count($matriculas);

                ?>

                <h2 class="titulo">Nº Matrículas: <?php echo count($matriculas) ?></h2>

                <div class="table-responsive">
                    <table class="table pmd-table table-hover">
                        <thead>
                        <tr>
                            <th width="80">Data Matrícula</th>
                            <th>Aluno</th>
                            <th>Unidade</th>
                            <th>Empresa</th>
                            <th>Estágio</th>
                            <th>Valor Mensalidade</th>
                            <th>Mídia</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php

                        foreach($matriculas as $matricula):

                            try{
                                $aluno = Alunos::find($matricula->id_aluno);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $aluno = '';
                            }

                            try{
                                $origem = Origem_Aluno::find($aluno->id_origem);
                            } catch (\ActiveRecord\RecordNotFound $e){
                                $origem = '';
                            }

                            $turma = Turmas::find($matricula->id_turma);
                            $unidade = Unidades::find($turma->id_unidade);


                            echo '<tr>';
                            echo '<td width="80" class="text-center">'.$matricula->data_matricula->format('d/m/Y').'</td>';
                            echo '<td width="250" class="text-center">'.$aluno->nome.'</td>';
                            echo '<td width="180" class="text-center">'.$unidade->nome_fantasia.'</td>';
                            echo '<td width="180" class="text-center">'.$matricula->nome_fantasia.'</td>';
                            echo '<td width="20%" class="text-center">'.$turma->nome.'</td>';
                            echo '<td width="10%" class="text-center">R$ '.number_format($matricula->valor_parcela, 2, ',', '.').'</td>';
                            echo !empty($origem->origem) ? '<td width="10%" class="text-center">'.$origem->origem.'</td>' : '<td></td>';
                            echo '</tr>';

                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="espaco20"></div>
                <?php

            endif;

        endforeach;

        if($total_matriculas > 0):
            echo '<h2 class="titulo">TOTAL DE MATRÍCULAS: '.$total_matriculas.'</h2>';
        else:
            echo '<div class="text-center fw-bold size-1-5">NENHUMA MATRÍCULA ENCONTRADA.</div>';
        endif;

    endif;

endif;
