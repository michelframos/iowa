<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

function intervalo( $entrada, $saida ) {
    $entrada = explode( ':', $entrada );
    $saida   = explode( ':', $saida );
    $minutos = ( $saida[0] - $entrada[0] ) * 60 + $saida[1] - $entrada[1];
    if( $minutos < 0 ) $minutos += 24 * 60;
    return sprintf( '%d:%d', $minutos / 60, $minutos % 60 );
}

function converterHora($total_segundos){

    $hora = sprintf("%02s",floor($total_segundos / (60*60)));
    $total_segundos = ($total_segundos % (60*60));

    $minuto = sprintf("%02s",floor ($total_segundos / 60 ));
    $total_segundos = ($total_segundos % 60);

    $hora_minuto = $hora.":".$minuto;
    return $hora_minuto;
}

if($dados['acao'] == 'busca-turmas'):

    $turmas = Turmas::all(array('conditions' => array('id_colega = ?', $dados['professor']), 'order' => 'nome asc'));

    if(!empty($turmas)):
        echo '<option value="">Todas</option>';
        foreach($turmas as $turma):
            echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
        endforeach;
    endif;

endif;



if($dados['acao'] == 'gerar-relatorio'):

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

    $pago = $dados['pago'];

    if(!empty($_POST)):
        if(!empty($data_inicial) and (!empty($data_final))):
            $registros = Contas_Pagar::all(array('conditions' => array('(data_vencimento between ? and ?) and id_categoria like ? and id_natureza like ? and id_fornecedor like ? and id_unidade like ? and pago like ?', $data_inicial, $data_final, $id_categoria, $id_natureza, $id_fornecedor, $id_unidade, $pago),'order' => 'data_vencimento asc'));
        else:
            $registros = Contas_Pagar::all(array('conditions' => array('id_categoria like ? and id_natureza like ? and id_fornecedor like ? and id_unidade like ? and pago like ?', $id_categoria, $id_natureza, $id_fornecedor, $id_unidade, $pago),'order' => 'data_vencimento asc'));
        endif;
    else:
        $registro = '';
    endif;

    if(!empty($registros)):
    ?>

        <div class="table-responsive">
            <table class="table pmd-table table-hover">
                <thead>
                <tr>
                    <th width="150">Data Vencto.</th>
                    <th>Fornecedor</th>
                    <!--<th>Descrição</th>-->
                    <th>Categoria</th>
                    <th>Natureza</th>
                    <th>Unidade</th>
                    <th>Valor</th>
                    <th>Pago</th>
                    <th>Cancelada</th>
                </tr>
                </thead>
                <tbody>

                <?php
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

                    echo '<tr>';

                    echo !empty($registro->data_vencimento) ? '<td data-title="Data Vencto">'.$registro->data_vencimento->format("d/m/Y").'</td>' : '<td></td>';
                    echo '<td data-title="Descrição">'.$fornecedor->fornecedor.'</td>';
                    //echo '<td data-title="Descrição">'.$registro->descricao.'</td>';
                    echo '<td data-title="Categoria">'.$categoria->categoria.'</td>';
                    echo '<td data-title="Natureza">'.$natureza->natureza.'</td>';
                    echo '<td data-title="Unidade">'.$dados_unidade->nome_fantasia.'</td>';
                    echo '<td data-title="Valor">'.number_format($registro->valor, 2, ',','.').'</td>';

                    echo $registro->pago == 's' ? '<td data-title="Pago">SIM</td>' : '<td data-title="Pago">NÃO</td>';
                    echo $registro->cancelada == 's' ? '<td data-title="Pago">SIM</td>' : '<td data-title="Pago">NÃO</td>';

                    echo '</tr>';

                    $total_contas += $registro->valor;

                endforeach;

                echo '<tr>'.
                    '<td colspan="5">TOTAL</td>'.
                    '<td colspan="3">'.number_format($total_contas, 2, ',', '.').'</td>'.
                     '</tr>';

                ?>

                </tbody>
            </table>
        </div>

    <?php
    else:

        echo '<div>NENHUMA CONTA A PAGAR FOI ENCONTRADA COM OS FILTROS SELECIONADOS</div>';

    endif;

endif;
