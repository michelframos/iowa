<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$registro = Matriculas::find(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));
$permissao = Permissoes::find_by_id_usuario_and_tela(idUsuario(), 'Matriculas');

?>

<script>
    $(function(){
        $('#content-matriculas').find('#valor').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
        $('#content-matriculas').find('#valor_parcela').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    });
</script>

<button name="voltar-matricula" id="voltar-matricula" value="Voltar" class="btn btn-info pmd-btn-raised">Voltar</button>
<div class="espaco20"></div>

<h4 class="h2">Matrícula</h4>
<div class="espaco20"></div>

<form action="" name="formAlteraMatricula" id="formAlteraMatricula" method="post">

    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3 <?php echo $permissao->ai == 'n' ? 'oculto' : ''; ?>">
        <label>Situação da Matrícula</label>
        <select name="status_matricula" id="status_matricula" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" readonly="">
            <option <?php echo $registro->status == 'a' ? 'selected' : '' ?> value="a">Ativa</option>
            <option <?php echo $registro->status == 'i' ? 'selected' : '' ?> value="i">Inativa</option>
            <option <?php echo $registro->status == 's' ? 'selected' : '' ?> value="s">Stand By</option>
            <option <?php echo $registro->status == 't' ? 'selected' : '' ?> value="t">Transferido</option>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <?php if(!empty($registro->id_situacao_aluno_turma)): ?>

    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3 <?php echo $permissao->ai == 'n' ? 'oculto' : ''; ?>">
        <label>Situação do Aluno a Turma</label>
        <select name="id_situacao_aluno_turma" id="id_situacao_aluno_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" readonly="">
            <option></option>
            <?php
            $situacao_aluno_turma = Situacao_Aluno_Turma::all(array('conditions' => array('status = ?', 'a'), 'order' => 'situacao asc'));
            if(!empty($situacao_aluno_turma)):
                foreach($situacao_aluno_turma as $situacao):
                    if($situacao->id == $registro->id_situacao_aluno_turma):
                        $select = 'selected';
                    else:
                        $select = '';
                    endif;
                    echo '<option '.$select.' value="'.$situacao->id.'">'.$situacao->situacao.'</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3 <?php echo $permissao->ai == 'n' ? 'oculto' : ''; ?>">
        <label>Motivo da Desistência</label>
        <select name="id_motivo_desistencia" id="id_motivo_desistencia" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" readonly="">
            <option></option>
            <?php
            $motivos = Motivos_Desistencia::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_motivo_desistencia), 'order' => 'motivo asc'));
            if(!empty($motivos)):
                foreach($motivos as $motivo):
                    if($motivo->id == $registro->id_motivo_desistencia):
                        $select = 'selected';
                    else:
                        $select = '';
                    endif;
                    echo '<option '.$select.' value="'.$motivo->id.'">'.$motivo->motivo.'</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <div class="clear"></div>
    <?php endif; ?>

    <div class="coluna-3 float-left margin-right-5">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Turma</label>
            <select name="id_turma" id="id_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <?php
                $turma = Turmas::find($registro->id_turma);
                if(!empty($turma)):
                    echo '<option selected value="'.$turma->id.'">'.$turma->nome.'</option>';
                endif;
                ?>
            </select>
            <!--
            <select name="id_turma" id="id_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" disabled>
                <option></option>
                <?php
                $turmas = Turmas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                if(!empty($turmas)):
                    foreach($turmas as $turma):
                        if($turma->id == $registro->id_turma):
                            $select = 'selected';
                        else:
                            $select = '';
                        endif;
                        echo '<option '.$select.' value="'.$turma->id.'">'.$turma->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            -->
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
    </div>
    <div class="clear"></div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
        <label for="regular1" class="control-label">Número de Parcelas</label>
        <input type="text" name="numero_parcelas" id="numero_parcelas" value="<?php echo $registro->numero_parcelas ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
        <label for="regular1" class="control-label">Valor da Parcela</label>
        <input type="text" name="valor_parcela" id="valor_parcela" value="<?php echo number_format($registro->valor_parcela, 2, ',', '.') ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
        <label for="regular1" class="control-label">Primeira Data de Vencimento</label>
        <input type="text" name="data_vencimento" id="data_vencimento" value="<?php echo !empty($registro->data_vencimento) ? $registro->data_vencimento->format('d/m/Y') : ''; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
    </div>
    <div class="clear"></div>

    <h4 class="h2">Ajustes Financeiros</h4>
    <div class="espaco20"></div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
        <label>Responsável Financeiro</label>
        <select name="responsavel_financeiro" id="responsavel_financeiro" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
            <option></option>
            <?php
            $responsavel_financeiro = Responsavel_Financeiro::all();
            if(!empty($responsavel_financeiro)):
                foreach($responsavel_financeiro as $responsavel):
                    if($responsavel->id == $registro->responsavel_financeiro):
                        $select = 'selected';
                    else:
                        $select = '';
                    endif;
                    echo '<option '.$select.' value="'.$responsavel->id.'">'.$responsavel->responsavel.'</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <div id="box-empresa-financeiro">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Empresa</label>
            <select name="id_empresa_financeiro" id="id_empresa_financeiro" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option></option>
                <?php
                $empresas = Empresas::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_empresa_financeiro), 'order' => 'nome_fantasia asc'));
                if(!empty($empresas)):
                    foreach($empresas as $empresa):
                        if($empresa->id == $registro->id_empresa_financeiro):
                            $select = 'selected';
                        else:
                            $select = '';
                        endif;
                        echo '<option '.$select.' value="'.$empresa->id.'">'.$empresa->nome_fantasia.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Porcentagem da Empresa</label>
            <input type="text" name="porcentagem_empresa" id="porcentagem_empresa" value="<?php echo $registro->porcentagem_empresa; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
    </div>


    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
        <label>Responsável Pedagógico</label>
        <select name="responsavel_pedagogico" id="responsavel_pedagogico" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
            <option></option>
            <?php
            $responsavel_pedagogico = Responsavel_Pedagogico::all();
            if(!empty($responsavel_pedagogico)):
                foreach($responsavel_pedagogico as $responsavel):
                    if($responsavel->id == $registro->responsavel_pedagogico):
                        $select = 'selected';
                    else:
                        $select = '';
                    endif;
                    echo '<option '.$select.' value="'.$responsavel->id.'">'.$responsavel->responsavel.'</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <div id="box-empresa-pedagogico">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Empresa</label>
            <select name="id_empresa_pedagogico" id="id_empresa_pedagogico" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option></option>
                <?php
                $empresas = Empresas::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_empresa_financeiro), 'order' => 'nome_fantasia asc'));
                if(!empty($empresas)):
                    foreach($empresas as $empresa):
                        if($empresa->id == $registro->id_empresa_pedagogico):
                            $select = 'selected';
                        else:
                            $select = '';
                        endif;
                        echo '<option '.$select.' value="'.$empresa->id.'">'.$empresa->nome_fantasia.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">E-mail do Gestor Pedagógico</label>
                <input type="text" name="email_gestor_pedagogico" id="email_gestor_pedagogico" value="<?php echo $registro->email_gestor_pedagogico; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
        </div>
    </div>

    </div>

    <button type="submit" name="alterar-matricula" id="alterar-matricula" value="Alterar Matrícula" registro="<?php echo $registro->id ?>" class="btn btn-danger pmd-btn-raised">Alterar Matrícula</button>
    <div class="espaco20"></div>

