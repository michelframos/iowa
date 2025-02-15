<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $id_parcela = filter_input(INPUT_POST, 'id_parcela', FILTER_VALIDATE_INT);
    $empresa = Empresas::find($id);
    $parcela = Parcelas::find($id_parcela);
    /*
    $matricula = Matriculas::find($parcela->id_matricula);
    if($matricula->responsavel_financeiro == 2):
        $empresa = Empresas::find($matricula->id_empresa_financeiro);
    endif;
    */

?>

<script src="js/parcelas_empresas.js"></script>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já existe um Idioma com este nome.</p>
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

<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">event_note</i>
        <h1>Alteração de Parcela</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar" registro="<?php echo $aluno->id ?>">Voltar</a>
        <div class="espaco20"></div>

        <form action="" name="formDados" id="formDados" method="post" style="max-width: 800px;">

            <div class="clear"></div>
            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
                <label for="regular1" class="control-label">Valor da Parcela</label>
                <input type="text" name="valor_parcela" id="valor_parcela" value="<?php echo number_format($parcela->valor, 2, ',', '.') ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
                <label for="regular1" class="control-label">Primeira Data de Vencimento</label>
                <input type="text" name="data_vencimento" id="data_vencimento" value="<?php echo $parcela->data_vencimento->format('d/m/Y') ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield">
                <label class="control-label">Observação</label>
                <textarea required class="form-control" name="observacao" id="observacao" style="height: 150px;"></textarea>
            </div>

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $empresa->id ?>" parcela="<?php echo $parcela->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

        </form>

    </section>

<script>
    $(function(){
        $("#data_vencimento").datetimepicker({
            format: "DD/MM/YYYY"
        });
    });
</script>
