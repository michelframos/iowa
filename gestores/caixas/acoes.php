<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
//$registro = Natureza_Conta::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Abrir Caixa', 'i');

    /*Verificando se existe caixa aberto*/
    $caixa_aberto = Caixas::find_by_id_colega_and_situacao(idUsuario(), 'aberto');

    /*
    if(!empty($caixa_aberto)):
        echo json_encode(array('status' => 'erro-caixa-aberto', 'mensagem' => 'Você já possui um caixa aberto! Feche-o antes de abrir outro.'));
        exit();
    endif;
    */


    $registro = new Caixas();
    //$registro->data_abertura = implode('-', array_reverse(explode('/', $dados['data_abertura'])));
    $registro->nome = $dados['nome'];
    $registro->data_abertura = date('Y-m-d');
    $registro->hora_abertura = date('H:i:s');
    $registro->usuario_abertura = idUsuario();
    //$registro->id_colega = $dados['responsavel'];
    $registro->id_colega = idUsuario();

    $saldo_inicial = str_replace(".", "", $dados['saldo_inicial']);
    $saldo_inicial = str_replace(",", ".", $saldo_inicial);
    $registro->saldo_inicial = $saldo_inicial;

    $registro->situacao = 'aberto';
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Caixa', 'Inclusão', 'O caixa '.$dados['nome'].' foi aberto.');

    $id_caixa = $registro->id;

    /*Gerando movimento se houver saldo inicial*/
    if($dados['saldo_inicial'] > 0):

        $movimento = new Movimentos_Caixa();
        $movimento->id_caixa = $id_caixa;
        $movimento->numero = 1;
        $movimento->data = date('Y-m-d');
        $movimento->hora = date('H:i:s');
        $movimento->total = $saldo_inicial;
        $movimento->descricao = 'Saldo Inicial';
        $movimento->tipo = 'e';
        $movimento->id_forma_pagamento = 1;
        $movimento->save();

        $id_movimento = $movimento->id;

        /*Gerando detalhe*/
        $detalhe = new Detalhes_Movimento();
        $detalhe->id_movimento = $id_movimento;
        $detalhe->id_parcela = $parcela->id;
        $detalhe->numero_movimento = 1;
        $detalhe->total = $saldo_inicial;
        $detalhe->save();

    endif;

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'adicionar-responsaveis'):

    $registro = Caixas::find($dados['id']);
    $lista_usuarios = explode('|', $dados['usuarios']);

    if(!empty($lista_usuarios)):
        foreach($lista_usuarios as $id_usuario):
            if(!empty($id_usuario)):
                /*Verificando se usuario nao é responsável por nenhum outro caixa*/
                $usuario = Usuarios::find($id_usuario);
                $em_outro_caixa = 'n';

                $caixas_abertos = Caixas::find_all_by_situacao('aberto');
                if(!empty($caixas_abertos)):
                    foreach($caixas_abertos as $caixa):
                        if(Responsaveis_Caixa::find_by_id_caixa_and_id_usuario($caixa->id, $usuario->id)):

                            $em_outro_caixa = 's';

                        endif;
                    endforeach;
                endif;

                if($em_outro_caixa == 'n'):
                    /*adicionando usuario como responsavel*/
                    $responsavel = new Responsaveis_Caixa();
                    $responsavel->id_caixa = $registro->id;
                    $responsavel->id_usuario = $usuario->id;
                    $responsavel->save();
                endif;

            endif;
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'calcular-saldo-caixa'):

    $registro = Caixas::find($dados['id']);
    $movimentos_entrada = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($registro->id, 'e');
    $movimentos_saida = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($registro->id, 's');

    $entradas = 0;
    $saidas = 0;
    $saldo = 0;

    if(!empty($movimentos_entrada)):
        foreach($movimentos_entrada as $movimento_entrada):

            $entradas+=$movimento_entrada->total;

        endforeach;
    endif;

    if(!empty($movimentos_saida)):
        foreach($movimentos_saida as $movimento_saida):

            $saidas+=$movimento_saida->total;

        endforeach;
    endif;

    $saldo = $entradas-$saidas;

    echo json_encode(array('status' => 'ok', 'saldo' => number_format($saldo, 2, ',', '.')));

endif;


