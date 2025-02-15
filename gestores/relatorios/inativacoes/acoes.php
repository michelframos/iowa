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

        echo '<h2 class="titulo">RELATÓRIO DE DESISTÊNCIAS</h2>';

        foreach($turmas as $turma):

            try{
                $unidade = Unidades::find($turma->id_unidade);
            } catch(\ActiveRecord\RecordNotFound $e){
                $unidade = '';
            }

            try{
                $colega = Colegas::find($turma->id_colega);
            } catch( \ActiveRecord\RecordNotFound $e){
                $colega = '';
            }


            if(empty($data_final)):
                $matriculas = V_Matriculas::all(array('conditions' => array('id_turma like ? and id_unidade like ? and id_situacao_aluno_turma = ? and COALESCE(id_empresa, "") like ?', $turma->id, $id_unidade, 2, $id_empresa), 'order' => 'data_desistencia asc'));
            else:
                $matriculas = V_Matriculas::all(array('conditions' => array('id_turma like ? and id_unidade like ? and (data_desistencia between ? and ?) and id_situacao_aluno_turma = ? and COALESCE(id_empresa, "") like ?', $turma->id, $id_unidade, $data_inicial, $data_final, 2, $id_empresa), 'order' => 'data_desistencia asc'));
            endif;

            if(count($matriculas) > 0):

                echo '<h2 class="titulo">Turma: '.$turma->nome.'</h2>';
                echo '<h2 class="titulo">Unidade: '.$unidade->nome_fantasia.'</h2>';
                $total_matriculas += count($matriculas);

            ?>

            <h2 class="titulo">Nº Cancelamentos: <?php echo count($matriculas) ?></h2>

            <div class="table-responsive">
            <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="250">Turma</th>
                <th>Professor</th>
                <th>Data Cancelamento</th>
                <th>Aluno</th>
                <th>Motivo Cancelamento</th>
                <th>Empresa</th>
                <th>Tempo Matriculado</th>
            </tr>
            </thead>
            <tbody>

            <?php

                foreach($matriculas as $matricula):

                    $aluno = Alunos::find($matricula->id_aluno);

                    try{
                        $motivo = Motivos_Desistencia::find($matricula->id_motivo_desistencia);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $motivo = '';
                    }

                    $turma = Turmas::find($matricula->id_turma);
                    $unidade = Unidades::find($turma->id_unidade);

                    try{
                        $periodo = $matricula->data_matricula->diff($matricula->data_desistencia);
                    } catch (Exception $e){

                    }

                    echo '<tr>';
                    echo '<td width="20%" class="text-center">'.$turma->nome.'</td>';
                    echo '<td width="20%" class="text-center">'.$colega->nome.'</td>';
                    echo !empty($matricula->data_desistencia) ? '<td width="20%" class="text-center">'.$matricula->data_desistencia->format('d/m/Y').'</td>' : '';
                    echo '<td width="250" class="text-center">'.$aluno->nome.'</td>';
                    echo '<td width="180" class="text-center">'.$motivo->motivo.'</td>';
                    echo '<td width="180" class="text-center">'.$matricula->nome_fantasia.'</td>';
                    echo '<td class="text-center">'.$periodo->days.' dia(s)</td>';
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
            echo '<h2 class="titulo">TOTAL DE CANCELAMENTOS: '.$total_matriculas.'</h2>';
            echo '<br>';

            $motivos = Motivos_Desistencia::find_all_by_status('a');
            if(!empty($motivos)):
                foreach($motivos as $motivo):

                    //$matriculas = Matriculas::all(array('conditions' => array('id_situacao_aluno_turma = ? and id_motivo_desistencia = ?', 2, $motivo->id)));

                    if(empty($data_final)):
                        $matriculas = V_Matriculas::all(array('conditions' => array('id_turma like ? and id_unidade like ? and id_situacao_aluno_turma = ? and COALESCE(id_empresa, "") like ? and id_motivo_desistencia = ?', $id_turma, $id_unidade, 2, $id_empresa, $motivo->id)));
                    else:
                        $matriculas = V_Matriculas::all(array('conditions' => array('id_turma like ? and id_unidade like ? and (data_desistencia between ? and ?) and id_situacao_aluno_turma = ? and COALESCE(id_empresa, "") like ? and id_motivo_desistencia = ?', $id_turma, $id_unidade, $data_inicial, $data_final, 2, $id_empresa, $motivo->id), 'order' => 'data_desistencia asc'));
                    endif;

                    echo $motivo->motivo.' : '.count($matriculas).'<br>';

                endforeach;
            endif;

        else:
            echo '<div class="text-center fw-bold size-1-5">NENHUMA CENCALAMENTO ENCONTRADO.</div>';
        endif;

    endif;

endif;
