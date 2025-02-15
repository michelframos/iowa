<?php
    session_start();
    include_once('config.php');

    if(filter_input(INPUT_POST, 'enviar', FILTER_SANITIZE_STRING)):

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        if(empty($email)):
            Mensagem('Informe o e-mail!', '');
            exit();
        endif;

        if(!Usuarios::find_by_email($email)):
            Mensagem('Desculpe, não encontramos nenhum cadastro com o email informado.', '');
            exit();
        endif;

        $usuario = Usuarios::find_by_email($email);

        /*Criando registro de recuperação de senha*/
        $recuperacao = new Recuperacao_Senha();
        $recuperacao->tipo = 'usuario';
        $recuperacao->id_usuario = $usuario->id;
        $recuperacao->email = $usuario->email;
        $recuperacao->data = date('Y-m-d H:i:s');
        $recuperacao->utilizado = 'n';
        $recuperacao->save();

        /*criando hash*/
        $id_recuperacao = $recuperacao->id;
        $recuperacao = Recuperacao_Senha::find($id_recuperacao);
        $recuperacao->hash = md5($id_recuperacao);
        $recuperacao->save();

        try{
            $configuracao_email = Envio_Emails::find(1);
        } catch (Exception $e) {
            $configuracao_email = '';
        }

        /*Enviando instruções*/
        $mensagem  = "Olá {$usuario->nome}, você solicitou uma recuperação de senha no sistema IOWA IDIOMAS. Siga as instruções abaixo:\r\n";
        $mensagem .= "<a href='".HOME."/nova-senha.php?recuperacao={$recuperacao->hash}'>Clique aqui</a> para abrir a página de recuperação de senha, em seguida digite sua nova senha e confirme-a no campo de baixo, depois clique em 'Salvar Nova Senha'.\r\n";
        $mensagem .= "Esta solicitação de recuperação de senha tem a validade de 24 horas, após isso, uma nova solicitação deve ser realizada.";

        include_once('classes/PHPMailer/class.phpmailer.php');

        $mail = new PHPMailer();

        //$mail->SMTPDebug = 1;
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
        $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
        //$mail->Port = $configuracao_email->porta_smtp;
        $mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
        $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

        $mail->From = $configuracao_email->email;
        $mail->FromName = 'Recuperação de Senha - IOWA Idiomas';

        $mail->AddAddress($usuario->email, $usuario->nome);
        //$mail->AddBCC($aluno->email, $aluno->nome);

        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

        $mail->Subject  = 'Recuperação de Senha - IOWA Idiomas'; // Assunto da mensagem
        $mail->Body = $mensagem;

        if(!$mail->Send()):
            Mensagem('Desculpe. Ocorreu um erro ao enviar as intruções de recuperação de senha.', '');
        else:
            Mensagem('As instruções de recuperação de senha foram enviadas para o email informado.', 'login.php');
        endif;

        $mail->ClearAllRecipients();
        $mail->ClearAttachments();

    endif;


    if(filter_input(INPUT_POST, 'voltar', FILTER_SANITIZE_STRING)):

        header('Location:index.php');

    endif;


    /*
     * Calculando diferença entre datas
    $data1 = new DateTime( '2019-01-30 16:30' );
    $data2 = new DateTime( '2019-01-31 16:30' );
    $intervalo = $data1->diff( $data2 );

    echo "Intervalo é de {$intervalo->y} anos, {$intervalo->m} meses e {$intervalo->d} dias";
    */

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha - IOWA Idiomas</title>

    <link rel="shortcut icon" href="<?php echo HOME ?>/assets/imagens/favicon.ico" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/fontello.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/boot.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/estilo.css">

    <script src="<?php echo HOME.'/assets/js/selects.js' ?>"></script>
</head>
<body class="fundo-login">

<section>

    <article class="texto-centro box-recupera-senha bg-branco">

        <header class="topo-login">
            <img src="<?php echo HOME ?>/assets/imagens/logo-login.png">
        </header>
        <div class="espaco"></div>

        <!--
        <h2 class="titulo texto-centro size-1-5" style="display: inline-block;">UNIDADE TABOÃO DA SERRA</h2>
        <div class="espaco20"></div>
        -->

        <div class="padding-15">
            <form action="" name="formLogin" id="formLogin" method="post">

                <div class="espaco20"></div>
                <h1 class="titulo size-1-5 texto-centro" style="display: inline-block;">RECUPERAÇÃO DE SENHA</h1>
                <p>Informe abaixo o email utilizado no cadastro e as instruções para recuperar sua senha serão enviadas para ele.</p>
                <div class="espaco20"></div>

                <label>E-mail utilizado no cadastro.</label>
                <div class="espaco"></div>
                <input type="text" name="email" id="email" value="" placeholder="" class="campo-100 texto-centro">
                <div class="espaco20"></div>

                <input type="submit" name="enviar" id="enviar" value="Enviar Instruções" class="btn btn-azul texto-branco cursor-pointer"/>
                <input type="submit" name="voltar" id="voltar" value="Cancelar" class="btn btn-vermelho texto-branco cursor-pointer"/>
                <div class="espaco20"></div>

            </form>
        </div>

        <div class="clear"></div>
    </article>

    <div class="espaco20"></div>

</section>
