<?php
include_once ('../config.php');
$alunos = Alunos::all();
if(!empty($alunos)):
    echo '<table>';
    echo '<tr>';
    echo '<th>Código</th>';
    echo '<th>Nome</th>';
    echo '<th>Parcela de 2019</th>';
    echo '<th>Parcela de 2020</th>';
    echo '</tr>';
    foreach ($alunos as $aluno):
        $parcelas2019 = Parcelas::find(['conditions' => ['id_aluno = ? and month(data_vencimento) = ? and year(data_vencimento) = ?', $aluno->id, '11', '2019'], 'order' => 'data_vencimento desc', 'limit' => 1]);
        $parcelas2020 = Parcelas::find(['conditions' => ['id_aluno = ? and month(data_vencimento) = ? and year(data_vencimento) = ?', $aluno->id, '11', '2020'], 'order' => 'data_vencimento desc', 'limit' => 1]);

        if(!empty($parcelas2020)):
            if($parcelas2020->total < $parcelas2019->total):
                echo '<tr>';
                echo '<td>'.$aluno->id.'</td>';
                echo '<td>'.$aluno->nome.'</td>';
                echo '<td>'.number_format($parcelas2019->total, 2, ',', '.').'</td>';
                echo '<td>'.number_format($parcelas2020->total, 2, ',', '.').'</td>';
                echo '</tr>';
            endif;
        endif;

    endforeach;
    echo '</table>';
endif;