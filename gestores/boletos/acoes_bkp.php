<?php
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

if($dados['acao'] == 'enviar-emails'):

    $status = 'ok';
    $ids_boletos = explode('|', $dados['boletos']);
    if(!empty($ids_boletos)):
        foreach($ids_boletos as $id_boleto):
            if(!empty($id_boleto)):

                include_once('../../classes/PHPMailer/class.phpmailer.php');

                try{
                    $configuracao_email = Envio_Emails::find(1);
                } catch (Exception $e) {
                    $configuracao_email = '';
                }

                $boleto = Boletos::find($id_boleto);
                $parcela = Parcelas::find($boleto->id_parcela);

                if($parcela->pagante == 'aluno'):

                    $aluno = Alunos::find($parcela->id_aluno);

                    try{
                        $matricula = Matriculas::find($parcela->id_matricula);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $matricula = '';
                    }

                    if($matricula->responsavel_financeiro == 3):
                        $inicio_mensagem = 'Caro aluno(a)';
                    elseif($matricula->responsavel_financeiro == 1):
                        $inicio_mensagem = 'Caro senhor(a)';
                    endif;

                    /*
                    $mensagem  = $inicio_mensagem.' , esse é o <a href="'.HOME.'/boleto.php?boleto='.$boleto->chave.'">link</a> para o pagamento do boleto de sua mensalidade da IOWA IDIOMAS, caso você tenha algum problema';
                    $mensagem .= ' com o pagamento entre em contato conosco pelo telefone 11 2440-7729 ou através do e-mail boletos@iowa.com.br.';
                    */

                    $mensagem = '<div style="text-align: center;"><img src="'.HOME.'/assets/imagens/logo-email.png"/></div><br><br>';

                    $mensagem .= '<div style="padding: 10px; border: 1px solid #d7d5d2; border-radius: 4px; font: 1em Arial; text-align: center;">';
                    $mensagem .= $inicio_mensagem.', este email contem abaixo um link para acessar o boleto de sua mensalidade da IOWA IDIOMAS, caso você tenha algum problema';
                    $mensagem .= ' com o pagamento entre em contato conosco pelo telefone 11 2440-7729 ou através do e-mail boletos@iowa.com.br.';

                    $mensagem .= '<br><br>';
                    $mensagem .= '<div style="text-align: center; background-color: #efefef; padding: 10px; border: 1px solid #a7a7a7; border-radius: 4px; display: inline-block; "><a href="'.HOME.'/boleto.php?boleto='.$boleto->chave.'" style="text-decoration: none; color: #000000; display: block;">Clique Aqui Para Acessar Seu Boleto</a></div>';
                    $mensagem .= '<div>';

                    $mail = new PHPMailer();

                    //$mail->SMTPDebug = 1;
                    $mail->IsSMTP(); // Define que a mensagem será SMTP
                    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
                    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
                    //$mail->Port = $configuracao_email->porta_smtp;
                    //$mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
                    $mail->Username = $configuracao_email->usuario_smtp; // Usuário do servidor SMTP
                    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

                    $mail->From = $configuracao_email->email;
                    $mail->FromName = 'Boleto - IOWA Idiomas';

                    if($matricula->responsavel_financeiro == 3):
                        $mail->AddAddress($aluno->email1, $aluno->nome);
                    elseif($matricula->responsavel_financeiro == 1):
                        $mail->AddAddress($aluno->email1_responsavel, $aluno->nome_responsavel);
                    endif;

                    $mail->AddBCC('boletos@iowa.com.br', 'Boleto IOWA Idiomas');

                    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
                    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

                    $mail->Subject  = 'Boleto - IOWA Idiomas'; // Assunto da mensagem
                    $mail->Body = $mensagem;

                    if(!$mail->Send()):
                        $status = 'erro';
                    else:
                        //$status = 'ok';
                    endif;

                    $mail->ClearAllRecipients();
                    $mail->ClearAttachments();

                elseif($parcela->pagante == 'empresa'):

                    $empresa = Empresas::find($parcela->id_empresa);

                    $mensagem = '<div style="text-align: center;"><img src="'.HOME.'/assets/imagens/logo-email.png"/></div><br><br>';

                    $mensagem .= '<div style="padding: 10px; border: 1px solid #d7d5d2; border-radius: 4px; font: 1em Arial; text-align: center;">';
                    $mensagem .= 'Este email contem abaixo um link para acessar o boleto de sua mensalidade da IOWA IDIOMAS, caso você tenha algum problema';
                    $mensagem .= ' com o pagamento entre em contato conosco pelo telefone 11 2440-7729 ou através do e-mail boletos@iowa.com.br.';

                    $mensagem .= '<br><br>';
                    $mensagem .= '<div style="text-align: center; background-color: #efefef; padding: 10px; border: 1px solid #a7a7a7; border-radius: 4px; display: inline-block; "><a href="'.HOME.'/boleto.php?boleto='.$boleto->chave.'" style="text-decoration: none; color: #000000; display: block;">Clique Aqui Para Acessar Seu Boleto</a></div>';
                    $mensagem .= '<div>';

                    $mail = new PHPMailer();

                    //$mail->SMTPDebug = 1;
                    $mail->IsSMTP(); // Define que a mensagem será SMTP
                    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
                    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
                    //$mail->Port = $configuracao_email->porta_smtp;
                    //$mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
                    $mail->Username = $configuracao_email->usuario_smtp; // Usuário do servidor SMTP
                    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

                    $mail->From = $configuracao_email->email;
                    $mail->FromName = 'Boleto - IOWA Idiomas';

                    $mail->AddAddress($empresa->email, $empresa->razao_social);
                    $mail->AddBCC('boletos@iowa.com.br', 'Boleto IOWA Idiomas');

                    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
                    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

                    $mail->Subject  = 'Boleto - IOWA Idiomas'; // Assunto da mensagem
                    $mail->Body = $mensagem;

                    if(!$mail->Send()):
                        $status = 'erro';
                    else:
                        //$status = 'ok';
                    endif;

                    $mail->ClearAllRecipients();
                    $mail->ClearAttachments();

                endif;

            endif;
        endforeach;

        echo json_encode(array('status' => $status));

    endif;

