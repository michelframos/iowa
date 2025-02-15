<?php
    include_once('../../../config.php');
    include_once('../../funcoes_painel.php');
    parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'gerar-relatorio'):

    if(!empty($dados['id_unidade'])):
        $id_unidade = $dados['id_unidade'];
    else:
        $id_unidade = '%';
    endif;

    $mostrar_nomes = $dados['mostrar_nomes'];

    if(!empty($dados['status'])):
        if(is_array($dados['status'])):
            $status_aluno .= '(';
            foreach ($dados['status'] as $status):
                if($status != end($dados['status'])):
                    $status_aluno .= " m.status = '".$status."' or ";
                else:
                    $status_aluno .= " m.status = '".$status."' ";
                endif;
            endforeach;
            $status_aluno .= ')';
        endif;
    endif;

    $unidades = Unidades::all(['conditions' => ['id like ?', $id_unidade], 'order' => 'nome_fantasia asc']);
    if(!empty($unidades)):

        echo '<div class="table-responsive">';
        echo '<table class="table pmd-table table-hover">';

        foreach ($unidades as $unidade):

            echo '<tr>';
            echo '<td colspan="4" class="negrito size-1-5">UNIDADE: '.$unidade->nome_fantasia.'</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>Data da Matrícula</td>';
            echo '<td>Aluno</td>';
            echo '<td>Turma</td>';
            echo '<td>Unidade do Aluno</td>';
            echo '<td>Status da Matrícula</td>';
            echo '</tr>';

            if(!empty($status_aluno)):
                //$alunos = Alunos::find_by_sql("select a.nome, a.data_criacao from alunos a where a.id_unidade = '{$unidade->id}' and ".$status_aluno." order by a.nome asc");
                $alunos = Alunos::find_by_sql("select m.data_matricula, m.status, a.id_unidade, a.nome, t.nome as nome_turma from matriculas m inner join alunos a on m.id_aluno = a.id inner join turmas t on m.id_turma = t.id where t.id_unidade = '{$unidade->id}' and ".$status_aluno." order by a.nome, m.data_matricula asc");
            else:
                $alunos = Alunos::find_by_sql("select m.data_matricula, m.status, a.id_unidade, a.nome, t.nome as nome_turma from matriculas m inner join alunos a on m.id_aluno = a.id inner join turmas t on m.id_turma = t.id where t.id_unidade = '{$unidade->id}' order by a.nome, m.data_matricula asc");
            endif;

            /*Verifica se vai mostrar nomes dos alunos*/
            if($mostrar_nomes == 's'):

                if(!empty($alunos)):
                    foreach ($alunos as $aluno):

                        try{
                            $unidade_aluno = Unidades::find($aluno->id_unidade);
                        } catch (Exception $e){
                            $unidade_aluno = '';
                        }

                        $status_matricula = [
                            'a' => 'Ativa',
                            'i' => 'Inativa',
                            's' => 'StandBy',
                        ];

                        echo '<tr>';
                        echo !empty($aluno->data_matricula) ? '<td>'.implode("/", array_reverse(explode("-", $aluno->data_matricula))).'</td>' : '<td></td>';
                        echo '<td>'.$aluno->nome.'</td>';
                        echo '<td>'.$aluno->nome_turma.'</td>';
                        echo '<td>'.$unidade_aluno->nome_fantasia.'</td>';
                        echo '<td>'.$status_matricula[$aluno->status].'</td>';
                        echo '</tr>';
                    endforeach;
                endif;

            endif;

            echo '<tr>';
            echo '<td colspan="4" class="negrito">TOTAL MATRÍCULAS: '.count($alunos).'</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td colspan="4"></td>';
            echo '</tr>';

            $totalMatriculas += count($alunos);

        endforeach;

            echo '<tr>';
            echo '<td colspan="4" class="negrito size-1-5">TOTAL DE MATRÍCULAS: '.$totalMatriculas.'</td>';
            echo '</tr>';

        echo '</table>';
        echo '</div>';

    endif;

endif;
