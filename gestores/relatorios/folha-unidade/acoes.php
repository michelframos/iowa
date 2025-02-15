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

    if($dados['unidade'] == ''):
        $id_unidade = '%';
    else:
        $id_unidade = $dados['unidade'];
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

    ?>
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th>Unidade</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
    <?php

    $unidades = Unidades::all(['conditions' => ['id like ?', $id_unidade],'order' => 'nome_fantasia asc']);
    if(!empty($unidades)):
        foreach ($unidades as $unidade):

            $total = 0;
            $subTotal = 0;
            $totalUnidade = 0;
            $subTotalHelps = 0;
            $totalGeralHelps = 0;
            $totalGeralTurma = 0;

            $turmas = Turmas::find_by_sql("select * from aulas_turmas INNER JOIN turmas on aulas_turmas.id_turma = turmas.id where turmas.id_unidade like '{$unidade->id}' group by turmas.id");
            if(!empty($turmas)):

                foreach($turmas as $turma):

                    $total = 0;
                    $subTotal = 0;
                    /*
                    $totalUnidade = 0;
                    $subTotalHelps = 0;
                    $totalGeralHelps = 0;
                    */

                    try{
                        $valor_hora_aula = Valores_Hora_Aula::find($turma->id_valor_hora_aula);
                    } catch (Exception $e){
                        $valor_hora_aula = '';
                    }


                    if(!empty($data_inicial)):
                        $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula <> ? and data between ? and ?', $turma->id_turma, 0, $data_inicial, $data_final), 'order' => 'data asc'));
                    else:
                        $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula <> ?', $turma->id_turma, 0), 'order' => 'data asc'));
                    endif;

                    if(!empty($aulas)):
                        foreach ($aulas as $aula):

                            try{
                                $colega = Colegas::find($aula->id_colega);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $colega = '';
                            }

                            if($valor_hora_aula->aplicar_categoria == 's'):
                                $subTotal += $aula->valor_hora_aula*$colega->instrutor_categoria;
                            elseif($valor_hora_aula->aplicar_categoria == 'n'):
                                $subTotal += $aula->valor_hora_aula;
                            endif;

                        endforeach;
                    endif;

                    $totalUnidade += $subTotal;
                    //echo $turma->nome.' : '.$totalUnidade.'<br>';

                endforeach;

            endif;

            $helps = Helps::find_by_sql("select * from helps where id_unidade = '{$unidade->id}' and status = 'a' ");
            if(!empty($helps)):
                foreach ($helps as $help):

                    $totalGeralHelps = 0;
                    $total_horas = 0;

                    if(!empty($data_inicial)):
                        $aulas = Aulas_Help::all(array('conditions' => array('id_help = ? and id_situacao_aula <> ? and data between ? and ?', $help->id, 0, $data_inicial, $data_final), 'order' => 'data asc'));
                    else:
                        $aulas = Aulas_Help::all(array('conditions' => array('id_help = ? and id_situacao_aula <> ?', $help->id, 0), 'order' => 'data asc'));
                    endif;

                    if(!empty($aulas)):
                        foreach ($aulas as $aula):
                            $subTotalHelps += $aula->valor_hora_aula;
                        endforeach;
                    endif;

                    $totalGeralHelps += $subTotalHelps;

                endforeach;

                //echo 'Total Help: '.$totalUnidade.'<br>';

            endif;

            /*
            echo 'Turmas: '.$totalUnidade.'<br>';
            echo 'Helps: '.$totalGeralHelps.'<br>';
            */
            !empty($totalGeralHelps) || ($totalGeralHelps == 0) ? $totalGeralUnidade = $totalUnidade+$totalGeralHelps : $totalGeralUnidade = $totalUnidade;
            $totalGeral += $totalGeralUnidade;

            /*
            echo '<h2 class="titulo">UNIDADE: '.$unidade->nome_fantasia.'</h2>';
            echo 'Unidade: '.$unidade->nome_fantasia.' - Total: '.$totalGeralUnidade.'<br>';
            */

            echo '<tr>';
            echo '<td class="text-center">'.$unidade->nome_fantasia.'</td>';
            echo '<td class="text-center">R$ '.number_format($totalGeralUnidade, 2, ',', '.').'</td>';
            echo '</tr>';

        endforeach;
    endif;

        echo '<tr>';
        echo '<td class="bold size-1-5">TOTAL GERAL</td>';
        echo '<td class="bold size-1-5">R$ '.number_format($totalGeral, 2,',','.').'</td>';
        echo '</tr>';


    ?>
                </tbody>
            </table>
        </div>
        <div class="espaco20"></div>
    <?php

endif;
