<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Alunos::find(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));
$observacoes = Alunos_Observacoes::all(array('conditions' => array('id_aluno = ?', $registro->id), 'order' => 'data_criacao asc'));
?>

<button type="button" name="nova-observacao" id="nova-observacao" value="Nova Observação" class="btn btn-info pmd-btn-raised">Nova Observação</button>
<div class="espaco20"></div>

<?php
    if(!empty($observacoes)):
?>
<!-- Basic Table -->
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Data</th>
            <th>Observação</th>
            <th>Criado Por</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($observacoes as $observacao):
            echo '<tr>';
            echo '<td data-title="Data">'.$observacao->data_criacao->format('d/m/Y H:m:i').'</td>';
            echo '<td data-title="Observacao">'.substr($observacao->observacao, 0, 100).'...</td>';
            echo '<td data-title="Criado Por">Nome do Usuário</td>';
            echo '<td data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-visualiza-observacao" registro="'.$observacao->id.'"><i class="material-icons pmd-sm">pageview</i> </a></td>';
            echo '</tr>';
        endforeach;
        ?>
        </tbody>
    </table>
</div>

<?php
else:
    echo '<h2 class="h2">Este aluno não possui observações.</h2>';
endif;
?>
