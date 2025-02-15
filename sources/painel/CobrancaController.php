<?php
namespace IowaPainel;
use CnabPHP\Remessa;
use Sicoob\Remessa\CNAB240\Arquivo;
use Sicoob\Remessa\CNAB240\Boleto;

class CobrancaController
{

    static public function getOpcoesCobranca()
    {
        try{
            $opcoes_cobranca = \Opcoes_Cobranca::find(1);
        } catch(\ActiveRecord\RecordNotFound $e){
            $opcoes_cobranca = '';
        }

        return $opcoes_cobranca;
    }

    static public function geraRemessaBancoBrasil($dados)
    {

        $opcoes_cobranca = self::getOpcoesCobranca();
        $dados_banco = UnidadesController::getDadosBanco($dados['id_unidade'], $dados['codigo_banco']);

        try{
            /*Selecionando Unidade*/
            $id_unidade = $dados['id_unidade'];
            $usar_dados = \Unidades::find($id_unidade);

            $cnpj = str_replace('.', '', $usar_dados->cnpj);
            $cnpj = str_replace('/', '', $cnpj);
            $cnpj = str_replace('-', '', $cnpj);

            $nome_empresa = $usar_dados->razao_social;

            $numero_agencia = explode('-', $dados_banco->agencia);
            $numero_conta_corrente = explode('-', $dados_banco->conta);

            //$convenio = explode('-', $usar_dados->codigo_cliente);
            $convenio = str_replace('-', '', $dados_banco->codigo_cliente);

        } catch (\ActiveRecord\RecordNotFound $e){
            $usar_dados = '';
        }

        /*--------------------------------------------------------------------*/
        /*Dados para geração e impressão dos boletos*/
        /*Verificando se os boletos seguirão a ordem automatica ou a indicada na tela de geração*/
        if(isset($dados['iniciar_sequencia'])):
            $numero_inicial = $dados['numero_inicial'];
        else:
            $numero_inicial = '';
        endif;

        /*Verificando se existe uma quantidade máxima a ser gerada*/
        if(isset($dados['quantidade_maxima'])):
            $quantidade_maxima = $dados['quantidade'];
        else:
            $quantidade_maxima = '';
        endif;

        /*Verificando se haverá taxa no boleto*/
        if(isset($dados['adicionar_taxa'])):
            $adicionar_taxa = 's';
            $taxa = str_replace(',', '', $dados['taxa']);
            $taxa = str_replace('.', '', $taxa);
        else:
            $adicionar_taxa = 'n';
            $taxa = '';
        endif;

        /*Verificando se as parcelas serão discriminadas*/
        if(isset($dados['discriminar_observacao'])):
            $discriminar_parcelas = 's';
        else:
            $discriminar_parcelas = 'n';
        endif;

        /*Verificando se o endereço do sacado será impresso*/
        if(isset($dados['imprimir_endereco'])):
            $imprimir_endereco = 's';
        else:
            $imprimir_endereco = 'n';
        endif;

        $instrucoes_atraso = $dados['instrucoes_atraso'];
        $multa = $dados['multa'];

        $instrucoes_mora = $dados['instrucoes_mora'];
        $mora = $dados['juros'];

        $campo_livre1 = $dados['campo_livre1'];
        $campo_livre2 = $dados['campo_livre2'];
        $mensagem_complementar = $dados['mensagem_complementar'];
        /*Fim da coleta de informações que orientam a geração e impressao dos boletos*/
        /*--------------------------------------------------------------------*/

        //$directory = '../../remessas/'.date('m').'/'.date('Y');
        $directory = '../../remessas/';

        $ids_parcelas = explode('|', $dados['parcelas']);
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

                if($parcela->boleto != 's'):

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
                        $nosso_numero = nossoNumero($pega_nosso_numero[0]->nosso_numero+1);

                    else:
                        $nosso_numero_boleto_sem_zero = $pega_nosso_numero[0]->nosso_numero+1;
                        $nosso_numero = nossoNumero($pega_nosso_numero[0]->nosso_numero+1);

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

                    $boleto = new \Boletos();
                    $boleto->chave = md5($numero_documento);
                    $boleto->codigo_banco = $dados_banco->codigo_banco;
                    $boleto->id_unidade = $id_unidade;
                    $boleto->id_parcela = $parcela->id;
                    $boleto->numero_boleto = $numero_documento;

                    if(!empty($adicionar_taxa == 's')):
                        $boleto->taxa = $taxa;
                    else:
                        $boleto->taxa = 0;
                    endif;

                    $boleto->multa = $valor_multa_boleto;
                    $boleto->juros_mora = $valor_juros_boleto;

                    if(!empty($parcela->data_vencimento)):
                        $boleto->data_vencimento = $parcela->data_vencimento->format('Y-m-d');
                    else:
                        $boleto->data_vencimento = null;
                    endif;

                    $boleto->valor = $parcela->total;
                    $boleto->agencia = $agencia_cooperativa;
                    $boleto->conta = $conta_corrente;
                    //$boleto->convenio = '4687693';
                    $boleto->convenio = $convenio[0].$convenio[1];
                    $boleto->nosso_numero = $nosso_numero_boleto_sem_zero;
                    $boleto->sequencia = null;
                    $boleto->data_processamento = date('Y-m-d');
                    $boleto->demonstratitvo1 = $opcoes_cobranca->instrucoes_atraso;
                    $boleto->demonstratitvo2 = $opcoes_cobranca->instrucoes_mora;
                    $boleto->informacoes1 = $campo_livre1;
                    $boleto->informacoes2 = $campo_livre2;
                    $boleto->informacoes3 = $mensagem_complementar;
                    $boleto->informacoes4 = '';
                    $boleto->pago = 'n';
                    $boleto->cancelado = 'n';
                    $boleto->renegociado = 'n';
                    $boleto->save();

                    //adicionando id do boleto a chave do boleto
                    $atualiza_boleto = \Boletos::find_by_id($boleto->id);
                    $atualiza_boleto->chave = $atualiza_boleto->id.'_'.$atualiza_boleto->chave;
                    $atualiza_boleto->save();

                    //adicionaHistorico(idUsuario(), idColega(), 'Geração de Cobrança', 'Inclusão', 'Um boleto para o sacado '.$sacado.' foi gerado sob o numero '. $nosso_numero_boleto);

                    /*marcando a parcela como boleto gerado*/
                    $parcela->boleto = 's';
                    $parcela->numero_boleto = $numero_documento;
                    $parcela->save();

                    /*----------------------------------------------------*/
                    /*Fim Salvando Boletos no Banco de Dados*/

                    $countBoleto++;

                endif;

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
    }

