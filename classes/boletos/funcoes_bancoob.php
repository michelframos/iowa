<?php
include_once('config.php');

$opcoes_cobranca = \IowaPainel\CobrancaController::getOpcoesCobranca();
$dados_banco = IowaPainel\UnidadesController::getDadosBanco($boleto->id_unidade, $boleto->codigo_banco);

try{
    //$usar_dados = Unidades::find_by_usar_dados_boleto('s');
    $usar_dados = Unidades::find($boleto->id_unidade);

    $dados_cliente = explode('-', $usar_dados->codigo_cliente);
    $codigo_clente = $dados_cliente[0].$dados_cliente[1];

    $cnpj = str_replace('.', '', $usar_dados->cnpj);
    $cnpj = str_replace('/', '', $cnpj);
    $cnpj = str_replace('-', '', $cnpj);

    $nome_empresa = $usar_dados->razao_social;

    $numero_agencia = explode('-', $dados_banco->agencia);
    $numero_conta_corrente = explode('-', $dados_banco->conta);

    //$convenio = str_replace('-', '', $usar_dados->codigo_cliente);
    $convenio = explode('-', $dados_banco->codigo_cliente);

} catch (\ActiveRecord\RecordNotFound $e){
    $usar_dados = '';
}

// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto BANCOOB/SICOOB: Marcelo de Souza              |
// | Ajuste de algumas rotinas: Anderson Nuernberg                        |
// +----------------------------------------------------------------------+


