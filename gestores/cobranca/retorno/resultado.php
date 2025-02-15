<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');

$id = filter_input(INPUT_GET, 'resultado', FILTER_VALIDATE_INT);
$retornos = Resultado_Retornos::find($id);

/*
$parcelas = explode(',', $retornos->parcelas);
if(!empty($parcelas)):
    foreach($parcelas as $id_parcela):
        try{
            $parcela = Parcelas::find($id_parcela);
        } catch(\ActiveRecord\RecordNotFound $e){
            $parcela = '';
        }

        $retornos->boletos.= $parcela->numero_boleto.',';
        $retornos->save();
    endforeach;
endif;
*/

$total = 0;
$registros = 0;
$descontos = 0;
?>



<table width="100%">

    <tr>
        <td colspan="3" width="160" align="center"><img src="../../../assets/imagens/logo-login.png" width="150"/></td>
        <td colspan="9" align="center" class="size2">IOWA IDIOMAS<br>Situação das Cobraças</td>
    </tr>

    <tr>
        <th align="left" width="5%">Parc.</th>
        <th align="right" width="5%">Valor</th>
        <th align="center" width="80">Data Venc.</th>
        <th align="right" width="80">Val. Pago</th>
        <th align="center" width="80">Data Pagto</th>
        <th align="left" width="200">Sacado</th>
        <th align="center">Categoria</th>
        <th align="center" width="40">Nosso Número</th>
    </tr>

    <?php
    $boletos = array_filter(explode(',', $retornos->boletos));
    if(!empty($boletos)):
        foreach($boletos as $num_boleto):

            $boleto = Boletos::find_by_numero_boleto($num_boleto);
            $parcela = Parcelas::find($boleto->id_parcela);

            try{
                $turma = Turmas::find($parcela->id_turma);
            } catch(\ActiveRecord\RecordNotFound $e){
                $turma = '';
            }

            if($parcela->pagante == 'aluno'):

                try{
                    $sacado = Alunos::find($parcela->id_aluno);
                }catch (\ActiveRecord\RecordNotFound $e){
                    $sacado = '';
                }

                $nome = $sacado->nome;

            elseif($parcela->pagante == 'empresa'):

                try{
                    $sacado = Empresas::find($parcela->id_empresa);
                }catch (\ActiveRecord\RecordNotFound $e){
                    $sacado = '';
                }

                $nome = $sacado->nome_fantasia;

            endif;

            if(empty($boleto->data_pagamento)):
                $data_pagamento = $parcela->data_pagamento->format('d/m/Y');
            else:
                $data_pagamento = $boleto->data_pagamento->format('d/m/Y');
            endif;

            echo    '<tr>'.
                    '<td align="left">'.$parcela->parcela.'</td>'.
                    '<td align="right">'.number_format($parcela->total, 2, ',', '.').'</td>'.
                    '<td align="center">'.$parcela->data_vencimento->format('d/m/Y').'</td>'.
                    '<td align="right">'.number_format($boleto->valor_pago, 2, ',', '.').'</td>'.
                    '<td align="center">'.$data_pagamento.'</td>'.
                    //'<td align="left">'.$sacado->nome.'</td>'.
                    '<td align="left">'.$nome.'</td>'.
                    '<td align="center">'.$turma->nome.'</td>'.
                    '<td align="center">'.$boleto->nosso_numero.'</td>'.
                    '</tr>';

            $total_original += $boleto->valor;
            $total_geral += $boleto->valor_pago;
            $descontos += $parcela->desconto;
            $registros ++;

        endforeach;
    endif;
    ?>

    <tr>
        <td></td>
        <td align="right"><?php echo number_format($total_original, 2, ',', '.') ?></td>
        <td></td>
        <td align="right"><?php echo number_format($total_geral, 2, ',', '.') ?></td>
        <td colspan="9"></td>
    </tr>

    <tr>
        <td colspan="12">Total de Registros: <?php echo $registros; ?></td>
    </tr>

</table>

<?php
//=========================================================================================
//IMPRIMINDO EM PDF

//include_once("../../../classes/mpdf60/mpdf.php");
$html = ob_get_clean();

$mpdf = new \Mpdf\Mpdf();
$mpdf->AddPage('L');
$mpdf->SetFooter("impresso em " . date("d/m/Y") . " às " . date("H:i:s") . ' - Página: {PAGENO}');
$stylesheet = file_get_contents('../../../assets/css/impressos.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html,2);
$mpdf->Output();
exit;


?>
