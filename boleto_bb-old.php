<?php
    include_once('config.php');

    try{
        $opcoes_cobranca = Opcoes_Cobranca::find(1);
    } catch (\ActiveRecord\RecordNotFound $e){
        $opcoes_cobranca = '';
    }

    $chave_boleto = filter_input(INPUT_GET, 'boleto', FILTER_SANITIZE_STRING);
    $boleto_sistema = Boletos::find_by_chave_and_cancelado($chave_boleto, 'n');
    $parcela = Parcelas::find($boleto_sistema->id_parcela);


    /*pegando dados da Unidade*/
    try{
        //$usar_dados = Unidades::find_by_usar_dados_boleto('s');
        $usar_dados = Unidades::find($boleto_sistema->id_unidade);

        $cnpj = str_replace('.', '', $usar_dados->cnpj);
        $cnpj = str_replace('/', '', $cnpj);
        $cnpj = str_replace('-', '', $cnpj);

        $nome_empresa = $usar_dados->razao_social;

        $numero_agencia = explode('-', $usar_dados->agencia);
        $numero_conta_corrente = explode('-', $usar_dados->conta);

        $convenio = str_replace('-', '', $usar_dados->codigo_cliente);

    } catch (\ActiveRecord\RecordNotFound $e){
        $usar_dados = '';
    }


    if(empty($parcela->parcela)):
        $numero_parcela = '001';
    else:
        $numero_parcela = str_pad($parcela->parcela, 3, '0', STR_PAD_LEFT);
    endif;

    echo 'oi';

    /*FimSalvandoBoletosnoBancodeDados*/

    //include_once('classes/boletos/funcoes_bancoob.php');
    //include_once("classes/boletos/funcoes_bb.php");

    /*************************************************************************
     *+++
     *************************************************************************/
    $dadosboleto["numero_documento"]=$boleto_sistema->numero_boleto;//Numdopedidooudodocumento
    $dadosboleto["data_vencimento"]=$boleto_sistema->data_vencimento->format('d/m/Y');//DatadeVencimentodoBoleto-REGRA:FormatoDD/MM/AAAA
    $dadosboleto["data_documento"]=date("d/m/Y");//DatadeemissãodoBoleto
    $dadosboleto["data_processamento"]=date("d/m/Y");//Datadeprocessamentodoboleto(opcional)
    $dadosboleto["valor_boleto"]=$valor_boleto;//ValordoBoleto-REGRA:Comvírgulaesemprecomduascasasdepoisdavirgula
    //DADOSDOSEUCLIENTE
    if($parcela->pagante=='aluno'):
        $aluno=Alunos::find($parcela->id_aluno);

        try{
            $matricula = Matriculas::find($parcela->id_matricula);
        } catch(\ActiveRecord\RecordNotFound $e){
            $matricula = '';
        }


        if($matricula->responsavel_financeiro == 3):


            try{
                $cidade = Cidades::find($aluno->cidade);
            } catch(\ActiveRecord\RecordNotFound $e){
                $cidade = '';
            }

            try{
                $estado = Estados::find($aluno->estado);
            } catch(\ActiveRecord\RecordNotFound $e){
                $estado = '';
            }

            $nome=$aluno->nome;
            $endereco1=$aluno->endereco.','.$aluno->numero.'-'.$aluno->bairro;
            $endereco2=$cidade->nome.'-'.$estado->uf.'-'.$aluno->cep;


        elseif($matricula->responsavel_financeiro == 1):


            try{
                $cidade = Cidades::find($aluno->cidade_responsavel);
            } catch(\ActiveRecord\RecordNotFound $e){
                $cidade = '';
            }

            try{
                $estado = Estados::find($aluno->estado_responsavel);
            } catch(\ActiveRecord\RecordNotFound $e){
                $estado = '';
            }

            $nome=$aluno->nome_responsavel." (aluno(a): {$aluno->nome}): CPF: ".mascara($aluno->cpf_responsavel, "###.###.###-##");
            $endereco1=$aluno->endereco_responsavel.','.$aluno->numero_responsavel.'-'.$aluno->bairro_responsavel;
            $endereco2=$cidade->nome.'-'.$estado->uf.'-'.$aluno->cep_responsavel;

        endif;


    elseif($parcela->pagante=='empresa'):
        $empresa=Empresas::find($parcela->id_empresa);

        try{
            $cidade=Cidades::find($empresa->cidade);
        }catch(\ActiveRecord\RecordNotFound$e){
            $cidade='';
        }

        try{
            $estado=Estados::find($empresa->estado);
        }catch(\ActiveRecord\RecordNotFound$e){
            $estado='';
        }

        $nome=$empresa->razao_social;
        $endereco1=$empresa->rua.','.$empresa->numero.'-'.$empresa->bairro;
        $endereco2=$cidade->nome.'-'.$estado->uf.'-'.$aluno->cep;

    endif;

    $dadosboleto["sacado"]=$nome;
    $dadosboleto["endereco1"]=$endereco1;
    $dadosboleto["endereco2"]=$endereco2;
    //INFORMACOESPARAOCLIENTE
    $dadosboleto["demonstrativo1"]=$boleto_sistema->demonstratitvo1;
    $dadosboleto["demonstrativo2"]=$boleto_sistema->demonstratitvo2;
    $dadosboleto["demonstrativo3"]=$boleto_sistema->demonstratitvo3;
    //INSTRUÇÕESPARAOCAIXA
    $dadosboleto["instrucoes1"]=$boleto_sistema->informacoes1;
    $dadosboleto["instrucoes2"]=$boleto_sistema->informacoes2;
    $dadosboleto["instrucoes3"]=$boleto_sistema->informacoes3;
    $dadosboleto["instrucoes4"]=$boleto_sistema->informacoes4;
    //DADOSOPCIONAISDEACORDOCOMOBANCOOUCLIENTE
    //$dadosboleto["quantidade"]="10";
    //$dadosboleto["valor_unitario"]="10";
    $dadosboleto["aceite"]="N";
    $dadosboleto["especie"]="R$";
    $dadosboleto["especie_doc"]="DM";

    //----------------------DADOSFIXOSDECONFIGURAÇÃODOSEUBOLETO---------------//
    //DADOSESPECIFICOSDOSICOOB
    $dadosboleto["modalidade_cobranca"]="02";
    $dadosboleto["numero_parcela"]=$numero_parcela;
    //DADOSDASUACONTA-BANCOSICOOB
    $dadosboleto["agencia"]=$numero_agencia[0];//Numdaagencia,semdigito
    $dadosboleto["conta"]=$numero_conta_corrente[0];//Numdaconta,semdigito
    //DADOSPERSONALIZADOS-SICOOB
    $dadosboleto["convenio"]=$convenio[0];//Numdoconvênio-REGRA:Nomáximo7dígitos
    $dadosboleto["carteira"]="17";
    //SEUSDADOS
    $dadosboleto["identificacao"]="Boleto ".$usar_dados->razao_social;
    $dadosboleto["cpf_cnpj"]="";
    $dadosboleto["endereco"]="Coloqueoendereçodasuaempresaaqui";
    $dadosboleto["cidade_uf"]="São Paulo/SP";
    $dadosboleto["cedente"]=$usar_dados->razao_social;

    //NÃOALTERAR!
    include_once("classes/boletos/funcoes_bb.php");
    include_once("classes/boletos/layout_bb.php");

    /*PartedoBoleto*/
    /*----------------------------------------------------*/
    ?>

    <script>
        //window.print();
        //window.close();
    </script>
