<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
include_once('../../classes/ValorMonetario.php');

$id = filter_input(INPUT_GET, 'recibo', FILTER_VALIDATE_INT);
$recibo = Recibos::find($id);

try{
    $aluno = Alunos::find($recibo->id_aluno);
} catch (\ActiveRecord\RecordNotFound $e){
    $aluno = '';
}



$usuario = Usuarios::find($recibo->id_usuario);

try{
    $colega = Colegas::find($usuario->id_colega);
} catch(\ActiveRecord\RecordNotFound $e) {
    $colega = '';
}

try{
    $unidade = Unidades::find($colega->id_unidade);
} catch(\ActiveRecord\RecordNotFound $e){
    $unidade = '';
}

try{
    $cidade = Cidades::find($unidade->cidade);
} catch(\ActiveRecord\RecordNotFound $e){
    $cidade = '';
}

try{
    $estado = Estados::find($unidade->estado);
} catch(\ActiveRecord\RecordNotFound $e){

}

?>

<style>

    .text-right{ text-align: right; }
    .texto{ font: 1em 'Arial'; }
    .size-0-8{ font-size: 0.8em; }
    .size-0-7{ font-size: 0.75em; }
    .size-0-6{ font-size: 0.6em; }
    .bold{ font-weight: bold; }
    .linha{ width: 100%; height: 2px; border-bottom: 2px solid #041218; }
    .linha-fina{ width: 100%; height: 2px; border-bottom: 1px solid #041218; }
    .pontilhado{ width: 100%; height: 2px; border-bottom: 2px dashed #041218; }

</style>

<!-- -------------------------------------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------------------------------------- -->

<table class="texto" width="100%" style="position: relative;">
    
    <tr>
        <td rowspan="3"><img src="<?php echo HOME ?>/assets/imagens/logo-iowa-idiomas.png" width="170"></td>
        <td class="bold">IOWA IDIOMAS</td>
        <td class="bold text-right">RECIBO Nº <?php echo $id ?></td>
    </tr>

    <tr>
        <td class="size-0-8"><?php echo $unidade->razao_social ?> - C.N.P.J. <?php echo mascara($unidade->cnpj, '##.###.###/####-##') ?></td>
        <td class="size-0-8 text-right"><?php echo date('d/m/Y'); ?></td>
    </tr>

    <tr>
        <td colspan="2" class="size-0-7"><?php echo $unidade->rua.', '.$unidade->numero.', '.$unidade->bairro.', '.$cidade->nome.'-'.$estado->uf ?></td>
    </tr>

    <tr>
        <td colspan="3" class="text-right size-0-6 bold">Impressão <?php echo date('d/m/Y H:i:s'); ?></td>
    </tr>

    <tr>
        <td colspan="3"><div class="linha"></div></td>
    </tr>

    <!-- ----------------------------------------------------------------------------------------------------------- -->

    <tr>
        <td class="bold size-0-8">Recebemos de</td>
        <td colspan="2" class="size-0-8"><?php echo $aluno->nome ?></td>
    </tr>

    <tr>
        <td class="bold size-0-8">a importância de</td>
        <td class="size-0-8"><?php echo number_format($recibo->total, 2, ',', '.') ?> (<?php echo ValorMonetario::valorPorExtenso($recibo->total) ?>)</td>
        <td class="size-0-8 bold text-right">Referente à</td>
    </tr>

    <tr>
        <td colspan="3"><div class="linha"></div></td>
    </tr>

    <!-- ----------------------------------------------------------------------------------------------------------- -->

    <tr>
        <td colspan="3">
            <div style="min-height: 200px; position: relative; top: 0;">
                <table width="100%">
                    <tr>
                       <td class="bold size-0-6">Categoria</td>
                       <td class="bold size-0-6">Parc.</td>
                       <td class="bold size-0-6">Vencto.</td>
                       <td class="bold size-0-6">Turma Atual</td>
                       <td class="bold size-0-6">Recebimento/Documento/Emitente</td>
                       <td class="bold size-0-6">Dsc/Juros/Multa</td>
                       <td class="bold size-0-6 text-right">Val Liq</td>
                    </tr>

                    <?php
                    $descricao_forma_pagamento = '';
                    $ids_parcelas = explode(',', $recibo->parcelas);
                    if(!empty(array_filter($ids_parcelas))):
                        foreach(array_filter($ids_parcelas) as $id_parcela):
                            $parcela = Parcelas::find($id_parcela);

                            try{
                                $turma = Turmas::find($parcela->id_turma);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $turma = '';
                            }

                            /*puxando formas de pagamento*/
                            $detalhes_movimento = Detalhes_Movimento::all(array('conditions' => array('id_parcela = ?', $parcela->id)));
                            if(!empty($detalhes_movimento)):
                                foreach ($detalhes_movimento as $detalhe_movimento):
                                    $movimento = Movimentos_Caixa::find($detalhe_movimento->id_movimento);
                                    $forma_pagemento = Formas_Pagamento::find($movimento->id_forma_pagamento);
                                    $descricao_forma_pagamento .= $forma_pagemento->forma_pagamento . ', ';
                                endforeach;;
                            endif;

                            $forma_pagemento = Formas_Pagamento::find($parcela->id_forma_pagamento);

                            //$descricao_forma_pagamento = $forma_pagemento->forma_pagamento;

                            try{
                                $matricula = Matriculas::find($parcela->id_matricula);
                            } catch( \ActiveRecord\RecordNotFound $e ){
                                $matricula = '';
                            }

                            try{
                                $motivo = Motivos_Parcela::find($parcela->id_motivo);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $motivo = '';
                            }

                            echo '<tr>';

                            echo !(empty($motivo)) ? '<td class="size-0-6">'.$motivo->motivo.'</td>' : '<td class="size-0-6">Parcela</td>';
                            echo '<td class="size-0-6">'.$parcela->parcela.'/'.$matricula->numero_parcelas.'</td>';
                            echo '<td class="size-0-6">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                            echo '<td class="size-0-6">'.$turma->nome.'</td>';
                            //echo '<td class="size-0-6">'.$parcela->data_pagamento->format('d/m/Y').'/'.$forma_pagemento->forma_pagamento.'/'.$aluno->nome.'</td>';
                            echo '<td class="size-0-6">'.$parcela->data_pagamento->format('d/m/Y').'/'.$descricao_forma_pagamento.'/'.$aluno->nome.'</td>';
                            echo '<td class="size-0-6">R$ '.number_format($parcela->desconto, 2, ',', '.').' / '.number_format($parcela->juros, 2, ',', '.').' / '.number_format($parcela->multa, 2, ',', '.').'</td>';
                            echo '<td class="size-0-6 text-right">'.number_format(!empty($parcela->valor_pago) ? $parcela->valor_pago : $parcela->total, 2,',','.').'</td>';

                            echo '</tr>';

                        endforeach;
                    endif;
                    ?>

                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="2" class="bold size-0-8">Total: <?php echo $descricao_forma_pagamento ?>: R$ <?php echo number_format($recibo->total, 2, ',', '.') ?></td>
        <td class="bold size-0-8 text-right">Total: R$ <?php echo number_format($recibo->total, 2, ',', '.') ?></td>
    </tr>

    <tr>
        <td colspan="3"><div class="linha"></div></td>
    </tr>

    <tr>
        <td class="size-0-8 bold">Recebido por</td>
        <td colspan="2" class="linha-fina"></td>
    </tr>

    <tr>
        <td></td>
        <td colspan="2" class="size-0-8"><?php echo $usuario->nome ?> em <?php echo date('d/m/Y') ?></td>
    </tr>

</table>

<br/>
<div class="pontilhado"></div>

<!-- -------------------------------------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------------------------------------- -->


<br/>
<br/>


<!-- -------------------------------------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------------------------------------- -->

<table class="texto" width="100%" style="position: relative;">

    <tr>
        <td rowspan="3"><img src="<?php echo HOME ?>/assets/imagens/logo-iowa-idiomas.png" width="170"></td>
        <td class="bold">IOWA IDIOMAS</td>
        <td class="bold text-right">RECIBO Nº <?php echo $id ?></td>
    </tr>

    <tr>
        <td class="size-0-8"><?php echo $unidade->razao_social ?> - C.N.P.J. <?php echo mascara($unidade->cnpj, '##.###.###/####-##') ?></td>
        <td class="size-0-8 text-right"><?php echo date('d/m/Y'); ?></td>
    </tr>

    <tr>
        <td colspan="2" class="size-0-7"><?php echo $unidade->rua.', '.$unidade->numero.', '.$unidade->bairro.', '.$cidade->nome.'-'.$estado->uf ?></td>
    </tr>

    <tr>
        <td colspan="3" class="text-right size-0-6 bold">Impressão <?php echo date('d/m/Y H:i:s'); ?></td>
    </tr>

    <tr>
        <td colspan="3"><div class="linha"></div></td>
    </tr>

    <!-- ----------------------------------------------------------------------------------------------------------- -->

    <tr>
        <td class="bold size-0-8">Recebemos de</td>
        <td colspan="2" class="size-0-8"><?php echo $aluno->nome ?></td>
    </tr>

    <tr>
        <td class="bold size-0-8">a importância de</td>
        <td class="size-0-8"><?php echo number_format($recibo->total, 2, ',', '.') ?> (<?php echo ValorMonetario::valorPorExtenso($recibo->total) ?>)</td>
        <td class="size-0-8 bold text-right">Referente à</td>
    </tr>

    <tr>
        <td colspan="3"><div class="linha"></div></td>
    </tr>

    <!-- ----------------------------------------------------------------------------------------------------------- -->

    <tr>
        <td colspan="3">
            <div style="min-height: 200px; position: relative; top: 0;">
                <table width="100%">
                    <tr>
                       <td class="bold size-0-6">Categoria</td>
                       <td class="bold size-0-6">Parc.</td>
                       <td class="bold size-0-6">Vencto.</td>
                       <td class="bold size-0-6">Turma Atual</td>
                       <td class="bold size-0-6">Recebimento/Documento/Emitente</td>
                       <td class="bold size-0-6">Dsc/Juros/Multa</td>
                       <td class="bold size-0-6 text-right">Val Liq</td>
                    </tr>

                    <?php
                    $descricao_forma_pagamento = '';
                    $ids_parcelas = explode(',', $recibo->parcelas);
                    if(!empty(array_filter($ids_parcelas))):
                        foreach(array_filter($ids_parcelas) as $id_parcela):
                            $parcela = Parcelas::find($id_parcela);

                            try{
                                $turma = Turmas::find($parcela->id_turma);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $turma = '';
                            }

                            /*puxando formas de pagamento*/
                            $detalhes_movimento = Detalhes_Movimento::all(array('conditions' => array('id_parcela = ?', $parcela->id)));
                            if(!empty($detalhes_movimento)):
                                foreach ($detalhes_movimento as $detalhe_movimento):
                                    $movimento = Movimentos_Caixa::find($detalhe_movimento->id_movimento);
                                    $forma_pagemento = Formas_Pagamento::find($movimento->id_forma_pagamento);
                                    $descricao_forma_pagamento .= $forma_pagemento->forma_pagamento . ', ';
                                endforeach;;
                            endif;

                            $forma_pagemento = Formas_Pagamento::find($parcela->id_forma_pagamento);

                            //$descricao_forma_pagamento = $forma_pagemento->forma_pagamento;

                            try{
                                $matricula = Matriculas::find($parcela->id_matricula);
                            } catch( \ActiveRecord\RecordNotFound $e ){
                                $matricula = '';
                            }

                            try{
                                $motivo = Motivos_Parcela::find($parcela->id_motivo);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $motivo = '';
                            }

                        echo '<tr>';

                            echo !(empty($motivo)) ? '<td class="size-0-6">'.$motivo->motivo.'</td>' : '<td class="size-0-6">Parcela</td>';
                            echo '<td class="size-0-6">'.$parcela->parcela.'/'.$matricula->numero_parcelas.'</td>';
                            echo '<td class="size-0-6">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                            echo '<td class="size-0-6">'.$turma->nome.'</td>';
                            //echo '<td class="size-0-6">'.$parcela->data_pagamento->format('d/m/Y').'/'.$forma_pagemento->forma_pagamento.'/'.$aluno->nome.'</td>';
                            echo '<td class="size-0-6">'.$parcela->data_pagamento->format('d/m/Y').'/'.$descricao_forma_pagamento.'/'.$aluno->nome.'</td>';
                            echo '<td class="size-0-6">R$ '.number_format($parcela->desconto, 2, ',', '.').' / '.number_format($parcela->juros, 2, ',', '.').' / '.number_format($parcela->multa, 2, ',', '.').'</td>';
                            echo '<td class="size-0-6 text-right">'.number_format($recibo->total, 2,',','.').'</td>';

                        echo '</tr>';

                        endforeach;
                    endif;
                    ?>

                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="2" class="bold size-0-8">Total: <?php echo $descricao_forma_pagamento ?>: R$ <?php echo number_format($recibo->total, 2, ',', '.') ?></td>
        <td class="bold size-0-8 text-right">Total: R$ <?php echo number_format($recibo->total, 2, ',', '.') ?></td>
    </tr>

    <tr>
        <td colspan="3"><div class="linha"></div></td>
    </tr>

    <tr>
        <td class="size-0-8 bold">Recebido por</td>
        <td colspan="2" class="linha-fina"></td>
    </tr>

    <tr>
        <td></td>
        <td colspan="2" class="size-0-8"><?php echo $usuario->nome ?> em <?php echo date('d/m/Y') ?></td>
    </tr>

</table>

<!-- -------------------------------------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------------------------------------- -->

<script>
    window.print();
</script>
