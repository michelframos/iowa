<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'gerar-relatorio'):

    if($dados['turma'] == ''):
        $id_turma = '%';
    else:
        $id_turma = $dados['turma'];
    endif;

    $turmas = Turmas::all(array('conditions' => array('id like ?', $id_turma), 'order' => 'nome asc'));

    if(!empty($turmas)):

        echo '<h2 class="titulo">RELATÓRIO DE TURMAS</h2>';

        foreach($turmas as $turma):

            try{
                $unidade = Unidades::find($turma->id_unidade);
            } catch (\ActiveRecord\RecordNotFound $e){
                $unidade = '';
            }
            ?>

            <h2 class="titulo">Unidade: <?php echo $unidade->nome_fantasia; ?></h2>
            <h2 class="titulo">Turma: <?php echo $turma->nome; ?></h2>

            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th width="150">Data Matrícula</th>
                        <th>Aluno</th>
                        <th>Situação</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $status_matricula = ['a' => 'Ativo', 'i' => 'Inativo', 't' => 'Trnaferido'];
                    $alunos_turmas = Matriculas::find_by_sql("select m.id, m.id_turma, m.id_aluno, m.data_criacao, m.status, t.id as id_tabela_turma, t.nome, a.nome as nome_aluno from matriculas m inner join alunos a on m.id_aluno = a.id inner join turmas t on m.id_turma = t.id where id_turma = '{$turma->id}' ");
                    if(!empty($alunos_turmas)):
                        foreach ($alunos_turmas as $aluno_turma):
                            echo '<tr>';
                            echo '<td>'.$aluno_turma->data_criacao->format('d/m/Y').'</td>';
                            echo '<td>'.$aluno_turma->nome_aluno.'</td>';
                            echo '<td>'.$status_matricula[$aluno_turma->status].'</td>';
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
