<?php
    if(!isset($_SESSION)):
        session_start();
    endif;

    include_once('config.php');

    if(filter_input(INPUT_POST, 'entrar', FILTER_SANITIZE_STRING)):

        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

        $usuario = Usuarios::find_by_login_and_senha_and_status($login, md5($senha), 'a');

        if(!empty($usuario)):
            $_SESSION['usuario']['id'] = $usuario->id;
            header('Location:gestores/index.php?tela=inicio');
        else:
            Mensagem('Usuário e/ou senha inválido! Tente novamente.', '');
        endif;

    endif;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Acesso - IOWA Idiomas</title>

    <link rel="shortcut icon" href="<?php echo HOME ?>/assets/imagens/favicon.ico" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/fontello.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/boot.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/estilo.css">

    <script src="<?php echo HOME.'/assets/js/selects.js' ?>"></script>
</head>
<body class="fundo-login">

<section>

    <article class="texto-centro box-login bg-azul texto-branco">

        <div class="padding-15">
            <div><a href="<?php echo HOME ?>/alunos/login.php">SOU ALUNO</a></div>
        </div>

    </article>

    <article class="texto-centro box-login bg-branco">

        <header class="topo-login">
            <img src="<?php echo HOME ?>/assets/imagens/logo-login.png">
        </header>
        <div class="espaco"></div>

        <!--
        <h2 class="titulo texto-centro size-1-5" style="display: inline-block;">UNIDADE TABOÃO DA SERRA</h2>
        <div class="espaco20"></div>
        -->

        <!--
        <h2 class="titulo texto-centro size-1-5" style="display: inline-block;">UNIDADE VIRUTAL</h2>
        <div class="espaco20"></div>
        -->


        <div class="padding-15">
            <form action="" name="formLogin" id="formLogin" method="post">

                <label>Login</label>
                <div class="espaco"></div>
                <input type="text" name="login" id="login" value="" placeholder="Informe seu Login" class="campo-100 texto-centro">
                <div class="espaco20"></div>

                <label>Senha</label>
                <div class="espaco"></div>
                <input type="password" name="senha" id="senha" value="" placeholder="Informe sua Senha" class="campo-100 texto-centro">
                <div class="espaco20"></div>

                <input type="submit" name="entrar" id="entrar" value="Entrar" class="btn btn-azul campo-100 texto-branco cursor-pointer"/>
                <div class="espaco20"></div>

                <a href="recupera-senha-usuario.php"><i class="icon-lock-circled size-1-5 texto-laranja"></i> Esqueci minha senha!</a>

            </form>
        </div>

        <div class="clear"></div>
    </article>

    <article class="texto-centro box-login bg-azul texto-branco">

        <div class="padding-15">
            <div><a href="http://iowaidiomas.com.br/taboao/login.php">UNIDADE TABOÃO DA SERRA</a></div>
        </div>

        <div class="padding-15">
            <div><a href="http://iowaidiomas.com.br/virtual/login.php">UNIDADE VIRTUAL</a></div>
        </div>

        <!--
        <div class="padding-15">
            <div><a href="http://iowaidiomas.com.br/sis/login.php">OUTRAS UNIDADES</a></div>
        </div>
        -->



    </article>

    <div class="espaco20"></div>

</section>
