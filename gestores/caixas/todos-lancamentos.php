<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

?>

<h2>TODOS OS LANÇAMENTOS DOS CAIXAS ABERTOS</h2>
<div class="espaco20"></div>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data</th>
                <th width="150">Hora</th>
                <th>Dono do Caixa</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Forma Pagamento</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $entradas = 0;
            $saidas = 0;
            $saldo = 0;

            $registros = Caixas::find_by_sql("select caixas.id, caixas.id_colega, caixas.situacao, movimentos_caixa.* from caixas inner join movimentos_caixa on caixas.id = movimentos_caixa.id_caixa where caixas.situacao = 'aberto'");
            if(!empty($registros)):
                foreach($registros as $registro):

                    $dono = Usuarios::find($registro->id_colega);

                    if($registro->tipo == 'e'):
                        $tipo = 'Entrada';
                        $entradas += $registro->total;
                    elseif($registro->tipo == 's'):
                        $tipo = 'Saida';
                        $saidas += $registro->total;
                    endif;

                    try{
                        $forma_pagamento = Formas_Pagamento::find($registro->id_forma_pagamento);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $forma_pagamento = '';
                    }


                    echo '<tr>';
                    echo '<td data-title="Data de Abertura">'.$registro->data.'</td>';
                    echo '<td data-title="Data de Abertura">'.$registro->hora.'</td>';
                    echo '<td data-title="Código Caixa">'.$dono->login.'</td>';
                    echo '<td data-title="Código Caixa">'.$registro->descricao.'</td>';
                    echo '<td data-title="Código Caixa">'.$tipo.'</td>';
                    echo '<td data-title="Código Caixa">'.$forma_pagamento->forma_pagamento.'</td>';
                    echo '<td data-title="Saldo Inicial">R$ '.number_format($registro->total, 2, ',', '.').'</td>';
                    echo '</tr>';

                endforeach;

                /*Saldo Final*/
                $saldo = $entradas-$saidas;

            endif;
            ?>

            <tr>
                <td colspan="6">Total</td>
                <td><?php echo 'R$ '.number_format($saldo, 2, ',', '.') ?></td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
