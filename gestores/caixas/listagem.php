<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<h2>RESUMO DOS CAIXAS</h2>
<div class="espaco20"></div>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data de Abertura</th>
                <th width="150">Hora Abertura</th>
                <th>Código Caixa</th>
                <th>Saldo Inicial</th>
                <th>Dono</th>
                <th>Nome</th>
                <th>Saldo Atual</th>
                <th width="20">Lançamento</th>
                <th width="20">Transferir</th>
                <th width="20">Fechar</th>
                <th width="20">Detalhes</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $registros = Caixas::all(array('conditions' => array('situacao = ?', 'aberto'),'order' => 'data_abertura, hora_abertura asc'));
            if(!empty($registros)):
                foreach($registros as $registro):
                    $usuario = Usuarios::find($registro->id_colega);

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
                    echo '<td data-title="Data de Abertura">'.$registro->hora_abertura.'</td>';
                    echo '<td data-title="Código Caixa">'.$registro->id.'</td>';
                    echo '<td data-title="Saldo Inicial">R$ '.number_format($registro->saldo_inicial, 2, ',', '.').'</td>';
                    echo '<td data-title="Dono">'.$usuario->login.'</td>';
                    echo '<td data-title="Nome">'.$registro->nome.'</td>';

                    echo '<td data-title="Saldo Atual">R$ '.number_format($saldo, 2, ',', '.').'</td>';

                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-fazer-lancamento" registro="'.$registro->id.'" data-target="#lancamentos-dialog" data-toggle="modal" title="Fazer Lançamento"><i class="material-icons pmd-sm">send</i> </a></td>';
                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-tranferir" registro="'.$registro->id.'" data-target="#tranferencia-dialog" data-toggle="modal" title="Realizar Transferência"><i class="material-icons pmd-sm">compare_arrows</i> </a></td>';
                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-fechr-caixa" registro="'.$registro->id.'" data-target="#fechar-caixa-dialog" data-toggle="modal" title="Fechar Caixa"><i class="material-icons pmd-sm">https</i> </a></td>';
                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-detalhes" registro="'.$registro->id.'" title="Fechar Caixa"><i class="material-icons pmd-sm">assignment</i> </a></td>';

                endforeach;
            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
