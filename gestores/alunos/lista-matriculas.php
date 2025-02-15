<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Alunos::find(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));
$matriculas = Matriculas::all(array('conditions' => array('id_aluno = ?', $registro->id), 'order' => 'data_criacao asc'));
?>

<?php if(Permissoes::find_by_id_usuario_and_tela_and_i(idUsuario(), 'Matriculas', 's')): ?>
<button type="button" name="nova-matricula" id="nova-matricula" value="Nova Matrícula" class="btn btn-info pmd-btn-raised">Nova Matrícula</button>
<?php endif; ?>

<?php
    if(!empty($matriculas)):
?>
<!-- Basic Table -->
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Data</th>
            <th>Turma</th>
            <th>Idioma</th>
            <th>Situação</th>
            <th colspan="3"></th>
        </tr>
        </thead>
        <tbody>
        <?php

        if(Permissoes::find_by_id_usuario_and_tela_and_c(idUsuario(), 'Matriculas', 's')):

        foreach($matriculas as $matricula):

            try{
                $turma = Turmas::find($matricula->id_turma);
                $idioma = Idiomas::find($turma->id_idioma);

                switch($matricula->status)
                {
                    case 'a': $status = 'Ativa'; break;
                    case 'i': $status = 'Inativa'; break;
                    case 's': $status = 'Stand By'; break;
                    case 't': $status = 'Transferido'; break;
                }

                echo '<tr>';
                echo '<td data-title="Data">'.$matricula->data_criacao->format('d/m/Y H:m:i').'</td>';
                echo '<td data-title="Observacao">'.$turma->nome.'</td>';
                echo '<td data-title="Observacao">'.$idioma->idioma.'</td>';
                echo '<td data-title="Situação">'.$status.'</td>';
                echo '<td data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera-matricula" registro="'.$matricula->id.'"><i class="material-icons pmd-sm">mode_edit</i> </a></td>';
                echo '<td data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-excluir-matricula" data-target="#exclui-matricula-dialog" data-toggle="modal" registro="'.$matricula->id.'"><i class="material-icons pmd-sm">delete_forever</i> </a></td>';
                echo '</tr>';
            } catch (Exception $e){

            }

        endforeach;

        endif;
        ?>
        </tbody>
    </table>
</div>

<?php
else:
    echo '<h2 class="h2">Este aluno não está matriculado em nenhum curso.</h2>';
endif;
?>

<script>
    $(function (){
        $('.bt-excluir-matricula').click(function(){

            $('#bt-modal-excluir-matricula').attr('registro', $(this).attr('registro'));

        });
    });
</script>
