<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
?>
<!-- Start Content -->
<script src="js/jQuery.print.min.js"></script>
<script src="js/rel-consolidado-faltas.js"></script>

<section class="padding-10">

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Empresa</label>
            <select name="empresa" id="empresa" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <?php
                //$turmas = Turmas::find_by_sql("select turmas.id as id_turma, turmas.id_unidade, turmas.nome, turmas.status, matriculas.* from matriculas inner join turmas on matriculas.id_turma = turmas.id where turmas.status = 'a' and turmas.nome <> 'Nova Turma' and responsavel_pedagogico = 2 and id_empresa_pedagogico = '".idEmpresa()."' GROUP BY turmas.id;");
                $empresas = Empresas::all(['conditions' => ['status = ?', 'a']]);

                if(!empty($empresas)):
                    foreach($empresas as $empresa):
                        echo '<option value="'.$empresa->id.'">'.$empresa->nome_fantasia.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Turma</label>
            <select name="turma" id="turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <?php
                //$turmas = Turmas::find_by_sql("select turmas.id as id_turma, turmas.id_unidade, turmas.nome, turmas.status, matriculas.* from matriculas inner join turmas on matriculas.id_turma = turmas.id where turmas.status = 'a' and turmas.nome <> 'Nova Turma' and responsavel_pedagogico = 2 and id_empresa_pedagogico = '".idEmpresa()."' GROUP BY turmas.id;");
                $turmas = Turmas::all(['conditions' => ['status = ?', 'a']]);

                if(!empty($turmas)):
                    foreach($turmas as $turma):
                        echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="clear"></div>
        <button type="button" name="gerar-relatorio" id="gerar-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Gerar Relatório</button>
        <button type="button" name="imprimir-relatorio" id="imprimir-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Imprimir Relatório</button>
        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

    <div id="relatorio"></div>

</section>

<script src="<?php echo HOME ?>/assets/js/bootstrap-datetimepicker.js"></script>

<script type="text/javascript">
    $("#data_inicial, #data_final").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>