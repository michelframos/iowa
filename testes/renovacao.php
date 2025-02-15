<?php
include_once ('../config.php');
?>

<div class="table-responsive">
    <table class="table pmd-table table-hover">
        <thead>
        <tr>
            <!--
            <th>
                <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                    <input type="checkbox" checked value="" id="selecionar-todos">
                    <span></span>
                </label>
            </th>
            -->
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

            $mes = 11;
            $ano = 2019;
            $nome_aluno = '%';
            $motivos = ['', 1,2,3,4,5];
            //$motivos = [''];

            //$matriculas = Matriculas::find_all_by_status('a');
            $matriculas = Matriculas::find_by_sql("select matriculas.*, alunos.nome from matriculas inner join alunos on matriculas.id_aluno = alunos.id where matriculas.`status` = 'a' and alunos.nome like '{$nome_aluno}' order by alunos.nome asc;");
            if(!empty($matriculas)):
                foreach($matriculas as $matricula):

                    foreach ($motivos as $motivo):

                        try{
                            $parcela = V_Parcelas2::find(array('conditions' => array('id_matricula = ? and id_aluno = ? and id_motivo = ?', $matricula->id, $matricula->id_aluno, $motivo), 'order' => 'data_vencimento desc', 'limit' => 1));
                        } catch (\ActiveRecord\RecordNotFound $e){
                            $parcela = '';
                        }

                        if(!empty($parcela->data_vencimento)):

                            if($parcela->data_vencimento->format('m') == $mes && $parcela->data_vencimento->format('Y') == $ano):

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
                                /*
                                echo '<td>';
                                echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                                echo '<input type="checkbox" value="'.$parcela->id.'" class="parcela">';
                                echo '<span></span>';
                                echo '</label>';
                                echo '</td>';
                                */

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

                endforeach;
            endif;
        ?>

        </tbody>
    </table>
</div>
