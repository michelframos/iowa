<?php
    include_once('../config.php');
    include_once('funcoes_painel.php');

    if(filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_STRING) == 'sairPainel'):
        sairPainel();
    endif;
?>
<!-- Start Content -->
<h1 class="headline">Dashboard</h1>
<div class="espaco20"></div>

<article class="flex">



</article>

<div class="pmd-sidebar-overlay"></div>