/*----------------------------------------------------*/
/*PartedoBoleto*/
//DADOSDOBOLETOPARAOSEUCLIENTE
$dias_de_prazo_para_pagamento=7;
$taxa_boleto=0;
$data_venc=$boleto->data_vencimento->format('d/m/Y');//date("d/m/Y",time()+($dias_de_prazo_para_pagamento*86400));//PrazodeXdiasOUinformedata:"13/04/2006";
$valor_cobrado=$boleto->valor;//Valor-REGRA:Sempontosnamilharetantofazcom"."ou","oucom1ou2ousemcasadecimal
$valor_cobrado=str_replace(",",".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto,2,',','');

if(!function_exists('formata_numdoc'))
{
    function formata_numdoc($num,$tamanho)
    {
        while(strlen($num)<$tamanho)
        {
            $num="0".$num;
        }
        return $num;
    }
}

$nosso_numero_boleto = $boleto->nosso_numero;
$IdDoSeuSistemaAutoIncremento=substr("$nosso_numero_boleto", -7);//Deveinformarumnumerosequencialaserpassadaafunçãoabaixo,Até6dígitos
$agencia=$numero_agencia[0];//Numdaagencia,semdigito
$conta=$boleto->conta;//Numdaconta,semdigito
$convenio= $convenio[0].$convenio[1];//Númerodoconvênioindicadonofrontend
$NossoNumero=formata_numdoc($IdDoSeuSistemaAutoIncremento,7);
$qtde_nosso_numero=strlen($NossoNumero);
$sequencia=formata_numdoc($agencia,4).formata_numdoc(str_replace("-","",$convenio),10).formata_numdoc($IdDoSeuSistemaAutoIncremento,6);

/*
$cont=0;
$calculoDv='';
for($num=0;$num<=strlen($sequencia);$num++)
{
    $cont++;
    if($cont==1)
    {
        //constantefixaSicoob»3197
        $constante=3;
    }
    if($cont==2)
    {
        $constante=1;
    }
    if($cont==3)
    {
        $constante=9;
    }
    if($cont==4)
    {
        $constante=7;
        $cont=0;
    }
    $calculoDv=$calculoDv+(substr($sequencia,$num,1)*$constante);
}
$Resto=$calculoDv%11;
$Dv=11-$Resto;
if($Dv==0)$Dv=0;
if($Dv==1)$Dv=0;
if($Dv>9)$Dv=0;
*/

function modulo11($index, $ag, $conv) {
    $sequencia = str_pad($ag, 4, 0, STR_PAD_LEFT) . str_pad($conv, 10, 0, STR_PAD_LEFT) . str_pad($index, 3, 0, STR_PAD_LEFT);
    $cont = 0;
    $calculoDv = 0;
    for ($num = 0; $num <= strlen($sequencia); $num++) {
        $cont++;
        if ($cont == 1) {
            // constante fixa Sicoob » 3197
            $constante = 3;
        }
        if ($cont == 2) {
            $constante = 1;
        }
        if ($cont == 3) {
            $constante = 9;
        }
        if ($cont == 4) {
            $constante = 7;
            $cont = 0;
        }
        $calculoDv += ((int) substr($sequencia, $num, 1) * $constante);
    }
    $Resto = $calculoDv % 11;
    if ($Resto == 0 || $Resto == 1) {
        $Dv = 0;
    } else {
        $Dv = 11 - $Resto;
    };
    return $Dv;
}

//$digitoVerificadorNossoNumero = modulo11($NossoNumero,5142,65838);
$digitoVerificadorNossoNumero = modulo11($NossoNumero,$numero_agencia[0],$codigo_clente);

$dadosboleto["nosso_numero"]=$NossoNumero.$digitoVerificadorNossoNumero;

/*************************************************************************
 *+++
 *************************************************************************/

$codigobanco = $dados_banco->codigo_banco;
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";

if(!empty($boleto->data_vencimento)):
    $fator_vencimento = fator_vencimento($boleto->data_vencimento->format('d/m/Y'));
endif;

//valor tem 10 digitos, sem virgula
$valor = formata_numero(number_format($boleto->valor, 2, '', ''),10,0,"valor");
//agencia é sempre 4 digitos
$agencia = formata_numero($numero_agencia[0],4,0);
//conta é sempre 8 digitos
$conta = formata_numero($numero_conta_corrente[0],8,0);

$carteira = 1;


//Zeros: usado quando convenio de 7 digitos
$livre_zeros='000000';
$modalidadecobranca = '01';
$numeroparcela      = '001';

//$convenio = formata_numero($boleto->convenio,7,0);
$convenio = formata_numero($convenio,7,0);
//$convenio = formata_numero('4687693',7,0);

//agencia e conta
$agencia_codigo = $agencia ." / ". $convenio;

// Nosso número de até 8 dígitos - 2 digitos para o ano e outros 6 numeros sequencias por ano 
// deve ser gerado no programa boleto_bancoob.php
//$nossonumero =  formata_numero($boleto->nosso_numero,7,0);
$nossonumero =  $dadosboleto["nosso_numero"];
$campolivre  = "$modalidadecobranca$convenio$nossonumero$numeroparcela";

$dv=modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$carteira$agencia$campolivre");
$linha = "$codigobanco$nummoeda$carteira$agencia$dv$fator_vencimento$valor$campolivre";
$linha_codigo_barras_novo = "$codigobanco$nummoeda$dv$fator_vencimento$valor$carteira$agencia$campolivre";



//die($linha_codigo_barras_novo);

/*
echo 'Cod Banco ' . $codigobanco.'<br>';
echo 'moeda ' . $nummoeda.'<br>';
echo 'DV ' . $dv.'<br>';
echo 'Fator Vencimento ' . $fator_vencimento.'<br>';
echo 'Valor ' . $valor.'<br>';
echo 'Carteira ' . $carteira.'<br>';
echo 'Agencia ' . $agencia.'<br>';

echo 'Campo Livre ' . $campolivre.'<br>';

echo 'Mdalidade Cobrança ' . $modalidadecobranca.'<br>';
echo 'Convenio ' . $convenio.'<br>';
echo 'Nosso Numero ' . $nossonumero.'<br>';
echo 'Numero Parcela ' . $numeroparcela.'<br>';


die($linha);
*/


$dadosboleto["codigo_barras"] = $linha_codigo_barras_novo;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha, $dv);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $dadosboleto['nosso_numero'];
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

// FUNÇÕES
// Algumas foram retiradas do Projeto PhpBoleto e modificadas para atender as particularidades de cada banco

