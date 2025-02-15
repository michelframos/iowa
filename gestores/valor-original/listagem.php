<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">
    <div class="table-responsive">

        <form action="" method="post" name="formValores" id="formValores">

        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data Matrícula</th>
                <th>Aluno</th>
                <th>Turma</th>
                <th>Unidade</th>
                <th>Situação</th>
                <th>Valor Original</th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(isset($_POST['valor_pesquisa'])):
                if(!empty($_POST['valor_pesquisa'])):
                    $valor_pesquisa = filter_input(INPUT_POST, 'valor_pesquisa', FILTER_SANITIZE_STRING).'%';
                else:
                    $valor_pesquisa = '%';
                endif;

                if(!empty($_POST['situacao'])):
                    $situacao = filter_input(INPUT_POST, 'situacao', FILTER_SANITIZE_STRING);
                else:
                    $situacao = '%';
                endif;

                if(!empty($_POST['unidade'])):
                    $unidade = filter_input(INPUT_POST, 'unidade', FILTER_SANITIZE_STRING);
                else:
                    $unidade = '%';
                endif;

                if(!empty($_POST['turma'])):
                    $turma = filter_input(INPUT_POST, 'turma', FILTER_SANITIZE_STRING);
                else:
                    $turma = '%';
                endif;

                $registros = Matriculas::find_by_sql('
                    select 
                    matriculas.*,
                    turmas.id as id_tabela_turma,
                    turmas.id_unidade,
                    alunos.nome
                    from matriculas 
                    inner join alunos on matriculas.id_aluno = alunos.id 
                    inner join turmas on matriculas.id_turma
                    where coalesce(matriculas.status, "") like "'.$situacao.'" and coalesce(turmas.id_unidade, "") like "'.$unidade.'" and coalesce(id_turma, "") like "'.$turma.'" and alunos.nome like "'.$valor_pesquisa.'" group by matriculas.id order by alunos.nome
                ');

                if(!empty($registros)):
                    foreach($registros as $registro):

                        if(!empty($registro->id_unidade)):
                            $unidade = Unidades::find($registro->id_unidade);
                        endif;

                        if(!empty($registro->id_turma)):
                            try{
                                $turma = Turmas::find($registro->id_turma);
                            } catch (Exception $e){
                                $turma = '';
                            }

                        endif;

                        echo '<tr>';
                        echo '<td data-title="Data Cadastro">'.$registro->data_matricula->format("d/m/Y").'</td>';
                        echo '<td data-title="Aluno">'.$registro->nome.'</td>';
                        echo '<td data-title="Turma">'.$turma->nome.'</td>';
                        echo '<td data-title="Unidade">'.$unidade->nome_fantasia.'</td>';

                        echo '<td>';

                        switch($registro->status)
                        {
                            case 'a': $status = 'Ativa'; break;
                            case 'i': $status = 'Inativa'; break;
                            case 's': $status = 'Stand By'; break;
                            case 't': $status = 'Transferido'; break;
                        }

                        echo $status;

                        echo '</td>';

                        echo '<td data-title="Valor Original" width="150">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <input type="text" name="valor_parcela['.$registro->id.']" value="'.number_format($registro->valor_parcela, 2, ',', '.').'" class="form-control text-center valor"><span class="pmd-textfield-focused"></span>
                                </div>
                              </td>';

                        echo '</tr>';
                    endforeach;
                endif;

            else:

                echo '<div class="titulo fw-bold size-1-5">Selecione os filtros desejados e clique em Pesquisar</div>';

            endif;
            ?>

            </tbody>
        </table>

            <?php
            if(!empty($registros)):
            ?>
                <button type="button" name="salvar" id="salvar" value="Pesquisar" class="btn btn-info pmd-btn-raised">Salvar</button>
            <?php
            endif;
            ?>

        </form>

    </div>
</div>
