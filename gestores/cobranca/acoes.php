<?php
//require_once ("../autoloader.php");

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

include_once('../../config.php');
include_once('../funcoes_painel.php');
//include_once('../../classes/Remessa.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Geração de Cobrança', 'a');

    try{
        $registro = Opcoes_Cobranca::find(1);
        $registro->tipo_acao = $dados['tipo_acao'];

        if(isset($dados['iniciar_sequencia'])):
            $registro->iniciar_sequencia = 's';
        else:
            $registro->iniciar_sequencia = 'n';
        endif;

        $registro->numero_inicial = $dados['numero_inicial'];

        if(isset($dados['quantidade_maxima'])):
            $registro->quantidade_maxima = 's';
        else:
            $registro->quantidade_maxima = 'n';
        endif;

        $registro->quantidade = $dados['quantidade'];

        if(isset($dados['adicionar_taxa'])):
            $registro->adicionar_taxa = 's';
        else:
            $registro->adicionar_taxa = 'n';
        endif;

        $registro->taxa = $dados['taxa'];

        if(isset($dados['discriminar_observacao'])):
            $registro->discriminar_observacao = 's';
        else:
            $registro->discriminar_observacao = 'n';
        endif;

        if(isset($dados['imprimir_endereco'])):
            $registro->imprimir_endereco = 's';
        else:
            $registro->imprimir_endereco = 'n';
        endif;

        $registro->instrucoes_atraso = $dados['instrucoes_atraso'];

        $multa = str_replace(',', '.', $dados['multa']);
        $registro->multa = $multa;
        $registro->instrucoes_mora = $dados['instrucoes_mora'];

        $juros = str_replace(',', '.', $dados['juros']);
        $registro->juros = $juros;


        $registro->campo_livre1 = $dados['campo_livre1'];
        $registro->campo_livre2 = $dados['campo_livre2'];
        $registro->mensagem_complementar = $dados['mensagem_complementar'];
        $registro->save();

    } catch( \ActiveRecord\RecordNotFound $e){
        $registro = new Opcoes_Cobranca();
        $registro->id = 1;
        $registro->tipo_acao = $dados['tipo_acao'];

        if(isset($dados['iniciar_sequencia'])):
            $registro->iniciar_sequencia = 's';
        else:
            $registro->iniciar_sequencia = 'n';
        endif;

        $registro->numero_inicial = $dados['numero_inicial'];

        if(isset($dados['quantidade_maxima'])):
            $registro->quantidade_maxima = 's';
        else:
            $registro->quantidade_maxima = 'n';
        endif;

        $registro->quantidade = $dados['quantidade'];

        if(isset($dados['adicionar_taxa'])):
            $registro->adicionar_taxa = 's';
        else:
            $registro->adicionar_taxa = 'n';
        endif;

        $registro->taxa = $dados['taxa'];

        if(isset($dados['discriminar_observacao'])):
            $registro->discriminar_observacao = 's';
        else:
            $registro->discriminar_observacao = 'n';
        endif;

        if(isset($dados['imprimir_endereco'])):
            $registro->imprimir_endereco = 's';
        else:
            $registro->imprimir_endereco = 'n';
        endif;

        $registro->instrucoes_atraso = $dados['instrucoes_atraso'];

        $multa = str_replace(',','.', $dados['multa']);
        $registro->multa = $multa;
        $registro->instrucoes_mora = $dados['instrucoes_mora'];

        $juros = str_replace(',', '.', $dados['juros']);
        $registro->juros = $juros;
        $registro->campo_livre1 = $dados['campo_livre1'];
        $registro->campo_livre2 = $dados['campo_livre2'];
        $registro->mensagem_complementar = $dados['mensagem_complementar'];
        $registro->save();
        $registro->save();
    }

    adicionaHistorico(idUsuario(), idColega(), 'Geração de Cobrança', 'Alteração', 'As configurações para a geração de cobrança foram alteradas.');

    echo json_encode(array('status' => 'ok'));

endif;



