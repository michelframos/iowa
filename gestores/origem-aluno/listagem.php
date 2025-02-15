<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data Cadastrto</th>
                <th>Origem</th>
                <th width="100">Status</th>
                <th colspan="2"></th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(isset($_POST['valor_pesquisa'])):
                if(!empty($_POST['valor_pesquisa'])):
                    $valor_pesquisa = filter_input(INPUT_POST, 'valor_pesquisa', FILTER_SANITIZE_STRING);
                else:
                    $valor_pesquisa = '';
                endif;

                $registros = Origem_Aluno::all(array('conditions' => array('origem like ?', '%'.$valor_pesquisa.'%'), 'order' => 'origem asc'));
                if(!empty($registros)):
                    foreach($registros as $registro):
                        if(!empty($registro->id_idioma)):
                            $idioma = Idiomas::find($registro->id_idioma);
                        endif;
                        echo '<tr>';
                        echo '<td data-title="Data Cadastro">'.$registro->data_criacao->format("d/m/Y").'</td>';
                        echo '<td data-title="Origem">'.$registro->origem.'</td>';

                        echo '<td data-title="Status">';
                        echo '<div class="pmd-switch">';
                        echo '<label>';
                        echo $registro->status == 'a' ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                        echo '<span class="pmd-switch-label ativa-inativa" registro="'.$registro->id.'"></span>';
                        echo ' </label>';
                        echo '</div>';
                        echo '</td>';

                        echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>';
                        echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-excluir" registro="'.$registro->id.'" data-target="#delete-dialog" data-toggle="modal" data-trigger="hover" data-placement="top" title="Excluir"><i class="material-icons pmd-sm">delete_forever</i> </a></td>';
                    endforeach;
                endif;

            else:

                echo '<div class="titulo fw-bold size-1-5">Selecione os filtros desejados e clique em Pesquisar</div>';

            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