function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	if ($tipo == "geral") {
		$numero = str_replace(",","",$numero);
		$numero = str_replace(".","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "valor") {
		/*
		retira as virgulas
		formata o numero
		preenche com zeros
		*/
		$numero = str_replace(",","",$numero);
		$numero = str_replace(".","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "convenio") {
		while(strlen($numero)<$loop){
			$numero = $numero . $insert;
		}
	}
	return $numero;
}


function fbarcode($valor){

$fino = 1 ;
$largo = 3 ;
$altura = 50 ;

  $barcodes[0] = "00110" ;
  $barcodes[1] = "10001" ;
  $barcodes[2] = "01001" ;
  $barcodes[3] = "11000" ;
  $barcodes[4] = "00101" ;
  $barcodes[5] = "10100" ;
  $barcodes[6] = "01100" ;
  $barcodes[7] = "00011" ;
  $barcodes[8] = "10010" ;
  $barcodes[9] = "01010" ;
  for($f1=9;$f1>=0;$f1--){ 
    for($f2=9;$f2>=0;$f2--){  
      $f = ($f1 * 10) + $f2 ;
      $texto = "" ;
      for($i=1;$i<6;$i++){ 
        $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      }
      $barcodes[$f] = $texto;
    }
  }


//Desenho da barra


//Guarda inicial
?><img src=classes/boletos/imagens/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=classes/boletos/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=classes/boletos/imagens/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=classes/boletos/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
<?php
$texto = $valor ;
if((strlen($texto) % 2) <> 0){
	$texto = "0" . $texto;
}

// Draw dos dados
while (strlen($texto) > 0) {
  $i = round(esquerda($texto,2));
  $texto = direita($texto,strlen($texto)-2);
  $f = $barcodes[$i];
  for($i=1;$i<11;$i+=2){
    if (substr($f,($i-1),1) == "0") {
      $f1 = $fino ;
    }else{
      $f1 = $largo ;
    }
?>
    src=classes/boletos/imagens/p.png width=<?php echo $f1?> height=<?php echo $altura?> border=0><img
<?php
    if (substr($f,$i,1) == "0") {
      $f2 = $fino ;
    }else{
      $f2 = $largo ;
    }
?>
    src=classes/boletos/imagens/b.png width=<?php echo $f2?> height=<?php echo $altura?> border=0><img
<?php
  }
}

// Draw guarda final
?>
src=classes/boletos/imagens/p.png width=<?php echo $largo?> height=<?php echo $altura?> border=0><img
src=classes/boletos/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=classes/boletos/imagens/p.png width=<?php echo 1?> height=<?php echo $altura?> border=0>
  <?php
} //Fim da função

function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}

function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}

function fator_vencimento($data) {
	$data = explode("/",$data);
	$ano = $data[2];
	$mes = $data[1];
	$dia = $data[0];
    return(abs((_dateToDays("1997","10","07")) - (_dateToDays($ano, $mes, $dia))));
}

function _dateToDays($year,$month,$day) {
    $century = substr($year, 0, 2);
    $year = substr($year, 2, 2);
    if ($month > 2) {
        $month -= 3;
    } else {
        $month += 9;
        if ($year) {
            $year--;
        } else {
            $year = 99;
            $century --;
        }
    }
    return ( floor((  146097 * $century)    /  4 ) +
            floor(( 1461 * $year)        /  4 ) +
            floor(( 153 * $month +  2) /  5 ) +
                $day +  1721119);
}

/*
#################################################
FUNÇÃO DO MÓDULO 10 RETIRADA DO PHPBOLETO
ESTA FUNÇÃO PEGA O DÍGITO VERIFICADOR DO PRIMEIRO, SEGUNDO
E TERCEIRO CAMPOS DA LINHA DIGITÁVEL
#################################################
*/
function modulo_10($num) {
    $numtotal10 = 0;
    $fator = 2;

    for ($i = strlen($num); $i > 0; $i--) {
        $numeros[$i] = substr($num,$i-1,1);
        $parcial10[$i] = $numeros[$i] * $fator;
        $numtotal10 .= $parcial10[$i];
        if ($fator == 2) {
            $fator = 1;
        }
        else {
            $fator = 2;
        }
    }

    $soma = 0;
    for ($i = strlen($numtotal10); $i > 0; $i--) {
        $numeros[$i] = substr($numtotal10,$i-1,1);
        $soma += $numeros[$i];
    }
    $resto = $soma % 10;
    $digito = 10 - $resto;
    if ($resto == 0) {
        $digito = 0;
    }
    return $digito;
}
/*
#################################################
FUNÇÃO DO MÓDULO 11 RETIRADA DO PHPBOLETO
MODIFIQUEI ALGUMAS COISAS...
ESTA FUNÇÃO PEGA O DÍGITO VERIFICADOR:
NOSSONUMERO
AGENCIA
CONTA
CAMPO 4 DA LINHA DIGITÁVEL
#################################################
*/
function modulo_11($num, $base=9, $r=0) {
    $soma = 0;
    $fator = 2;
    for ($i = strlen($num); $i > 0; $i--) {
        $numeros[$i] = substr($num,$i-1,1);
        $parcial[$i] = $numeros[$i] * $fator;
        $soma += $parcial[$i];
        if ($fator == $base) {
            $fator = 1;
        }
        $fator++;
    }
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;

        //corrigido

        if ($digito == 10) {
            $digito = "1";
        }

        /*
        alterado por mim, Daniel Schultz
        Vamos explicar:
        O módulo 11 só gera os digitos verificadores do nossonumero,
        agencia, conta e digito verificador com codigo de barras (aquele que fica sozinho e triste na linha digitável)
        só que é foi um rolo...pq ele nao podia resultar em 0, e o pessoal do phpboleto se esqueceu disso...

        No BB, os dígitos verificadores podem ser X ou 0 (zero) para agencia, conta e nosso numero,
        mas nunca pode ser X ou 0 (zero) para a linha digitável, justamente por ser totalmente numérica.
        Quando passamos os dados para a função, fica assim:
        Agencia = sempre 4 digitos
        Conta = até 8 dígitos
        Nosso número = de 1 a 17 digitos
        A unica variável que passa 17 digitos é a da linha digitada, justamente por ter 43 caracteres
        Entao vamos definir ai embaixo o seguinte...
        se (strlen($num) == 43) { não deixar dar digito X ou 0 }
        */

        if (strlen($num) == "43") {
            //então estamos checando a linha digitável
            if ($digito == "0" or $digito == "1" or $digito > 9) {
                $digito = 1;
            }
        }
        return $digito;
    }
    elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
}
/*
Montagem da linha digitável - Função tirada do PHPBoleto
Não mudei nada
*/


