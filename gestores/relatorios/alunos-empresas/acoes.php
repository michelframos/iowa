<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'gerar-relatorio'):

    $id_unidade = filtra_int($dados['id_unidade']);
    $id_unidade = !empty($id_unidade) ? $id_unidade : '%';

    $status = filtra_string($dados['status']);
    $status = !empty($status) ? $status : '%';

    $alunos = Alunos::all(['conditions' => ['id_unidade like ? and status like ?', $id_unidade, $status], 'order' => 'nome asc']);


    if(!empty($alunos)):

        echo '<h2 class="titulo">RELATÓRIO DE ALUNOS / EMPRESAS</h2>';

        echo '
            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Empresa</th>
                        <th>Grupo</th>
                        <th>Unidade</th>
                        <th>Situação</th>
                    </tr>
                    </thead>
                    <tbody>
        ';

        foreach($alunos as $aluno):

            $unidade = Unidades::find_by_id($aluno->id_unidade);
            $matriculas = V_Matriculas::all(['conditions' => ['id_aluno = ? and status = ?', $aluno->id, 'a']]);
            $turmas = '';

            if(!empty($matriculas)):
                foreach ($matriculas as $matricula):
                    $turmas .= '<div>'.(Turmas::find_by_id($matricula->id_turma)->nome).'</div>';
                endforeach;
            endif;

            echo '
                    <tr>
                        <td>'.$aluno->nome.'</td>
                        <td>'.$aluno->nome_empresa.'</td>
                        <td>'.$turmas.'</td>
                        <td class="texto-centro">'.$unidade->nome_fantasia.'</td>
                        <td class="texto-centro">'.($aluno->status == 'a' ? 'Ativo' : 'Inativo').'</td>
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
