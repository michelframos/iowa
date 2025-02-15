<?php
include_once ('../config.php');
$meses_2019 = [3,4,5,6,7,8,9,10,11,12];
$meses_2020 = [1,2];
$numero_parcela = 1;

foreach ($meses_2019 as $mes):

    $parcelas = Parcelas::all(['conditions' => ['month(data_vencimento) = ? and year(data_vencimento) = ? and parcela = ? and pagante = ?', $mes, 2019, 12, 'aluno']]);
    if(!empty($parcelas)):
        echo '<table width="1000">';
            echo '<tr>';
                echo '<th width="300">Aluno</th>';
                echo '<th width="100">Id Parcela</th>';
                echo '<th width="100">Valor Parcela</th>';
                echo '<th width="100">Valor Matricula</th>';
                echo '<th width="100">Data Parcela</th>';
                echo '<th width="300">Turma</th>';
            echo '</tr>';
        foreach ($parcelas as $parcela):
            $aluno = Alunos::find(['conditions' => ['id = ? and status = ?', $parcela->id_aluno, 'a']]);
            try{
                $turma = Turmas::find($parcela->id_turma);
            } catch (Exception $e){
                $turma = '';
            }
            $matricula = Matriculas::find($parcela->id_matricula);
            if($aluno->status == 'a'):
                echo '<tr>';
                echo '<td>'.$aluno->nome.'</td>';
                echo '<td>'.$parcela->id.'</td>';
                echo '<td>'.$parcela->valor.'</td>';
                echo '<td>'.$matricula->valor_parcela.'</td>';
                echo '<td>'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                echo '<td>'.$turma->nome.'</td>';
                echo '</tr>';

                /*
                $matricula->valor_parcela = $parcela->valor;
                $matricula->save();
                */

            endif;
        endforeach;
        echo '</table>';
    endif;

endforeach;

foreach ($meses_2020 as $mes):

    $parcelas = Parcelas::all(['conditions' => ['month(data_vencimento) = ? and year(data_vencimento) = ? and parcela = ? and pagante = ?', $mes, 2020, 12, 'aluno']]);
    if(!empty($parcelas)):
        echo '<table width="900">';
            echo '<tr>';
                echo '<th width="300">Aluno</th>';
                echo '<th width="100">Id Parcela</th>';
                echo '<th width="100">Valor Parcela</th>';
                echo '<th width="100">Valor Matricula</th>';
                echo '<th width="100">Data Parcela</th>';
                echo '<th width="300">Turma</th>';
            echo '</tr>';
        foreach ($parcelas as $parcela):
            $aluno = Alunos::find(['conditions' => ['id = ? and status = ?', $parcela->id_aluno, 'a']]);
            try{
                $turma = Turmas::find($parcela->id_turma);
            } catch (Exception $e){
                $turma = '';
            }
            $matricula = Matriculas::find($parcela->id_matricula);
            if($aluno->status == 'a'):
                echo '<tr>';
                echo '<td>'.$aluno->nome.'</td>';
                echo '<td>'.$parcela->id.'</td>';
                echo '<td>'.$parcela->valor.'</td>';
                echo '<td>'.$matricula->valor_parcela.'</td>';
                echo '<td>'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                echo '<td>'.$turma->nome.'</td>';
                echo '</tr>';

                /*
                $matricula->valor_parcela = $parcela->valor;
                $matricula->save();
                */


            endif;
        endforeach;
        echo '</table>';
    endif;

endforeach;