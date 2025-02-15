<?php
namespace IowaPainel;
use Cnab\Factory;
use CnabPHP\Retorno;
use IowaGeral\Rotas;

use Sicoob\Retorno\CNAB240\Arquivo;
use Sicoob\Retorno\CNAB240\Boleto;
include_once('../../classes/Sicoob/Retorno/CNAB240/LineAbstract.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/Boleto.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/Arquivo.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/Header.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/HeaderLote.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/Trailer.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/TrailerLote.php');

include_once('../../classes/Sicoob/Retorno/CNAB240/SegmentT.php');
include_once('../../classes/Sicoob/Retorno/CNAB240/SegmentU.php');

include_once('../../classes/Sicoob/Retorno/Fields/Field.php');
include_once('../../classes/Sicoob/Helpers/Helper.php');

include_once('../../config.php');
//include_once('../../funcoes_painel.php');

class RetornoBancoController extends Rotas
{

    static public function retornoSicoob($caixa, $arquivo)
    {

        $arquivoObj = new \Sicoob\Retorno\CNAB240\Arquivo('retorno/'.$arquivo);

        /*Array com numeros dos numero dos boletos e ids das parcelas*/
        $umeros_boletos = '';
        $ids_parcelas = '';
        /*--------*/

        $boletos = [];
        $boletos_recebidos = [];
        $num_boletos_recebidos = 0;
        $num_boletos_n_recebidos = 0;

        foreach($arquivoObj->boletos as $boleto):

            $boleto->codigoMov = $boleto->segmentT->fields['codigo_movimento_retorno']->value;
            $boleto->numeroInscricao = $boleto->segmentT->fields['numero_inscricao']->value;
            $boleto->vencimento = $boleto->segmentT->fields['data_vencimento']->value;
            $numero_documento = $boleto->segmentT->fields['numero_documento']->value;

            //Busca o boleto registrado no banco de dados e dar baixa

            //echo $boleto->segmentT->fields['codigo_movimento_retorno']->value;

            //if((int)$boleto->segmentT->fields['codigo_movimento_retorno']->value == 1 || (int)$boleto->segmentT->fields['codigo_movimento_retorno']->value == 2):
            if(((int)$boleto->segmentT->fields['codigo_movimento_retorno']->value == 06)):

                //echo (string)$boleto->segmentT->fields['numero_documento']->value;

                try{
                    $busca_boleto = \Boletos::find_by_numero_boleto_and_pago_and_cancelado_and_renegociado((string)$boleto->segmentT->fields['numero_documento']->value, 'n', 'n', 'n');
                    if(!empty($busca_boleto)):

                        if($busca_boleto->pago != 's'):

                            $data_pagamento = implode('-', array_reverse(explode('/', (string)$boleto->segmentU->fields['data_evento']->value)));
                            $data_credito = implode('-', array_reverse(explode('/', (string)$boleto->segmentU->fields['data_credito']->value)));

                            /*Marcando boleto como pago*/
                            $busca_boleto->pago = 's';
                            $busca_boleto->juros_mora = (string)$boleto->segmentU->fields['valor_juros']->value;
                            $busca_boleto->data_pagamento = $data_pagamento;
                            $busca_boleto->valor_pago = (string)$boleto->segmentU->fields['valor_pago']->value;
                            $busca_boleto->save();

                            /*Marcando parcela do boleto como paga*/
                            $parcela = \Parcelas::find($busca_boleto->id_parcela);
                            $parcela->valor_pago = (string)$boleto->segmentU->fields['valor_pago']->value;
                            $parcela->pago = 's';
                            $parcela->data_pagamento = $data_pagamento;
                            $parcela->id_forma_pagamento = 7;
                            $parcela->save();

                            /*Gerando o Movimento*/
                            //$caixa = Caixas::find($caixa_selecionado->id_caixa);
                            $ultimo_movimento = \Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa->id), 'order' => 'numero desc', 'limit' => 1));
                            $numero_movimento = $ultimo_movimento->numero+1;

                            $movimento = new \Movimentos_Caixa();
                            $movimento->id_caixa = $caixa->id;
                            $movimento->numero = $numero_movimento;
                            $movimento->data = $data_credito;
                            $movimento->hora = date('H:i:s');
                            $movimento->total = $parcela->total;
                            $movimento->descricao = 'Pagamento de Mensalidade';
                            $movimento->id_aluno = $parcela->id_aluno;
                            $movimento->tipo = 'e';
                            $movimento->id_forma_pagamento = 7;
                            $movimento->save();

                            $id_movimento = $movimento->id;

                            /*Gerando detalhes do movimento*/
                            $detalhe = new \Detalhes_Movimento();
                            $detalhe->id_movimento = $id_movimento;
                            $detalhe->id_parcela = $parcela->id;
                            $detalhe->numero_movimento = $numero_movimento;
                            $detalhe->total = $parcela->total;
                            $detalhe->save();

                            $boletos_recebidos[] = $busca_boleto->id;
                            $num_boletos_recebidos = ($num_boletos_recebidos+1);

                            /*Acrescentando numero de boleto à variavel número_boletos e ids das parcelas na varivável ids_parcelas*/
                            $umeros_boletos .= (string)$boleto->segmentT->fields['numero_documento']->value.',';
                            $ids_parcelas .= $parcela->id.',';

                        endif;
                    endif;
                } catch (\ActiveRecord\RecordNotFound $e){
                    $num_boletos_n_recebidos++;
                }

            else:

                $num_boletos_n_recebidos = $num_boletos_n_recebidos+1;

            endif;

            //$boleto->verifica = $verifica;
            array_push($boletos, $boleto);

            $retorno = '';
            $retorno .= 'Resultado do Arquivo de retorno '.$arquivo.' importado em: '.date('d/m/Y H:i:s')."\r\n";
            if(!empty($boletos_recebidos)):
                foreach($boletos_recebidos as $boleto_recebido):
                    $boleto_info = \Boletos::find($boleto_recebido->id);
                    $parcela = \Parcelas::find($boleto_info->id_parcela);

                    $nome = '';
                    if($parcela->pagante == 'aluno'):
                        $aluno = \Alunos::find($parcela->id_aluno);
                        $nome = $aluno->nome;
                    elseif($parcela->pagante == 'empresa'):
                        $empresa = \Empresas::find($parcela->id_empresa);
                        $nome = $empresa->razao_social.' / '.$empresa->nome_fantasia;
                    endif;

                    $retorno.= $nome. ' - boleto numero: '.$boleto_info->numero_boleto.'<br>';

                endforeach;

            endif;

        endforeach;

        $retorno .= 'Numero de Boletos recebidos: '.$num_boletos_recebidos."\r\n";
        $retorno .= 'Numero de Boletos não recebidos: '.$num_boletos_n_recebidos."\r\n";

        $nome_arquivo = "resultado_retorno_".date('d_m_Y_H_m_i_s').".txt";
        $fp = fopen("retorno/".$nome_arquivo, "w+");
        fwrite($fp, $retorno."\r\n");
        fclose($fp);

        /*Gravando nome do arquivo no banco de dados*/
        $resultado_retorno = new \Resultado_Retornos();
        //$resultado_retorno->data = date('Y-m-d H:i:s');
        $resultado_retorno->data = $data_pagamento;
        $resultado_retorno->arquivo = $nome_arquivo;
        $resultado_retorno->boletos = $umeros_boletos;
        $resultado_retorno->parcelas = $ids_parcelas;
        $resultado_retorno->save();

    }



    static public function retornoBancoBrasil($caixa, $arquivo_retorno)
    {

        $cnabFactory = new Factory();
        $dados_retorno = $cnabFactory->createRetorno('retorno/'.$arquivo_retorno);


        /*Array com numeros dos numero dos boletos e ids das parcelas*/
        $umeros_boletos = '';
        $ids_parcelas = '';
        /*--------*/

        $boletos = [];
        $boletos_recebidos = [];
        $num_boletos_recebidos = 0;
        $num_boletos_n_recebidos = 0;

        $registros = $dados_retorno->listDetalhes();

        $count = 3;
        foreach($registros as $boleto):

            $codigo_movimento = $boleto->arquivo->linhas[$count]->linhaCnab->codigo_movimento;

            $nosso_numero = $boleto->arquivo->linhas[$count-1]->linhaCnab->nosso_numero;
            $nosso_numero = substr($nosso_numero, -10);
            $nosso_numero = ltrim($nosso_numero, "0");

            //$boleto->arquivo->linhas[$count]->linhaCnab->data_credito
            $data_credito = \DateTime::createFromFormat('dmY', sprintf('%08d', $boleto->arquivo->linhas[$count]->linhaCnab->data_credito));
            $data_ocorrencia = \DateTime::createFromFormat('dmY', sprintf('%08d', $boleto->arquivo->linhas[$count]->linhaCnab->data_ocorrencia));
            $valor_pago = $boleto->arquivo->linhas[$count]->linhaCnab->valor_pago;
            $valor_juros = $boleto->arquivo->linhas[$count]->linhaCnab->valor_acrescimos;

            $count += 2;

            if($codigo_movimento == 6):

                try{
                    $busca_boleto = \Boletos::find_by_nosso_numero_and_pago_and_cancelado_and_renegociado($nosso_numero, 'n', 'n', 'n');
                    if(!empty($busca_boleto)):

                        if($busca_boleto->pago != 's'):

                            /*
                            $data_pagamento = implode('-', array_reverse(explode('/', (string)$boleto->segmentU->fields['data_evento']->value)));
                            $data_credito = implode('-', array_reverse(explode('/', (string)$boleto->segmentU->fields['data_credito']->value)));
                            */

                            /*Marcando boleto como pago*/
                            $busca_boleto->pago = 's';
                            $busca_boleto->juros_mora = $valor_juros;
                            $busca_boleto->data_pagamento = $data_ocorrencia->format('Y-m-d');
                            $busca_boleto->valor_pago = $valor_pago;
                            $busca_boleto->save();

                            /*Marcando parcela do boleto como paga*/
                            $parcela = \Parcelas::find($busca_boleto->id_parcela);
                            $parcela->valor_pago = $valor_pago;
                            $parcela->pago = 's';
                            $parcela->data_pagamento = $data_ocorrencia->format('Y-m-d');
                            $parcela->id_forma_pagamento = 7;
                            $parcela->save();

                            /*Gerando o Movimento*/
                            //$caixa = Caixas::find($caixa_selecionado->id_caixa);
                            $ultimo_movimento = \Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa->id), 'order' => 'numero desc', 'limit' => 1));
                            $numero_movimento = $ultimo_movimento->numero+1;

                            $movimento = new \Movimentos_Caixa();
                            $movimento->id_caixa = $caixa->id;
                            $movimento->numero = $numero_movimento;
                            $movimento->data = $data_credito;
                            $movimento->hora = date('H:i:s');
                            $movimento->total = $parcela->total;
                            $movimento->descricao = 'Pagamento de Mensalidade';
                            $movimento->id_aluno = $parcela->id_aluno;
                            $movimento->tipo = 'e';
                            $movimento->id_forma_pagamento = 7;
                            $movimento->save();

                            $id_movimento = $movimento->id;

                            /*Gerando detalhes do movimento*/
                            $detalhe = new \Detalhes_Movimento();
                            $detalhe->id_movimento = $id_movimento;
                            $detalhe->id_parcela = $parcela->id;
                            $detalhe->numero_movimento = $numero_movimento;
                            $detalhe->total = $parcela->total;
                            $detalhe->save();

                            $boletos_recebidos[] = $busca_boleto->id;
                            $num_boletos_recebidos++;

                            //echo $num_boletos_recebidos;

                            /*Acrescentando numero de boleto à variavel número_boletos e ids das parcelas na varivável ids_parcelas*/
                            $umeros_boletos .= $busca_boleto->numero_boleto.',';
                            $ids_parcelas .= $parcela->id.',';

                        endif;
                    endif;
                } catch (\ActiveRecord\RecordNotFound $e){
                    $num_boletos_n_recebidos++;
                }

            else:

                $num_boletos_n_recebidos = $num_boletos_n_recebidos+1;

            endif;

            /*
            $boleto->codigoMov = $boleto->segmentT->fields['codigo_movimento_retorno']->value;
            $boleto->numeroInscricao = $boleto->segmentT->fields['numero_inscricao']->value;
            $boleto->vencimento = $boleto->segmentT->fields['data_vencimento']->value;
            $numero_documento = $boleto->segmentT->fields['numero_documento']->value;
            */

            //$boleto->verifica = $verifica;
            array_push($boletos, $boleto);

            $retorno = '';
            $retorno .= 'Resultado do Arquivo de retorno '.$arquivo_retorno.' importado em: '.date('d/m/Y H:i:s')."\r\n";
            if(!empty($boletos_recebidos)):
                foreach($boletos_recebidos as $boleto_recebido):
                    $boleto_info = \Boletos::find($boleto_recebido->id);
                    $parcela = \Parcelas::find($boleto_info->id_parcela);

                    $nome = '';
                    if($parcela->pagante == 'aluno'):
                        $aluno = \Alunos::find($parcela->id_aluno);
                        $nome = $aluno->nome;
                    elseif($parcela->pagante == 'empresa'):
                        $empresa = \Empresas::find($parcela->id_empresa);
                        $nome = $empresa->razao_social.' / '.$empresa->nome_fantasia;
                    endif;

                    $retorno.= $nome. ' - boleto numero: '.$boleto_info->numero_boleto."\r\n";

                endforeach;

            endif;

        endforeach;

        $retorno .= 'Numero de Boletos recebidos: '.$num_boletos_recebidos."\r\n";
        $retorno .= 'Numero de Boletos não recebidos: '.$num_boletos_n_recebidos."\r\n";

        $nome_arquivo = "resultado_retorno_".date('d_m_Y_H_m_i_s').".txt";
        $fp = fopen("retorno/".$nome_arquivo, "w+");
        fwrite($fp, $retorno."\r\n");
        fclose($fp);

        /*Gravando nome do arquivo no banco de dados*/
        $resultado_retorno = new \Resultado_Retornos();
        $resultado_retorno->data = date('Y-m-d H:i:s');
        //$resultado_retorno->data = $data_ocorrencia->format('Y-m-d');
        $resultado_retorno->arquivo = $nome_arquivo;
        $resultado_retorno->boletos = $umeros_boletos;
        $resultado_retorno->parcelas = $ids_parcelas;
        $resultado_retorno->save();

    }

}