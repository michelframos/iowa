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

    if($dados['situacao'] == ''):
        $id_situacao = '%';
    else:
        $id_situacao = $dados['situacao'];
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

    $turmas = Turmas::all(array('conditions' => array('id_unidade like ? and id like ?', $id_unidade, $id_turma), 'order' => 'nome asc'));

    if(!empty($turmas)):

        echo '<h2 class="titulo">RELATÓRIO DE TURMAS</h2>';

        foreach($turmas as $turma):

            try{
                $unidade = Unidades::find($turma->id_unidade);
            } catch (\ActiveRecord\RecordNotFound $e){
                $unidade = '';
            }

            $situacoes = Situacao_Aulas::all(array('conditions' => array('id like ?', $id_situacao)));
            if(!empty($situacoes)):
                ?>

                <h2 class="titulo">Unidade: <?php echo $unidade->nome_fantasia; ?></h2>
                <h2 class="titulo">Turma: <?php echo $turma->nome; ?></h2>

                <div class="table-responsive">
                <table class="table pmd-table table-hover">
                <thead>
                <tr>
                    <th width="150">Data da Aula</th>
                    <th>Conteúdo</th>
                    <th>Duração</th>
                    <th>Situação</th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach($situacoes as $situacao):

                    /*Listando turmas*/
                    if(!empty($data_inicial)):
                        $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula like ? and id_situacao_aula <> ? and data between ? and ?', $turma->id, $situacao->id, 0, $data_inicial, $data_final), 'order' => 'data asc'));
                    else:
                        $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula like ? and id_situacao_aula <> ?', $turma->id, $situacao->id, 0), 'order' => 'data asc'));
                    endif;

                    $total_horas = 0;

                    if(!empty($aulas)):

                        echo '<tr>';
                        echo '<td colspan="4" class="fw-bold">'.$situacao->situacao.' ['.$situacao->descricao.']</td>';
                        echo '</tr>';

                        foreach($aulas as $aula):

                            echo '<tr>';
                            echo '<td class="text-center" width="5%">'.$aula->data->format('d/m/Y').'</td>';
                            echo '<td class="text-center" width="20%">'.$aula->conteudo_dado.'</td>';
                            echo '<td class="text-center" width="5%">'.intervalo($aula->hora_inicio, $aula->hora_termino).'</td>';
                            echo '<td class="text-center" width="5%">'.$situacao->situacao.'</td>';
                            echo '</tr>';

                            $total_horas += strtotime($aula->hora_termino.':00') - strtotime($aula->hora_inicio.':00');

                        endforeach;

                        echo '<tr>';
                        echo '<td colspan="3" class="fw-bold">Total de Horas</td>';
                        echo '<td class="fw-bold">'.converterHora($total_horas).'</td>';
                        echo '</tr>';

                    endif;
                endforeach;
                ?>
                    </tbody>
                </table>
                </div>
                <div class="espaco20"></div>
                <?php
            endif;
        endforeach;

    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUMA AULA ENCONTRADA.</div>';

    endif;


endif;