    /*----------------------------------------------------------*/

    static public function geraRemessaSicoob($dados)
    {

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

        $opcoes_cobranca = self::getOpcoesCobranca();
        $dados_banco = UnidadesController::getDadosBanco($dados['id_unidade'], $dados['codigo_banco']);

        try{
            /*Selecionando Unidade*/
            $id_unidade = $dados['id_unidade'];
            $usar_dados = \Unidades::find($id_unidade);
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

        /*--------------------------------------------------------------------*/
        /*Dados para geração e impressão dos boletos*/
        /*Verificando se os boletos seguirão a ordem automatica ou a indicada na tela de geração*/
        if(isset($dados['iniciar_sequencia'])):
            $numero_inicial = $dados['numero_inicial'];
        else:
            $numero_inicial = '';
        endif;

        /*Verificando se existe uma quantidade máxima a ser gerada*/
        if(isset($dados['quantidade_maxima'])):
            $quantidade_maxima = $dados['quantidade'];
        else:
            $quantidade_maxima = '';
        endif;

        /*Verificando se haverá taxa no boleto*/
        if(isset($dados['adicionar_taxa'])):
            $adicionar_taxa = 's';
            $taxa = str_replace(',', '', $dados['taxa']);
            $taxa = str_replace('.', '', $taxa);
        else:
            $adicionar_taxa = 'n';
            $taxa = '';
        endif;

        /*Verificando se as parcelas serão discriminadas*/
        if(isset($dados['discriminar_observacao'])):
            $discriminar_parcelas = 's';
        else:
            $discriminar_parcelas = 'n';
        endif;

        /*Verificando se o endereço do sacado será impresso*/
        if(isset($dados['imprimir_endereco'])):
            $imprimir_endereco = 's';
        else:
            $imprimir_endereco = 'n';
        endif;

        $instrucoes_atraso = $dados['instrucoes_atraso'];
        $multa = $dados['multa'];

        $instrucoes_mora = $dados['instrucoes_mora'];
        $mora = $dados['juros'];

        $campo_livre1 = $dados['campo_livre1'];
        $campo_livre2 = $dados['campo_livre2'];
        $mensagem_complementar = $dados['mensagem_complementar'];
        /*Fim da coleta de informações que orientam a geração e impressao dos boletos*/
        /*--------------------------------------------------------------------*/

        $directory = '../../remessas/'.date('m').'/'.date('Y');

        $ids_parcelas = explode('|', $dados['parcelas']);
        if (empty($ids_parcelas)) {
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Não há boletos para geração do arquivo.'));
            exit();
        }

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        /*verificando o ultimo numero de arquivo*/
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
        foreach ($ids_parcelas as $id_parcela) {

            if(!empty($id_parcela)):

                $parcela = \Parcelas::find($id_parcela);
                //$aluno = Alunos::find($parcela->id_aluno);

                if($parcela->boleto != 's'):

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

                    $dtVencimento = $parcela->data_vencimento->format('dmY');
                    $emissao = date('dmY');
                    $hora = date('His');

                    $valor_boleto = $parcela->total;
                    //$nosso_numero = '000000000001011     ';
                    $pega_nosso_numero = \Boletos::find_by_sql('select max(nosso_numero) as nosso_numero from boletos limit 1');

                    if(empty($pega_nosso_numero)):
                        $nosso_numero_boleto_sem_zero = 1;
                        $nosso_numero = 1;
                        $nosso_numero = str_pad("$nosso_numero", 7, 0, STR_PAD_LEFT);
                        //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,65838), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                        //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,159670), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                        $nosso_numero_boleto = str_pad("$nosso_numero".self::modulo11($nosso_numero, $numero_agencia[0],$convenio[0].$convenio[1]), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    else:
                        $nosso_numero_boleto_sem_zero = $pega_nosso_numero[0]->nosso_numero+1;
                        $nosso_numero = $pega_nosso_numero[0]->nosso_numero+1;
                        $nosso_numero = str_pad("$nosso_numero", 7, 0, STR_PAD_LEFT);
                        //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,65838), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                        //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,159670), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                        $nosso_numero_boleto = str_pad("$nosso_numero".self::modulo11($nosso_numero, $numero_agencia[0],$convenio[0].$convenio[1]), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    endif;

                    //$nosso_numero = '00000000000000      ';
                    $numero_documento = str_pad($parcela->id . $id_cliente,
                        15,
                        '0',
                        STR_PAD_RIGHT);

                    $message3 = "Mensagem 3";
                    $message4 = "Mensagem 4";

                    $tamanho = 40;
                    //$endereco = $endereco;
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
                    $valor_juros_boleto = number_format($valor_boleto*($opcoes_cobranca->juros/100), 2, '.', '');
                    $valor_mora = number_format($valor_boleto * ($opcoes_cobranca->juros / 100), 2, '.', ',');
                    $valor_mora = str_replace(',', '', $valor_mora);
                    $valor_mora = str_replace('.', '', $valor_mora);


                    /*Verificando se haverá multa*/
                    $codigo_multa = 1;
                    $valor_multa_boleto = number_format($valor_boleto*($opcoes_cobranca->multa/100), 2, '.','');
                    $valor_multa = number_format($valor_boleto* ($opcoes_cobranca->multa / 100), 2, '.',',');
                    $valor_multa = str_replace(',', '', $valor_multa);
                    $valor_multa = str_replace('.', '', $valor_multa);
                    //$valor_multa = 225;


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
                            'informacao_3' => $campo_livre1,
                            'informacao_4' => $campo_livre2,
                        ],
                        'segmentS' => [
                            'informacao_5' => $mensagem_complementar,
                            'informacao_6' => "",
                            'informacao_7' => "",
                            'informacao_8' => "",
                            'informacao_9' => "",
                        ]
                    ]);

