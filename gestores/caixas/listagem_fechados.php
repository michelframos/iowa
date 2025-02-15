<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data de Abertura</th>
                <th width="150">Hora de Fechamento</th>
                <th>Código Caixa</th>
                <th>Saldo Inicial</th>
                <th>Dono</th>
                <th>Saldo Final</th>
                <th>Detalhes</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $registros = Caixas::all(array('conditions' => array('situacao = ?', 'fechado'),'order' => 'data_abertura, hora_abertura desc'));
            if(!empty($registros)):
                foreach($registros as $registro):

                    try{
                        $usuario = Usuarios::find($registro->id_colega);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $usuario = '';
                    }


                    /*Saldo Atual*/
                    $movimentos_entrada = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($registro->id, 'e');
                    $movimentos_saida = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($registro->id, 's');

                    $entradas = 0;
                    $saidas = 0;
                    $saldo = 0;

                    if(!empty($movimentos_entrada)):
                        foreach($movimentos_entrada as $movimento_entrada):

                            $entradas+=$movimento_entrada->total;

                        endforeach;
                    endif;

                    if(!empty($movimentos_saida)):
                        foreach($movimentos_saida as $movimento_saida):

                            $saidas+=$movimento_saida->total;

                        endforeach;
                    endif;

                    $saldo = $entradas-$saidas;


                    echo '<tr>';
                    echo '<td data-title="Data de Abertura">'.$registro->data_abertura->format("d/m/Y").'</td>';
                    echo '<td data-title="Data de Abertura">'.$registro->data_fechamento->format('d/m/Y').'</td>';
                    echo '<td data-title="Código Caixa">'.$registro->id.'</td>';
                    echo '<td data-title="Saldo Inicial">R$ '.number_format($registro->saldo_inicial, 2, ',', '.').'</td>';
                    echo '<td data-title="Dono">'.$usuario->login.'</td>';

                    echo '<td data-title="Saldo Atual">R$ '.number_format($saldo, 2, ',', '.').'</td>';

                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-detalhes" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">assignment</i> </a></td>';
                endforeach;
            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
