<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data de Início</th>
                <th>Promoção</th>
                <th>Data Término</th>
                <th>Mensagem</th>
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

                $registros = Promocoes::all(array('conditions' => array('nome like ?', '%'.$valor_pesquisa.'%'),'order' => 'data_inicio asc'));
                if(!empty($registros)):
                    foreach($registros as $registro):
                        echo '<tr>';
                        echo !empty($registro->data_inicio) ? '<td data-title="Data Cadastro">'.$registro->data_inicio->format("d/m/Y").'</td>' : '<td data-title="Data Cadastro"></td>';
                        echo '<td data-title="Nome">'.$registro->nome.'</td>';
                        echo !empty($registro->data_termino) ? '<td data-title="Data Término">'.$registro->data_termino->format("d/m/Y").'</td>' : '<td data-title="Data Cadastro"></td>';
                        echo '<td data-title="Mensagem">'.substr($registro->mensagem, 0, 200).'</td>';

                        echo '<td data-title="Status">';
                        echo '<div class="pmd-switch">';
                        echo '<label>';
                        echo $registro->status == 'a' ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                        echo '<span class="pmd-switch-label ativa-inativa" registro="'.$registro->id.'"></span>';
                        echo ' </label>';
                        echo '</div>';
                        echo '</td>';

                        if(!empty($registro->data_termino)):
                            $data_termino = $registro->data_termino->format('d/m/Y');
                        else:
                            $data_termino = '';
                        endif;

                        if($registro->tempo_indeterminado == 's' || date('d/m/Y') <= $data_termino):
                            echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-enviar-link" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Enviar link"><i class="material-icons pmd-sm">send</i> </a></td>';
                        else:
                            echo '<td width="20"></td>';
                        endif;
                        echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-envios-participacoes" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Envios e Participações"><i class="material-icons pmd-sm">account_circle</i> </a></td>';
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