                    /*----------------------------------------------------*/
                    /*Salvando Boletos no Banco de Dados*/

                    $boleto = new \Boletos();
                    $boleto->chave = md5($numero_documento);
                    $boleto->codigo_banco = $dados_banco->codigo_banco;
                    $boleto->id_unidade = $id_unidade;
                    $boleto->id_parcela = $parcela->id;
                    $boleto->numero_boleto = $numero_documento;

                    if(!empty($adicionar_taxa == 's')):
                        $boleto->taxa = $taxa;
                    else:
                        $boleto->taxa = 0;
                    endif;

                    $boleto->multa = $valor_multa_boleto;
                    $boleto->juros_mora = $valor_juros_boleto;

                    if(!empty($parcela->data_vencimento)):
                        $boleto->data_vencimento = $parcela->data_vencimento->format('Y-m-d');
                    else:
                        $boleto->data_vencimento = null;
                    endif;

                    $boleto->valor = $parcela->total;
                    $boleto->agencia = $agencia_cooperativa;
                    $boleto->conta = $conta_corrente;
                    //$boleto->convenio = '4687693';
                    $boleto->convenio = $convenio[0].$convenio[1];
                    $boleto->nosso_numero = $nosso_numero_boleto_sem_zero;
                    $boleto->sequencia = null;
                    $boleto->data_processamento = date('Y-m-d');
                    $boleto->demonstratitvo1 = $opcoes_cobranca->instrucoes_atraso;
                    $boleto->demonstratitvo2 = $opcoes_cobranca->instrucoes_mora;
                    $boleto->informacoes1 = $campo_livre1;
                    $boleto->informacoes2 = $campo_livre2;
                    $boleto->informacoes3 = $mensagem_complementar;
                    $boleto->informacoes4 = '';
                    $boleto->pago = 'n';
                    $boleto->cancelado = 'n';
                    $boleto->renegociado = 'n';
                    $boleto->save();

