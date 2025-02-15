<?php
    include_once('../../../config.php');
    include_once('../../funcoes_painel.php');

    /*Verificando Permissões*/
    //verificaPermissao(idUsuario(), 'Categorias de Usuários', 'c', 'index');

?>
<script src="js/jQuery.print.min.js"></script>
<script src="js/rel-email-marketing.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">description</i>
    <h1>E-mail Marketing</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed col-md-2">
            <label></label>
            <div class="clear"></div>
            <label>Situação do Aluno</label>
            <select name="situacao_aluno" id="situacao_aluno" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <?php
                $situacoes = Situacao_Aluno::all();
                if(!empty($situacoes)):
                    foreach($situacoes as $situacao):
                        echo '<option value="'.$situacao->status.'">'.$situacao->situacao.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="col-md-5">
            <label>Matriculado Em</label>
            <div class="clear"></div>
            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left col-md-6">
                <label for="regular1" class="control-label">Data Inicial</label>
                <input type="text" name="data_inicial_matricula" id="data_inicial_matricula" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left col-md-6">
                <label for="regular1" class="control-label">Data Final</label>
                <input type="text" name="data_final_matricula" id="data_final_matricula" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
        </div>

        <div class="col-md-5">
            <label>Inativado Em</label>
            <div class="clear"></div>
            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left col-md-6">
                <label for="regular1" class="control-label">Data Inicial</label>
                <input type="text" name="data_inicial_nativado" id="data_inicial_nativado" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left col-md-6">
                <label for="regular1" class="control-label">Data Final</label>
                <input type="text" name="data_final_inativado" id="data_final_inativado" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
        </div>
        <div class="clear"></div>

        <button type="button" name="gerar-relatorio" id="gerar-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Gerar Relatório</button>
        <button type="button" name="imprimir-relatorio" id="imprimir-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Imprimir Relatório</button>
        <button type="button" name="exportar-relatorio" id="exportar-relatorio" value="Exportar Relatório" class="btn btn-info pmd-btn-raised">Exportar</button>
        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

</section>

<section class="pmd-card pmd-z-depth padding-10">

    <div id="relatorio"></div>

</section>

<script type="text/javascript">
    $("#data_inicial_matricula, #data_final_matricula, #data_inicial_nativado, #data_final_inativado").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>