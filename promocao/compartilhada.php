<?php
    if(isset($_POST['enviar'])):
        $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
        $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING));
        $interesse = trim(filter_input(INPUT_POST, 'interesse', FILTER_SANITIZE_STRING));

        $participacao = new Participacoes_Promocoes();
        $participacao->id_envio_promocao = $envio->id;
        $participacao->nome_participante = $nome;
        $participacao->email_participante = $email;
        $participacao->telefone_participante = $telefone;
        $participacao->interesse = $interesse;
        $participacao->data_participacao = date('Y-m-d H:i:s');
        $participacao->save();

        $envio->utilizado = 's';
        $envio->save();

        /*enviado email para o gestor da unidade*/
        include_once('classes/PHPMailer/class.phpmailer.php');

        try{
            $configuracao_email = Envio_Emails::find(1);
        } catch (Exception $e) {
            $configuracao_email = '';
        }

        $aluno = Alunos::find($envio->id_aluno);
        $unidade = Unidades::find($aluno->id_unidade);
        $gerente = Usuarios::find($unidade->id_gerente);
        $promocao = Promocoes::find($envio->id_promocao);

        $mensagem  = "Olá {$gerente->nome}, alguém por nome {$nome} acaba de participar da promoção {$promocao->nome} a convite do aluno {$aluno->nome} da unidade {$unidade->nome_fantasia}. ";


        $mail = new PHPMailer();

        $mail->SMTPDebug = 1;
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
        $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
        //$mail->Port = $configuracao_email->porta_smtp;
        $mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
        $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

        $mail->From = $configuracao_email->email;
        $mail->FromName = 'Promoção IOWA';

        $mail->AddAddress($gerente->email, $gerente->nome);

        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

        $mail->Subject  = 'Particiacao na Promocao: '.$promocao->nome; // Assunto da mensagem
        $mail->Body = $mensagem;

        if($mail->Send()):
            header('location:'.HOME.'/promocao/obrigado');
        else:
            echo 'Ops! Alguma coisa saiu errada.';
        endif;

    endif;
?>

<div style="position: absolute; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
    <div style="max-width: 450px;">
        <div>
            <div class="texto-centro"><img src="assets/images/logoiowa.png" width="100"/> </div>
            <div class="espaco20"></div>
            <div class="texto-branco texto-centro size-3" style="color: #f9b03e">Você ganhou 10% de desconto!</div>
            <div class="espaco20"></div>
            <p class="texto-branco texto-centro size-1-5 texto">Olá, seja bem-vindo a promoção <?php echo $promocao->nome ?>. Precisamos de alguns dados para entrarmos em contato com você!</p>
            <div class="espaco20"></div>

            <div id="box-dados">

                <form action="" method="post" name="formDados" id="formDados">

                    <input type="text" name="nome" id="nome" value="" placeholder="Nome*" autocomplete="off" required>
                    <div class="espaco20"></div>

                    <input type="text" name="email" id="email" value="" placeholder="E-mail*" autocomplete="off" required>
                    <div class="espaco20"></div>

                    <input type="text" name="telefone" id="telefone" value="" placeholder="Celular/WhatsApp*" autocomplete="off" required>
                    <div class="espaco20"></div>

                    <select name="interesse" id="interesse" required>
                        <option value="">Interesse</option>
                        <option value="Ingles">Ingles</option>
                        <option value="Espanhol">Espanhol</option>
                        <option value="Francês">Francês</option>
                    </select>
                    <div class="espaco20"></div>

                    <input type="submit" name="enviar" id="enviar" value="ENVIAR" class="btn-enviar">

                </form>

            </div>

        </div>
    </div>

</div>