if($dados['tipo_acao'] == 'arquivo_cnab' && !isset($dados['acao'])):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Geração de Cobrança', 'i');

    try{
        $opcoes_cobranca = Opcoes_Cobranca::find(1);
    } catch(\ActiveRecord\RecordNotFound $e){
        $opcoes_cobranca = '';
    }

    try{
        /*Selecionando Unidade*/
        $id_unidade = $dados['id_unidade'];
        $usar_dados = Unidades::find($id_unidade);
        //$usar_dados = Unidades::find_by_usar_dados_boleto('s');

        $cnpj = str_replace('.', '', $usar_dados->cnpj);
        $cnpj = str_replace('/', '', $cnpj);
        $cnpj = str_replace('-', '', $cnpj);

        $nome_empresa = $usar_dados->razao_social;

        $numero_agencia = explode('-', $usar_dados->agencia);
        $numero_conta_corrente = explode('-', $usar_dados->conta);

        $convenio = explode('-', $usar_dados->codigo_cliente);

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


    /*Pegando os dados para geração do arquivo de remessa*/

    //include_once('../../classes/boletos/funcoes_bancoob.php');

    parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

    include_once('../../classes/Sicoob/Remessa/CNAB240/Arquivo.php');
    include_once('../../classes/Sicoob/Remessa/CNAB240/Boleto.php');

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
    $arquivo_cnab = new Arquivos_Cnab();
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

            $parcela = Parcelas::find($id_parcela);
            //$aluno = Alunos::find($parcela->id_aluno);

            if($parcela->boleto != 's'):

                if($parcela->pagante == 'aluno'):
                    $dados_sacado = Alunos::find($parcela->id_aluno);

                    try{
                        $matricula = Matriculas::find($parcela->id_matricula);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $matricula = '';
                    }


                    $id_cliente = $dados_sacado->id;
                    $tipo_inscricao = 1;

                    if($matricula->responsavel_financeiro == 3):

                        try{
                            $cidade = Cidades::find($dados_sacado->cidade);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $cidade = '';
                        }

                        try{
                            $estado = Estados::find($dados_sacado->estado);
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
                            $cidade = Cidades::find($dados_sacado->cidade_responsavel);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $cidade = '';
                        }

                        try{
                            $estado = Estados::find($dados_sacado->estado_responsavel);
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
                    $dados_sacado = Empresas::find($parcela->id_empresa);

                    try{
                        $cidade = Cidades::find($dados_sacado->cidade);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $cidade = '';
                    }

                    try{
                        $estado = Estados::find($dados_sacado->estado);
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
                $pega_nosso_numero = Boletos::find_by_sql('select max(nosso_numero) as nosso_numero from boletos limit 1');

                if(empty($pega_nosso_numero)):
                    $nosso_numero_boleto_sem_zero = 1;
                    $nosso_numero = 1;
                    $nosso_numero = str_pad("$nosso_numero", 7, 0, STR_PAD_LEFT);
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,65838), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,159670), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    $nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero, $numero_agencia[0],$convenio[0].$convenio[1]), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                else:
                    $nosso_numero_boleto_sem_zero = $pega_nosso_numero[0]->nosso_numero+1;
                    $nosso_numero = $pega_nosso_numero[0]->nosso_numero+1;
                    $nosso_numero = str_pad("$nosso_numero", 7, 0, STR_PAD_LEFT);
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,65838), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    //$nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,159670), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
                    $nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero, $numero_agencia[0],$convenio[0].$convenio[1]), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
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

                $boleto = new Boletos();
                $boleto->chave = md5($numero_documento);
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
                $atualiza_boleto = Boletos::find_by_id($boleto->id);
                $atualiza_boleto->chave = $boleto->id.'_'.$boleto->chave;
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
    $arquivo_cnab = Arquivos_Cnab::find($id_arquivo_cnab);
    $arquivo_cnab->arquivo = 'remessa_' . date('Y') . '_' . date('m') . "_" . $numero_arquivo . '_boletos.rem';
    $arquivo_cnab->save();

    echo json_encode(array('status' => 'ok'));

endif;


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


//echo '000001030'.modulo11(1030,5142,65838);
