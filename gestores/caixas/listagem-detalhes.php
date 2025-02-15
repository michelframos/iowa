<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$caixa = Caixas::find($id);

if(empty($caixa)):

    $caixa = Caixas::find_by_id_colega_and_situacao(idUsuario(), 'aberto');

endif;

?>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data</th>
                <th width="150">Hora</th>
                <!-- <th>Categoria</th> -->
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Forma Pagamento</th>
                <th>Valor</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php
            $entradas = 0;
            $saidas = 0;
            $saldo = 0;

            $registros = Movimentos_Caixa::all(array('conditions' => array('id_caixa = ?', $caixa->id),'order' => 'numero asc'));
            if(!empty($registros)):
                foreach($registros as $registro):

                    if($registro->tipo == 'e'):
                        $tipo = 'Entrada';
                        $entradas += $registro->total;
                    elseif($registro->tipo == 's'):
                        $tipo = 'Saida';
                        $saidas += $registro->total;
                    endif;

                    /*
                    try{
                        $categoria = Categorias_Lancamentos::find($registro->id_categoria);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $categoria = '';
                    }
                    */

                    try{
                        $forma_pagamento = Formas_Pagamento::find($registro->id_forma_pagamento);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $forma_pagamento = '';
                    }


                    echo '<tr>';
                    echo '<td data-title="Data de Abertura">'.$registro->data->format("d/m/Y").'</td>';
                    echo '<td data-title="Data de Abertura">'.$registro->hora.'</td>';
                    //echo '<td data-title="Código Caixa">'.$categoria->categoria.'</td>';
                    echo '<td data-title="Código Caixa">'.$registro->descricao.'</td>';
                    echo '<td data-title="Código Caixa">'.$tipo.'</td>';
                    echo '<td data-title="Código Caixa">'.$forma_pagamento->forma_pagamento.'</td>';
                    echo '<td data-title="Saldo Inicial">R$ '.number_format($registro->total, 2, ',', '.').'</td>';

                    echo !empty($registro->id_conta_pagar) && empty($registro->estorno) ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-estornar" data-target="#remover-pagamento-dialog" data-toggle="modal" registro="'.$registro->id.'" conta="'.$registro->id_conta_pagar.'" title="Estornar Conta a Pagar"><i class="material-icons pmd-sm">undo</i> </a></td>' : '<td></td>' ;

                    echo '</tr>';

                endforeach;

                /*Saldo Final*/
                $saldo = $entradas-$saidas;

            endif;
            ?>

            <tr>
                <td colspan="5">Total</td>
                <td><?php echo 'R$ '.number_format($saldo, 2, ',', '.') ?></td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
