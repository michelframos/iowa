<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$registro = Alunos::find_by_id($id);
?>

<form action="" name="formParcelas" id="formParcelas" method="post">

    <?php
    if(!empty($_POST['id_turma'])):
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    endif;
    empty($id_turma) ? $id_turma = '%' : '';

    if(!empty($_POST['id_idioma'])):
        $id_idioma = filter_input(INPUT_POST, 'id_idioma', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    endif;
    empty($id_idioma) ? $id_idioma = '%' : '';

    if(!empty($_POST['id_empresa'])):
        $id_empresa = filter_input(INPUT_POST, 'id_empresa', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    endif;
    empty($id_empresa) ? $id_empresa = '%' : '';

    if(!empty($_POST['sacado'])):
        $sacado = filter_input(INPUT_POST, 'sacado', FILTER_DEFAULT);
    endif;

    if(!empty($_POST['data_inicial'])):
        $data_inicial = implode('-', array_reverse(explode('/', filter_input(INPUT_POST, 'data_inicial', FILTER_SANITIZE_STRING))));
    endif;

    if(!empty($_POST['data_final'])):
        $data_final = implode('-', array_reverse(explode('/', filter_input(INPUT_POST, 'data_final', FILTER_SANITIZE_STRING))));;
    endif;

    if(!empty($data_inicial) && empty($data_final)):
        $data_final = $data_inicial;
    endif;

    if(!empty($data_inicial)):
        $data_vencimento = " and data_vencimento between '{$data_inicial}' and '{$data_final}' ";
    else:
        $data_vencimento = "";
    endif;

    $nome_aluno = filter_input(INPUT_POST, 'nome_aluno', FILTER_SANITIZE_STRING);
    empty($nome_aluno) ? $nome_aluno = '%' : '';

    /*
    if(!empty($data_inicial)):
        $parcelas = V_Parcelas::all(array('conditions' => array('nome like ? and pagante like ? and id_turma like ? and id_idioma like ? and id_empresa like ? and data_vencimento between ? and ?', $nome_aluno.'%', $sacado, $id_turma, $id_idioma, $id_empresa, $data_inicial, $data_final), 'order' => 'id_idioma, data_vencimento asc'));
    else:
        $parcelas = V_Parcelas::all(array('conditions' => array('nome like ? and pagante like ? and id_turma like ? and id_idioma like ? and id_empresa like ?', $nome_aluno.'%', $sacado, $id_turma, $id_idioma, $id_empresa), 'order' => 'id_idioma, data_vencimento asc'));
    endif;
    */

    /*
    if($sacado == 'aluno'):
        $parcelas = V_Parcelas::all(array('conditions' => array('nome like ? and pagante like ? and id_turma like ? and id_idioma like ? and id_empresa like ? '.$data_vencimento, $nome_aluno.'%', $sacado, $id_turma, $id_idioma, $id_empresa), 'order' => 'id_idioma, data_vencimento asc'));
    elseif($sacado == 'empresa' || $sacado == '%'):
        //$parcelas = V_Parcelas::all(array('conditions' => array('(nome like ? or nome is null) and pagante like ? and (id_turma like ? or id_turma is null) and (id_idioma like ? or id_idioma is null) and id_empresa like ? '.$data_vencimento, $nome_aluno.'%', $sacado, $id_turma, $id_idioma, $id_empresa), 'order' => 'id_idioma, data_vencimento asc'));
    endif;
    */

    $parcelas = V_Parcelas2::all(array('conditions' => array('COALESCE (nome, "") like ? and pagante like ? and COALESCE (id_turma, "") like ? and COALESCE (id_idioma, "") like ? and id_empresa like ? and coalesce(cancelada, "n") = "n" and coalesce(renegociada, "n") = "n" '.$data_vencimento, $nome_aluno.'%', $sacado, $id_turma, $id_idioma, $id_empresa), 'order' => 'id_idioma, data_vencimento asc'));

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
                    <th>Recibo</th>
                    <th>Data Vencimento</th>
                    <th>Data Pagamento</th>
                    <th>Aluno/Empresa</th>
                    <th>Idioma</th>
                    <th>Vr Original</th>
                    <th>Referente</th>
                    <th>Vr Pago</th>
                    <th>Pago</th>
                    <th>Pausada</th>
                    <!-- <th>Observações</th> -->
                    <th colspan="5"></th>
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


                    /*se estiver pago, opção para marcar e gerar recibo*/
                    if($parcela->pago == 's'):
                        echo '<td>';
                        echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                        echo '<input type="checkbox" value="'.$parcela->id.'" class="parcela_recibo">';
                        echo '<span></span>';
                        echo '</label>';
                        echo '</td>';
                    else:
                        echo '<td></td>';
                    endif;


                    echo '<td data-title="Data">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                    echo !(empty($parcela->data_pagamento)) ? '<td data-title="Data">'.$parcela->data_pagamento->format('d/m/Y').'</td>' : '<td></td>';

                    if($parcela->pagante == 'aluno'):
                        echo '<td data-title="Idioma">'.$parcela->nome.'</td>';
                    elseif($parcela->pagante == 'empresa'):
                        echo '<td data-title="Idioma">'.$parcela->nome_empresa.'</td>';
                    endif;
                    echo '<td data-title="Idioma">'.$idioma->idioma.'</td>';
                    echo '<td data-title="Valor">R$ '.number_format($parcela->total, 2, ',', '.').'</td>';
                    echo !empty($motivo) ? '<td data-title="Motivo">'.$motivo->motivo.'</td>' : '<td data-title="Motivo">Parcela</td>';
                    echo !(empty($parcela->valor_pago)) ? '<td data-title="Valor">R$ '.number_format($parcela->valor_pago, 2, ',', '.').'</td>' : '<td></td>';
                    echo $parcela->pago == 's' ? '<td data-title="Pago">SIM</td>' : '<td data-title="Pago">NÂO</td>';
                    echo $parcela->pausada == 's' ? '<td>SIM</td>' : '<td>NÃO</td>';
                    //echo '<td data-title="Idioma">'.$parcela->observacoes.'</td>';

                    if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')):
                        echo ($parcela->pago == 'n') && ($parcela->cancelada == 'n') && ($parcela->renegociada == 'n') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-alterar-parcela" parcela="'.$parcela->id.'" registro="'.$parcela->id_aluno.'" aluno="'.$parcela->id_aluno.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar Parcela"><i class="material-icons pmd-sm">mode_edit</i> </a></td>' : '<td></td>';
                    else:
                        echo '<td></td>';
                    endif;

                    if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')):
                        echo ($parcela->pago == 'n' && $parcela->cancelada == 'n') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-cancelar-parcela" data-target="#cancelar-parcela-dialog" data-toggle="modal" aluno="'.$parcela->id_aluno.'" parcela="'.$parcela->id.'" registro="'.$parcela->id.'" title="Cencelar Parcela"><i class="material-icons pmd-sm">highlight_off</i> </a></td>' : '<td></td>';
                    else:
                        echo '<td></td>';
                    endif;

                    if(Permissoes::find_by_id_usuario_and_tela_and_e(idUsuario(), 'Contas a Receber', 's')):
                        echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-excluir-parcela" data-target="#exclui-parcela-dialog" data-toggle="modal" aluno="'.$parcela->id_aluno.'" registro="'.$parcela->id.'"><i class="material-icons pmd-sm">delete_forever</i> </a></td>';
                    else:
                        echo '<td></td>';
                    endif;

                    if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')):
                        //echo ($parcela->pago == 's' || $parcela->cancelada == 's')  ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-remover-pagamento-parcela" registro="'.$parcela->id.'" aluno="'.$parcela->id_aluno.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Remover Pagamento"><i class="material-icons pmd-sm">undo</i> </a></td>' : '<td></td>';
                        echo '<td></td>';
                    else:
                        echo '<td></td>';
                    endif;

                    echo ($parcela->pago == 'n' && $parcela->cancelada == 'n' && $parcela->renegociada == 'n') ? '<td class="texto-center"><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-renegociar" parcela="'.$parcela->id.'" data-trigger="hover" title="Renegociar"><i class="material-icons pmd-sm">assignment_late</i></a></td>' : '<td></td>';

                    echo '</tr>';
                endforeach;
                ?>
                </tbody>
            </table>
        </div>

        <?php
    else:
        echo '<h2 class="h2">Selecione os filtros desejados e clique em Pesquisar</h2>';
    endif;
    ?>

</form>