endif;


if($dados['acao'] == 'gerar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Gestão de Boletos', 'i');

    /*Gerar novo boleto e arquivo cnab*/
    try{
        $opcoes_cobranca = Opcoes_Cobranca::find(1);
    } catch(\ActiveRecord\RecordNotFound $e){
        $opcoes_cobranca = '';
    }

    $boleto_original = Boletos::find($dados['id_boleto']);
    $parcela = Parcelas::find($boleto_original->id_parcela);

    $nova_parcela = new Parcelas();
    $nova_parcela->id_matricula = $parcela->id_matricula;
    $nova_parcela->id_turma = $parcela->id_turma;
    $nova_parcela->id_idioma = $parcela->id_idioma;
    $nova_parcela->id_aluno = $parcela->id_aluno;
    $nova_parcela->id_empresa = $parcela->id_empresa;
    $nova_parcela->pagante = $parcela->pagante;
    $nova_parcela->id_motivo = $parcela->id_motivo;
    $nova_parcela->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));


    $data_atual = new DateTime("now");
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

    //include_once('../../classes/boletos/funcoes_bancoob.php');

    /*verificando o ultimo numero de arquivo*/
    $arquivo_cnab = new Arquivos_Cnab();
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
    $numero_inscricao = '19895217000135';
    //RAZAO SOCIAL CEDENTE
    $nome_empresa = 'IWS CURSOS DE IDIOMAS LTDA';
    $agencia_cooperativa = 5142;
    $dv_prefixo = 0;
    $conta_corrente = 6445;
    $dv_conta_corrente = 9;

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

            $parcela_atual = Parcelas::find($id_nova_parcela);
            //$aluno = Alunos::find($parcela_atual->id_aluno);


            if($parcela_atual->pagante == 'aluno'):
                $dados_sacado = Alunos::find($parcela_atual->id_aluno);

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
                $dados_sacado = Empresas::find($parcela_atual->id_empresa);

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

            $dtVencimento = $parcela_atual->data_vencimento->format('dmY');
            $emissao = date('dmY');
            $hora = date('His');

            //$valor_boleto = $parcela_atual->total;
            $valor_boleto = $valor /*+ $parcela->acrescimo + $multa + $juros_mora*/;
            //$nosso_numero = '000000000001011     ';
            $pega_nosso_numero = Boletos::find_by_sql('select max(nosso_numero) as nosso_numero from boletos limit 1');

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
                $nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,65838), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
            else:
                $nosso_numero_boleto_sem_zero = $pega_nosso_numero[0]->nosso_numero+1;
                $nosso_numero = $pega_nosso_numero[0]->nosso_numero+1;
                $nosso_numero = str_pad("$nosso_numero", 7, 0, STR_PAD_LEFT);
                $nosso_numero_boleto = str_pad("$nosso_numero".modulo11($nosso_numero,5142,65838), 10, '0', STR_PAD_LEFT)."01"."01"."1"."     ";
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

            $novo_boleto = new Boletos();
            $novo_boleto->chave = md5($numero_documento);
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
            $novo_boleto->convenio = '65838';
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
            $novo_boleto->save();

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



if($dados['acao'] == 'excluir-boletos'):

    $ids_boletos = explode('|', $dados['boletos']);

    if(!empty(array_filter($ids_boletos))):
        foreach(array_filter($ids_boletos) as $id_boleto):
            $boleto = Boletos::find($id_boleto);
            $boleto->delete();
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;
