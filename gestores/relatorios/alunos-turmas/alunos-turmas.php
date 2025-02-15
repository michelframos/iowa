<?php
    include_once('../../../config.php');
    include_once('../../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Relatório - Alunos / Turmas', 'c', 'index');

?>
<script src="js/jQuery.print.min.js"></script>
<script src="js/rel-alunos-turmas.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">description</i>
    <h1>Alunos / Turmas</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Listar Por</label>
            <select name="pesquisar_por" id="pesquisar_por" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="turma">Turma</option>
                <option value="professor_turma">Professor e Turma</option>
            </select>
        </div>
        <div class="clear"></div>

        <div id="box-turmas">
            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Turma</label>
                <select name="turma" id="turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="">Todas</option>
                    <?php
                    $turmas = Turmas::all(['conditions' => ['status = ? and coalesce(id_unidade, "") <> ?', 'a', ''], 'order' => 'nome asc']);
                    if(!empty($turmas)):
                        foreach($turmas as $turma):
                            if($turma->nome != 'Nova Turma'):
                            echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
                            endif;
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <div class="clear"></div>
        </div>

        <div id="box-professores-turmas">
            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Professores</label>
                <select name="professor" id="professor" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="">Todos</option>
                    <?php
                    $professores = Colegas::all(array('conditions' => array('status = ? and id_funcao = ?', 'a', 3), 'order' => 'apelido asc'));
                    if(!empty($professores)):
                        foreach($professores as $professor):
                            echo '<option value="'.$professor->id.'">'.$professor->apelido.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Turmas</label>
                <select name="turma_professor" id="turma_professor" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="clear"></div>
        </div>

        <button type="button" name="gerar-relatorio" id="gerar-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Gerar Relatório</button>

        <?php if(Permissoes::find_by_id_usuario_and_tela_and_imp(idUsuario(), 'Relatório - Alunos / Turmas', 's')): ?>
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
