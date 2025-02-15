<?php
    include ('../config.php');

    if(isset($_POST['enviar'])):

        include_once (__DIR__.'/../classes/PHPMailer/class.phpmailer.php');

        $configuracao_email = Envio_Emails::find_by_id(1);

        $numero = $_POST['numero'];
        $email = $_POST['email'];
        $mensagem = $_POST['conteudo'];

        for($i = 0; $i < $numero; $i++):

            echo 'Tentativa ...'.($i+1).' - '.date('d/m/Y H:i:s').'<br>';
            //sleep(1);

            $mail = new PHPMailer();

            $mail->SMTPDebug = 1;
            $mail->IsSMTP(); // Define que a mensagem será SMTP
            $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
            $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
            $mail->Port = $configuracao_email->porta_smtp;
            //$mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
            $mail->Username = 'iowaidiomas'; // Usuário do servidor SMTP
            $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada
            $mail->Timeout = 3600;

            $mail->From = $configuracao_email->email;
            $mail->FromName = 'Teste Envio - IOWA Idiomas';

            $mail->AddAddress($email, 'TESTE');

            $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
            $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

            $mail->Subject  = 'Teste Envio - IOWA Idiomas'; // Assunto da mensagem
            $mail->Body = $mensagem;

            if(!$mail->Send()):
                echo 'Erro na '.($i+1).'ª tentativa - '.date('d/m/Y H:i:s').'<br>';
                echo $mail->SMTPDebug;
            else:
                echo 'E-mail enviado com sucesso na tantariva: '.($i+1).' - '.date('d/m/Y H:i:s');
            endif;

            $mail->ClearAllRecipients();
            $mail->ClearAttachments();

            ob_flush();

        endfor;

    endif;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área do Gestor - IOWA Idiomas</title>

    <!--<link rel="shortcut icon" href="<?php echo HOME ?>/imagens/favicon.ico" />-->
    <meta name="viewport" content="width=device-width">
    <!-- Google icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/fontello.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/propeller.min.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/propeller-admin.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/propeller-theme.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/boot.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/estilo.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/imports.css">

    <script src="<?php echo HOME ?>/assets/js/jquery-1.12.2.min.js"></script>
    <script src="<?php echo HOME ?>/assets/js/jquery.mask.min.js"></script>
    <script src="<?php echo HOME ?>/assets/js/jquery.maskMoney.js"></script>


</head>
<body>

<section id="pmd-main">

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="col-md-12">
        <div class="titulo">TESTE DE ENVIO DE E-MAILS</div>
        </div>

        <form action="" method="post" name="form">

            <div class="form-group pmd-textfield pmd-textfield-floating-label col-md-2">
                <label for="regular1" class="control-label">Enviar Quantas Vezes?</label>
                <input type="text" name="numero" id="numero" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label col-md-6">
                <label for="regular1" class="control-label">Endereço E-mail</label>
                <input type="text" name="email" id="email" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label col-md-6">
                <label for="regular1" class="control-label">Conteúdo do E-mail</label>
                <textarea name="conteudo" id="conteudo" value="" class="form-control"></textarea>
            </div>
            <div class="clear"></div>

            <button type="submit" name="enviar" id="enviar" class="btn btn-info pmd-btn-raised">Enviar E-mails</button>
            <div class="espaco20"></div>

        </form>

    </section>

</section>

</body>

<script src="<?php echo HOME ?>/assets/js/bootstrap.min.js"></script>

<script src="<?php echo HOME ?>/assets/js/moment.min.js"></script>
<script src="<?php echo HOME ?>/assets/js/moment-with-locales.js"></script>
<script src="<?php echo HOME ?>/assets/js/propeller.js"></script>

<!-- Propeller textfield js -->
<script type="text/javascript" src="<?php echo HOME ?>/assets/js/textfield.js"></script>

<!-- Propeller Bootstrap datetimepicker -->
<script type="text/javascript" language="javascript" src="<?php echo HOME ?>/assets/js/bootstrap-datetimepicker.js"></script>

</html>