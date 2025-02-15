<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');
?>

<script src="js/caixas.js"></script>

<div tabindex="-1" class="modal fade" id="delete-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão deste Caixa e todos os seus registros? Esta ação é irreversível! </p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="novo-caixa-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Novo Caixa</h2>
            </div>
            <div class="modal-body">

                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Data de Abertura</label>
                    <input type="text" name="data_abertura" id="data_abertura" value="<?php echo date('d/m/Y') ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Saldo Inicial</label>
                    <input type="text" name="saldo_inicial" id="saldo_inicial" value="<?php echo number_format(0, 2, ',', '.'); ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">Abrir</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">attach_money</i>
        <h1>Caixas Fechados</h1>
    </div>

    <div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        Este Registro não pode ser excluído por já ter sido utilizado no sistema.
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
        <div class="espaco20"></div>

        <!-- Form de Pesquisa -->
        <!--
        <form action="" name="formPesquisa" id="formPesquisa" method="post">
            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                <label for="regular1" class="control-label">Data Inicial</label>
                <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                <label for="regular1" class="control-label">Data Final</label>
                <input type="text" name="data_final" id="data_final" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
            <div class="espaco20"></div>

            <button type="button" name="pesquisar" id="pesquisar" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
            <div class="espaco20"></div>
        </form>
        -->
        <!-- Form de Pesquisa -->

        <div id="listagem">
            <?php include_once('listagem_fechados.php'); ?>
        </div>

    </section>

<script type="text/javascript">
    $("#data_inicial, #data_final").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>
