<?php
    include_once('../../../config.php');
    include_once('../../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Relatório - Alunos / Empresas', 'c', 'index');

?>
<script src="js/jQuery.print.min.js"></script>
<script src="js/rel-alunos-empresas.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">description</i>
    <h1>Alunos / Empresas</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Unidade</label>
            <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <?php
                $unidades = Unidades::all(['conditions' => ['status = ? and coalesce(id, "") <> ?', 'a', ''], 'order' => 'nome_fantasia asc']);
                if(!empty($unidades)):
                    foreach($unidades as $unidade):
                        echo '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Situação do Aluno</label>
            <select name="status" id="status" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <option value="a">Ativos</option>
                <option value="i">Inativos</option>
            </select>
        </div>
        <div class="clear"></div>

        <button type="button" name="gerar-relatorio" id="gerar-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Gerar Relatório</button>

        <?php if(Permissoes::find_by_id_usuario_and_tela_and_imp(idUsuario(), 'Relatório - Alunos / Empresas', 's')): ?>
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