function monta_linha_digitavel($linha, $dv) {

    //$campolivre  = "$modalidadecobranca$convenio$nossonumero$numeroparcela";
    //$dv=modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$carteira$agencia$campolivre");
    //$linha="$codigobanco$nummoeda$carteira$dv$fator_vencimento$valor$agencia$campolivre";

    $banco = substr($linha, 0, 3);
    $moeda = substr($linha, 3, 1);
    $carteira = substr($linha, 4, 1);
    $agencia = substr($linha, 5, 4);

    $campo1=$banco.$moeda.$carteira.".".$agencia;
    $campo1 = $campo1.modulo_10($campo1)." ";

    $campolivre = substr($linha, 24);

    $modalidade = substr($campolivre, 0, 2);
    $cliente = substr($campolivre, 2, 7);
    $nossonumero = substr($campolivre, 9, 8);
    $parcela = substr($campolivre, 17, 3);

    $campo2 =  $modalidade.$cliente.substr($nossonumero, 0,1);
    $campo2_linha =  $modalidade.substr($cliente, 0,3).".".substr($cliente, 3,4).substr($nossonumero, 0,1).modulo_10($campo2)." ";
    $campo2 = $campo2.modulo_10($campo2)." ";

    $campo3 = substr($nossonumero, 1, 7).$parcela;
    $campo3_linha = substr($nossonumero, 1, 5).".".substr($nossonumero, 6,2).$parcela.modulo_10($campo3)." ";
    $campo3 = $campo3.modulo_10($campo3);

    $fator_vencimento = substr($linha, 10, 4);
    $valor = substr($linha, 14, 10);
    $campo4 = $fator_vencimento.$valor;

    return $campo1.$campo2_linha.$campo3_linha.$dv." ".$campo4;

    // Posição 	Conteúdo
    // 1 a 3    Número do banco
    // 4        Código da Moeda - 9 para Real
    // 5        Digito verificador do Código de Barras
    // 6 a 19   Valor (12 inteiros e 2 decimais)
    // 20 a 44  Campo Livre definido por cada banco
    // 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
    // do campo livre e DV (modulo10) deste campo
    /*
    $p1 = substr($linha, 0, 4);
    $p2 = substr($linha, 19, 5);
    $p3 = modulo_10("$p1$p2");

    $p4 = "$p1$p2$p3";
    $p5 = substr($p4, 0, 5);
    $p6 = substr($p4, 5);
    $campo1 = "$p5.$p6";
    // 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
    // e livre e DV (modulo10) deste campo
    $p1 = substr($linha, 24, 10);
    $p2 = modulo_10($p1);
    $p3 = "$p1$p2";
    $p4 = substr($p3, 0, 5);
    $p5 = substr($p3, 5);
    $campo2 = "$p4.$p5";
    // 3. Campo composto pelas posicoes 16 a 25 do campo livre
    // e livre e DV (modulo10) deste campo
    //$p1 = substr($linha, 34, 10);
    $p1= substr($linha, 33, 10);
    $p2 = modulo_10($p1);
    $p3 = "$p1$p2";

    $p4 = substr($p3, 0, 6);
    $p5 = substr($p3, 5);
    //die($p5);
    $campo3 = "$p4.$p5";
    // 4. Campo - digito verificador do codigo de barras
    $campo4 = substr($linha, 4, 1);
    // 5. Campo composto pelo valor nominal pelo valor nominal do documento, sem
    // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
    // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
    $campo5 = substr($linha, 5, 14);
    return "$campo1 $campo2 $campo3 $campo4 $campo5";
    */



}

function geraCodigoBanco($numero) {
    $parte1 = substr($numero, 0, 3);
    $parte2 = modulo_11($parte1);
    return $parte1 . "-" . $parte2;
}

?>
