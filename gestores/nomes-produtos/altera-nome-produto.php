<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Nomes_Produtos::find($id);

?>

<script src="js/nomes-produtos.js"></script>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já exite um Um Nome de Produto com este Nome de Material.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="alterado-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterações</h2>
            </div>
            <div class="modal-body">
                <p>Alterações salvas com sucesso.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
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
    <i class="material-icons texto-laranja pmd-md">business_center</i>
    <h1>Cadastro / Alteração de Nome de Produto e Hora Semanal</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
    <div class="espaco20"></div>

    <form action="" name="formDados" id="formDados" method="post" style="max-width: 600px;">

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Nome do Material</label>
            <input type="text" name="nome_material" id="nome_material" value="<?php echo $registro->nome_material ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
        </div>

        <!--
        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Nome do Pacote de Horas Semanal</label>
            <input type="text" name="nome_pacote_horas" id="nome_pacote_horas" value="<?php echo $registro->nome_pacote_horas ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        -->

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Hora(s) Por Aula</label>
            <input type="text" name="horas_semanais" id="horas_semanais" value="<?php echo $registro->horas_semanais ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
        </div>

        <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
        <div class="espaco20"></div>

        <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

    </form>

</section>
