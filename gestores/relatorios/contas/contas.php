<?php
    include_once('../../../config.php');
    include_once('../../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Relatório - Contas a Pagar', 'c', 'index');

?>
<script src="js/jQuery.print.min.js"></script>
<script src="js/rel-contas-pagar.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">description</i>
    <h1>Relatório de Contas a Pagar</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">
        <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-4">
            <label for="regular1" class="control-label">Data Vencto Inicial</label>
            <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-4">
            <label for="regular1" class="control-label">Data Vencto Final</label>
            <input type="text" name="data_final" id="data_final" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
            <label>Natureza</label>
            <select name="id_natureza" id="id_natureza" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="%">Todas</option>
                <?php
                $naturezas = Natureza_Conta::all(array('conditions' => array('status = ?', 'a'), 'order' => 'natureza asc'));
                if(!empty($naturezas)):
                    foreach($naturezas as $natureza):
                        echo '<option value="'.$natureza->id.'">'.$natureza->natureza.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
            <label>Pago</label>
            <select name="pago" id="pago" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="%">Todas</option>
                <option value="n">Não Pagas</option>
                <option value="s">Pagas</option>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Categoria</label>
            <select name="id_categoria" id="id_categoria" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="%">Todas</option>
                <?php
                $categorias = Categorias_Lancamentos::all(array('conditions' => array('status = ?', 'a'), 'order' => 'categoria asc'));
                if(!empty($categorias)):
                    foreach($categorias as $categoria):
                        echo '<option value="'.$categoria->id.'">'.$categoria->categoria.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Unidade</label>
            <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="%">Todas</option>
                <?php
                $unidades = Unidades::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome_fantasia asc'));
                if(!empty($unidades)):
                    foreach($unidades as $unidade):
                        echo '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Fornecedor</label>
            <select name="id_fornecedor" id="id_fornecedor" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="%">Todas</option>
                <?php
                $fornecedores = Fornecedores::all(array('conditions' => array('status = ?', 'a'), 'order' => 'fornecedor asc'));
                if(!empty($fornecedores)):
                    foreach($fornecedores as $fornecedore):
                        echo '<option value="'.$fornecedore->id.'">'.$fornecedore->fornecedor.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <button type="button" name="gerar-relatorio" id="gerar-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Gerar Relatório</button>

        <?php if(Permissoes::find_by_id_usuario_and_tela_and_imp(idUsuario(), 'Relatório - Contas a Pagar', 's')): ?>
        <button type="button" name="imprimir-relatorio" id="imprimir-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Imprimir Relatório</button>
        <?php endif; ?>

        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

</section>

<section class="pmd-card pmd-z-depth padding-10">

    <div id="relatorio"></div>

</section>

<script type="text/javascript">
    $("#data_inicial, #data_final").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>