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
                    $status_aluno .= " a.status = '".$status."' or ";
                else:
                    $status_aluno .= " a.status = '".$status."' ";
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
            echo '<td colspan="2" class="negrito size-1-5">UNIDADE: '.$unidade->nome_fantasia.'</td>';
            echo '</tr>';

            if(!empty($status_aluno)):
                $alunos = Alunos::find_by_sql("select a.nome, a.data_criacao from alunos a where a.id_unidade = '{$unidade->id}' and ".$status_aluno." order by a.nome asc");
            else:
                $alunos = Alunos::find_by_sql("select a.nome, a.data_criacao from alunos a where a.id_unidade = '{$unidade->id}' order by a.nome asc");
            endif;

            /*Verifica se vai mostrar nomes dos alunos*/
            if($mostrar_nomes == 's'):

                if(!empty($alunos)):
                    foreach ($alunos as $aluno):
                        echo '<tr>';
                        echo '<td>'.$aluno->nome.'</td>';
                        echo !empty($aluno->data_criacao) ? '<td>'.$aluno->data_criacao->format('d/m/Y').'</td>' : '<td></td>';
                        echo '</tr>';
                    endforeach;
                endif;

            endif;

            echo '<tr>';
            echo '<td colspan="2" class="negrito">TOTAL ALUNOS: '.count($alunos).'</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td colspan="2"></td>';
            echo '</tr>';

            $totalAlunos += count($alunos);

        endforeach;

            echo '<tr>';
            echo '<td colspan="4" class="negrito size-1-5">TOTAL DE MATRÍCULAS: '.$totalAlunos.'</td>';
            echo '</tr>';

        echo '</table>';
        echo '</div>';

    endif;

endif;
