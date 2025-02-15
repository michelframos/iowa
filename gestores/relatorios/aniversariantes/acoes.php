<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'gerar-relatorio'):

    if(!empty($dados['unidade'])):
        $unidade = $dados['unidade'];
    else:
        $unidade = '%';
    endif;

    if(!empty($dados['professor'])):
        $professor = $dados['professor'];
    else:
        $professor = '"%"';
    endif;

    if(!empty($dados['mes'])):
        $mes = $dados['mes'];
    else:
        $mes = '"%"';
    endif;

    $meses = [
      1 => 'Janeiro',
      2 => 'Fevereiro',
      3 => 'Março',
      4 => 'Abril',
      5 => 'Maio',
      6 => 'Junho',
      7 => 'Julho',
      8 => 'Agosto',
      9 => 'Setembro',
      10 => 'Outubro',
      11 => 'Novembro',
      12 => 'Dezembro',
    ];

    ($professor != '"%"') ? $instrutor = Colegas::find($professor) : $instrutor = '';
    $alunos = Turmas::find_by_sql("select turmas.nome, alunos.nome as nome_aluno, alunos.data_nascimento, alunos.status from turmas inner join alunos_turmas on turmas.id = alunos_turmas.id_turma inner join alunos on alunos_turmas.id_aluno = alunos.id where turmas.id_unidade = {$unidade} and turmas.id_colega like ".$professor." and alunos.status = 'a' and MONTH(alunos.data_nascimento) like ".$mes." group by alunos.id order by month(alunos.data_nascimento), day(alunos.data_nascimento)  asc");
    $colegas = Colegas::all(array('conditions' => array('id_unidade = ? and month(data_nascimento) like '.$mes.' and data_nascimento is not null', $unidade), 'order' => 'month(data_nascimento), day(data_nascimento)'));

    echo '<h2 class="titulo">RELATÓRIO DE ANIVERSARIANTES DO MES DE '.strtoupper($meses[$mes]).'</h2>';
    if(!empty($alunos)):

        echo '<h2 class="titulo">ALUNOS</h2>';
        echo '<h2 class="titulo">Instrutor: '.$instrutor->nome.'</h2>';
        ?>

        <div class="table-responsive">
        <table class="table pmd-table table-hover">
        <thead>
        <tr>
            <th width="150">Data do Aniversário</th>
            <th>Aluno</th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach($alunos as $aluno):

            $data_nascimento = implode('/', array_reverse(explode('-', $aluno->data_nascimento)));

            echo '<tr>';
                echo !empty($aluno->data_nascimento) ? '<td>'.substr($data_nascimento, 0, -5).'</td>' : '<td></td>';
                echo '<td>'.$aluno->nome_aluno.'</td>';
            echo '</tr>';

        endforeach;
        ?>
            </tbody>
        </table>
        </div>
        <div class="espaco20"></div>

        <?php
    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUM ALUNO ANIVERSARIANTE ENCONTRADO.</div>';

    endif;


    /*------------------------------------------------------------------------------------------------*/
    /*------------------------------------------------------------------------------------------------*/


    if(!empty($colegas)):

        echo '<h2 class="titulo">COLEGAS IOWA</h2>';
        ?>

        <div class="table-responsive">
        <table class="table pmd-table table-hover">
        <thead>
        <tr>
            <th width="150">Data do Aniversário</th>
            <th>Aluno</th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach($colegas as $colega):

            //$data_nascimento = implode('/', array_reverse(explode('-', $aluno->data_nascimento)));

            echo '<tr>';
                echo !empty($colega->data_nascimento) ? '<td>'.$colega->data_nascimento->format('d/m').'</td>' : '<td></td>';
                echo '<td>'.$colega->nome.'</td>';
            echo '</tr>';

        endforeach;
        ?>
            </tbody>
        </table>
        </div>
        <div class="espaco20"></div>

        <?php
    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUM COLEGA IOWA ANIVERSARIANTE ENCONTRADO.</div>';

    endif;


endif;
