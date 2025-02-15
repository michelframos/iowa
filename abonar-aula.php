<?php
    include_once('config.php');
    $token = filter_input(INPUT_GET, 'pedido',FILTER_SANITIZE_STRING);
    $pedido = Pedidos_Abono::find_by_token($token);
    $aula_aluno = Aulas_Alunos::find($pedido->id_aula_aluno);
    $aula_turma = Aulas_Turmas::find($pedido->id_aula_turma);
    $turma = Turmas::find($aula_turma->id_turma);
    $aluno = Alunos::find($aula_aluno->id_aluno);

    $pedido->abonada = 's';
    $pedido->save();

    $aula_aluno->abonada = 's';
    $aula_aluno->save();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Abono de Aula - IOWA Idiomas</title>

    <link rel="shortcut icon" href="<?php echo HOME ?>/assets/imagens/favicon.ico" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/fontello.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/boot.css">
    <link rel="stylesheet" href="<?php echo HOME ?>/assets/css/estilo.css">

    <script src="<?php echo HOME.'/assets/js/jquery-1.12.2.min.js' ?>"></script>
    <script src="<?php echo HOME.'/assets/js/selects.js' ?>"></script>
    <script src="<?php echo HOME.'/assets/js/jquery.mask.min.js' ?>"></script>
</head>
<body class="fundo-login">

<section>

    <article class="texto-centro box-recupera-senha bg-branco">

        <div class="espaco20"></div>
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
                <h1 class="titulo size-1-5 texto-centro" style="display: inline-block;">Abono de Aula</h1>
                <p>Você abonou a aula do dia <?php echo $aula_turma->data->format('d/m/Y') ?> da turma <?php echo $turma->nome ?> do Aluno <?php echo $aluno->nome ?>.</p>
                <div class="espaco20"></div>

                <h2 class="negrito texto-laranja">JUSTIFICATIVA:</h2>
                <p><?php echo $pedido->justificativa; ?></p>
                <div class="espaco20"></div>

            </form>
        </div>

        <div class="clear"></div>
    </article>

    <div class="espaco20"></div>

</section>

<script>
    $('#data_nascimento').mask('00/00/0000');
</script>
