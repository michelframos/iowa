<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Turmas::find($id);
?>

<script src="js/listagem-atas.js"></script>

<h1>ATAS</h1>
<div class="espaco20"></div>
<?php if(Permissoes::find_by_id_usuario_and_tela_and_i(idUsuario(), 'Coachs - Criar Ata Para Turma', 's')): ?>
<button type="button" name="bt-nova-ata-turma" id="bt-nova-ata-turma" data-target="#nova-ata-turma-dialog" data-toggle="modal" value="Nova Ata" class="btn btn-danger pmd-btn-raised">Nova Ata</button>
<?php endif; ?>
<div class="espaco20"></div>

<?php if(Permissoes::find_by_id_usuario_and_tela_and_c(idUsuario(), 'Coachs - Consultar Atas da Turma', 's')): ?>
<div class="table-responsive">
    <table class="table pmd-table table-hover">
        <thead>
        <tr>
            <th width="150">Data</th>
            <th>Ata</th>
            <th colspan="2"></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $atas = Atas_Coach::all(array('conditions' => array('id_turma = ? and id_aluno is null', $registro->id), 'order' => 'data desc'));

        if(!empty($atas)):
            foreach($atas as $ata):

                $instrutor = Colegas::find($ata->id_colega);

                echo '<tr>';
                echo '<td data-title="Data">'.$ata->data->format("d/m/Y").'</td>';
                echo '<td data-title="Instrutor">'.$instrutor->nome.'</td>';
                echo '<td data-title="Ata">'.$ata->ata.'</td>';

                echo Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Coachs - Altera Ata da Turma', 's') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera-ata-turma" turma="'.$registro->id.'" ata="'.$ata->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>' : '<td></td>';
                echo '<tr>';

            endforeach;
        endif;
        ?>

        </tbody>
    </table>
    <?php endif; ?>

    <div class="oculto" id="ms-alterar-ata-turma-dialog" data-target="#alterar-ata-turma-dialog" data-toggle="modal"></div>

</div>

