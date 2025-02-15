<?php
    session_start();
    include_once('config.php');


    $hash = filter_input(INPUT_GET, 'recuperacao', FILTER_SANITIZE_STRING);
    $registro = Recuperacao_Senha::find_by_hash($hash);

    if($registro->utilizado == 's'):
        Mensagem('Desculpe, este link para recuperação de senha já foi utilizado!', 'index.php');
        exit();
    endif;


    /*Verificando se já se passou 24 horas*/
    $data2 = new DateTime();
    $intervalo = $registro->data->diff( $data2 );

    if($intervalo->d >= 1):
        Mensagem('Desculpe, este link foi gerado a mais de 24 horas! Por favor faça outra solicitação para recuperar sua senha.', 'index.php');
        exit();
    endif;
    /*Fim da verificação*/



    if(filter_input(INPUT_POST, 'salvar', FILTER_SANITIZE_STRING)):

        $hash = filter_input(INPUT_GET, 'recuperacao', FILTER_SANITIZE_STRING);
        $registro = Recuperacao_Senha::find_by_hash($hash);

        if($registro->tipo == 'usuario'):
            $usuario = Usuarios::find($registro->id_usuario);
        elseif($registro->tipo == 'aluno'):
            $usuario = Alunos::find($registro->id_usuario);
        endif;

        $senha = md5(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING));
        $usuario->senha = $senha;
        $usuario->save();

        /*marcando a recuperação como utilizada*/
        $registro->utilizado = 's';
        $registro->data_utilizacao = date('Y-m-d H:i:s');
        $registro->save();

        Mensagem('Senha salva com sucesso!', 'index.php');

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

    <article class="texto-centro box-login bg-branco">

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
                <h1 class="titulo size-1-5 texto-centro" style="display: inline-block;">NOVA SENHA</h1>
                <p>Informe a baixo sua nova senha, a confirmação e clique em 'Salvar Nova Senha'.</p>
                <div class="espaco20"></div>

                <label>Nova Senha</label>
                <div class="espaco"></div>
                <input type="password" name="senha" id="senha" value="" placeholder="Nova Senha" class="campo-100 texto-centro">
                <div class="espaco20"></div>

                <label>Confirme a Nova Senha</label>
                <div class="espaco"></div>
                <input type="password" name="confirma_senha" id="confirma_senha" value="" placeholder="Confirme a Nova Senha" class="campo-100 texto-centro">
                <div class="espaco20"></div>

                <input type="submit" name="salvar" id="salvar" value="Salvar Nova Senha" class="btn btn-azul texto-branco cursor-pointer"/>
                <input type="submit" name="voltar" id="voltar" value="Cancelar" class="btn btn-vermelho texto-branco cursor-pointer"/>
                <div class="espaco20"></div>

            </form>
        </div>

        <div class="clear"></div>
    </article>

    <div class="espaco20"></div>

</section>

<script src="<?php echo HOME ?>/assets/js/jquery-1.12.2.min.js" ></script>
<script src="<?php echo HOME ?>/assets/js/nova-senha.js" ></script>
