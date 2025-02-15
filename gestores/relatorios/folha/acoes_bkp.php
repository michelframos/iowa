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

    $turmas = Turmas::all(array('conditions' => array('id_colega = ?', $dados['professor']), 'order' => 'nome asc'));

    if(!empty($turmas)):
        echo '<option value="">Todas</option>';
        foreach($turmas as $turma):
            echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
        endforeach;
    endif;

endif;



if($dados['acao'] == 'busca-alunos'):

    $id_turma = $dados['turma'];
    $alunos = Alunos::find_by_sql("select * from alunos_turmas inner join alunos on alunos_turmas.id_aluno = alunos.id where alunos_turmas.id_turma = '{$id_turma}' order by nome asc");
    if(!empty($alunos)):
        foreach($alunos as $aluno):
            echo '<option value="'.$aluno->id_aluno.'">'.$aluno->nome.'</option>';
        endforeach;
    endif;

endif;


if($dados['acao'] == 'gerar-relatorio'):

    if($dados['turma'] == ''):
        $id_turma = '%';
    else:
        $id_turma = $dados['turma'];
    endif;

    if($dados['aluno'] == ''):
        $id_aluno = '%';
    else:
        $id_aluno = $dados['aluno'];
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

    $turmas = Turmas::find_by_sql("select * from aulas_turmas INNER JOIN turmas on aulas_turmas.id_turma = turmas.id where turmas.id like '{$id_turma}' and aulas_turmas.id_colega like {$dados['professor']} /*and turmas.status = 'a'*/ group by turmas.nome order by turmas.nome asc");

    if(!empty($turmas)):

        $totalGeralTurmas = 0;
        $total_horas = 0;

        $professor = Colegas::find($dados['professor']);

        echo '<h2 class="titulo">FOLHA DE PAGAMENTO</h2>';
        echo '<h2 class="titulo">Professor: '.$professor->nome.'</h2>';

        foreach($turmas as $turma):

            $valor_hora_aula = Valores_Hora_Aula::find($turma->id_valor_hora_aula);

            if(!empty($data_inicial)):
                $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_colega = ? and id_situacao_aula <> ? and data between ? and ?', $turma->id_turma, $dados['professor'], 0, $data_inicial, $data_final), 'order' => 'data asc'));
            else:
                $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_colega = ? and id_situacao_aula <> ?', $turma->id_turma, $dados['professor'], 0), 'order' => 'data asc'));
            endif;

            $total = 0;
            $subTotal = 0;

            ?>

            <h2 class="titulo">Turma: <?php echo $turma->nome; ?> | Hora/Aula: R$ <?php echo number_format($valor_hora_aula->valor, 2, ',', '.'); ?></h2>

            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th width="150">Data da Aula</th>
                        <th>Conteúdo</th>
                        <th>Duração</th>
                        <th>Situação</th>
                        <th>Valor</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(!empty($aulas)):

                        $total_horas_turma = 0;

                        foreach($aulas as $aula):
                            $situacao = Situacao_Aulas::find($aula->id_situacao_aula);

                            echo '<tr>';
                                echo '<td class="text-center" width="5%">'.$aula->data->format('d/m/Y').'</td>';
                                echo '<td class="text-center" width="20%">'.$aula->conteudo_dado.'</td>';
                                echo '<td class="text-center" width="5%">'.intervalo($aula->hora_inicio, $aula->hora_termino).'</td>';
                                echo '<td class="text-center" width="5%">'.$situacao->situacao.'</td>';

                                /*
                                if($turma->id_colega == $aula->id_colega):
                                    echo '<td class="text-center" width="5%">R$ '.number_format($aula->valor_hora_aula, 2, ',', '.').'</td>';
                                else:
                                    echo '<td class="text-center" width="5%">R$ 0,00</td>';
                                endif;
                                */

                                echo '<td class="text-center" width="5%">R$ '.number_format($aula->valor_hora_aula, 2, ',', '.').'</td>';
                            echo '</tr>';

                            /*
                            if($turma->id_colega == $aula->id_colega):
                                $subTotal += $aula->valor_hora_aula;
                            endif;
                            */

                            $situacao_aula = Situacao_Aulas::find($aula->id_situacao_aula);

                            if($situacao_aula->contar_hora_folha_pagamento == 's'):
                                $total_horas_turma += strtotime($aula->hora_termino.':00') - strtotime($aula->hora_inicio.':00');
                                $total_horas += strtotime($aula->hora_termino.':00') - strtotime($aula->hora_inicio.':00');
                            endif;
                                $subTotal += $aula->valor_hora_aula;
                        endforeach;

                        echo '<tr>';
                        echo '<td colspan="2">Sub Total: </td>';
                        echo '<td colspan="2">Total De Horas: '.($total_horas_turma/60/60).'</td>';
                        echo '<td>R$ '.number_format($subTotal, 2, ',', '.').'</td>';
                        echo '</tr>';

                        $totalGeralTurmas += $subTotal;
                        $subTotal = 0;

                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
            <div class="espaco20"></div>
            <?php

        endforeach;

        echo '<div class="size-1-5 fw-bold text-right">Total De Horas: '.($total_horas/60/60).'</div>';
        echo '<div class="size-1-5 fw-bold text-right">Total: R$'.number_format($totalGeralTurmas, 2, ',', '.').'</div>';

    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUMA AULA ENCONTRADA.</div>';

    endif;


    /*----------------------------------------------------------------------------------------------------------------*/
    /*HELPS*/

    $helps = Helps::find_by_sql("select * from helps where id_colega like '{$dados['professor']}' and id_aluno like '{$id_aluno}' and status = 'a'");

    if(!empty($helps)):

        $totalGeralHelps = 0;
        $total_horas = 0;

        $professor = Colegas::find($dados['professor']);


        echo '<h2 class="titulo">FOLHA DE PAGAMENTO</h2>';
        echo '<h2 class="titulo">Professor: '.$professor->nome.'</h2>';

        foreach($helps as $help):

            if(!empty($data_inicial)):
                $aulas = Aulas_Help::all(array('conditions' => array('id_help = ? and id_colega = ? and id_situacao_aula <> ? and data between ? and ?', $help->id, $dados['professor'], 0, $data_inicial, $data_final), 'order' => 'data asc'));
            else:
                $aulas = Aulas_Help::all(array('conditions' => array('id_help = ? and id_colega = ? and id_situacao_aula <> ?', $help->id, $dados['professor'], 0), 'order' => 'data asc'));
            endif;

            $total = 0;
            $subTotal = 0;

            ?>

            <h2 class="titulo">HELP: Instrutor <?php echo $professor->nome; ?></h2>

            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th width="150">Data da Aula</th>
                        <th>Aluno</th>
                        <th>Conteúdo</th>
                        <th>Duração</th>
                        <th>Situação</th>
                        <th>Valor</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(!empty($aulas)):

                        foreach($aulas as $aula):
                            $situacao = Situacao_Aulas::find($aula->id_situacao_aula);

                            try{
                                $aluno = Alunos::find($help->id_aluno);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $aluno = '';
                            }

                            echo '<tr>';
                            echo '<td class="text-center" width="5%">'.$aula->data->format('d/m/Y').'</td>';
                            echo '<td class="text-center" width="20%">'.$aluno->nome.'</td>';
                            echo '<td class="text-center" width="20%">'.$aula->conteudo_dado.'</td>';
                            echo '<td class="text-center" width="5%">'.intervalo($aula->hora_inicio, $aula->hora_termino).'</td>';
                            echo '<td class="text-center" width="5%">'.$situacao->situacao.'</td>';

                            /*
                            if($turma->id_colega == $aula->id_colega):
                                echo '<td class="text-center" width="5%">R$ '.number_format($aula->valor_hora_aula, 2, ',', '.').'</td>';
                            else:
                                echo '<td class="text-center" width="5%">R$ 0,00</td>';
                            endif;
                            */

                            echo '<td class="text-center" width="5%">R$ '.number_format($aula->valor_hora_aula, 2, ',', '.').'</td>';
                            echo '</tr>';

                            /*
                            if($turma->id_colega == $aula->id_colega):
                                $subTotal += $aula->valor_hora_aula;
                            endif;
                            */

                            $situacao_aula = Situacao_Aulas::find($aula->id_situacao_aula);

                            if($situacao_aula->contar_hora_folha_pagamento == 's'):
                                $total_horas += strtotime($aula->hora_termino.':00') - strtotime($aula->hora_inicio.':00');
                            endif;
                            $subTotal += $aula->valor_hora_aula;
                        endforeach;

                        echo '<tr>';
                        echo '<td colspan="5">Sub Total:</td>';
                        echo '<td>R$ '.number_format($subTotal, 2, ',', '.').'</td>';
                        echo '</tr>';

                        $totalGeralHelps += $subTotal;
                        $subTotal = 0;

                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
            <div class="espaco20"></div>
            <?php

        endforeach;

        echo '<div class="size-1-5 fw-bold text-right">Total De Horas: '.($total_horas/60/60).'</div>';
        echo '<div class="size-1-5 fw-bold text-right">Total: R$'.number_format($totalGeralHelps, 2, ',', '.').'</div>';

    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUM HELP ENCONTRADO.</div>';

    endif;

    echo '<br>';
    echo '<div class="size-1-5 fw-bold text-right">Total Geral: R$'.number_format($totalGeralHelps+$totalGeralTurmas, 2, ',', '.').'</div>';


endif;
