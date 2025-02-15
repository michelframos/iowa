<?php
    include_once('../../../config.php');
    include_once('../../funcoes_painel.php');
    parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'gerar-relatorio'):

    $professor = $dados['professor'];

    if(!empty($dados['id_unidade'])):
        $id_unidade = $dados['id_unidade'];
    else:
        $id_unidade = '%';
    endif;

    $alunos = Alunos::find_by_sql("select alunos.nome, alunos.celular, alunos.email1, alunos.email2, alunos.celular_responsavel, alunos.email1_responsavel, alunos.email2_responsavel, nomes_produtos.nome_material, matriculas.responsavel_financeiro from turmas inner join colegas on turmas.id_colega = colegas.id inner join unidades on turmas.id_unidade = unidades.id inner join nomes_produtos on turmas.id_produto = nomes_produtos.id inner join matriculas on matriculas.id_turma = turmas.id inner join alunos on matriculas.id_aluno = alunos.id where turmas.id_colega like '{$professor}' and turmas.id_unidade like '{$id_unidade}' and matriculas.status = 'a' ");

    if(!empty($alunos)):

    ?>
        <div class="table-responsive">
        <table class="table pmd-table table-hover">
        <thead>
        <tr>
            <th>Aluno</th>
            <th>Livro</th>
            <th>Celular</th>
            <th>Email(s)</th>
        </tr>
        </thead>
        <tbody>
    <?php

        foreach($alunos as $aluno):

            echo '<tr>';
            echo '<td>'.$aluno->nome.'</td>';
            echo '<td class="text-center">'.$aluno->nome_material.'</td>';

            if($aluno->responsavel_financeiro == 3 || $aluno->responsavel_financeiro == 2):
                echo '<td>'.$aluno->celular.'</td>';
                echo '<td>'.$aluno->email1.'<br>'.$aluno->email2.'</td>';
            elseif($aluno->responsavel_financeiro == 1):
                echo '<td>'.$aluno->celular_responsavel.'</td>';
                echo '<td>'.$aluno->email1_responsavel.'<br>'.$aluno->email2_responsavel.'</td>';
            endif;
            echo '</tr>';

        endforeach;
    ?>
        </tbody>
        </table>
        </div>

    <?php
    endif;

endif;
