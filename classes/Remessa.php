<?php
class Remessa{

    private
        $linha1,
        $linha2,
        $linha3,
        $linha4,
        $linha5,
        $linha6,
        $linha7,
        $linha8;

    /*Array de Lotes*/
    public $novoLote = array();
    public $novoSegmentoP = array();
    public $novoSegmentoQ = array();
    public $novoSegmentoR = array();
    public $novoSegmentoS = array();
    public $nome_arquivo;

    /*Header do Arquivo*/
    public $header_arquivo = array(
        'codigo_banco'      =>array( 'tamanho'=>3, 'default'=>'756', 'tipo'=>'num', 'required'=>true),
        'codigo_lote'       =>array( 'tamanho'=>4, 'default'=>'0000', 'tipo'=>'num','required'=>true), /*Lote de Serviço: "0000"*/
        'tipo_registro'     =>array( 'tamanho'=>1, 'default'=>'0', 'tipo'=>'num', 'required'=>true),
        'filler1'           =>array( 'tamanho'=>9, 'default'=>'', 'tipo'=>'alfa', 'required'=>true), /*Uso Exclusivo FEBRABAN / CNAB: Preencher com espaços em branco*/
        'tipo_inscricao'    =>array( 'tamanho'=>1, 'default'=>'2', 'tipo'=>'num', 'required'=>true),
        'numero_inscricao'  =>array( 'tamanho'=>14, 'default'=>'19895217000135', 'tipo'=>'num', 'required'=>true),
        'filler2'           =>array( 'tamanho'=>20, 'default'=>'', 'tipo'=>'alfa', 'required'=>true), /*Código do Convênio no Sicoob: Preencher com espaços em branco*/
        'agencia'           =>array( 'tamanho'=>5, 'default'=>'5142', 'tipo'=>'num', 'required'=>true),
        'agencia_dv'        =>array( 'tamanho'=>1, 'default'=>'0', 'tipo'=>'alfa', 'required'=>true),
        'conta'             =>array( 'tamanho'=>12, 'default'=>'6445', 'tipo'=>'num', 'required'=>true),
        'conta_dv'          =>array( 'tamanho'=>1, 'default'=>'9', 'tipo'=>'alfa', 'required'=>true),
        'filler3'           =>array( 'tamanho'=>1, 'default'=>'0', 'tipo'=>'alfa', 'required'=>true), /*Dígito Verificador da Ag/Conta: Preencher com zeros*/
        'nome_empresa'      =>array( 'tamanho'=>30, 'default'=>'IWS Cursos de Idiomas Ltda', 'tipo'=>'alfa', 'required'=>true), /*Nome da Empresa*/
        'nome_banco'        =>array( 'tamanho'=>30, 'default'=>'SICOOB', 'tipo'=>'alfa','required'=>true),
        'filler4'           =>array( 'tamanho'=>10, 'default'=>'', 'tipo'=>'alfa', 'required'=>true), /*Uso Exclusivo FEBRABAN / CNAB: Preencher com espaços em branco*/
        'codigo_remessa'    =>array( 'tamanho'=>1, 'default'=>'1', 'tipo'=>'num', 'required'=>true),
        'data_geracao'      =>array( 'tamanho'=>8, 'default'=> '', 'tipo'=>'date', 'required'=>true),
        'hora_geracao'      =>array( 'tamanho'=>6, 'default'=> '', 'tipo'=>'time', 'required'=>true),
        'numero_sequencial' =>array( 'tamanho'=>6, 'default'=> '1', 'tipo'=>'num', 'required'=>true), /*Número Seqüencial do Arquivo: Número seqüencial adotado e controlado pelo responsável pela geração do arquivo para ordenar a disposição dos arquivos encaminhados. Evoluir um número seqüencial a cada header de arquivo.*/
        'versao_layout'     =>array( 'tamanho'=>3, 'default'=>'081',  'tipo'=>'num', 'required'=>true),
        'filler5'           =>array( 'tamanho'=>5, 'default'=>'0', 'tipo'=>'num', 'required'=>true),
        'filler6'           =>array( 'tamanho'=>69,'default'=>'', 'tipo'=>'alfa','required'=>true) /*Uso Exclusivo FEBRABAN / CNAB: Preencher com espaços em branco*/
    );

