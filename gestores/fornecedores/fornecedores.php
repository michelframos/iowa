<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Fornecedores', 'c', 'index');
?>

<script src="js/fornecedores.js"></script>

<div tabindex="-1" class="modal fade" id="delete-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão deste Fornecedor? Esta ação é irreversível! </p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="permissao-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Permissão Negada</h2>
            </div>
            <div class="modal-body">
                <p id="msg-permissao-dialog"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">local_shipping</i>
        <h1>Fornecedores</h1>
    </div>

    <div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        Este Registro não pode ser excluído por já ter sido utilizado no sistema.
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-novo"> Novo Fornecedor</a>
        <div class="espaco20"></div>

        <!-- Form de Pesquisa -->
        <form action="" name="formPesquisa" id="formPesquisa" method="post">
            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Pesquisar</label>
                <input type="text" name="valor_pesquisa" id="valor_pesquisa" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <button type="button" name="pesquisar" id="pesquisar" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
            <div class="espaco20"></div>
        </form>
        <!-- Form de Pesquisa -->

        <div id="listagem">
            <?php include_once('listagem.php'); ?>
        </div>

    </section>

    <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>