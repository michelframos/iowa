<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Alunos::find($id);
$turmas = Turmas::find_by_sql("select alunos.id, alunos.nome, alunos_turmas.id_turma, alunos_turmas.id_aluno, turmas.nome as nome_turma, turmas.`status`, turmas.id_idioma from alunos inner join alunos_turmas on alunos.id = alunos_turmas.id_aluno inner join turmas on alunos_turmas.id_turma = turmas.id where COALESCE(turmas.status, '') like '%' and alunos.id = '{$registro->id}'");
?>

<script src="js/listagem-atas.js"></script>

<div class="pmd-card">

    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data</th>
                <th>Turma</th>
                <th>Instrutor</th>
                <th>Ata</th>
                <th colspan="2"></th>
            </tr>
            </thead>
            <tbody>

            <?php
            //$registros = Turmas::all(array('conditions' => array('id_colega like ? and status like ?', $id_colega, $status_turma),'order' => 'nome asc'));
            foreach($turmas as $turma):

                $atas = Atas_Coach::all(array('conditions' => array('id_turma = ? and id_aluno = ?', $turma->id_turma, $registro->id), 'order' => 'data desc'));

                if(!empty($atas)):
                    foreach($atas as $ata):

                        $instrutor = Colegas::find($ata->id_colega);

                        echo '<tr>';
                        echo '<td data-title="Data Cadastro">'.$ata->data->format("d/m/Y").'</td>';
                        echo '<td data-title="Nome da Prova">'.$turma->nome_turma.'</td>';
                        echo '<td data-title="Nome da Prova">'.$instrutor->nome.'</td>';
                        echo !empty($ata->ata) ? '<td width="30%" data-title="Nome da Prova">'.substr($ata->ata, 0, 150).'...</td>' : '<td></td>';

                        echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera-ata" turma="'.$turma->id_turma.'" ata="'.$ata->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>';
                        echo '<tr>';

                    endforeach;
                endif;

            endforeach;
            ?>

            </tbody>
        </table>

        <div class="" id="ms-alterar-ata-dialog" data-target="#alterar-ata-dialog" data-toggle="modal"></div>

    </div>

</div>

<script>

    $(function(){



    });

</script>