if($dados['acao'] == 'transferir'):

    /*tipo de transferencia*/
    $transferir_para = $dados['transferir_para'];

    /*Caixa de Origem*/
    $registro = Caixas::find($dados['id']);


    /*saldo atual*/
    $movimentos_entrada = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($registro->id, 'e');
    $movimentos_saida = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($registro->id, 's');

    $entradas = 0;
    $saidas = 0;
    $saldo = 0;

    if(!empty($movimentos_entrada)):
        foreach($movimentos_entrada as $movimento_entrada):

            $entradas+=$movimento_entrada->total;

        endforeach;
    endif;

    if(!empty($movimentos_saida)):
        foreach($movimentos_saida as $movimento_saida):

            $saidas+=$movimento_saida->total;

        endforeach;
    endif;

    $saldo = $entradas-$saidas;
    /*saldo atual*/

    $valor_transferencia = str_replace(".", "", $dados['valor_transferencia']);
    $valor_transferencia = str_replace(",", ".", $valor_transferencia);


    //if($valor_transferencia > $saldo):
        /*
        echo json_encode(array('status' => 'erro'));
        exit();
        */

    //else:

        if($transferir_para == 'outro_caixa'):

            /*Caixa de Origem*/
            $caixa_origem = Caixas::find($registro->id);
            $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa_origem->id), 'order' => 'numero desc', 'limit' => 1));

            /*Dando saida do caixa de origem*/
            $movimento_saida = new Movimentos_Caixa();
            $movimento_saida->id_caixa = $caixa_origem->id;
            $movimento_saida->numero = $ultimo_movimento->numero+1;
            $movimento_saida->data = date('Y-m-d');
            $movimento_saida->hora = date('H:i:s');
            $movimento_saida->total = $valor_transferencia;
            $movimento_saida->descricao = 'Tranferência para o Caixa Nº '.$dados['caixa'];
            $movimento_saida->tipo = 's';
            $movimento_saida->id_forma_pagamento = 1;
            $movimento_saida->save();

            $id_movimento = $movimento_saida->id;

            /*Gerando detalhe*/
            $detalhe = new Detalhes_Movimento();
            $detalhe->id_movimento = $id_movimento;
            //$detalhe->id_parcela = $parcela->id;
            $detalhe->numero_movimento = $ultimo_movimento->numero+1;
            $detalhe->total = $valor_transferencia;
            $detalhe->save();


            /*caixa de destino*/
            $caixa_destino = Caixas::find($dados['caixa']);
            $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa_destino->id), 'order' => 'numero desc', 'limit' => 1));

            /*Dando entrada no caixa de destino*/
            $movimento_saida = new Movimentos_Caixa();
            $movimento_saida->id_caixa = $caixa_destino->id;
            $movimento_saida->numero = $ultimo_movimento->numero+1;
            $movimento_saida->data = date('Y-m-d');
            $movimento_saida->hora = date('H:i:s');
            $movimento_saida->total = $valor_transferencia;
            $movimento_saida->descricao = 'Tranferência do Caixa Nº '.$caixa_origem->id;
            $movimento_saida->tipo = 'e';
            $movimento_saida->id_forma_pagamento = 1;
            $movimento_saida->save();

            $id_movimento = $movimento_saida->id;

            /*Gerando detalhe*/
            $detalhe = new Detalhes_Movimento();
            $detalhe->id_movimento = $id_movimento;
            //$detalhe->id_parcela = $parcela->id;
            $detalhe->numero_movimento = $ultimo_movimento->numero+1;
            $detalhe->total = $valor_transferencia;
            $detalhe->save();

            adicionaHistorico(idUsuario(), idColega(), 'Caixa', 'Alteração', 'O valor de R$'. number_format($valor_transferencia, 2, ', ', '.').' foi transferido do caixa '.$caixa_origem->nome.' para o caixa '.$caixa_destino->nome);

        elseif($transferir_para == 'conta_bancaria'):

            /*Caixa de Origem*/
            $caixa_origem = Caixas::find($registro->id);
            $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa_origem->id), 'order' => 'numero desc', 'limit' => 1));

            $conta_bancaria = Unidades::find($dados['conta_bancaria']);

            /*Dando saida do caixa de origem*/
            $movimento_saida = new Movimentos_Caixa();
            $movimento_saida->id_caixa = $caixa_origem->id;
            $movimento_saida->numero = $ultimo_movimento->numero+1;
            $movimento_saida->data = date('Y-m-d');
            $movimento_saida->hora = date('H:i:s');
            $movimento_saida->total = $valor_transferencia;
            $movimento_saida->descricao = 'Tranferência para a conta bancaria da Unidade '.$conta_bancaria->nome_fantasia;
            $movimento_saida->tipo = 's';
            $movimento_saida->id_forma_pagamento = 1;
            $movimento_saida->save();

            $id_movimento = $movimento_saida->id;

            /*Gerando detalhe*/
            $detalhe = new Detalhes_Movimento();
            $detalhe->id_movimento = $id_movimento;
            //$detalhe->id_parcela = $parcela->id;
            $detalhe->numero_movimento = $ultimo_movimento->numero+1;
            $detalhe->total = $valor_transferencia;
            $detalhe->save();

            adicionaHistorico(idUsuario(), idColega(), 'Caixa', 'Alteração', 'O valor de R$'. number_format($valor_transferencia, 2, ', ', '.').' foi transferido do caixa '.$caixa_origem->nome.' para a conta '.$dados['conta_bancaria']);

        endif;

        echo json_encode(array('status' => 'ok'));

    //endif;

