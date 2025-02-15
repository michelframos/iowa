<?php
include_once('../config.php');
include_once('funcoes_painel.php');

if(filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_STRING) == 'sairPainel'):
    sairPainel();
endif;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área do Aluno - IOWA Idiomas</title>

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

    <script src="<?php echo HOME.'/assets/js/selects.js' ?>"></script>
    <script src="<?php echo HOME ?>/assets/js/jquery-1.12.2.min.js"></script>
    <script src="<?php echo HOME ?>/assets/js/jquery.mask.min.js"></script>
    <script src="<?php echo HOME ?>/assets/js/jquery.maskMoney.js"></script>
    <script src="js/menu.js"></script>

</head>
<body>

<?php include_once('topo.php'); ?>

<section id="pmd-main">
    <!-- Left sidebar -->
    <aside id="basicSidebar" class="pmd-sidebar sidebar-default pmd-z-depth" role="navigation">
        <?php include_once('menu.php'); ?>
    </aside>

    <div id="content" class="pmd-content custom-pmd-content">

        <?php
        if(filter_input(INPUT_GET, "tela")):
            $url = new mudaURL();
            $url->mudarUrl(filter_input(INPUT_GET, "tela"));
        endif;
        ?>
    </div>

</section>


</body>

<script src="<?php echo HOME ?>/assets/js/jquery-1.12.2.min.js"></script>
<script src="<?php echo HOME ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo HOME ?>/assets/js/circles.min.js"></script>
<script src="<?php echo HOME ?>/assets/js/highcharts-more.js"></script>
<script src="<?php echo HOME ?>/assets/js/highcharts.js"></script>
<script src="<?php echo HOME ?>/assets/js/moment.min.js"></script>
<script src="<?php echo HOME ?>/assets/js/propeller.min.js"></script>

</html>
