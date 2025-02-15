<?php
namespace IowaPainel;

use CnabPHP\Remessa;
use Sicoob\Remessa\CNAB240\Arquivo;
use Sicoob\Remessa\CNAB240\Boleto;
include_once('../../classes/Sicoob/Remessa/Fields/Field.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/LineAbstract.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/Arquivo.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/HeaderArquivo.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/HeaderLote.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/TrailerLote.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/TrailerArquivo.php');
include_once('../../classes/Sicoob/Helpers/Helper.php');

include_once('../../classes/Sicoob/Remessa/CNAB240/Boleto.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/SegmentP.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/SegmentQ.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/SegmentR.php');
include_once('../../classes/Sicoob/Remessa/CNAB240/SegmentS.php');

class BoletoController
{

    public static function novoBoletoBB($dados)
    {

        $adicionar_taxa = '';
        $boleto_original = \Boletos::find($dados['id_boleto']);

        /*Gerar novo boleto e arquivo cnab*/
        try{
            $opcoes_cobranca = \Opcoes_Cobranca::find(1);
        } catch(\ActiveRecord\RecordNotFound $e){
            $opcoes_cobranca = '';
        }

        try{
            /*Selecionando Unidade*/
            $id_unidade = $boleto_original->id_unidade;
            $usar_dados = \Unidades::find($id_unidade);
            $dados_banco = UnidadesController::getDadosBanco($boleto_original->id_unidade, $boleto_original->codigo_banco);

            //$usar_dados = Unidades::find_by_usar_dados_boleto('s');

            $cnpj = str_replace('.', '', $usar_dados->cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);

            $nome_empresa = $usar_dados->razao_social;

            $numero_agencia = explode('-', $dados_banco->agencia);
            $numero_conta_corrente = explode('-', $dados_banco->conta);

            //$convenio = explode('-', $dados_banco->codigo_cliente);
            $convenio = str_replace('-', '', $dados_banco->codigo_cliente);

        } catch (\ActiveRecord\RecordNotFound $e){
            $usar_dados = '';
        }

        $parcela = \Parcelas::find($boleto_original->id_parcela);

        $nova_parcela = new \Parcelas();
        $nova_parcela->id_matricula = $parcela->id_matricula;
        $nova_parcela->id_turma = $parcela->id_turma;
        $nova_parcela->id_idioma = $parcela->id_idioma;
        $nova_parcela->id_aluno = $parcela->id_aluno;
        $nova_parcela->id_empresa = $parcela->id_empresa;
        $nova_parcela->pagante = $parcela->pagante;
        $nova_parcela->id_motivo = $parcela->id_motivo;
        $nova_parcela->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));


        $data_atual = new \DateTime("now");
        $dias = $parcela->data_vencimento->diff($data_atual);
        //$dias_atraso = $dias->d;
        $dias_atraso = $dias->format('%R%a');

        $valor = str_replace(".", "", $dados['valor_parcela']);
        $valor = str_replace(",", ".", $valor);
        $nova_parcela->valor = $valor;

        if($dados['importar_acrescimos'] == 's'):
            if($dias_atraso > 0):
                $multa = $valor*($opcoes_cobranca->multa/100);
            else:
                $multa = 0;
            endif;

            if($dias_atraso > 0):
                $juros_mora = ($valor*($opcoes_cobranca->juros/100))*$dias_atraso;
            else:
                $juros_mora = 0;
            endif;
        elseif($dados['importar_acrescimos'] == 'n'):
            $multa = 0;
            $juros_mora = 0;
        endif;

        $nova_parcela->juros = $juros_mora;


        /*importação de acrescimos*/
        if($dados['importar_acrescimos'] == 's'):
            $nova_parcela->juros = $juros_mora;
            $nova_parcela->multa = $multa;
            $nova_parcela->acrescimo = $parcela->acrescimo;
            $nova_parcela->total = $valor /*+ $parcela->acrescimo + $multa + $juros_mora*/;
        else:
            $nova_parcela->juros = 0;
            $nova_parcela->multa = 0;
            $nova_parcela->acrescimo = 0;
            $nova_parcela->total = $valor;
        endif;

        $nova_parcela->desconto = 0;
        $nova_parcela->pago = 'n';
        $nova_parcela->cancelada = 'n';
        $nova_parcela->renegociada = 'n';
        $nova_parcela->boleto = 's';
        $nova_parcela->save();

        $id_nova_parcela = $nova_parcela->id;

        /*Cenceslando Parcela Original*/
        $parcela->cancelada = 's';
        $parcela->renegociada = 's';
        $parcela->observacoes = 'Parcela renegociada em '.date('d/m/Y H:i:s');
        $parcela->save();
        //criar observação dizendo que a parcela foi cancelada

        /*-----------------------------------------------------------------------------*/
        /*-----------------------------------------------------------------------------*/

        //$directory = '../../remessas/'.date('m').'/'.date('Y');
        $directory = '../../remessas/';

        $ids_parcelas = explode('|', $id_nova_parcela);
        if (empty($ids_parcelas)) {
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Não há boletos para geração do arquivo.'));
            exit();
        }

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $arquivo_cnab = new \Arquivos_Cnab();
        $arquivo_cnab->data = date('Y-m-d H:i:s');
        $arquivo_cnab->save();
        $id_arquivo_cnab = $arquivo_cnab->id;

        $numero_arquivo = $arquivo_cnab->id;

        $emissao = date('dmY');
        $hora = date('His');
        //Lote fixo ou realize o registro no banco de dados e torne-o incremental.
        $lote = 1;
        $inscricao = 2;
        //CNPJ do CEDENTE
        $numero_inscricao = $cnpj;
        //RAZAO SOCIAL CEDENTE
        $nome_empresa = $nome_empresa;
        $agencia_cooperativa = $numero_agencia[0];
        $dv_prefixo = $numero_agencia[1];
        $conta_corrente = $numero_conta_corrente[0];
        $dv_conta_corrente = $numero_conta_corrente[1];

        $arquivo = new Remessa("001",'cnab240', array(

            //Informações da emrpesa recebedora
            'tipo_inscricao'  	=>	$inscricao, // 1 para cpf, 2 cnpj
            'numero_inscricao'	=>	$numero_inscricao, // seu cpf ou cnpj completo
            'agencia'       	=>	$agencia_cooperativa, // agencia sem o digito verificador
            'agencia_dv'    	=>	$dv_prefixo, // somente o digito verificador da agencia
            'conta'         	=> 	$conta_corrente, // número da conta
            'conta_dv'     		=> 	$dv_conta_corrente, // digito da conta
            'nome_empresa' 		=>	$nome_empresa, // seu nome de empresa
            'numero_sequencial_arquivo'	=>	$numero_arquivo, //Deve ter no máximo 5 dígitos, pode ficar com zeros.
            'convenio'	=> $convenio, // codigo fornecido pelo banco
            'carteira'	=> '17', // codigo fornecido pelo banco
            'situacao_arquivo' =>'', // Deve ficar em branco para ser aceito. (TS para testes)
            'uso_bb1' => '00'.$convenio.'001417019', //Deve ter 18 dígitos
            //Deve ser preenchido no seguinte formato: convênio + 0014 + carteira + variação da carteira, com zeros a esquerda
            'filler5' => " ",
            'filler6' => " ",
            'filler7' => " ",
        ));

        $lote  = $arquivo->addLote(array(
            'tipo_servico'=> '1', //1 para cobrança registrada, 2 para sem registro
            'variacao' => '019' //Variação da carteira
        ));

        $countBoleto = 0;
        foreach ($ids_parcelas as $id_parcela):

            if(!empty($id_parcela)):

                $parcela = \Parcelas::find($id_parcela);
                $parcela_atual = \Parcelas::find($id_nova_parcela);

                //if($parcela->boleto != 's'):

                    if($parcela->pagante == 'aluno'):

                        $dados_sacado = \Alunos::find($parcela->id_aluno);

                        try{
                            $matricula = \Matriculas::find($parcela->id_matricula);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $matricula = '';
                        }


                        $id_cliente = $dados_sacado->id;
                        $tipo_inscricao = 1;

                        if($matricula->responsavel_financeiro == 3):

                            try{
                                $cidade = \Cidades::find($dados_sacado->cidade);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $cidade = '';
                            }

                            try{
                                $estado = \Estados::find($dados_sacado->estado);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $estado = '';
                            }

                            $sacado = $dados_sacado->nome;
                            $cpf_cnpj = $dados_sacado->cpf;
                            $endereco = $dados_sacado->endereco.' ,'.$dados_sacado->numero;
                            $bairro = $dados_sacado->bairro;
                            $cep = substr($dados_sacado->cep, 0, 5);
                            $sufixo_cep = substr($dados_sacado->cep, -3);

                        elseif($matricula->responsavel_financeiro == 1):

                            try{
                                $cidade = \Cidades::find($dados_sacado->cidade_responsavel);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $cidade = '';
                            }

                            try{
                                $estado = \Estados::find($dados_sacado->estado_responsavel);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $estado = '';
                            }

                            $sacado = $dados_sacado->nome_responsavel;
                            $cpf_cnpj = $dados_sacado->cpf_responsavel;
                            $endereco = $dados_sacado->endereco_responsavel.' ,'.$dados_sacado->numero_responsavel;
                            $bairro = $dados_sacado->bairro_responsavel;
                            $cep = substr($dados_sacado->cep_responsavel, 0, 5);
                            $sufixo_cep = substr($dados_sacado->cep_responsavel, -3);
                        endif;

                    else:

                        $dados_sacado = \Empresas::find($parcela->id_empresa);

                        try{
                            $cidade = \Cidades::find($dados_sacado->cidade);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $cidade = '';
                        }

                        try{
                            $estado = \Estados::find($dados_sacado->estado);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $estado = '';
                        }

                        $id_cliente = $dados_sacado->id;
                        $tipo_inscricao = 2;
                        $cpf_cnpj = $dados_sacado->cnpj;
                        $sacado = $dados_sacado->nome_fantasia;
                        $endereco = $dados_sacado->rua.' ,'.$dados_sacado->numero;
                        $bairro = $dados_sacado->bairro;
                        $cep = substr($dados_sacado->cep, 0, 5);
                        $sufixo_cep = substr($dados_sacado->cep, -3);

                    endif;

                    $dtVencimento = $parcela->data_vencimento->format('Ymd');

                    $emissao = date('dmY');
                    $hora = date('His');

                    $valor_boleto = $parcela->total;

                    $pega_nosso_numero = \Boletos::find_by_sql('select max(nosso_numero) as nosso_numero from boletos limit 1');
                    //$nosso_numero = nossoNumero($convenio, ($pega_nosso_numero[0]->nosso_numero)+1);

                    if(empty($pega_nosso_numero)):
                        $nosso_numero_boleto_sem_zero = 1;
                        $nosso_numero = 1;
                        $nosso_numero = self::nossoNumero($pega_nosso_numero[0]->nosso_numero+1);

                    else:
                        $nosso_numero_boleto_sem_zero = $pega_nosso_numero[0]->nosso_numero+1;
                        $nosso_numero = self::nossoNumero($pega_nosso_numero[0]->nosso_numero+1);

                    endif;

                    /*Verificando se haverá juros*/
                    $codigo_juros = 1;
                    $valor_juros_boleto = number_format($valor_boleto*($opcoes_cobranca->juros/100), 2, '.', '');
                    $valor_mora = number_format($valor_boleto * ($opcoes_cobranca->juros / 100), 2, '.', ',');
                    $valor_mora = str_replace(',', '', $valor_mora);
                    $valor_mora = str_replace('.', '', $valor_mora);

                    //$nosso_numero = '00000000000000      ';
                    $numero_documento = str_pad($parcela->id . $id_cliente,
                        15,
                        '0',
                        STR_PAD_RIGHT);

                    /*Verificando se haverá multa*/
                    $codigo_multa = 1;
                    $valor_multa_boleto = number_format($valor_boleto*($opcoes_cobranca->multa/100), 2, '.','');
                    $valor_multa = number_format($valor_boleto* ($opcoes_cobranca->multa / 100), 2, '.',',');
                    $valor_multa = str_replace(',', '', $valor_multa);
                    $valor_multa = str_replace('.', '', $valor_multa);
                    //$valor_multa = 225;

                    $lote->inserirDetalhe(array(
                        //Registro 3P Dados do Boleto
                        'nosso_numero'      => $nosso_numero, // numero sequencial de boleto
                        //Consulte a pág. 9 da documentação para mais informações sobre o nosso número
                        //'nosso_numero_dv'   =>	1, // pode ser informado ou calculado pelo sistema
                        'parcela' 			=>	'01',
                        'modalidade'		=>	'01',
                        'tipo_formulario'	=>	'4',
                        'codigo_carteira'   =>	'1', // codigo da carteira ()
                        'com_registro' => 1,
                        'aceite' => 'N',
                        //1 – para carteira 11/12 na modalidade Simples
                        //2 ou 3 – para carteira 11/17 modalidade Vinculada/Caucionada e carteira 31
                        //4 – para carteira 11/17 modalidade Descontada e carteira 51
                        //7 – para carteira 17 modalidade Simples
                        //'carteira'   		=>	'17', // codigo da carteira
                        'seu_numero'        =>	"",// se nao informado usarei o nosso numero
                        'data_vencimento'   =>	$dtVencimento, // informar a data neste formato AAAA-MM-DD
                        'valor'             =>	$valor_boleto, // Valor do boleto como float valido em php
                        //'cod_emissao_boleto'=>	'2', // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
                        'emissao_boleto'=>	'2', // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
                        'especie_titulo'    => 	"DM", // informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
                        'data_emissao'      => 	date('Y-m-d'), // informar a data neste formato AAAA-MM-DD
                        'codigo_moeda'      => '09',
                        'codigo_juros'		=>	'2', // Taxa por mês,
                        'data_juros'   	  	=> 	$dtVencimento, // data dos juros, mesma do vencimento
                        'vlr_juros'         => 	$valor_mora, // Valor do juros/mora informa 1% e o sistema recalcula a 0,03% por
                        // Você pode inserir desconto se houver, ou deixar em branco
                        //'codigo_desconto'	=>	'1',
                        //'data_desconto'		=> 	'2018-04-15', // inserir data para calcular desconto
                        //'vlr_desconto'		=> 	'0', // Valor do desconto
                        //'vlr_IOF'			=> 	'0',
                        'protestar'         => 	'1', // 1 = Protestar com (Prazo) dias, 3 = Devolver após (Prazo) dias
                        'prazo_protesto'    => 	'29', // Informar o numero de dias apos o vencimento para iniciar o protesto
                        'identificacao_contrato'	=>	"0000000000", //Campo não tratado pelo sistema. Pode ser informado 'zeros' ou o número do contrato de cobrança.

                        // Registro 3Q [PAGADOR]
                        'tipo_inscricao'    => $tipo_inscricao, //campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
                        'numero_inscricao'  => $cpf_cnpj,//cpf ou ncpj do pagador
                        'nome_pagador'      => $sacado, // O Pagador é o cliente, preste atenção nos campos abaixo
                        'endereco_pagador'  => $endereco,
                        'bairro_pagador'    => $bairro,
                        'cep_pagador'       => $cep.'-'.$sufixo_cep, // com hífem
                        'cidade_pagador'    => $cidade->nome,
                        'uf_pagador'        => $estado->uf,

                        // Registro 3R Multas, descontos, etc
                        // Você pode inserir desconto se houver, ou deixar em branco, mas quando informar
                        // deve preencher os 3 campos: codigo, data e valor
                        'codigo_multa'		=>	$codigo_multa, // Taxa por mês
                        'data_multa'   	  	=> 	$dtVencimento, // data dos juros, mesma do vencimento
                        'vlr_multa'         => 	$valor_multa, // Valor do juros de 2% ao mês

                        // Registro 3S3 Mensagens a serem impressas
                        'mensagem_sc_1' 	=> $mensagem_complementar,
                        'mensagem_sc_2' 	=> "",
                        'mensagem_sc_3' 	=> "",
                        'mensagem_sc_4' 	=> "",

                    ));

                    /*----------------------------------------------------*/
                    /*Salvando Boletos no Banco de Dados*/

                    $novo_boleto = new \Boletos();
                    $novo_boleto->chave = md5($numero_documento);
                    $novo_boleto->codigo_banco = $boleto_original->codigo_banco;
                    $novo_boleto->id_parcela = $parcela_atual->id;
                    $novo_boleto->numero_boleto = $numero_documento;

                    if(!empty($adicionar_taxa == 's')):
                        $novo_boleto->taxa = $opcoes_cobranca->taxa;
                    else:
                        $novo_boleto->taxa = 0;
                    endif;

                    $novo_boleto->multa = $valor_multa_boleto;
                    $novo_boleto->juros_mora = $valor_juros_boleto;

                    if(!empty($parcela_atual->data_vencimento)):
                        $novo_boleto->data_vencimento = $parcela_atual->data_vencimento->format('Y-m-d');
                    else:
                        $novo_boleto->data_vencimento = null;
                    endif;

                    $novo_boleto->valor = $valor /*+ $parcela->acrescimo + $multa + $juros_mora*/;
                    $novo_boleto->agencia = $agencia_cooperativa;
                    $novo_boleto->conta = $conta_corrente;
                    $novo_boleto->convenio = $convenio[0].$convenio[1];
                    $novo_boleto->nosso_numero = $nosso_numero_boleto_sem_zero;
                    $novo_boleto->sequencia = null;
                    $novo_boleto->data_processamento = date('Y-m-d');
                    $novo_boleto->demonstratitvo1 = $opcoes_cobranca->instrucoes_atraso;
                    $novo_boleto->demonstratitvo2 = $opcoes_cobranca->instrucoes_mora;
                    $novo_boleto->informacoes1 = $opcoes_cobranca->campo_livre1;
                    $novo_boleto->informacoes2 = $opcoes_cobranca->campo_livre2;
                    $novo_boleto->informacoes3 = $opcoes_cobranca->mensagem_complementar;
                    $novo_boleto->informacoes4 = '';
                    $novo_boleto->pago = 'n';
                    $novo_boleto->cancelado = 'n';
                    $novo_boleto->renegociado = 'n';
                    $novo_boleto->id_unidade = $boleto_original->id_unidade;
                    $novo_boleto->save();

                    //adicionando id do boleto a chave do boleto
                    $atualiza_boleto = \Boletos::find_by_id($novo_boleto->id);
                    $atualiza_boleto->chave = $atualiza_boleto->id.'_'.$atualiza_boleto->chave;
                    $atualiza_boleto->save();

                    $aluno = \Alunos::find($parcela_atual->id_aluno);
                    //adicionaHistorico(idUsuario(), idColega(), 'Boletos', 'Inclusão', 'Um novo boleto de R$ '.number_format($valor, 2, ',','.').' com vencimento em '. $parcela_atual->data_vencimento->format('Y-m-d').' foi incluído para o aluno '.$aluno->nome);
                    adicionaHistorico(idUsuario(), idColega(), 'Boletos', 'Inclusão', 'Um boleto de R$ '.number_format($boleto_original->valor, 2, ',', '.').' com vencimento em '.$boleto_original->data_vencimento->format('d/m/Y').' foi renegociado com o vencimento em '.$parcela_atual->data_vencimento->format('d/m/Y').' e valor de R$'.number_format($valor, 2, ',','.'));

                    /*marcando a parcela como boleto gerado*/
                    $parcela->boleto = 's';
                    $parcela->save();

                    /*Registrando numero do boleto na nova parcela*/
                    $parcela_atual->boleto = 's';
                    $parcela_atual->numero_boleto = $numero_documento;
                    $parcela_atual->save();

                    /*marcando boleto antigo como boleto cancelado*/
                    $boleto_original->cancelado = 's';
                    $novo_boleto->renegociado = 's';
                    $boleto_original->observacoes = 'Boleto renegociado em '.date('d/m/Y H:i:s');
                    $boleto_original->save();

                    /*----------------------------------------------------*/
                    /*Fim Salvando Boletos no Banco de Dados*/

                    $countBoleto++;

                //endif;

            endif;

        endforeach;

        $remessa = utf8_decode($arquivo->getText());
        file_put_contents($directory.$arquivo->getFileName(), $remessa);

        /*Salvando nome do arquivo no banco de dados*/
        $arquivo_cnab = \Arquivos_Cnab::find($id_arquivo_cnab);
        //$arquivo_cnab->arquivo = 'remessa_' . date('Y') . '_' . date('m') . "_" . $numero_arquivo . '_boletos.rem';
        //$arquivo_cnab->arquivo = date('Y').$arquivo->getFileName();
        $arquivo_cnab->arquivo = $arquivo->getFileName();
        $arquivo_cnab->save();

        echo json_encode(array('status' => 'ok'));

    }

    public static function novoBoletoSicoob($dados)
    {

        $boleto_original = \Boletos::find($dados['id_boleto']);

        /*Gerar novo boleto e arquivo cnab*/
        try{
            $opcoes_cobranca = \Opcoes_Cobranca::find(1);
        } catch(\ActiveRecord\RecordNotFound $e){
            $opcoes_cobranca = '';
        }

        try{
            /*Selecionando Unidade*/
            $id_unidade = $boleto_original->id_unidade;
            $usar_dados = \Unidades::find($id_unidade);
            $dados_banco = UnidadesController::getDadosBanco($boleto_original->id_unidade, $boleto_original->codigo_banco);

            //$usar_dados = Unidades::find_by_usar_dados_boleto('s');

            $cnpj = str_replace('.', '', $usar_dados->cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);

            $nome_empresa = $usar_dados->razao_social;

            $numero_agencia = explode('-', $dados_banco->agencia);
            $numero_conta_corrente = explode('-', $dados_banco->conta);

            $convenio = explode('-', $dados_banco->codigo_cliente);

        } catch (\ActiveRecord\RecordNotFound $e){
            $usar_dados = '';
        }

        $parcela = \Parcelas::find($boleto_original->id_parcela);

        $nova_parcela = new \Parcelas();
        $nova_parcela->id_matricula = $parcela->id_matricula;
        $nova_parcela->id_turma = $parcela->id_turma;
        $nova_parcela->id_idioma = $parcela->id_idioma;
        $nova_parcela->id_aluno = $parcela->id_aluno;
        $nova_parcela->id_empresa = $parcela->id_empresa;
        $nova_parcela->pagante = $parcela->pagante;
        $nova_parcela->id_motivo = $parcela->id_motivo;
        $nova_parcela->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));


        $data_atual = new \DateTime("now");
        $dias = $parcela->data_vencimento->diff($data_atual);
        //$dias_atraso = $dias->d;
        $dias_atraso = $dias->format('%R%a');

        $valor = str_replace(".", "", $dados['valor_parcela']);
        $valor = str_replace(",", ".", $valor);
        $nova_parcela->valor = $valor;

        if($dados['importar_acrescimos'] == 's'):
            if($dias_atraso > 0):
                $multa = $valor*($opcoes_cobranca->multa/100);
            else:
                $multa = 0;
            endif;

            if($dias_atraso > 0):
                $juros_mora = ($valor*($opcoes_cobranca->juros/100))*$dias_atraso;
            else:
                $juros_mora = 0;
            endif;
        elseif($dados['importar_acrescimos'] == 'n'):
            $multa = 0;
            $juros_mora = 0;
        endif;

        $nova_parcela->juros = $juros_mora;


        /*importação de acrescimos*/
        if($dados['importar_acrescimos'] == 's'):
            $nova_parcela->juros = $juros_mora;
            $nova_parcela->multa = $multa;
            $nova_parcela->acrescimo = $parcela->acrescimo;
            $nova_parcela->total = $valor /*+ $parcela->acrescimo + $multa + $juros_mora*/;
        else:
            $nova_parcela->juros = 0;
            $nova_parcela->multa = 0;
            $nova_parcela->acrescimo = 0;
            $nova_parcela->total = $valor;
        endif;

        $nova_parcela->desconto = 0;
        $nova_parcela->pago = 'n';
        $nova_parcela->cancelada = 'n';
        $nova_parcela->renegociada = 'n';
        $nova_parcela->boleto = 's';
        $nova_parcela->save();

        $id_nova_parcela = $nova_parcela->id;

        /*Cenceslando Parcela Original*/
        $parcela->cancelada = 's';
        $parcela->renegociada = 's';
        $parcela->observacoes = 'Parcela renegociada em '.date('d/m/Y H:i:s');
        $parcela->save();
        //criar observação dizendo que a parcela foi cancelada

        /*-----------------------------------------------------------------------------*/
        /*-----------------------------------------------------------------------------*/

        /*verificando o ultimo numero de arquivo*/
        $arquivo_cnab = new \Arquivos_Cnab();
        $arquivo_cnab->data = date('Y-m-d H:i:s');
        $arquivo_cnab->save();
        $id_arquivo_cnab = $arquivo_cnab->id;

        $numero_arquivo = $arquivo_cnab->id;

        $ids_parcelas = $id_nova_parcela.'|';
        $parcelas = explode('|', $ids_parcelas);

        $emissao = date('dmY');
        $hora = date('His');
        //Lote fixo ou realize o registro no banco de dados e torne-o incremental.
        $lote = 1;
        $inscricao = 2;
        //CNPJ do CEDENTE
        $numero_inscricao = $cnpj;
        //RAZAO SOCIAL CEDENTE
        $nome_empresa = $nome_empresa;
        $agencia_cooperativa = $numero_agencia[0];
        $dv_prefixo = $numero_agencia[1];
        $conta_corrente = $numero_conta_corrente[0];
        $dv_conta_corrente = $numero_conta_corrente[1];

        $arquivo = new Arquivo();
        $arquivo->fill([
            'lote' => $lote,
            'header' => [
                'inscricao' => $inscricao,
                'numero_inscricao' => $numero_inscricao,
                'nome_empresa' => $nome_empresa,
                'agencia_cooperativa' => $agencia_cooperativa,
                'dv_prefixo' => $dv_prefixo,
                'conta_corrente' => $conta_corrente,
                'dv_conta_corrente' => $dv_conta_corrente,
                'data_geracao' => $emissao,
                'hora_geracao' => $hora,
            ],
            'header_lote' => [
                'inscricao' => $inscricao,
                'numero_inscricao' => $numero_inscricao,
                'nome_empresa' => $nome_empresa,
                'agencia_cooperativa' => $agencia_cooperativa,
                'dv_prefixo' => $dv_prefixo,
                'conta_corrente' => $conta_corrente,
                'dv_conta_corrente' => $dv_conta_corrente,
                'data_gravacao' => $emissao,
                'num_controle_cobranca' => $id_arquivo_cnab,
            ]
        ]);

        $countBoleto = 0;
        foreach ($parcelas as $id_parcela) {

            if(!empty($id_parcela)):

                $parcela_atual = \Parcelas::find($id_nova_parcela);
                //$aluno = Alunos::find($parcela_atual->id_aluno);


                if($parcela_atual->pagante == 'aluno'):
                    $dados_sacado = \Alunos::find($parcela_atual->id_aluno);

                    try{
                        $matricula = \Matriculas::find($parcela->id_matricula);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $matricula = '';
                    }

                    $id_cliente = $dados_sacado->id;
                    $tipo_inscricao = 1;

                    if($matricula->responsavel_financeiro == 3):

                        try{
                            $cidade = \Cidades::find($dados_sacado->cidade);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $cidade = '';
                        }

                        try{
                            $estado = \Estados::find($dados_sacado->estado);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $estado = '';
                        }

                        $sacado = $dados_sacado->nome;
                        $cpf_cnpj = $dados_sacado->cpf;
                        $endereco = $dados_sacado->endereco.' ,'.$dados_sacado->numero;
                        $bairro = $dados_sacado->bairro;
                        $cep = substr($dados_sacado->cep, 0, 5);
                        $sufixo_cep = substr($dados_sacado->cep, -3);

                    elseif($matricula->responsavel_financeiro == 1):

                        try{
                            $cidade = \Cidades::find($dados_sacado->cidade_responsavel);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $cidade = '';
                        }

                        try{
                            $estado = \Estados::find($dados_sacado->estado_responsavel);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $estado = '';
                        }

                        $sacado = $dados_sacado->nome_responsavel;
                        $cpf_cnpj = $dados_sacado->cpf_responsavel;
                        $endereco = $dados_sacado->endereco_responsavel.' ,'.$dados_sacado->numero_responsavel;
                        $bairro = $dados_sacado->bairro_responsavel;
                        $cep = substr($dados_sacado->cep_responsavel, 0, 5);
                        $sufixo_cep = substr($dados_sacado->cep_responsavel, -3);
                    endif;

                else:
                    $dados_sacado = \Empresas::find($parcela_atual->id_empresa);

                    try{
                        $cidade = \Cidades::find($dados_sacado->cidade);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $cidade = '';
                    }

                    try{
                        $estado = \Estados::find($dados_sacado->estado);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $estado = '';
                    }

                    $id_cliente = $dados_sacado->id;
                    $tipo_inscricao = 2;
                    $cpf_cnpj = $dados_sacado->cnpj;
                    $sacado = $dados_sacado->nome_fantasia;
                    $endereco = $dados_sacado->rua.' ,'.$dados_sacado->numero;
                    $bairro = $dados_sacado->bairro;
                    $cep = substr($dados_sacado->cep, 0, 5);
                    $sufixo_cep = substr($dados_sacado->cep, -3);

                endif;

                $dtVencimento = $parcela_atual->data_vencimento->format('dmY');
                $emissao = date('dmY');
                $hora = date('His');

                //$valor_boleto = $parcela_atual->total;
                $valor_boleto = $valor /*+ $parcela->acrescimo + $multa + $juros_mora*/;
                //$nosso_numero = '000000000001011     ';
                $pega_nosso_numero = \Boletos::find_by_sql('select max(nosso_numero) as nosso_numero from boletos limit 1');

                /*
                if(empty($pega_nosso_numero)):
                    $nosso_numero = 1;
                    $nosso_numero_boleto = str_pad(1, 15, '0', STR_PAD_LEFT)."     ";
                else:
                    $nosso_numero = $pega_nosso_numero[0]->nosso_numero+1;
                    $nosso_numero_boleto = str_pad("$nosso_numero", 15, '0', STR_PAD_LEFT)."     ";
                endif;
                */

                if(empty($pega_nosso_numero)):
                    $nosso_numero_boleto_sem_zero = 1;
                    $nosso_numero = 1;
                    $nosso_numero = str_pad("$nosso_numero", 7, 0, STR_PAD_LEFT);
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,65838), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,159670), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,$agencia[0],$convenio[0].$convenio[1]), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    $nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero, $numero_agencia[0],$convenio[0].$convenio[1]), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                else:
                    $nosso_numero_boleto_sem_zero = $pega_nosso_numero[0]->nosso_numero+1;
                    $nosso_numero = $pega_nosso_numero[0]->nosso_numero+1;
                    $nosso_numero = str_pad("$nosso_numero", 7, 0, STR_PAD_LEFT);
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,65838), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,159670), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,$agencia[0],$convenio[0].$convenio[1]), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    $nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero, $numero_agencia[0],$convenio[0].$convenio[1]), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                endif;

                $numero_documento = str_pad($parcela_atual->id . $id_cliente,
                    15,
                    '0',
                    STR_PAD_RIGHT);
                $message3 = "";
                $message4 = "";

                $tamanho = 40;
                //$endereco = $aluno->endereco;
                /*
                Caso o endereço seja maior que o tamanho necessário,
                localiza o "Número do endereço" na string e ajusta a string com o numero cortando
                Somente a parte descritiva
                para que o endereço caiba no espaço e seja localizável.
                */
                if (strlen($endereco) > $tamanho) {
                    preg_match('/^([^\d]*[^\d\s]) *(\d.*)$/', $endereco, $match);
                    $end1 = "";
                    if(isset($match[1])) {
                        $end1 = substr($match[1], 0, 40 - (strlen($match[2]) + 1));
                        $endereco = sprintf("%s,%s", $end1, $match[2]);
                    } else {
                        $endereco = substr($endereco, 0, 40);
                    }
                }

                /*Verificando se haverá juros*/
                $codigo_juros = 1;
                $valor_juros_boleto = number_format($valor_boleto*($opcoes_cobranca->juros/100), 2,  '.', '');
                $valor_mora = number_format($valor_boleto*($opcoes_cobranca->juros/100), 2, '.', ',');
                $valor_mora = str_replace(',', '', $valor_mora);
                $valor_mora = str_replace('.', '', $valor_mora);


                /*Verificando se haverá multa*/
                $codigo_multa = 1;
                $valor_multa_boleto = number_format($valor_boleto*($opcoes_cobranca->multa/100), 2, '.',',');
                $valor_multa = number_format($valor_boleto*($opcoes_cobranca->multa/100), 2, '.',',');
                $valor_multa = str_replace(',', '', $valor_multa);
                $valor_multa = str_replace('.', '', $valor_multa);


                $Boleto = new Boleto();
                $Boleto->fill([
                    'valor' => $valor_boleto,
                    'lote' => $lote,
                    'count' => ($countBoleto + 1),
                    'segmentP' => [
                        'num_cc_agencia_codigo' => $agencia_cooperativa,
                        'digito_verificador' => $dv_prefixo,
                        'conta_corrente' => $conta_corrente,
                        'conta_corrente_dv' => $dv_conta_corrente,
                        'nosso_numero' => $nosso_numero_boleto,
                        'carteira' => 1,
                        'numero_documento' => $numero_documento,
                        'data_vencimento' => $dtVencimento,
                        'data_emissao' => $emissao,
                        'data_juros_mora' => $dtVencimento,
                        'cod_juros_mora' => $codigo_juros,
                        'valor_juros_mora' => $valor_mora,
                    ],
                    'segmentQ' => [
                        'tipo_inscricao_pagador' => $tipo_inscricao,
                        'numero_inscricao' => $cpf_cnpj,
                        'nome' =>  $sacado,
                        'endereco' =>  $endereco,
                        'bairro' =>  $bairro,
                        'CEP' =>  $cep,
                        'sufixo_CEP' =>  $sufixo_cep,
                        'cidade' =>  $cidade->nome,
                        'uf' =>  $estado->uf,
                    ],
                    //É necessário verificar a ordem das mensagens e quais são as que sobrescrevem outras.
                    'segmentR' => [
                        'codigo_multa' => $codigo_multa,
                        'data_multa' => $dtVencimento,
                        'valor_multa' => $valor_multa,
                        'informacao_3' => $opcoes_cobranca->campo_livre1,
                        'informacao_4' => $opcoes_cobranca->campo_livre2,
                    ],
                    'segmentS' => [
                        'informacao_5' => $opcoes_cobranca->mensagem_complementar,
                        'informacao_6' => "",
                        'informacao_7' => "",
                        'informacao_8' => "",
                        'informacao_9' => "",
                    ]
                ]);

                /*----------------------------------------------------*/
                /*Salvando Boletos no Banco de Dados*/

                $novo_boleto = new \Boletos();
                $novo_boleto->chave = md5($numero_documento);
                $novo_boleto->codigo_banco = $boleto_original->codigo_banco;
                $novo_boleto->id_parcela = $parcela_atual->id;
                $novo_boleto->numero_boleto = $numero_documento;

                if(!empty($adicionar_taxa == 's')):
                    $novo_boleto->taxa = $opcoes_cobranca->taxa;
                else:
                    $novo_boleto->taxa = 0;
                endif;

                $novo_boleto->multa = $valor_multa_boleto;
                $novo_boleto->juros_mora = $valor_juros_boleto;

                if(!empty($parcela_atual->data_vencimento)):
                    $novo_boleto->data_vencimento = $parcela_atual->data_vencimento->format('Y-m-d');
                else:
                    $novo_boleto->data_vencimento = null;
                endif;

                $novo_boleto->valor = $valor /*+ $parcela->acrescimo + $multa + $juros_mora*/;
                $novo_boleto->agencia = $agencia_cooperativa;
                $novo_boleto->conta = $conta_corrente;
                $novo_boleto->convenio = $convenio[0].$convenio[1];
                $novo_boleto->nosso_numero = $nosso_numero_boleto_sem_zero;
                $novo_boleto->sequencia = null;
                $novo_boleto->data_processamento = date('Y-m-d');
                $novo_boleto->demonstratitvo1 = $opcoes_cobranca->instrucoes_atraso;
                $novo_boleto->demonstratitvo2 = $opcoes_cobranca->instrucoes_mora;
                $novo_boleto->informacoes1 = $opcoes_cobranca->campo_livre1;
                $novo_boleto->informacoes2 = $opcoes_cobranca->campo_livre2;
                $novo_boleto->informacoes3 = $opcoes_cobranca->mensagem_complementar;
                $novo_boleto->informacoes4 = '';
                $novo_boleto->pago = 'n';
                $novo_boleto->cancelado = 'n';
                $novo_boleto->renegociado = 'n';
                $novo_boleto->id_unidade = $boleto_original->id_unidade;
                $novo_boleto->save();

                //adicionando id do boleto a chave do boleto
                $atualiza_boleto = \Boletos::find_by_id($novo_boleto->id);
                $atualiza_boleto->chave = $atualiza_boleto->id.'_'.$atualiza_boleto->chave;
                $atualiza_boleto->save();

                $aluno = \Alunos::find($parcela_atual->id_aluno);
                //adicionaHistorico(idUsuario(), idColega(), 'Boletos', 'Inclusão', 'Um novo boleto de R$ '.number_format($valor, 2, ',','.').' com vencimento em '. $parcela_atual->data_vencimento->format('Y-m-d').' foi incluído para o aluno '.$aluno->nome);
                adicionaHistorico(idUsuario(), idColega(), 'Boletos', 'Inclusão', 'Um boleto de R$ '.number_format($boleto_original->valor, 2, ',', '.').' com vencimento em '.$boleto_original->data_vencimento->format('d/m/Y').' foi renegociado com o vencimento em '.$parcela_atual->data_vencimento->format('d/m/Y').' e valor de R$'.number_format($valor, 2, ',','.'));

                /*marcando a parcela como boleto gerado*/
                $parcela->boleto = 's';
                $parcela->save();

                /*Registrando numero do boleto na nova parcela*/
                $parcela_atual->boleto = 's';
                $parcela_atual->numero_boleto = $numero_documento;
                $parcela_atual->save();

                /*marcando boleto antigo como boleto cancelado*/
                $boleto_original->cancelado = 's';
                $novo_boleto->renegociado = 's';
                $boleto_original->observacoes = 'Boleto renegociado em '.date('d/m/Y H:i:s');
                $boleto_original->save();

                /*----------------------------------------------------*/
                /*Fim Salvando Boletos no Banco de Dados*/

                $arquivo->addBoleto($Boleto);

                $countBoleto++;

            endif;

        }

        $filename = '../../remessas/remessa_' . date('Y') . '_' . date('m') . "_" . $numero_arquivo . '_boletos.rem';
        $path = $filename;
        $arquivo->render(false, $path, true);
        //$arquivo =  $filename;


        /*Salvando nome do arquivo no banco de dados*/
        $arquivo_cnab = \Arquivos_Cnab::find_by_id($id_arquivo_cnab);
        $arquivo_cnab->arquivo = 'remessa_' . date('Y') . '_' . date('m') . "_" . $numero_arquivo . '_boletos.rem';
        $arquivo_cnab->save();

        echo json_encode(array('status' => 'ok'));

    }

    /*########################*/
    public static function nossoNumero($sequencial)
    {
        $nosso_numero_sem_dv = str_pad($sequencial, 10, 0, STR_PAD_LEFT).str_pad(" ", 3, ' ', STR_PAD_LEFT);
        return $nosso_numero_sem_dv;

    }

}
