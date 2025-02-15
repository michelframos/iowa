<?php
    $empresa = Empresas::find(idEmpresa());
?>

<!-- Nav menu with user information -->
<nav class="navbar navbar-inverse pmd-navbar navbar-fixed-top pmd-z-depth" style="position:absolute;">
    <div class="container-fluid">
        <!-- User information -->
        <div class="dropdown pmd-dropdown pmd-user-info pull-right">
            <a href="javascript:void(0);" class="btn-user dropdown-toggle media" data-toggle="dropdown" aria-expanded="false">
                <div class="media-left">
                    <img src="<?php echo HOME ?>/assets/imagens/empresas/gde_<?php echo !empty($empresa->imagem) ? $empresa->imagem : '' ?>" width="40" height="40" alt="avatar">
                </div>
                <div class="media-body media-middle">
                    <?php echo $empresa->nome_fantasia ?>
                </div>
                <div class="media-right media-middle">
                    <i class="material-icons md-dark pmd-sm">more_vert</i>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a id="menu-perfil" href="javascript:void(0);">Meu Perfil</a></li>
                <li><a href="?tela=inicio&acao=sairPainel">Sair</a></li>
            </ul>
        </div>

        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a href="javascript:void(0);" data-target="basicSidebar" data-placement="left" data-position="slidepush" is-open="true" is-open-width="1000" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect pull-left margin-r8 pmd-sidebar-toggle"><i class="material-icons md-light">menu</i></a>
            <img src="<?php echo HOME ?>/assets/imagens/logo-peq-branco.png" class="margin-top-15 float-left"/>
        </div>
    </div>
    <div class="pmd-sidebar-overlay"></div>
</nav>