                    //adicionando id do boleto a chave do boleto
                    $atualiza_boleto = \Boletos::find_by_id($boleto->id);
                    $atualiza_boleto->chave = $atualiza_boleto->id.'_'.$atualiza_boleto->chave;
                    $atualiza_boleto->save();

                    adicionaHistorico(idUsuario(), idColega(), 'Geração de Cobrança', 'Inclusão', 'Um boleto para o sacado '.$sacado.' foi gerado sob o numero '. $nosso_numero_boleto);

                    /*marcando a parcela como boleto gerado*/
                    $parcela->boleto = 's';
                    $parcela->numero_boleto = $numero_documento;
                    $parcela->save();

                    /*----------------------------------------------------*/
                    /*Fim Salvando Boletos no Banco de Dados*/

                    $arquivo->addBoleto($Boleto);

                    $countBoleto++;

                endif;

            endif;

        }

        $filename = '../../remessas/remessa_' . date('Y') . '_' . date('m') . "_" . $numero_arquivo . '_boletos.rem';
        $path = $path . $filename;
        $arquivo->render(false, $path, true);
        $arquivo =  $directory . $filename;


        /*Salvando nome do arquivo no banco de dados*/
        $arquivo_cnab = \Arquivos_Cnab::find($id_arquivo_cnab);
        $arquivo_cnab->arquivo = 'remessa_' . date('Y') . '_' . date('m') . "_" . $numero_arquivo . '_boletos.rem';
        $arquivo_cnab->save();

    }

    static public function modulo11($index, $ag, $conv) {
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

}