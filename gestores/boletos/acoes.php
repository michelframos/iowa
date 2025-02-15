<?php

use IowaPainel\EnvioEmailApiController;
use IowaPainel\UnidadesController;
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

$usar_dados = Unidades::find_by_usar_dados_boleto('s');
$convenio = explode('-', $usar_dados->codigo_cliente);
$agencia = explode('-', $usar_dados->agencia);

if($dados['acao'] == 'enviar-emails'):

    $erros = [];
    $status = 'ok';
    $ids_boletos = explode('|', $dados['boletos']);
    if(!empty($ids_boletos)):

        try{
            $configuracao_email = Envio_Emails::find_by_id(1);
        } catch (Exception $e) {
            $configuracao_email = '';
        }

        $cont = 0;
        include_once('../../classes/PHPMailer/class.phpmailer.php');

        foreach($ids_boletos as $id_boleto):

            if($cont == 50):
                $cont = 0;
                sleep(05);
            endif;

            if(!empty($id_boleto)):

                $boleto = Boletos::find_by_id($id_boleto);
                $parcela = Parcelas::find_by_id($boleto->id_parcela);

                if($parcela->pagante == 'aluno'):

                    $aluno = Alunos::find_by_id($parcela->id_aluno);

                    try{
                        $matricula = Matriculas::find_by_id($parcela->id_matricula);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $matricula = '';
                    }

                if(!empty($matricula)):

                    if($matricula->responsavel_financeiro == 3):
                        $inicio_mensagem = 'Caro aluno(a)';
                    elseif($matricula->responsavel_financeiro == 1):
                        $inicio_mensagem = 'Caro senhor(a)';
                    endif;

                    $mensagem = "<div style='text-align: center;'><img src='".HOME."/assets/imagens/logo-email.png'/></div><br><br>";

                    $mensagem .= "<div style='padding: 10px; border: 1px solid #d7d5d2; border-radius: 4px; font: 1em Arial; text-align: center;'>";
                    $mensagem .= $inicio_mensagem.', este email contem abaixo um link para acessar o boleto de sua mensalidade da IOWA IDIOMAS, caso você tenha algum problema';
                    $mensagem .= ' com o pagamento entre em contato conosco pelo telefone 11 2440-7729 ou através do e-mail boletos@iowa.com.br.';

                    $mensagem .= '<br><br>';
                    $mensagem .= "<div style='text-align: center; background-color: #efefef; padding: 10px; border: 1px solid #a7a7a7; border-radius: 4px; display: inline-block; '><a href='".HOME."/boleto.php?boleto=".$boleto->chave."' style='text-decoration: none; color: #000000; display: block;'>Clique Aqui Para Acessar Seu Boleto</a></div>";
                    $mensagem .= '<div>';

                    if($matricula->responsavel_financeiro == 3):
                        $email_destinatario = $aluno->email1;
                    elseif($matricula->responsavel_financeiro == 1):
                        $email_destinatario = $aluno->email1_responsavel;
                    endif;

                    $mail = new EnvioEmailApiController();
                    $retorno = $mail->enviar(
                        'IOWA Idiomas <financeiro@iowaidiomas.com.br>',
                        'Boleto - IOWA Idiomas',
                        $mensagem,
                        $aluno->nome.' <'.$email_destinatario.'>'
                    );

                    if(!empty($email_destinatario)):
                        if($retorno == 'erro'):
                            if($matricula->responsavel_financeiro == 3):
                                $erros[] = ['email' => $aluno->email1];
                            elseif($matricula->responsavel_financeiro == 1):
                                $erros[] = ['email' => $aluno->email1_responsavel];
                            endif;
                        endif;
                    else:
                        $erros[] = ['email' => $aluno->nome];
                    endif;

