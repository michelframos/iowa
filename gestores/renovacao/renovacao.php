<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Renovação de Contrato', 'c', 'index');

?>

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

<script src="js/renovacao.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">restore_page</i>
    <h1>Renovação de Contrato</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Mês da Última Parcela</label>
            <select name="mes" id="mes" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="01">Janeiro</option>
                <option value="02">Fevereiro</option>
                <option value="03">Março</option>
                <option value="04">Abril</option>
                <option value="05">Maio</option>
                <option value="06">Junho</option>
                <option value="07">Julho</option>
                <option value="08">Agosto</option>
                <option value="09">Setembro</option>
                <option value="10">Outubro</option>
                <option value="11">Novembro</option>
                <option value="12">Dezembro</option>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
            <label for="regular1" class="control-label">Ano</label>
            <input type="text" name="ano" id="ano" value="<?php echo date('Y'); ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
        </div>

        <div class="coluna-3 float-left margin-right-5">
            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                <label>Motivo</label>
                <select name="id_motivo" id="id_motivo" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="">Parcela</option>
                    <?php
                    $motivos = Motivos_Parcela::all(array('order' => 'motivo asc'));
                    if(!empty($motivos)):
                        foreach($motivos as $motivo):
                            echo '<option matricula="'.$matricula->id.'" value="'.$motivo->id.'">'.$motivo->motivo.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Aluno</label>
            <input type="text" name="aluno" id="aluno" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <button type="submit" name="pesquisar" id="pesquisar" value="Listar Contratos" class="btn btn-info pmd-btn-raised">Listar Contratos</button>
        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

    <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

</section>

<section class="pmd-card pmd-z-depth padding-10">

    <div id="listagem">
        <?php include_once('listagem.php'); ?>
    </div>

</section>

<script type="text/javascript">
    $("#data_inicial, #data_final").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>