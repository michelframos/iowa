<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $empresa = Empresas::find($id);
    $matriculas = Matriculas::find_all_by_id_empresa_financeiro($empresa->id);


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
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar" registro="<?php echo $empresa->id ?>">Voltar</a>
        <div class="espaco20"></div>

        <form action="" name="formDados" id="formDados" method="post" style="max-width: 800px;">

            <!--
            <div class="coluna-3 float-left margin-right-5">
                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                    <label>Turma</label>
                    <select name="id_turma" id="id_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                        <option></option>
                        <?php
                        if(!empty($matriculas)):
                            foreach($matriculas as $matricula):
                                try{
                                    $turma = Turmas::find($matricula->id_turma);
                                } catch (Exception $e){
                                    $turma = '';
                                }

                                echo '<option matricula="'.$matricula->id.'" value="'.$turma->id.'">'.$turma->nome.'</option>';
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="clear"></div>

            <div class="coluna-3 float-left margin-right-5">
                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                    <label>Motivo</label>
                    <select name="id_motivo" id="id_motivo" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                        <option></option>
                        <?php
                        $motivos = Motivos_Parcela::all(array('conditions' => array('id <> ?', 4), 'order' => 'motivo asc'));
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
            -->

            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
                <label for="regular1" class="control-label">Número de Parcelas</label>
                <input type="text" name="numero_parcelas" id="numero_parcelas" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
                <label for="regular1" class="control-label">Valor da Parcela</label>
                <input type="text" name="valor_parcela" id="valor_parcela" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
                <label for="regular1" class="control-label">Data do Primeiro Vencimento</label>
                <input type="text" name="data_vencimento" id="data_vencimento" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div id="ajustes-financeiros">
                <h4 class="h2">Ajustes Financeiros</h4>
                <div class="espaco20"></div>

                <div id="box-empresa-financeiro">
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Empresa</label>
                        <input type="text" name="empresa" id="empresa" value="" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Porcentagem da Empresa</label>
                        <input type="text" name="porcentagem_empresa" id="porcentagem_empresa" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                </div>
            </div>


            <button type="submit" name="salvar-parcela" id="salvar-parcela" value="Salvar" registro="<?php echo $empresa->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

        </form>

    </section>

<script>
    $(function(){

        $('#ajustes-financeiros').hide();

        $("#data_vencimento").datetimepicker({
            format: "DD/MM/YYYY"
        });

        /*
        $('#id_turma').change(function(){

            var dados = 'id='+$('#salvar-parcela').attr('registro')+'&id_matricula='+$(this).find(':selected').attr('matricula')+'&acao=verifica-responsavel-financeiro';
            $.post('alunos/acoes.php', { dados: dados }, function(data){

                if(data.responsavel == 2)
                {
                    $('#ajustes-financeiros').show();
                    $('#empresa').val(data.empresa);
                }
                else
                {
                    $('#ajustes-financeiros').hide();
                    $('#empresa').val('');
                }

            }, 'json');

        });
        */

    });
</script>
