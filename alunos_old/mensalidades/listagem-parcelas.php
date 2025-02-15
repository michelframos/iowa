<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$aluno = Alunos::find(idAluno());

?>

<form action="" name="formParcelas" id="formParcelas" method="post">

    <?php
    $parcelas = Parcelas::all(array('conditions' => array('id_aluno = ? and pagante = ? and pago = ? and cancelada = ?', $aluno->id, 'aluno', 'n', 'n'), 'order' => 'data_vencimento asc'));
    if(!empty($parcelas)):
        ?>
        <!-- Basic Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Data Vencimento</th>
                    <th>Turma</th>
                    <th>Idioma</th>
                    <th>Referente</th>
                    <th>Valor</th>
                    <th>Boleto</th>
                    <th>Observações</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($parcelas as $parcela):

                    try{
                        $turma = Turmas::find($parcela->id_turma);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $turma = '';
                    }


                    try{
                        $idioma = Idiomas::find($parcela->id_idioma);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $idioma = '';
                    }

                    try{
                        $motivo = Motivos_Parcela::find($parcela->id_motivo);
                    } catch(\ActiveRecord\ReadOnlyException $e){
                        $motivo = '';
                    }

                    try{
                        $boleto = Boletos::find(array('conditions' => array('id_parcela = ? and pago = ? and (cancelado = ? or cancelado is null)', $parcela->id, 'n', 'n')));
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $boleto = '';
                    }

                    echo '<tr>';
                    echo '<td data-title="Data">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                    echo '<td data-title="Idioma">'.$turma->nome.'</td>';
                    echo '<td data-title="Idioma">'.$idioma->idioma.'</td>';
                    echo empty($motivo) ? '<td data-title="Referente">Parcela</td>' : '<td data-title="Referente">'.$motivo->motivo.'</td>';
                    echo '<td data-title="Valor">R$ '.number_format($parcela->total, 2, ',', '.').'</td>';
                    echo !empty($boleto) ? '<td data-title="Boleto"><a href="'.HOME.'/boleto.php?boleto='.$boleto->chave.'" target="_blank" class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat"><i class="material-icons pmd-sm">print</i></a></td>' : '<td data-title="Boleto"></td>';
                    echo '<td data-title="Data">'.$parcela->observacoes.'</td>';
                    echo '</tr>';
                endforeach;
                ?>
                </tbody>
            </table>
        </div>

        <?php
    else:
        echo '<h2 class="h2">Você não possue mensalidades.</h2>';
    endif;
    ?>

</form>
