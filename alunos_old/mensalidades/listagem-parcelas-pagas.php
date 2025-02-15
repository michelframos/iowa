<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$aluno = Alunos::find(idAluno());

?>

<form action="" name="formParcelas" id="formParcelas" method="post">

    <?php
    if(!empty($_POST['id_turma'])):
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    endif;

    $parcelas = Parcelas::all(array('conditions' => array('id_aluno = ? and pagante = ? and (pago = ? or cancelada = ?)', $aluno->id, 'aluno', 's', 's'), 'order' => 'data_vencimento asc'));
    if(!empty($parcelas)):
        ?>
        <!-- Basic Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Data Vencimento</th>
                    <th>Idioma</th>
                    <th>Valor</th>
                    <th>Data Pagamento</th>
                    <th class="texto-centro">Cancelada</th>
                    <th class="texto-centro">Observações</th>
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
                        $boleto = Boletos::find(array('conditions' => array('id_parcela = ? and pago = ? and cancelado <> ?', $parcela->id, 'n', 's')));
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $boleto = '';
                    }

                    echo '<tr>';
                    echo '<td data-title="Data">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                    echo '<td data-title="Idioma">'.$idioma->idioma.'</td>';
                    echo '<td data-title="Valor">R$ '.number_format($parcela->total, 2, ',', '.').'</td>';
                    echo !empty($parcela->data_pagamento) ? '<td data-title="Data Pagamento">'.$parcela->data_pagamento->format('d/m/Y').'</td>' : '<td data-title="Data Pagamento"></td>';
                    echo $parcela->cancelada == 's' ? '<td class="texto-centro">Sim</td>' : '<td class="texto-centro">Não</td>';
                    echo '<td data-title="Data">'.$parcela->observacoes.'</td>';
                    echo '</tr>';
                endforeach;
                ?>
                </tbody>
            </table>
        </div>

        <?php
    else:
        echo '<h2 class="h2">Nenhuma mensalidade encontrada.</h2>';
    endif;
    ?>

</form>
