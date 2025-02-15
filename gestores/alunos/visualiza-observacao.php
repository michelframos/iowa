<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$observacao = Alunos_Observacoes::find($id);
?>

<button name="voltar-observacoes" id="voltar-observacoes" value="Voltar" class="btn btn-info pmd-btn-raised">Voltar</button>
<div class="espaco20"></div>

<div class="form-group pmd-textfield">
    <label class="control-label">Observação</label>
    <textarea required class="form-control" name="observacao" id="observacao" style="height: 300px;" readonly><?php echo $observacao->observacao ?></textarea>
</div>
