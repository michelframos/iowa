<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">

    <?php
    if(isset($_POST['mes'])):
    ?>
    <form action="" name="formRenovacao" id="formRenovacao" method="post">

        <input type="hidden" value="<?php echo $_POST['id_motivo'] ?>" name="id_motivo_renovacao"/>

        <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
            <label for="regular1" class="control-label">Índice de Reajuste %</label>
            <input type="text" name="reajuste" id="reajuste" value="0" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
            <label for="regular1" class="control-label">Nº de Parcelas a gerar</label>
            <input type="text" name="numero_parcelas" id="numero_parcelas" value="12" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <button type="button" name="renovar" id="renovar" value="Renovar" class="btn btn-danger pmd-btn-raised">Renovar Contrato(s)</button>
        <div class="espaco20"></div>

    </form>

    <?php
    endif;
    ?>

    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th>
                    <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                        <input type="checkbox" checked value="" id="selecionar-todos">
                        <span></span>
                    </label>
                </th>
                <th width="150">Ultima Parcela</th>
                <th>Valor Original</th>
                <th>Aluno</th>
                <th>Referente</th>
                <th>Turma</th>
                <th>Unidade</th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(isset($_POST['mes'])):

                if(!empty($_POST['mes'])):
                    $mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
                endif;

                if(!empty($_POST['ano'])):
                    $ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_STRING);
                endif;

                if(!empty($_POST['id_motivo'])):
                    $motivo = 'id_motivo = '.filter_input(INPUT_POST, 'id_motivo', FILTER_SANITIZE_STRING);
                else:
                    $motivo = '(id_motivo is null or id_motivo = 0 or id_motivo = "")';
                endif;

                if(!empty($_POST['reajuste'])):
                    $reajuste = filter_input(INPUT_POST, 'reajuste', FILTER_SANITIZE_STRING);
                else:
                    $reajuste = 0;
                endif;

                if(!empty($_POST['numero_parcelas'])):
                    $numero_parcelas = filter_input(INPUT_POST, 'numero_parcelas', FILTER_SANITIZE_STRING);
                else:
                    $numero_parcelas = 0;
                endif;

                if(!empty($_POST['aluno'])):
                    $nome_aluno = $_POST['aluno'].'%';
                else:
                    $nome_aluno = '%';
                endif;

                //$matriculas = Matriculas::find_all_by_status('a');
                $matriculas = Matriculas::find_by_sql("select matriculas.*, alunos.nome from matriculas inner join alunos on matriculas.id_aluno = alunos.id where matriculas.`status` = 'a' and alunos.nome like '{$nome_aluno}' order by alunos.nome asc;");
                if(!empty($matriculas)):
                    foreach($matriculas as $matricula):

                        try{
                            $parcela = V_Parcelas2::find(array('conditions' => array('id_matricula = ? and id_aluno = ? and '.$motivo.'', $matricula->id, $matricula->id_aluno), 'order' => 'data_vencimento desc', 'limit' => 1));
                        } catch (\ActiveRecord\RecordNotFound $e){
                            $parcela = '';
                        }

                        if(!empty($parcela->data_vencimento)):

                            if($parcela->data_vencimento->format('m') == filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING) && $parcela->data_vencimento->format('Y') == filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_STRING)):

                                /*
                                echo 'Parcela ID aluno :'.$parcela->id.'<br>';
                                echo 'Matricula ID aluno :'.$matricula->id_aluno.'<br>';
                                */

                                $aluno = Alunos::find($parcela->id_aluno);
                                $turma = Turmas::find($parcela->id_turma);
                                $unidade = Unidades::find($turma->id_unidade);

                                try{
                                    $motivo_parcela = Motivos_Parcela::find($parcela->id_motivo);
                                } catch(\ActiveRecord\RecordNotFound $e){
                                    $motivo_parcela = '';
                                }

                                echo '<tr>';
                                echo '<td>';
                                echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                                echo '<input type="checkbox" value="'.$parcela->id.'" class="parcela">';
                                echo '<span></span>';
                                echo '</label>';
                                echo '</td>';

                                echo !empty($parcela->data_vencimento) ? '<td data-title="Data Cadastro">'.$parcela->data_vencimento->format("d/m/Y").'</td>' : '<td></td>';
                                echo '<td data-title="Aluno">R$ '.number_format($matricula->valor_parcela, 2, ',', '.').'</td>';
                                echo '<td data-title="Aluno">'.$parcela->nome.'</td>';
                                echo !empty($motivo_parcela) ? '<td data-title="Referente">'.$motivo_parcela->motivo.'</td>' : '<td data-title="Referente">Parcela</td>' ;
                                echo '<td data-title="Unidade">'.$turma->nome.'</td>';
                                echo '<td data-title="Unidade">'.$unidade->nome_fantasia.'</td>';
                                echo '</tr>';

                            endif;

                        endif;

                    endforeach;
                endif;

            else:

                echo '<div class="titulo fw-bold size-1-5">Selecione os filtros desejados e clique em Pesquisar</div>';

            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