    /*Header do Lote*/
    public $header_lote = array(
        'codigo_banco'      => array('tamanho' => 3, 'default' => '756', 'tipo' => 'num'),
        'codigo_lote'       => array('tamanho' => 4, 'default' => '0001', 'tipo' => 'num'), /*"Lote de Serviço: Número seqüencial para identificar univocamente um lote de serviço. Criado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo. Preencher com '0001' para o primeiro lote do arquivo. Para os demais: número do lote anterior acrescido de 1. O número não poderá ser repetido dentro do arquivo."*/
        'registro'          => array('tamanho' => 1, 'default' => '1', 'tipo' => 'num'), /*Tipo de Registro: "1"*/
        'operacao'          => array('tamanho' => 1, 'default' => 'R', 'tipo' => 'alfa'), /*Tipo de Operação: "R"*/
        'servico'           => array('tamanho' => 2, 'default' => '01', 'tipo' => 'num'), /*Tipo de Serviço: "01"*/
        'cnab'              => array('tamanho' => 2, 'default' => '', 'tipo' => 'alfa'), /*Uso Exclusivo FEBRABAN/CNAB: Brancos*/
        'layout_lote'       => array('tamanho' => 3, 'default' => '040', 'tipo' => 'num'), /*Nº da Versão do Layout do Lote: "040"*/
        'cnab2'             => array('tamanho' => 1, 'default' => '', 'tipo' => 'alfa'), /*Uso Exclusivo FEBRABAN/CNAB: Brancos*/
        'inscricao_tipo'    => array('tamanho' => 1, 'default' => '2', 'tipo' => 'num'), /*"Tipo de Inscrição da Empresa:'1'  =  CPF - '2'  =  CGC / CNPJ"*/
        'inscricao_numero'  => array('tamanho' => 15, 'default' => '19895217000135', 'tipo' => 'num'), /*"Nº de Inscrição da Empresa*/
        'convenio'          => array('tamanho' => 20, 'default' => '', 'tipo' => 'alfa'), /*Código do Convênio no Banco: Brancos*/
        'agencia'           => array('tamanho' => 5, 'tipo' => 'num', 'default' => '5142'), /*Prefixo da Cooperativa: vide planilha "Capa" deste arquivo*/
        'agencia_dv'        => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => '0'), /*Dígito Verificador do Prefixo: vide planilha "Capa" deste arquivo*/
        'conta'             => array('tamanho' => 12, 'tipo' => 'num', 'default' => '6445'), /*Conta Corrente: vide planilha "Capa" deste arquivo*/
        'conta_dv'          => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => '9'), /*Dígito Verificador da Conta: vide planilha "Capa" deste arquivo*/
        'dv'                => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''), /*Dígito Verificador da Ag/Conta: Brancos*/
        'empresa_nome'      => array('tamanho' => 30, 'tipo' => 'alfa', 'default' => 'IWS Cursos de Idiomas Ltda'), /*Nome da Empresa*/
        'informacao_1'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''), /*"Mensagem 2: Texto referente a mensagens que serão impressas em todos os boletos referentes ao mesmo lote. Estes campos não serão utilizados no arquivo retorno."*/
        'informacao_2'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''), /*idem*/
        'controle_cobranca' => array('tamanho' => 8, 'tipo' => 'num', 'default' => '1'), /*Número Remessa/Retorno: Número adotado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo para identificar a seqüência de envio ou devolução do arquivo entre o Beneficiário e o Sicoob.*/
        'data_gravacao'     => array('tamanho' => 8, 'tipo' => 'num', 'default' => ''),
        'data_credito'      => array('tamanho' => 8, 'tipo' => 'num', 'default' => '00000000'),
        'cnab3'             => array('tamanho' => 33, 'tipo' => 'alfa', 'default' => ''),
    );

    /*REGISTRO DETALHE SEGMENTO P*/
    public $segmento_p = array(
        'codigo_banco'      => array('tamanho' => 3, 'tipo' => 'num', 'default' => '756'),
        'codigo_lote'       => array('tamanho' => 4, 'tipo' => 'num', 'default' => '0000'),
        'tipo_registro'     => array('tamanho' => 1, 'tipo' => 'num', 'default' => '3'), /*Tipo de Registro: "3"*/
        'registro'          => array('tamanho' => 5, 'tipo' => 'num', 'default' => '0001'), /*"Nº Sequencial do Registro no Lote: Número adotado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo, para identificar a seqüência de registros encaminhados no lote. Deve ser inicializado sempre em '1', em cada novo lote.*/
        'segmento'          => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => 'P'), /*Cód. Segmento do Registro Detalhe: "P"*/
        'cnab'              => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''), /*Uso Exclusivo FEBRABAN/CNAB: Brancos*/
        'codigo_movimento'  => array('tamanho' => 2, 'tipo' => 'num', 'default' => '01'), /*"Código de Movimento Remessa: '01'  =  Entrada de Títulos"*/
        'agencia'           => array('tamanho' => 5, 'tipo' => 'num', 'default' => '5142'),
        'agencia_dv'        => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => '0'),
        'conta'             => array('tamanho' => 12, 'tipo' => 'num', 'default' => '6445'),
        'conta_dv'          => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => '9'),
        'dv'                => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''),
        'nosso_numero'      => array('tamanho' => 20, 'tipo' => 'alfa', 'default' => ''), /*Verificar documentação - mandar email para daniel*/
        'carteira'          => array('tamanho' => 1, 'tipo' => 'num', 'default' => '1'), /*Código da Carteira: vide planilha "Capa" deste arquivo*/
        'cadastramento'     => array('tamanho' => 1, 'tipo' => 'num', 'default' => '0'), /*Forma de Cadastr. do Título no Banco: "0"*/
        'documento'         => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => '1'), /*Tipo de Documento: Brancos *** Verificar com Daniel*/
        'emissao_boleto'    => array('tamanho' => 1, 'tipo' => 'num', 'default' => '2'), /*"Identificação da Emissão do Boleto: (vide planilha ""Capa"" deste arquivo) - '1'  =  Sicoob Emite - '2'  =  Beneficiário Emite" *** Verificar com Daniel*/
        'distribuicao_boleto' => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => '2'), /*""Identificação da Distribuição do Boleto: (vide planilha ""Capa"" deste arquivo) -'1'  =  Sicoob Distribui - '2'  =  Beneficiário Distribui"*/
        'numero_documento'  => array('tamanho' => 15, 'tipo' => 'alfa', 'default' => '1'), /*Inserir ID da parcela "Número do Documento de Cobrança: Número adotado e controlado pelo Cliente, para identificar o título de cobrança. Informação utilizada pelo Sicoob para referenciar a identificação do documento objeto de cobrança. Poderá conter número de duplicata, no caso de cobrança de duplicatas; número da apólice, no caso de cobrança de seguros, etc*/
        'data_vencimento'   => array('tamanho' => 8, 'tipo' => 'num', 'default' => '06122018'),
        'valor'             => array('tamanho' => 15, 'tipo' => 'num', 'default' => '15000'), /*dois campos são os decimais, o valor do campo é 13 +  2decimais*/
        'agencia_cobradora' => array('tamanho' => 5, 'tipo' => 'num', 'default' => '0000'),
        'agencia_cobradora_dv' => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''),
        'especie_titulo'    => array('tamanho' => 2, 'tipo' => 'num', 'default' => '02'),
        'aceite'            => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => 'N'),
        'data_emissao'      => array('tamanho' => 8, 'tipo' => 'num', 'default' => ''),
        'codigo_juros_mora' => array('tamanho' => 1, 'tipo' => 'num', 'default' => 0),
        'data_juros_mora'   => array('tamanho' => 8, 'tipo' => 'num', 'default' => ''), /*Data do Juros de Mora: preencher com a Data de Vencimento do Título*/
        'juros_mora'        => array('tamanho' => 15, 'tipo' => 'num', 'default' => ''), /*"Juros de Mora por Dia/Taxa ao Mês Valor = R$ ao dia - Taxa = % ao mês*/
        'codigo_desconto_1' => array('tamanho' => 1, 'tipo' => 'num', 'default' => '0'), /*"Código do Desconto 1 - '0'  =  Não Conceder desconto -'1'  =  Valor Fixo Até a Data Informada - '2'  =  Percentual Até a Data Informada"*/
        'data_desconto_1'   => array('tamanho' => 8, 'tipo' => 'num', 'default' => '0'),
        'desconto_1'        => array('tamanho' => 15, 'tipo' => 'num', 'default' => '0'), /*Valor/Percentual a ser Concedido*/
        'valor_iof'         => array('tamanho' => 15, 'tipo' => 'num', 'default' => '0'), /*Valor do IOF a ser Recolhido*/
        'valor_abatimento'  => array('tamanho' => 15, 'tipo' => 'num', 'default' => '0'),
        'uso_empresa_beneficiario'=> array('tamanho' => 25, 'tipo' => 'alfa', 'default' => ''),
        'codigo_protesto'   => array('tamanho' => 1, 'tipo' => 'num', 'default' => '1'),
        'prazo_protesto'    => array('tamanho' => 2, 'tipo' => 'num', 'default' => '0'), /*Número de Dias Corridos para Protesto*/
        'codigo_baixa_devolucao'=> array('tamanho' => 1, 'tipo' => 'num', 'default' => '0'), /*Código para Baixa/Devolução: "0"*/
        'prazo_baixa_devolucao' => array('tamanho' => 3, 'tipo' => 'alfa', 'default' => ''), /*Número de Dias para Baixa/Devolução: Brancos*/
        'codigo_moeda'      => array('tamanho' => 2, 'tipo' => 'num', 'default' => '09'), /*"Código da Moeda: - '02'  =  Dólar Americano Comercial (Venda) - '09'  = Real"*/
        'numero_contrato'   => array('tamanho' => 10, 'tipo' => 'num', 'default' => '0000000000'), /*Nº do Contrato da Operação de Créd.: "0000000000"*/
        'cnab2'             => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''), /*Uso Exclusivo FEBRABAN/CNAB: Brancos*/
    );

    /*REGISTRO DETALHE SEGMENTO Q*/
    public $segmento_q = array(
        'banco'             => array('tamanho' => 3, 'tipo' => 'num', 'default' => '756'),
        'lote'              => array('tamanho' => 4, 'tipo' => 'num', 'default' => '1'), /*"Lote de Serviço: Número seqüencial para identificar univocamente um lote de serviço. Criado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo. Preencher com '0001' para o primeiro lote do arquivo. Para os demais: número do lote anterior acrescido de 1. O número não poderá ser repetido dentro do arquivo."*/
        'tipo_registro'     => array('tamanho' => 1, 'tipo' => 'num', 'default' => '3'), /*"Lote de Serviço: Número seqüencial para identificar univocamente um lote de serviço. Criado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo. Preencher com '0001' para o primeiro lote do arquivo. Para os demais: número do lote anterior acrescido de 1. O número não poderá ser repetido dentro do arquivo."*/
        'numero_sequencial' => array('tamanho' => 5, 'tipo' => 'num', 'default' => '1'), /*"Nº Sequencial do Registro no Lote: Número adotado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo, para identificar a seqüência de registros encaminhados no lote. Deve ser inicializado sempre em '1', em cada novo lote.*/
        'codigo_sequencial' => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => 'Q'), /*"Nº Sequencial do Registro no Lote: Número adotado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo, para identificar a seqüência de registros encaminhados no lote. Deve ser inicializado sempre em '1', em cada novo lote.*/
        'cnab'              => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''), /*"Nº Sequencial do Registro no Lote: Número adotado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo, para identificar a seqüência de registros encaminhados no lote. Deve ser inicializado sempre em '1', em cada novo lote.*/
        'codigo_movimento_remessa'  => array('tamanho' => 2, 'tipo' => 'num', 'default' => '01'), /*Código de Movimento Remessa: '01'  =  Entrada de Títulos*/

        /*Dados do Pagador*/

        'tipo_inscricao_pagador'    => array('tamanho' => 1, 'tipo' => 'num', 'default' => '1'), /*"Tipo de Inscrição Pagador: '1'  =  CPF - '2'  =  CGC / CNPJ"*/
        'numero_inscricao'  => array('tamanho' => 15, 'tipo' => 'num', 'default' => '33390590803'), /*"Tipo de Inscrição Pagador: '1'  =  CPF - '2'  =  CGC / CNPJ"*/
        'nome'              => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => 'Michel Fernandes Ramos'),
        'endereco'          => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => 'Rua Varginha, 445'),
        'bairro'            => array('tamanho' => 15, 'tipo' => 'alfa', 'default' => 'Ernane Murad'),
        'cep'               => array('tamanho' => 5, 'tipo' => 'num', 'default' => '19400'),
        'cep_sufixo'        => array('tamanho' => 3, 'tipo' => 'num', 'default' => '000'),
        'cidade'            => array('tamanho' => 15, 'tipo' => 'alfa', 'default' => 'Pres Venceslau'),
        'uf'                => array('tamanho' => 2, 'tipo' => 'alfa', 'default' => 'SP'),
        'sacador_avalista'  => array('tamanho' => 1, 'tipo' => 'num', 'default' => '1'),/*"Tipo de Inscrição Sacador Avalista: - '1'  =  CPF - '2'  =  CGC / CNPJ"*/
        'numero_inscricao_2'=> array('tamanho' => 15, 'tipo' => 'num', 'default' => '33390590803'),/*"Tipo de Inscrição Sacador Avalista: - '1'  =  CPF - '2'  =  CGC / CNPJ"*/
        'nome_sacador'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => 'Michel Fernandes Ramos'),/*Nome do Sacador/Avalista*/
        'codigo_compensacao'            => array('tamanho' => 3, 'tipo' => 'num', 'default' => '000'),/*"Cód. Bco. Corresp. na Compensação: Caso o Beneficiário não tenha contratado a opção de Banco Correspondente com o Sicoob, preencher com ""000""; Caso o Beneficiário tenha contratado a opção de Banco Correspondente com o Sicoob e a emissão seja a cargo do Sicoob (SEQ 17.3.P do Segmento P do Detalhe), preencher com ""001"" (Banco do Brasil)"*/
        'nosso_num_bco_correspondente'  => array('tamanho' => 20, 'tipo' => 'alfa', 'default' => '000'),/*"Cód. Bco. Corresp. na Compensação: Caso o Beneficiário não tenha contratado a opção de Banco Correspondente com o Sicoob, preencher com ""000""; Caso o Beneficiário tenha contratado a opção de Banco Correspondente com o Sicoob e a emissão seja a cargo do Sicoob (SEQ 17.3.P do Segmento P do Detalhe), preencher com ""001"" (Banco do Brasil)"*/
        'cnab2'             => array('tamanho' => 8, 'tipo' => 'alfa', 'default' => ''),/*Uso Exclusivo FEBRABAN/CNAB*/
    );

    /*REGISTRO DETALHE SEGMENTO R*/
    public $segmento_r = array(
        'banco'             => array('tamanho' => 3, 'tipo' => 'num', 'default' => '756'),
        'lote'              => array('tamanho' => 4, 'tipo' => 'num', 'default' => '1'), /*"Lote de Serviço: Número seqüencial para identificar univocamente um lote de serviço. Criado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo. Preencher com '0001' para o primeiro lote do arquivo. Para os demais: número do lote anterior acrescido de 1. O número não poderá ser repetido dentro do arquivo."*/
        'tipo_registro'     => array('tamanho' => 1, 'tipo' => 'num', 'default' => '3'),
        'numero_registro'   => array('tamanho' => 5, 'tipo' => 'num', 'default' => '1'), /*"Nº Sequencial do Registro no Lote: Número adotado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo, para identificar a seqüência de registros encaminhados no lote. Deve ser inicializado sempre em '1', em cada novo lote.*/
        'codigo_sequencial' => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => 'R'), /*Cód. Segmento do Registro Detalhe: "R"*/
        'cnab'              => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''),
        'codigo_movimento'  => array('tamanho' => 2, 'tipo' => 'num', 'default' => '01'), /*"Código de Movimento Remessa: '01'  =  Entrada de Títulos*/
        'codigo_desconto_2' => array('tamanho' => 1, 'tipo' => 'num', 'default' => '0'), /*"Código do Desconto 2 - '0'  =  Não Conceder desconto - '1'  =  Valor Fixo Até a Data Informada - '2'  =  Percentual Até a Data Informada"*/
        'data_desconto_2'   => array('tamanho' => 8, 'tipo' => 'num', 'default' => '0'),
        'valor_desconto_2'  => array('tamanho' => 15, 'tipo' => 'num', 'default' => '0'),
        'codigo_desconto_3' => array('tamanho' => 1, 'tipo' => 'num', 'default' => '0'),
        'data_desconto_3'   => array('tamanho' => 8, 'tipo' => 'num', 'default' => '0'),
        'valor_desconto_3'  => array('tamanho' => 15, 'tipo' => 'num', 'default' => '0'),
        'codigo_multa'      => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => '0'), /*"Código da Multa: '0'  =  Isento - '1'  =  Valor Fixo - '2'  =  Percentual"*/
        'data_multa'        => array('tamanho' => 8, 'tipo' => 'num', 'default' => '0'), /*Data da Multa: preencher com a Data de Vencimento do Título*/
        'valor_multa'       => array('tamanho' => 15, 'tipo' => 'num', 'default' => '0'),
        'informacao_ao_pagador'  => array('tamanho' => 10, 'tipo' => 'alfa', 'default' => '0'), /*Data da Multa: preencher com a Data de Vencimento do Título*/
        'informacao_3'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''),
        'informacao_4'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''),
        'cnab2'             => array('tamanho' => 20, 'tipo' => 'alfa', 'default' => ''),
        'codigo_oco_pagador'=> array('tamanho' => 8, 'tipo' => 'num', 'default' => '00000000'),
        'banco_debito'      => array('tamanho' => 3, 'tipo' => 'num', 'default' => '000'),
        'agencia_debito'    => array('tamanho' => 5, 'tipo' => 'num', 'default' => '00000'),
        'agencia_dv_debito' => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''),
        'conta_debito'      => array('tamanho' => 12, 'tipo' => 'num', 'default' => '000000000000'),
        'conta_dv_debito'   => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''),
        'dv'                => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''),
        'aviso_debito_auto' => array('tamanho' => 1, 'tipo' => 'num', 'default' => ''),
        'cnab3'             => array('tamanho' => 9, 'tipo' => 'alfa', 'default' => ''),
    );

    /*REGISTRO DETALHE SEGMENTO S*/
    public $segmento_s = array(
        'banco'             => array('tamanho' => 3, 'tipo' => 'num', 'default' => '756'),
        'lote'              => array('tamanho' => 4, 'tipo' => 'num', 'default' => '1'), /*"Lote de Serviço: Número seqüencial para identificar univocamente um lote de serviço. Criado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo. Preencher com '0001' para o primeiro lote do arquivo. Para os demais: número do lote anterior acrescido de 1. O número não poderá ser repetido dentro do arquivo."*/
        'tipo_registro'     => array('tamanho' => 1, 'tipo' => 'num', 'default' => '3'),
        'numero_registro'   => array('tamanho' => 5, 'tipo' => 'num', 'default' => '1'), /*"Nº Sequencial do Registro no Lote: Número adotado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo, para identificar a seqüência de registros encaminhados no lote. Deve ser inicializado sempre em '1', em cada novo lote.*/
        'codigo_sequencial' => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => 'S'), /*Cód. Segmento do Registro Detalhe: "S"*/
        'cnab'              => array('tamanho' => 1, 'tipo' => 'alfa', 'default' => ''),
        'codigo_movimento'  => array('tamanho' => 2, 'tipo' => 'num', 'default' => '01'), /*"Código de Movimento Remessa: - '01'  =  Entrada de Títulos"*/
        'tipo_impresao'     => array('tamanho' => 1, 'tipo' => 'num', 'default' => '3'), /*"Identificação da Impressão: '3'  =  Corpo de Instruções da Ficha de Compensação do Boleto"*/
        'informacao_5'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''),
        'informacao_6'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''),
        'informacao_7'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''),
        'informacao_8'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''),
        'informacao_9'      => array('tamanho' => 40, 'tipo' => 'alfa', 'default' => ''),
        'cnab2'              => array('tamanho' => 22, 'tipo' => 'alfa', 'default' => ''),
    );

    /*REGISTRO TRAILLER DO LOTE*/
    public $trailler_lote = array(
        'banco'             => array('tamanho' => 3, 'tipo' => 'num', 'default' => '756'),
        'lote'              => array('tamanho' => 4, 'tipo' => 'num', 'default' => '1'), /*"Lote de Serviço: Número seqüencial para identificar univocamente um lote de serviço. Criado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo. Preencher com '0001' para o primeiro lote do arquivo. Para os demais: número do lote anterior acrescido de 1. O número não poderá ser repetido dentro do arquivo."*/
        'tipo_registro'     => array('tamanho' => 1, 'tipo' => 'num', 'default' => '5'),
        'cnab'              => array('tamanho' => 9, 'tipo' => 'alfa', 'default' => ''),
        'qtd_registros'     => array('tamanho' => 6, 'tipo' => 'num', 'default' => '1'),

        'qtd_titulos_simples'       => array('tamanho' => 6, 'tipo' => 'num', 'default' => '1'),
        'vlr_titulos_simples'       => array('tamanho' => 17, 'tipo' => 'num', 'default' => '15000'),

        'qtd_titulos_vinculados'    => array('tamanho' => 6, 'tipo' => 'num', 'default' => ''),
        'vlr_titulos_vinculados'    => array('tamanho' => 17, 'tipo' => 'num', 'default' => ''),

        'qtd_titulos_caiucionada'   => array('tamanho' => 6, 'tipo' => 'num', 'default' => ''),
        'vlr_titulos_caiucionada'   => array('tamanho' => 17, 'tipo' => 'num', 'default' => ''),

        'qtd_titulos_descontada'    => array('tamanho' => 6, 'tipo' => 'num', 'default' => ''),
        'vlr_titulos_descontada'    => array('tamanho' => 17, 'tipo' => 'num', 'default' => ''),

        'numero_aviso'      => array('tamanho' => 8, 'tipo' => 'alfa', 'default' => ''), /*Número do Aviso de Lançamento: Brancos*/
        'cnab2'             => array('tamanho' => 117, 'tipo' => 'alfa', 'default' => ''),
    );

    /*REGISTRO TRAILLER DO ARQUIVO*/
    public $trailler_arquivo = array(
        'banco'             => array('tamanho' => 3, 'tipo' => 'num', 'default' => '756'),
        'lote'              => array('tamanho' => 4, 'tipo' => 'num', 'default' => '9999'), /*"Lote de Serviço: Número seqüencial para identificar univocamente um lote de serviço. Criado e controlado pelo responsável pela geração magnética dos dados contidos no arquivo. Preencher com '0001' para o primeiro lote do arquivo. Para os demais: número do lote anterior acrescido de 1. O número não poderá ser repetido dentro do arquivo."*/
        'tipo_registro'     => array('tamanho' => 1, 'tipo' => 'num', 'default' => '9'),
        'cnab'              => array('tamanho' => 9, 'tipo' => 'alfa', 'default' => ''),
        'qtd_lotes'         => array('tamanho' => 6, 'tipo' => 'num', 'default' => '1'), /*Quantidade de Lotes do Arquivo*/
        'qtd_registros'     => array('tamanho' => 6, 'tipo' => 'num', 'default' => '1'), /*Quantidade de Registros do Arquivo*/
        'qtd_contas'        => array('tamanho' => 6, 'tipo' => 'num', 'default' => '000000'), /*Qtde de Contas p/ Conc. (Lotes): "000000"*/
        'cnab2'             => array('tamanho' => 205, 'tipo' => 'alfa', 'default' => ''),
    );



    /*Campos data
    $header_lote['data_gravacao'];
    $segmento_p['data_emissao'];
    */

    public function headerArquivo(){

        print_r($this->header_lote);

    }

    public function listaLotes(){

        print_r($this->novoLote);

    }

    public function adicionarLote($lote){

        $this->novoLote[] .= $lote;

    }

    public function adicionarSegmentoP($seguimentoP){

        $this->novoSegmentoP[] = $seguimentoP;

    }

    public function adicionarSegmentoQ($seguimentoQ){

        $this->novoSegmentoQ[] = $seguimentoQ;

    }

    public function adicionarSegmentoR($seguimentoR){

        $this->novoSegmentoR[] = $seguimentoR;

    }

    public function adicionarSegmentoS($seguimentoS){

        $this->novoSegmentoS[] = $seguimentoS;

    }

    public function listaSegmentos(){

        /*
        var_dump($this->novoSegmentoQ);

        $qtd_segmentos = count($this->novoSegmentoP);
        echo $qtd_segmentos;
        */

        print_r($this->novoSegmentoP);
        echo '<br><br>';
        print_r($this->novoSegmentoQ);
        echo '<br><br>';
        print_r($this->novoSegmentoR);
        echo '<br><br>';
        print_r($this->novoSegmentoS);
        echo '<br><br>';

    }
    public function geraArquivoRemessa($numero_arquivo){

        /*header do arquivo*/
        foreach($this->header_arquivo as $i => $v):
            if($v['tipo'] == 'num'): $this->linha1 .= str_pad($v['default'], $v['tamanho'], 0, STR_PAD_LEFT); endif;
            if($v['tipo'] == 'date'): $this->linha1 .= date('dmY'); endif;
            if($v['tipo'] == 'time'): $this->linha1 .= date('His'); endif;
            if($v['tipo'] == 'alfa'): $this->linha1 .= str_pad($v['default'], $v['tamanho'], " ", STR_PAD_RIGHT); endif;
        endforeach;

        foreach($this->header_lote as $i => $v):
            if($v['tipo'] == 'num'): $this->linha2 .= str_pad($v['default'], $v['tamanho'], 0, STR_PAD_LEFT); endif;
            if($v['tipo'] == 'date'): $this->linha2 .= date('dmY'); endif;
            if($v['tipo'] == 'time'): $this->linha2 .= date('His'); endif;
            if($v['tipo'] == 'alfa'): $this->linha2 .= str_pad($v['default'], $v['tamanho'], " ", STR_PAD_RIGHT); endif;
        endforeach;

        if(!file_exists('../../remessas')):
            mkdir('../../remessas', 0777, true);
        endif;

        $fp = fopen("../../remessas/remessa_".$numero_arquivo."_".date('d_m_Y').".rem", "w+");
        $this->nome_arquivo = "remessa_".$numero_arquivo."_".date('d_m_Y').".rem";

        // Escreve "exemplo de escrita" no bloco1.txt
        fwrite($fp, $this->linha1."\r\n");
        fwrite($fp, $this->linha2."\r\n");

        /*Listando os Segguimentos*/
        $qtd_segmentos = count($this->novoSegmentoP);
        for($i=0;$i<$qtd_segmentos;$i++):

            if(isset($this->novoSegmentoP[$i])):
            foreach($this->novoSegmentoP[$i] as $index => $value):
                if(!empty($index)):
                    if($value['tipo'] == 'num'): $this->linha3 .= str_pad($value['default'], $value['tamanho'], 0, STR_PAD_LEFT); endif;
                    if($value['tipo'] == 'date'): $this->linha3 .= date('dmY'); endif;
                    if($value['tipo'] == 'time'): $this->linha3 .= date('His'); endif;
                    if($value['tipo'] == 'alfa'): $this->linha3 .= str_pad($value['default'], $value['tamanho'], " ", STR_PAD_RIGHT); endif;
                endif;
            endforeach;
            endif;

            if(isset($this->novoSegmentoQ[$i])):
            foreach($this->novoSegmentoQ[$i] as $index => $value):
                if(!empty($index)):
                    if($value['tipo'] == 'num'): $this->linha4 .= str_pad($value['default'], $value['tamanho'], 0, STR_PAD_LEFT); endif;
                    if($value['tipo'] == 'date'): $this->linha4 .= date('dmY'); endif;
                    if($value['tipo'] == 'time'): $this->linha4 .= date('His'); endif;
                    if($value['tipo'] == 'alfa'): $this->linha4 .= str_pad($value['default'], $value['tamanho'], " ", STR_PAD_RIGHT); endif;
                endif;
            endforeach;
            endif;

            if(isset($this->novoSegmentoR[$i])):
            foreach($this->novoSegmentoR[$i] as $index => $value):
                if(!empty($index)):
                    if($value['tipo'] == 'num'): $this->linha5 .= str_pad($value['default'], $value['tamanho'], 0, STR_PAD_LEFT); endif;
                    if($value['tipo'] == 'date'): $this->linha5 .= date('dmY'); endif;
                    if($value['tipo'] == 'time'): $this->linha5 .= date('His'); endif;
                    if($value['tipo'] == 'alfa'): $this->linha5 .= str_pad($value['default'], $value['tamanho'], " ", STR_PAD_RIGHT); endif;
                endif;
            endforeach;
            endif;

            if(isset($this->novoSegmentoS[$i])):
            foreach($this->novoSegmentoS[$i] as $index => $value):
                if(!empty($index)):
                    if($value['tipo'] == 'num'): $this->linha6 .= str_pad($value['default'], $value['tamanho'], 0, STR_PAD_LEFT); endif;
                    if($value['tipo'] == 'date'): $this->linha6 .= date('dmY'); endif;
                    if($value['tipo'] == 'time'): $this->linha6 .= date('His'); endif;
                    if($value['tipo'] == 'alfa'): $this->linha6 .= str_pad($value['default'], $value['tamanho'], " ", STR_PAD_RIGHT); endif;
                endif;
            endforeach;
            endif;

            fwrite($fp, $this->linha3."\r\n");
            fwrite($fp, $this->linha4."\r\n");
            fwrite($fp, $this->linha5."\r\n");
            fwrite($fp, $this->linha6."\r\n");

            $this->linha3 = '';
            $this->linha4 = '';
            $this->linha5 = '';
            $this->linha6 = '';

        endfor;
        /*Fim Listando os Segguimentos*/

        /*REGISTRO TRAILLER DO LOTE*/
        foreach($this->trailler_lote as $i => $v):
            if($v['tipo'] == 'num'): $this->linha7 .= str_pad($v['default'], $v['tamanho'], 0, STR_PAD_LEFT); endif;
            if($v['tipo'] == 'date'): $this->linha7 .= date('dmY'); endif;
            if($v['tipo'] == 'time'): $this->linha7 .= date('His'); endif;
            if($v['tipo'] == 'alfa'): $this->linha7 .= str_pad($v['default'], $v['tamanho'], " ", STR_PAD_RIGHT); endif;
        endforeach;
        fwrite($fp, $this->linha7."\r\n");
        /*FIM REGISTRO TRAILLER DO LOTE*/

        /*REGISTRO TRAILLER DO ARQUIVO*/
        foreach($this->trailler_arquivo as $i => $v):
            if($v['tipo'] == 'num'): $this->linha8 .= str_pad($v['default'], $v['tamanho'], 0, STR_PAD_LEFT); endif;
            if($v['tipo'] == 'date'): $this->linha8 .= date('dmY'); endif;
            if($v['tipo'] == 'time'): $this->linha8 .= date('His'); endif;
            if($v['tipo'] == 'alfa'): $this->linha8 .= str_pad($v['default'], $v['tamanho'], " ", STR_PAD_RIGHT); endif;
        endforeach;
        fwrite($fp, $this->linha8."\r\n");
        /*FIM REGISTRO TRAILLER DO ARQUIVO*/

        // Fecha o arquivo
        fclose($fp);

    }


}