endif;



if($dados['acao'] == 'somar-totais'):

    $caixa = Caixas::find($dados['id']);
    $formas_pagamento = Formas_Pagamento::all();

    /*saldo atual*/
    $movimentos_entrada = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($caixa->id, 'e');
    $movimentos_saida = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($caixa->id, 's');

    $entradas = 0;
    $saidas = 0;
    $saldo = 0;

    if(!empty($movimentos_entrada)):
        foreach($movimentos_entrada as $movimento_entrada):

            $entradas+=$movimento_entrada->total;

        endforeach;
    endif;

    if(!empty($movimentos_saida)):
        foreach($movimentos_saida as $movimento_saida):

            $saidas+=$movimento_saida->total;

        endforeach;
    endif;

    $saldo = $entradas-$saidas;
    /*saldo atual*/

    /*Calculando Totais*/
    $total = 0;
    if(!empty($formas_pagamento)):
    ?>
    <!-- Basic Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Forma de Pagamento</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach($formas_pagamento as $forma_pagamento):

                $movimentos = Movimentos_Caixa::find_all_by_id_caixa_and_id_forma_pagamento_and_tipo($caixa->id, $forma_pagamento->id, 'e');
                if(!empty($movimentos)):
                    foreach($movimentos as $movimento):
                        $total+=$movimento->total;
                    endforeach;
                endif;

                echo '<tr>';
                echo '<td data-title="Data">'.$forma_pagamento->forma_pagamento.'</td>';
                echo '<td data-title="Idioma">R$ '.number_format($total, 2, ',', '.').'</td>';
                echo '</tr>';

                $total_geral+=$total;
                $total = 0;

            endforeach;
            ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Tipo de Movimento</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Entrada</td>
                    <td><?php echo 'R$ '.number_format($entradas, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>Saídas</td>
                    <td><?php echo 'R$ '.number_format($saidas, 2, ',', '.') ?></td>
                </tr>

                <tr>
                    <td>Total Geral</td>
                    <td><?php echo 'R$ '.number_format($total_geral-$saidas, 2, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

    </div>

    <?php
    endif;

endif;


if($dados['acao'] == 'lancamento'):

    $registro = Caixas::find($dados['id']);
    $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $registro->id), 'order' => 'numero desc', 'limit' => 1));
    $proximo_numero = $ultimo_movimento->numero+1;
    $tipo = $dados['tipo'];

    $valor = str_replace(".", "", $dados['valor_lancamento']);
    $valor = str_replace(",", ".", $valor);

    /*Realizando Lançamento*/
    $movimento_saida = new Movimentos_Caixa();
    $movimento_saida->id_caixa = $registro->id;
    //$movimento_saida->id_categoria = $dados['id_categoria'];
    $movimento_saida->numero = $proximo_numero;
    $movimento_saida->data = date('Y-m-d');
    $movimento_saida->hora = date('H:i:s');
    $movimento_saida->total = $valor;
    $movimento_saida->descricao = 'Lançameto Manual';
    $movimento_saida->tipo = $tipo;
    $movimento_saida->id_forma_pagamento = $dados['id_forma_pagamento'];
    $movimento_saida->save();

    $id_movimento = $movimento_saida->id;

    /*Gerando detalhe*/
    $detalhe = new Detalhes_Movimento();
    $detalhe->id_movimento = $id_movimento;
    //$detalhe->id_parcela = $parcela->id;
    $detalhe->numero_movimento = $proximo_numero;
    $detalhe->total = $valor;
    $detalhe->save();

    $tipo == 'e' ? $tipo_lancamento = 'Entrada' : 'Saída';
    adicionaHistorico(idUsuario(), idColega(), 'Caixa', 'Inclusão', 'Um lançamento do tipo '.$tipo_lancamento.' de R$ '.number_format($valor, 2, ',', '.').' foi realizado no caixa '.$registro->nome);

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'fechar-caixa'):

    $caixa = Caixas::find($dados['id']);
    /*saldo atual*/
    $movimentos_entrada_dinheiro = Movimentos_Caixa::find_all_by_id_caixa_and_tipo_and_id_forma_pagamento($caixa->id, 'e',1);
    $movimentos_entrada = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($caixa->id, 'e');
    $movimentos_saida = Movimentos_Caixa::find_all_by_id_caixa_and_tipo($caixa->id, 's');

    $dinheiro = 0;
    $entradas = 0;
    $saidas = 0;
    $saldo = 0;

    if(!empty($movimentos_entrada)):
        foreach($movimentos_entrada as $movimento_entrada):

            $entradas+=$movimento_entrada->total;

        endforeach;
    endif;

    if(!empty($movimentos_saida)):
        foreach($movimentos_saida as $movimento_saida):

            $saidas+=$movimento_saida->total;

        endforeach;
    endif;

    if(!empty($movimentos_entrada_dinheiro)):
        foreach($movimentos_entrada_dinheiro as $movimento_entrada_dinheiro):

            $dinheiro+=$movimento_entrada_dinheiro->total;

        endforeach;
    endif;

    $saldo = $entradas-$saidas;
    /*saldo atual*/

    /*Fechando o caixa*/
    $caixa->data_fechamento = date('Y-m-d');
    $caixa->hora_fechamento = date('H:i:s');
    $caixa->usuario_fechamento = idUsuario();
    $caixa->total_entradas = $entradas;
    $caixa->total_saidas = $saidas;
    $caixa->total_dinheiro = $dinheiro;
    $caixa->total_caixa = $saldo;
    $caixa->situacao = 'fechado';
    $caixa->save();

    adicionaHistorico(idUsuario(), idColega(), 'Caixa', 'Alteração', 'O caixa '.$caixa->nome.' foi fechado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'estornar'):

    $movimento_estorno = Movimentos_Caixa::find($dados['id']);
    $movimento_estorno->estorno = 's';
    $movimento_estorno->save();

    $movimentos_selecionados = Movimentos_Caixa::all(['conditions' => ['id_conta_pagar = ?', $movimento_estorno->id_conta_pagar]]);
    if(!empty($movimentos_selecionados)):

        foreach ($movimentos_selecionados as $movimento_selecionado):
            $movimento_selecionado->estorno = 's';
            $movimento_selecionado->save();
        endforeach;

    endif;

    $ids = explode('|', $movimento_estorno->id_conta_pagar);
    if(!empty($ids)):
        foreach (array_filter($ids) as $id):

            /*Verificando se é conta a pagar ou pacela*/
            //$conta = Contas_Pagar::find($movimento_estorno->id_conta_pagar);
            if($movimento_estorno->tipo == 'e'):
                $conta = Parcelas::find($id);
            else:
                $conta = Contas_Pagar::find($id);
            endif;

            $registro = Caixas::all($movimento_estorno->id_caixa);
            $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $registro->id), 'order' => 'numero desc', 'limit' => 1));
            $proximo_numero = $ultimo_movimento->numero+1;
            //$tipo = 'e';

            /*Realizando Lançamento*/
            $movimento = new Movimentos_Caixa();
            $movimento->id_caixa = $registro->id;
            //$movimento->id_categoria = $dados['id_categoria'];
            $movimento->numero = $proximo_numero;
            $movimento->data = date('Y-m-d');
            $movimento->hora = date('H:i:s');
            //$movimento->total = $movimento_estorno->total;
            $movimento->total = $conta->valor;

            if($movimento_estorno->tipo == 'e'):
                $movimento->descricao = 'Estorno da Parcela Código: '.$id;
            else:
                $movimento->descricao = 'Estorno da Conta a Pagar Código: '.$id;
            endif;

            //$movimento->descricao = 'Estorno da Conta a Pagar Código: '.$movimento_estorno->id_conta_pagar;

            /*informando se é entreda ou saída*/
            if($movimento_estorno->tipo == 'e'):
                $movimento->tipo = 's';
            else:
                $movimento->tipo = 'e';
            endif;

            $movimento->estorno = 's';
            $movimento->id_forma_pagamento = $dados['id_forma_pagamento'];
            $movimento->save();

            $id_movimento = $movimento_saida->id;

            /*Gerando detalhe*/
            $detalhe = new Detalhes_Movimento();
            $detalhe->id_movimento = $id_movimento;
            //$detalhe->id_parcela = $parcela->id;
            $detalhe->numero_movimento = $proximo_numero;
            //$detalhe->total = $movimento_estorno->total;
            $detalhe->total = $conta->valor;
            $detalhe->save();

            /*Removendo Pagamento da Conta a Pagar*/
            //$conta = Contas_Pagar::find($movimento_estorno->id_conta_pagar);
            //$conta = Contas_Pagar::find($id);
            $conta->data_pagamento = '';
            $conta->pago = 'n';
            $conta->save();

            if($movimento_estorno->tipo == 'e'):
                adicionaHistorico(idUsuario(), idColega(), 'Caixa', 'Alteração', 'A parcela de código '.$id.' foi estornada.');
            else:
                adicionaHistorico(idUsuario(), idColega(), 'Caixa', 'Alteração', 'A conta a pagar de código '.$id.' foi estornada.');
            endif;

        endforeach;
    endif;

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));


endif;


if($dados['acao'] == 'excluir'):

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Natureza de Conta a Pagar não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;
