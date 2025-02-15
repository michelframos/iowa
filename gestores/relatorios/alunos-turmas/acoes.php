<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'busca-turmas'):

    $turmas = Turmas::all(array('conditions' => array('id_colega = ? and status = ? and nome <> ? and coalesce(id_unidade, "") <> ?', $dados['professor'], 'a', 'Nova Turma', ''), 'order' => 'nome asc'));

    if(!empty($turmas)):
        echo '<option value="%">Todas</option>';
        foreach($turmas as $turma):
            echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
        endforeach;
    else:
        echo '<option value="%">Selecione uma Turma</option>';
    endif;

endif;

if($dados['acao'] == 'gerar-relatorio'):

    if($dados['pesquisar_por'] == 'turma'):

        if($dados['turma'] == ''):
            $id_turma = '%';
        else:
            $id_turma = $dados['turma'];
        endif;

        $turmas = Turmas::all(array('conditions' => array('id like ? and status = ? and nome <> ? and coalesce(id_unidade, "") <> ?', $id_turma, 'a', 'Nova Turma', ''), 'order' => 'nome asc'));

    else:

        if($dados['professor'] == ''):
            $id_colega = '%';
        else:
            $id_colega = $dados['professor'];
        endif;

        if($dados['turma_professor'] == ''):
            $id_turma = '%';
        else:
            $id_turma = $dados['turma_professor'];
        endif;

        $turmas = Turmas::all(array('conditions' => array('id like ? and id_colega like ? and status = ? and nome <> ? and coalesce(id_unidade, "") <> ?', $id_turma, $id_colega, 'a', 'Nova Turma', ''), 'order' => 'nome asc'));

    endif;



    if(!empty($turmas)):

        echo '<h2 class="titulo">RELATÓRIO DE TURMAS</h2>';

        echo '
            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Unidade</th>
                        <th>Professor</th>
                        <th class="texto-centro">Qtde. Alunos Ativos</th>
                    </tr>
                    </thead>
                    <tbody>
        ';

        foreach($turmas as $turma):

            //echo $turma->status.'<br>';

            try{
                $unidade = Unidades::find($turma->id_unidade);
            } catch (\ActiveRecord\RecordNotFound $e){
                $unidade = '';
            }
                    $alunos_turmas = Alunos_Turmas::find_by_sql('select alunos_turmas.*, matriculas.*, alunos.nome  from alunos_turmas inner join matriculas on alunos_turmas.id_matricula = matriculas.id inner join alunos on alunos_turmas.id_aluno = alunos.id where alunos_turmas.id_turma = "'.$turma->id.'" and matriculas.status = "a" order by alunos.nome');
                    $professor = Colegas::find_by_id($turma->id_colega);
                    //$status_matricula = ['a' => 'Ativo', 'i' => 'Inativo', 't' => 'Trnaferido'];
                    //$alunos_turmas = Matriculas::find_by_sql("select m.id, m.id_turma, m.id_aluno, m.data_criacao, m.status, t.id as id_tabela_turma, t.nome, a.nome as nome_aluno from matriculas m inner join alunos a on m.id_aluno = a.id inner join turmas t on m.id_turma = t.id where id_turma = '{$turma->id}' ");
                    echo '
                        <tr>
                            <td>'.$turma->nome.'</td>
                            <td>'.$unidade->nome_fantasia.'</td>
                            <td>'.$professor->nome.'</td>
                            <td class="texto-centro">'.(count($alunos_turmas)).'</td>
                        </tr>
                    ';
        endforeach;

        echo '
                    </tbody>
                </table>
            </div>
            <div class="espaco20"></div>
        ';

    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUMA INFORMAÇÃO ENCONTRADA.</div>';

    endif;


endif;