</form>

<script>
    $(function(){

        $('#data_vencimento').mask('00/00/0000');
        $("#data_vencimento").datetimepicker({
            format: "DD/MM/YYYY"
        });

        $('#box-empresa-financeiro, #box-empresa-pedagogico').hide();
        $('#responsavel_financeiro').change(function(){

            var resposavel_financeiro = $('#responsavel_financeiro option:selected').val();
            if(resposavel_financeiro == 2)
            {
                $('#box-empresa-financeiro').show();
            }
            else
            {
                $('#box-empresa-financeiro').hide();
            }

        });

        $('#responsavel_pedagogico').change(function(){

            var resposavel_pedagogico = $('#responsavel_pedagogico option:selected').val();
            if(resposavel_pedagogico == 2)
            {
                $('#box-empresa-pedagogico').show();
            }
            else
            {
                $('#box-empresa-pedagogico').hide();
            }

        });

        $('#id_situacao_aluno_turma').change(function(){

            var status = $(this).val();

            if(status == '2'){
                $('#status_matricula option[value="i"]').prop('selected', true);
            }

            if (status == '1'){
                $('#status_matricula option[value="a"]').prop('selected', true);
            }

        });


        /*-----------------------------------------------------------------------------------------------------------*/
        <?php if($registro->responsavel_financeiro == 2): ?>
        $('#box-empresa-financeiro').show();
        <?php endif; ?>


        <?php if($registro->responsavel_pedagogico == 2): ?>
        $('#box-empresa-pedagogico').show();
        <?php endif; ?>

    })
</script>
