<?php
    if(!isset($_SESSION)):
        session_start();
    endif;

    include_once('../config.php');
    include_once('funcoes_painel.php');
    verificaSessao();
    $usuario = Usuarios::find(idUsuario());
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
    <script src="<?php echo HOME ?>/assets/js/root.js"></script>
    <script src="js/menus.js"></script>
    <script>
        $('#salvando-dialog').click(function(){ return false; });
    </script>

</head>
<body>

<div tabindex="-1" class="modal fade" data-backdrop="static" id="salvando-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text" id="titulo-modal">Salvando...</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Salvando alterações. Por favor aguarde...</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary oculto" type="button" id="bt-salvou">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" data-backdrop="static" id="carregando-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text" id="titulo-modal">Carregando conteúdo, por favor aguarde...</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Salvando alterações. Por favor aguarde...</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary oculto" type="button" id="bt-carregou">Cancelar</button>
            </div>
        </div>
    </div>
</div>

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

<div class="oculto" id="ms-salvando-dialog" data-target="#salvando-dialog" data-toggle="modal"></div>
<div class="oculto" id="ms-carregando-dialog" data-target="#carregando-dialog" data-toggle="modal"></div>


</body>

<script src="<?php echo HOME ?>/assets/js/bootstrap.min.js"></script>
<!--
<script src="<?php echo HOME ?>/assets/js/circles.min.js"></script>
<script src="<?php echo HOME ?>/assets/js/highcharts-more.js"></script>
<script src="<?php echo HOME ?>/assets/js/highcharts.js"></script>
-->
<script src="<?php echo HOME ?>/assets/js/moment.min.js"></script>
<script src="<?php echo HOME ?>/assets/js/moment-with-locales.js"></script>
<script src="<?php echo HOME ?>/assets/js/propeller.js"></script>

<!-- Propeller textfield js -->
<script type="text/javascript" src="<?php echo HOME ?>/assets/js/textfield.js"></script>

<!-- Propeller Bootstrap datetimepicker -->
<script type="text/javascript" language="javascript" src="<?php echo HOME ?>/assets/js/bootstrap-datetimepicker.js"></script>

</html>

<script>
    $(function(){
       $('#loading').fadeOut(1000);
    });
</script>