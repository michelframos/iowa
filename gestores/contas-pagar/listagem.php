<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th>
                    <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                        <input type="checkbox" value="" id="selecionar-todos">
                        <span></span>
                    </label>
                </th>
                <th width="150">Data Vencto.</th>
                <th>Fornecedor</th>
                <!--<th>Descrição</th>-->
                <th>Categoria</th>
                <th>Natureza</th>
                <th>Unidade</th>
                <th>Valor</th>
                <th>Pago</th>
                <th>Cancelada</th>
                <th colspan="2">Ações</th>
            </tr>
            </thead>
            <tbody>

            <?php
            parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

            if(!empty($dados['data_inicial'])):
                $data_inicial = implode('-', array_reverse(explode('/', $dados['data_inicial'])));
            else:
                $data_inicial = '';
            endif;

            if(!empty($dados['data_final'])):
                $data_final = implode('-', array_reverse(explode('/', $dados['data_final'])));
            else:
                $data_final = '';
            endif;

            if(!empty($data_inicial) and empty($data_final)):
                $data_final = $data_inicial;
            endif;

            if(!empty($dados['id_natureza'])):
                $id_natureza = $dados['id_natureza'];
            else:
                $id_natureza = '%';
            endif;

            if(!empty($dados['id_fornecedor'])):
                $id_fornecedor = $dados['id_fornecedor'];
            else:
                $id_fornecedor = '%';
            endif;

            if(!empty($dados['id_unidade'])):
                $id_unidade = $dados['id_unidade'];
            else:
                $id_unidade = '%';
            endif;

            if(!empty($dados['id_categoria'])):
                $id_categoria = $dados['id_categoria'];
            else:
                $id_categoria = '%';
            endif;

            if(!empty($_POST)):
                if(!empty($data_inicial) and (!empty($data_final))):
                    $registros = Contas_Pagar::all(array('conditions' => array('(data_vencimento between ? and ?) and id_categoria like ? and id_natureza like ? and id_fornecedor like ? and id_unidade like ? and pago = ?', $data_inicial, $data_final, $id_categoria, $id_natureza, $id_fornecedor, $id_unidade, 'n'),'order' => 'data_vencimento asc'));
                else:
                    $registros = Contas_Pagar::all(array('conditions' => array('id_categoria like ? and id_natureza like ? and id_fornecedor like ? and id_unidade like ? and pago = ?', $id_categoria, $id_natureza, $id_fornecedor, $id_unidade, 'n'),'order' => 'data_vencimento asc'));
                endif;
            else:
                $registro = '';
            endif;

            if(!empty($registros)):
                foreach($registros as $registro):
                    try{
                        $categoria = Categorias_Lancamentos::find($registro->id_categoria);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $categoria = '';
                    }

                    try{
                        $natureza = Natureza_Conta::find($registro->id_natureza);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $natureza = '';
                    }

                    try{
                        $dados_unidade = Unidades::find($registro->id_unidade);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $dados_unidade = '';
                    }

                    try{
                        $fornecedor = Fornecedores::find($registro->id_fornecedor);
                    }catch (\ActiveRecord\RecordNotFound $e){
                        $fornecedor = '';
                    }

                    /*
                    try{
                        $compartilhadas = Unidades_Contas_Pagar::find_all_by_id_conta_pagar($registro->id);
                    } catch (\ActiveRecord\RecordNotFound $e){
                        $compartilhadas = '';
                    }
                    */

                    echo '<tr>';

                    if($registro->pago == 'n' && $registro->cancelada == 'n'):
                        echo '<td>';
                        echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                        echo '<input type="checkbox" value="'.$registro->id.'" class="parcela">';
                        echo '<span></span>';
                        echo '</label>';
                        echo '</td>';
                    else:
                        echo '<td></td>';
                    endif;

                    echo !empty($registro->data_vencimento) ? '<td data-title="Data Vencto">'.$registro->data_vencimento->format("d/m/Y").'</td>' : '<td></td>';
                    echo '<td data-title="Descrição">'.$fornecedor->fornecedor.'</td>';
                    //echo '<td data-title="Descrição">'.$registro->descricao.'</td>';
                    echo '<td data-title="Categoria">'.$categoria->categoria.'</td>';
                    echo '<td data-title="Natureza">'.$natureza->natureza.'</td>';
                    echo '<td data-title="Unidade">'.$dados_unidade->nome_fantasia.'</td>';
                    echo '<td data-title="Valor">'.number_format($registro->valor, 2, ',','.').'</td>';

                    /*
                    echo '<td data-title="Unidade(s)">';
                        if(!empty($compartilhadas)):
                            foreach($compartilhadas as $compartilhada):
                                $dados_unidade = Unidades::find($compartilhada->id_unidade);
                                echo $dados_unidade->nome_fantasia.'<br>';
                            endforeach;
                        endif;
                    echo '</td>';
                    */

                    /*
                    echo '<td data-title="Porcentagem">';
                        if(!empty($compartilhadas)):
                            foreach($compartilhadas as $compartilhada):
                                echo $compartilhada->porcentagem.'%<br>';
                            endforeach;
                        endif;
                    echo '</td>';
                    */

                    /*
                    echo '<td data-title="Valor">';
                        if(!empty($compartilhadas)):
                            foreach($compartilhadas as $compartilhada):
                                echo number_format($compartilhada->valor, 2, ',','.').'<br>';
                            endforeach;
                        endif;
                    echo '</td>';
                    */

                    echo $registro->pago == 's' ? '<td data-title="Pago">SIM</td>' : '<td data-title="Pago">NÃO</td>';
                    echo $registro->cancelada == 's' ? '<td data-title="Pago">SIM</td>' : '<td data-title="Pago">NÃO</td>';
                    echo $registro->pago == 'n' ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-alterar" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar Conta a Pagar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>' : '<td></td>';
                    echo ($registro->pago == 'n' && $registro->cancelada == 'n') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-quitar" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Quitar Conta a Pagar"><i class="material-icons pmd-sm">done</i> </a></td>' : '<td></td>';
                    echo ($registro->pago == 'n' && $registro->cancelada == 'n') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-cancelar" data-target="#cancelar-dialog" data-toggle="modal" registro="'.$registro->id.'" title="Cencelar Conta a Pagar"><i class="material-icons pmd-sm">highlight_off</i> </a></td>' : '<td></td>';
                    //echo ($registro->pago == 'n' && $registro->cancelada == 'n') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-excluir" data-target="#delete-dialog" data-toggle="modal" registro="'.$registro->id.'"><i class="material-icons pmd-sm">delete_forever</i> </a></td>' : '<td></td>';
                    echo ($registro->cancelada == 's')  ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-remover-cancelamento" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Remover Cancelamento"><i class="material-icons pmd-sm">undo</i> </a></td>' : '<td></td>';

                    echo '</tr>';
                endforeach;
            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
