<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Alunos::find($id);
?>

<form action="" name="formParcelas" id="formParcelas" method="post">

    <?php
    if(!empty($_POST['id_turma'])):
        $id_turma = trim(filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT));
    endif;
    empty($id_turma) ? $id_turma = '%' : '';

    if(!empty($_POST['id_idioma'])):
        $id_idioma = trim(filter_input(INPUT_POST, 'id_idioma', FILTER_SANITIZE_NUMBER_INT));
    endif;
    empty($id_idioma) ? $id_idioma = '%' : '';

    /*
    if(!empty($_POST['id_empresa'])):
        $id_empresa = filter_input(INPUT_POST, 'id_empresa', FILTER_DEFAULT);
    endif;
    */

    $status_parcela = filter_input(INPUT_POST, 'status_parcela', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    //$parcelas = Parcelas::all(array('conditions' => array('id_aluno = ? and pagante = ? and id_turma like ? and id_idioma like ? and id_empresa like ?', $registro->id, 'aluno', $id_turma, $id_idioma, $id_empresa), 'order' => 'data_vencimento asc'));

    if(empty($status_parcela)):
        $parcelas = Parcelas::all(array('conditions' => array('id_aluno = ? and pagante = ? and id_turma like ? and id_idioma like ?', $registro->id, 'aluno', $id_turma, $id_idioma), 'order' => 'data_vencimento asc'));
    else:
        $parcelas = Parcelas::all(array('conditions' => array('id_aluno = ? and pagante = ? and id_turma like ? and id_idioma like ? and '.$status_parcela, $registro->id, 'aluno', $id_turma, $id_idioma), 'order' => 'data_vencimento asc'));
    endif;
    if(!empty($parcelas)):
        ?>
        <!-- Basic Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                            <input type="checkbox" value="" id="selecionar-todos">
                            <span></span>
                        </label>
                    </th>
                    <th>Data Vencimento</th>
                    <th>Data Pagamento</th>
                    <th>Idioma</th>
                    <th>Vr Original</th>
                    <th>Referente</th>
                    <th>Vr Pago</th>
                    <th>Pausada</th>
                    <th>Observações</th>
                    <th></th>
                    <th>Descancelar</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($parcelas as $parcela):

                    try{
                        $turma = Turmas::find($parcela->id_turma);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $turma = '';
                    }


                    try{
                        $idioma = Idiomas::find($parcela->id_idioma);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $idioma = '';
                    }

                    try{
                        $motivo = Motivos_Parcela::find($parcela->id_motivo);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $motivo = '';
                    }

                    echo $parcela->pausada == 's' ? '<tr class="parcela-pausada">' : '<tr>';

                    if($parcela->pago == 'n' && $parcela->cancelada == 'n'):
                        echo '<td>';
                        echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                        echo '<input type="checkbox" value="'.$parcela->id.'" class="parcela">';
                        echo '<span></span>';
                        echo '</label>';
                        echo '</td>';
                    else:
                        echo '<td></td>';
                    endif;

                    echo '<td data-title="Data">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                    echo !empty($parcela->data_pagamento) ? '<td data-title="Data">'.$parcela->data_pagamento->format('d/m/Y').'</td>' : '<td></td>';
                    echo '<td data-title="Idioma">'.$idioma->idioma.'</td>';
                    echo '<td data-title="Valor">R$ '.number_format($parcela->total, 2, ',', '.').'</td>';
                    echo !empty($motivo) ? '<td data-title="Motivo">'.$motivo->motivo.'</td>' : '<td data-title="Motivo">Parcela</td>';
                    echo !empty($parcela->valor_pago) ? '<td data-title="Valor">R$ '.number_format($parcela->total, 2, ',', '.').'</td>' : '<td></td>';
                    echo $parcela->pausada == 's' ? '<td>SIM</td>' : '<td>NÃO</td>';
                    echo '<td data-title="Observações">'.$parcela->observacoes.'</td>';

                    if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')):
                        echo ($parcela->pago == 'n') && ($parcela->cancelada == 'n') && ($parcela->renegociada == 'n') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-alterar-parcela" parcela="'.$parcela->id.'" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar Parcela"><i class="material-icons pmd-sm">mode_edit</i> </a></td>' : '<td></td>';
                    else:
                        echo '<td></td>';
                    endif;

                    //echo ($parcela->pago == 'n' && $parcela->cancelada == 'n') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-cancelar-parcela" data-target="#cancelar-parcela-dialog" data-toggle="modal" parcela="'.$parcela->id.'" registro="'.$registro->id.'" title="Cencelar Parcela"><i class="material-icons pmd-sm">highlight_off</i> </a></td>' : '<td></td>';
                    //echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-excluir-parcela" data-target="#exclui-parcela-dialog" data-toggle="modal" registro="'.$parcela->id.'"><i class="material-icons pmd-sm">delete_forever</i> </a></td>';

                    if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')):
                        //echo ($parcela->pago == 's' || $parcela->cancelada == 's')  ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-remover-pagamento-parcela" registro="'.$parcela->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Remover Pagamento"><i class="material-icons pmd-sm">undo</i> </a></td>' : '<td></td>';
                        echo ($parcela->cancelada == 's')  ? '<td width="20" data-title="" class="texto-center"><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-descancelar-parcela" registro="'.$parcela->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Descancelar"><i class="material-icons pmd-sm">undo</i> </a></td>' : '<td></td>';
                        //echo '<td></td>';
                    else:
                        echo '<td></td>';
                    endif;
                    echo '</tr>';
                endforeach;
                ?>
                </tbody>
            </table>
        </div>

        <?php
    else:
        echo '<h2 class="h2">Este aluno não possue parcelas.</h2>';
    endif;
    ?>

</form>
