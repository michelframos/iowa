<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'gerar-relatorio'):

    if(!empty($dados['turma'])):
        $id_turma = $dados['turma'];
    else:
        $id_turma = '';
    endif;

    $duplicado = $dados['duplicados'];

    if(empty($id_turma)):
        if($duplicado ==  'n'):
            $alunos = Alunos::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
        else:
            $alunos = Alunos::find_by_sql("SELECT DISTINCT id, nome, login FROM alunos where alunos.status = 'a' GROUP BY login HAVING count(login) > 1");
        endif;
    else:
        if($duplicado ==  'n'):
            $turma = Turmas::find($id_turma);
            $alunos = Alunos::find_by_sql("select alunos.*, alunos_turmas.id_aluno from alunos left join alunos_turmas on alunos.id = alunos_turmas.id_aluno where alunos_turmas.id_turma = '{$id_turma}' and alunos.status = 'a' order by alunos.nome asc");
        else:
            $alunos = Alunos::find_by_sql("SELECT DISTINCT id, nome, login FROM alunos where alunos.status = 'a' GROUP BY login HAVING count(login) > 1");
        endif;
    endif;

    if(empty($id_turma)):
        echo '<h2 class="titulo">RELATÓRIO DE LOGINS DOS ALUNOS</h2>';
    else:
        echo '<h2 class="titulo">RELATÓRIO DE LOGINS DOS ALUNOS DA TURMA: '.$turma->nome.'</h2>';
    endif;

    if(!empty($alunos)):

        echo '<h2 class="titulo">ALUNOS</h2>';
        ?>

        <div class="table-responsive">
        <table class="table pmd-table table-hover">
        <thead>
        <tr>
            <th width="150">Aluno</th>
            <th width="150">Login</th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach($alunos as $aluno):

            echo '<tr>';
                echo '<td>'.$aluno->nome.'</td>';
                echo '<td>'.$aluno->login.'</td>';
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