//                    $mail = new PHPMailer();
//
//                    $mail->SMTPDebug = 1;
//                    $mail->IsSMTP(); // Define que a mensagem será SMTP
//                    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
//                    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
//                    //$mail->Port = $configuracao_email->porta_smtp;
//                    //$mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
//                    $mail->Username = $configuracao_email->usuario_smtp;
//                    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada
//                    $mail->Timeout = 3600;
//
//                    $mail->From = $configuracao_email->email;
//                    $mail->FromName = 'Boleto - IOWA Idiomas';
//
//                    if($matricula->responsavel_financeiro == 3):
//                        $email_destinatario = $aluno->email1;
//                    elseif($matricula->responsavel_financeiro == 1):
//                        $email_destinatario = $aluno->email1_responsavel;
//                    endif;
//
//                    $mail->AddAddress($email_destinatario, $aluno->nome);
//
//                    //$mail->AddBCC('boletos@iowa.com.br', 'Boleto IOWA Idiomas');
//
//                    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
//                    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)
//
//                    $mail->Subject  = 'Boleto - IOWA Idiomas'; // Assunto da mensagem
//                    $mail->Body = $mensagem;
//
//
//                    if(!empty($email_destinatario)):
//
//                        if(!$mail->Send()):
//                            //$status = 'erro';
//                            if($matricula->responsavel_financeiro == 3):
//                                $erros[] = ['email' => $aluno->email1];
//                            elseif($matricula->responsavel_financeiro == 1):
//                                $erros[] = ['email' => $aluno->email1_responsavel];
//                            endif;
//                        else:
//
//                        endif;
//
//                    else:
//
//                        $erros[] = ['email' => $aluno->nome];
//
//                    endif;
//
//                    $mail->ClearAllRecipients();
//                    $mail->ClearAttachments();

                    adicionaHistorico(idUsuario(), idColega(), 'Boletos', 'Alteração', 'Um boleto de R$ '.number_format($boleto->valor, 2, ',','.').' com vencimento em '. $boleto->data_vencimento->format('d/m/Y').' foi enviado para o aluno '.$aluno->nome);

                endif;

                elseif($parcela->pagante == 'empresa'):

                    $empresa = Empresas::find_by_id($parcela->id_empresa);

                    $mensagem = '<div style="text-align: center;"><img src="'.HOME.'/assets/imagens/logo-email.png"/></div><br><br>';

                    $mensagem .= "<div style='padding: 10px; border: 1px solid #d7d5d2; border-radius: 4px; font: 1em Arial; text-align: center;'>";
                    $mensagem .= 'Este email contem abaixo um link para acessar o boleto de sua mensalidade da IOWA IDIOMAS, caso você tenha algum problema';
                    $mensagem .= ' com o pagamento entre em contato conosco pelo telefone 11 2440-7729 ou através do e-mail boletos@iowa.com.br.';

                    $mensagem .= '<br><br>';
                    $mensagem .= "<div style='text-align: center; background-color: #efefef; padding: 10px; border: 1px solid #a7a7a7; border-radius: 4px; display: inline-block; '><a href=".HOME."/boleto.php?boleto=".$boleto->chave." style='text-decoration: none; color: #000000; display: block;'>Clique Aqui Para Acessar Seu Boleto</a></div>";
                    $mensagem .= '<div>';

                    $mail = new EnvioEmailApiController();
                    $retorno = $mail->enviar(
                        'IOWA Idiomas <financeiro@iowaidiomas.com.br>',
                        'Boleto - IOWA Idiomas',
                        $mensagem,
                        $aluno->nome.' <'.$email_destinatario.'>'
                    );

                    if(!empty($email_destinatario)):
                        if($retorno == 'erro'):
                            if($matricula->responsavel_financeiro == 3):
                                $erros[] = ['email' => $aluno->email1];
                            elseif($matricula->responsavel_financeiro == 1):
                                $erros[] = ['email' => $aluno->email1_responsavel];
                            endif;
                        endif;
                    else:
                        $erros[] = ['email' => $aluno->nome];
                    endif;

//                    $mail = new PHPMailer();
//
//                    //$mail->SMTPDebug = 1;
//                    $mail->IsSMTP(); // Define que a mensagem será SMTP
//                    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
//                    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
//                    //$mail->Port = $configuracao_email->porta_smtp;
//                    //$mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
//                    $mail->Username = $configuracao_email->usuario_smtp;
//                    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada
//                    $mail->Timeout = 3600;
//
//                    $mail->From = $configuracao_email->email;
//                    $mail->FromName = 'Boleto - IOWA Idiomas';
//
//                    $mail->AddAddress($empresa->email, $empresa->razao_social);
//                    //$mail->AddBCC('boletos@iowa.com.br', 'Boleto IOWA Idiomas');
//
//                    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
//                    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)
//
//                    $mail->Subject  = 'Boleto - IOWA Idiomas'; // Assunto da mensagem
//                    $mail->Body = $mensagem;
//
//                    if(!empty($empresa->email)):
//
//                        if(!$mail->Send()):
//                            $erros[] = ['email' => $empresa->email];
//                        else:
//                            $status = 'ok';
//                        endif;
//
//                    else:
//
//                        $erros[] = ['email' => $empresa->email];
//
//                    endif;
//
//                    $mail->ClearAllRecipients();
//                    $mail->ClearAttachments();

                    adicionaHistorico(idUsuario(), idColega(), 'Boletos', 'Alteração', 'Um boleto de R$ '.number_format($boleto->valor, 2, ',','.').' com vencimento em '. $boleto->data_vencimento->format('d/m/Y').' foi enviado para a empresa '.$empresa->nome_fantasia);

                endif;

            endif;

            $cont++;

        endforeach;

        echo json_encode(array('status' => $status, 'erros' => $erros));

    endif;

endif;


if($dados['acao'] == 'gerar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Gestão de Boletos', 'i');

    $boleto_original = Boletos::find_by_id($dados['id_boleto']);

    switch ($boleto_original->codigo_banco):
        case '001':
            \IowaPainel\BoletoController::novoBoletoBB($dados);
            break;

        case '756':
            \IowaPainel\BoletoController::novoBoletoSicoob($dados);
            break;
    endswitch;

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
            $parcela = Parcelas::find($boleto->id_parcela);

            try{
                $aluno = Alunos::find($parcela->id_aluno);
            } catch (Exception $e){
                $aluno = '';
            }

            try{
                $empresa = Empresas::find($parcela->id_empresa);
            } catch (Exception $e){
                $empresa = '';
            }

            if($parcela->id_aluno != 0 && $parcela->id_aluno != ''):
                adicionaHistorico(idUsuario(), idColega(), 'Boletos', 'Exclusão', 'Um boleto de '.number_format($boleto->valor, 2, ',', '.').' com vencimento em '.$boleto->data_vencimento->format('d/m/Y').' do aluno '.$aluno->nome);
            else:
                adicionaHistorico(idUsuario(), idColega(), 'Boletos', 'Exclusão', 'Um boleto de '.number_format($boleto->valor, 2, ',', '.').' com vencimento em '.$boleto->data_vencimento->format('d/m/Y').' da empresa'. $empresa->nome_fantasia);
            endif;

            $boleto->delete();
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;
