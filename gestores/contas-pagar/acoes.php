<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
try{
    $registro = Contas_Pagar::find($dados['id']);
} catch (\ActiveRecord\RecordNotFound $e){
    $registro = '';
}


if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Pagar', 'i');

    $registro = new Contas_Pagar();
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Inclusão', 'Uma nova conta a pagar foi incluída');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Pagar', 'a');

    /*Verificando duplicidade*/
    /*
    if($registro->natureza != $dados['natureza']):
        if(Natureza_Conta::find_by_natureza($dados['natureza'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;
    */

    if(!isset($dados['porcentagem'])):
        echo json_encode(array('status' => 'erro-unidade'));
        exit();
    endif;

    /*Vencimento Aluno*/
    $primeiro_vencimento = explode('/', $dados['data_vencimento']);
    $data_vencimento = $primeiro_vencimento[2].'-'.$primeiro_vencimento[1].'-'.$primeiro_vencimento[0];

    /*Salvando Alterações*/
    if(is_array($dados['porcentagem'])):
        foreach($dados['porcentagem'] as $id_unidade => $porcentagem):

            $mes = $primeiro_vencimento[1];
            $ano = $primeiro_vencimento[2];

            for($i=1;$i<=$dados['numero_parcelas'];$i++):

                if($mes > 12):
                    $mes = 1;
                    $ano++;
                endif;

                $conta = new Contas_Pagar();
                $conta->id_fornecedor = $dados['id_fornecedor'];
                $conta->id_categoria = $dados['id_categoria'];
                $conta->id_natureza = $dados['natureza'];
                $conta->id_unidade = $id_unidade;
                $conta->numero_parcela = $i;
                $conta->data_lancamento = implode('-', array_reverse(explode('/', $dados['data_lancamento'])));
                //$conta->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));

                $conta->data_vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$primeiro_vencimento[0]));

                $valor = str_replace(".", "", $dados['valor']);
                $valor = str_replace(",", ".", $valor);

                if($dados['porcentagem-valor'] == 'p'):
                    $valor_porcentagem = ($porcentagem*$valor)/100;
                elseif($dados['porcentagem-valor'] == 'v'):
                    $valor_porcentagem = str_replace(".", "", $porcentagem);
                    $valor_porcentagem = str_replace(",", ".", $valor_porcentagem);
                endif;
                $conta->valor = $valor_porcentagem;

                $conta->descricao = $dados['observacoes'];

                if(count($dados['porcentagem']) == 1):
                    $conta->compartilhada = 'n';
                elseif(count($dados['porcentagem']) > 1):
                    $conta->compartilhada = 's';
                endif;

                $conta->pago = 'n';
                $conta->cancelada = 'n';
                dadosAlteracao($conta);
                $conta->save();

                $id_conta_pagar = $conta->id;
                $conta = Contas_Pagar::find($id_conta_pagar);

                adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Inclusão', 'Uma conta a pagar foi incluída com valor de R$ '.number_format($conta->valor,2, ',', '.').' com vencimento em '.$conta->data_vencimento->format('d/m/Y'));

                $mes++;

            endfor;

        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'alterar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Pagar', 'a');

    $registro->id_fornecedor = $dados['id_fornecedor'];
    $registro->id_categoria = $dados['id_categoria'];
    $registro->id_natureza = $dados['natureza'];
    $registro->id_unidade = $dados['unidade'];
    $registro->data_lancamento = implode('-', array_reverse(explode('/', $dados['data_lancamento'])));
    $registro->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));

    $valor = str_replace(".", "", $dados['valor']);
    $valor = str_replace(",", ".", $valor);

    $registro->valor = $valor;

    $registro->descricao = $dados['observacoes'];
    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Alteração', 'A conta a pagar foi alterada com valor de R$ '.number_format($registro->valor,2, ',', '.').' com vencimento em '.$registro->data_vencimento->format('d/m/Y'));

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'cancelar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Pagar', 'a');

    $registro->observacoes = $dados['observacao-cancelamento'];
    $registro->cancelada = 's';
    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Alteração', 'A conta a pagar com valor de R$ '.number_format($registro->valor,2, ',', '.').' com vencimento em '.$registro->data_vencimento->format('d/m/Y').' foi cancelada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir-unidade'):

    try{
        $unidade = Unidades_Contas_Pagar::find($dados['id_unidade']);
        $unidade->delete();
    } catch(\ActiveRecord\RecordNotFound $e){

    }

    echo json_encode(array('status' => 'ok'));


endif;


if($dados['acao'] == 'excluir-conta'):

    /*
    if($registro->pago == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Conta a Pagar não pode ser excluída pois já está paga.'));
        exit();
    endif;
    */

    $ids_parcelas = explode('|', $dados['parcelas']);
    if(!empty($ids_parcelas)):
        foreach($ids_parcelas as $id_parcela):

            if(!empty($id_parcela)):
                $id_parcela = $id_parcela;
                $conta = Contas_Pagar::find($id_parcela);
                adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Exclusão', 'A conta a pagar com valor de R$ '.number_format($conta->valor,2, ',', '.').' com vencimento em '.$conta->data_vencimento->format('d/m/Y').' foi excluída.');
                $conta->delete();
            endif;
        endforeach;
    endif;

    /*
    $compartilhadas = Unidades_Contas_Pagar::all(array('conditions' => array('id_conta_pagar = ?', $registro->id)));
    if(!empty($compartilhadas)):
        foreach($compartilhadas as $compartilhada):
            $compartilhada->delete();
        endforeach;
    endif;
    */

    //$registro->delete();
    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'dados-conta'):

    try{
        $fornecedor = Fornecedores::find($registro->id_fornecedor);
    } catch(\ActiveRecord\RecordNotFound $e){
        $fornecedor = 'Fornecedor não cadastrado';
    }

    try{
        $categoria = Categorias_Lancamentos::find($registro->id_categoria);
    } catch(\ActiveRecord\RecordNotFound $e){
        $categoria = 'Categoria não cadastrada';
    }

    try{
        $natureza = Natureza_Conta::find($registro->id_natureza);
    } catch(\ActiveRecord\RecordNotFound $e){
        $natureza = 'Natureza de Conta não cadastrada';
    }

    $unidade = Unidades::find($registro->id_unidade);

    echo '<table class="table pmd-table table-hover">';

    echo '<tr>';
    echo '<td>Data vencimento:</td>';
    echo !empty($registro->data_vencimento) ? '<td>'.$registro->data_vencimento->format('d/m/Y').'</td>' : '<td></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Unidade:</td>';
    echo '<td>'.$unidade->nome_fantasia.'</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Fornecedor:</td>';
    echo '<td>'.$fornecedor->fornecedor.'</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Categoria:</td>';
    echo '<td>'.$categoria->categoria.'</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Natureza:</td>';
    echo '<td>'.$natureza->natureza.'</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Valor:</td>';
    echo '<td class="size-1-5 bold">R$ '.number_format($registro->valor, 2, ',', '.').'</td>';
    echo '</tr>';

    echo '</table>';

endif;



if($dados['acao'] == 'quitar'):

    if(empty($dados['caixa']) && empty($dados['conta_bancaria'])):
        echo json_encode(array('status' => 'erro'));
        exit();
    endif;


    $total_conta = $registro->valor;

    if(!empty($dados['forma_pagamento'])):
        foreach($dados['forma_pagamento'] as $forma):
            $forma = str_replace('.', '', $forma);
            $forma = str_replace(',', '.', $forma);

            $total_formas_pagamento += $forma;
        endforeach;;
    endif;

    if($total_formas_pagamento < $total_conta):
        echo json_encode(array('status' => 'erro-valor', 'mensagem' => 'Valor adicionado é menor que o valor a ser pago'));
        exit();
    endif;

    if($total_formas_pagamento > $total_conta):
        echo json_encode(array('status' => 'erro-valor', 'mensagem' => 'Valor adicionado é maior que o valor a ser pago'));
        exit();
    endif;

    if(!empty($dados['forma_pagamento'])):
        foreach($dados['forma_pagamento'] as $id_forma_pagamento => $forma):
            $forma = str_replace('.', '', $forma);
            $forma = str_replace(',', '.', $forma);

            $quitar_de = $dados['quitar_de'];

            $caixa = Caixas::find($dados['caixa']);
            $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa->id), 'order' => 'numero desc', 'limit' => 1));
            $proximo_numero = $ultimo_movimento->numero+1;
            $tipo = 's';

            /*
            $valor = str_replace(".", "", $registro->valor);
            $valor = str_replace(",", ".", $valor);
            */

            /*Realizando Lançamento de acordo com numero de formas de pagamento*/
            $movimento_saida = new Movimentos_Caixa();
            $movimento_saida->id_caixa = $caixa->id;
            $movimento_saida->id_conta_pagar = $registro->id;
            //$movimento_saida->id_categoria = $dados['id_categoria'];
            $movimento_saida->numero = $proximo_numero;
            $movimento_saida->data = date('Y-m-d');
            $movimento_saida->hora = date('H:i:s');
            //$movimento_saida->total = $registro->valor;
            $movimento_saida->total = $forma;
            $movimento_saida->descricao = 'Quitação da Conta a Pagar Código: '.$registro->id;
            $movimento_saida->tipo = $tipo;
            $movimento_saida->id_forma_pagamento = $id_forma_pagamento;
            $movimento_saida->save();

            $id_movimento = $movimento_saida->id;

            /*Gerando detalhe*/
            $detalhe = new Detalhes_Movimento();
            $detalhe->id_movimento = $id_movimento;
            //$detalhe->id_parcela = $parcela->id;
            $detalhe->numero_movimento = $proximo_numero;
            //$detalhe->total = $registro->valor;
            $detalhe->total = $forma;
            $detalhe->save();
        endforeach;;
    endif;

    /*Marcando a conta como paga*/
    $registro->pago = 's';
    $registro->data_pagamento = implode('-', array_reverse(explode('/', $dados['data_pagamento'])));
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Alteração', 'A conta a pagar com valor de R$ '.number_format($registro->valor,2, ',', '.').' com vencimento em '.$registro->data_vencimento->format('d/m/Y').' foi quitada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'remover-cancelamento'):

    $registro->cancelada = 'n';
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Alteração', 'A conta a pagar com valor de R$ '.number_format($registro->valor,2, ',', '.').' com vencimento em '.$registro->data_vencimento->format('d/m/Y').' foi teve seu cancelamento removido.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'ativa-inativa'):

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
    else:
        $registro->status = 'a';
        $registro->save();
    endif;

endif;

/*VARIAS PARCELAS DE UMA VEZ*/
if($dados['acao'] == 'calcular-parcelas'):

    $id_parcela = explode('|', $dados['parcelas']);
    $total = 0;

    if(!empty($id_parcela)):
        foreach($id_parcela as $id):
            if(!empty($id)):

                $parcela = Contas_Pagar::find($id);
                $total += $parcela->valor;

            endif;
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok', 'total' => number_format($total, 2, ',', '.')));

endif;


if($dados['acao'] == 'quitar-parcelas'):

    if(empty($dados['caixa-selecionadas']) && empty($dados['conta_bancaria_selecionadas'])):
        echo json_encode(array('status' => 'erro'));
        exit();
    endif;

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Abrir Caixa', 'i');

    /*Verificando se existe caixa aberto*/
    $caixa_aberto = Caixas::find_by_id_colega_and_situacao(idUsuario(), 'aberto');

    if(!empty($caixas)):
        $caixa_selecionado = '';
        foreach($caixas as $caixa):
            if(Responsaveis_Caixa::find_by_id_caixa_and_id_usuario($caixa->id, idUsuario())):
                $caixa_selecionado = Responsaveis_Caixa::find_by_id_caixa_and_id_usuario($caixa->id, idUsuario());
            endif;
        endforeach;
    endif;

    //if(empty($caixa_selecionado)):
    if(empty($caixa_aberto)):

        echo json_encode(array('status' => 'erro-caixa'));
        exit();

    else:

        /*Verificando o total da parcela com o total de formas de pagamento*/
        $total_parcelas = str_replace('.', '', $dados['total_parcelas']);
        $total_parcelas = str_replace(',', '.', $total_parcelas);
        $total_formas_pagamento = 0;

        if(!empty($dados['forma_pagamento_selecionadas'])):
            foreach($dados['forma_pagamento_selecionadas'] as $forma):
                $forma = str_replace('.', '', $forma);
                $forma = str_replace(',', '.', $forma);

                $total_formas_pagamento += $forma;
            endforeach;;
        endif;

        if($total_formas_pagamento < $total_parcelas):
            echo json_encode(array('status' => 'erro-valor', 'mensagem' => 'Valor adicionado é menor que o valor a ser pago'));
            exit();
        endif;

        if($total_formas_pagamento > $total_parcelas):
            echo json_encode(array('status' => 'erro-valor', 'mensagem' => 'Valor adicionado é maior que o valor a ser pago'));
            exit();
        endif;

        $total = 0;
        $id_parcela = explode('|', $dados['parcelas']);
        //$parcelas_recebidas = array();

        /*Contadores do Recibo*/
        $cont = 0;
        $sacado = '';
        $total = 0;
        $parcelas = '';

        if(!empty($id_parcela)):
            foreach(array_filter($id_parcela) as $id):
                if(!empty($id)):

                    $parcela = Contas_Pagar::find($id);

                    /*
                    $data_atual = new DateTime();
                    $diferenca_dias = $parcela->data_vencimento->diff($data_atual);
                    $dias_atraso = $diferenca_dias->format('%R%a');
                    */

                    /*Verificando vencimento*/
                    /*
                    if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Quitar Parcela Vencida', 'n')):
                        if($dias_atraso > 0):
                            echo json_encode(array('status' => 'erro-vencimento', 'mensagem' => 'A parcela de '.$parcela->data_vencimento->format('d/m/Y').' está vencida e precisa ser renegociada para poder ser recebida.'));
                            exit();
                        endif;
                    endif;
                    */

                    $parcela->pago = 's';
                    //$parcela->valor_pago = $parcela->total;
                    //$parcela->id_forma_pagamento = $dados['id_forma_pagamento'];
                    $parcela->data_pagamento = implode('-', array_reverse(explode('/', $dados['data_pagamento_selecionadas'])));
                    $parcela->save();

                    adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Alteração', 'A conta a pagar com valor de R$ '.number_format($parcela->valor,2, ',', '.').' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi quitada.');

                    $total += $parcela->valor;
                    $parcelas = $parcelas.','.$parcela->id;

                    $ids_das_parcelas .= $id.', ';

                endif;

                $cont++;
            endforeach;

        endif;

        /*Gerando o Movimentode acordo com a quantia de formas de pagamento utilizadas*/
        if(!empty($dados['forma_pagamento_selecionadas'])):
            foreach ($dados['forma_pagamento_selecionadas'] as $id_forma_pagamento => $forma):
                $forma = str_replace('.', '', $forma);
                $forma = str_replace(',', '.', $forma);

                $quitar_de = $dados['quitar_selecionadas_de'];

                //$caixa = Caixas::find($caixa_aberto->id);
                $caixa = Caixas::find($dados['caixa-selecionadas']);
                $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa->id), 'order' => 'numero desc', 'limit' => 1));
                $numero_movimento = $ultimo_movimento->numero+1;

                $movimento = new Movimentos_Caixa();
                $movimento->id_caixa = $caixa->id;
                $movimento->id_conta_pagar = $dados['parcelas'];
                $movimento->numero = $numero_movimento;
                $movimento->data = date('Y-m-d');
                $movimento->hora = date('H:i:s');
                //$movimento->total = $total;
                $movimento->total = $forma;
                $movimento->descricao = 'Quitação de Conta a Pagar Código: '.$ids_das_parcelas;
                //$movimento->id_aluno = $id_aluno;
                $movimento->tipo = 's';
                //$movimento->id_forma_pagamento = $dados['id_forma_pagamento'];
                $movimento->id_forma_pagamento = $id_forma_pagamento;
                $movimento->save();

                $id_movimento = $movimento->id;
                /*Gerando detalhes do movimento*/
                if(!empty($id_parcela)):
                    foreach($id_parcela as $id):
                        if(!empty($id)):

                            $parcela = Contas_Pagar::find($id);

                            $detalhe = new Detalhes_Movimento();
                            $detalhe->id_movimento = $id_movimento;
                            //$detalhe->id_parcela = $parcela->id;
                            $detalhe->numero_movimento = $numero_movimento;
                            //$detalhe->total = $parcela->total;
                            $detalhe->total = $forma;
                            $detalhe->save();

                        endif;
                    endforeach;
                endif;

            endforeach;
        endif;

        echo json_encode(array('status' => 'ok'));

    endif;

endif;


if($dados['acao'] == 'alterar-contas'):

    $novo_valor = str_replace('.', '', $dados['novo-valor']);
    $novo_valor = str_replace(',', '.', $novo_valor);

    if(!empty($novo_valor)):

        $id_parcela = explode('|', $dados['parcelas']);

        if(!empty($id_parcela)):
            foreach(array_filter($id_parcela) as $id):
                if(!empty($id)):

                    $parcela = Contas_Pagar::find($id);

                    adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Alteração', 'A conta a pagar com valor de R$ '.number_format($parcela->valor,2, ',', '.').' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' teve o valor alterado para '.number_format($novo_valor, 2, ',', '.'));

                    $parcela->valor = $novo_valor;
                    $parcela->save();

                endif;

                $cont++;
            endforeach;

        endif;

        echo json_encode(['status' => 'ok']);

    endif;

endif;


if($dados['acao'] == 'alterar-vencimento'):

    $partes_novo_vencimento = explode('/', $dados['novo-vencimento']);
    $dia = $partes_novo_vencimento[0];
    $mes = $partes_novo_vencimento[1];
    $ano = $partes_novo_vencimento[2];

    $id_parcela = explode('|', $dados['parcelas']);

    $meses_30 = array(
        4 => 4,
        6 => 6,
        9 => 9,
        11 => 11
    );

    if(!empty($id_parcela)):
        foreach($id_parcela as $id):
            if(!empty($id)):

                if($mes > 12):
                    $mes = 1;
                    $ano++;
                endif;

                //$novo_vencimento = $ano.'-'.$mes.'-'.$dia;

                /*Verificando se o proximo mês será Fevereiro*/
                if($mes == 2 && $partes_novo_vencimento[0] > 28):
                    $novo_vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-28'));
                    $novo_vencimento_historico = date('d/m/Y', strtotime('28/'.$mes.'/'.$ano));
                elseif(in_array($mes, $meses_30) && $partes_novo_vencimento[0] > 30):
                    $novo_vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-30'));
                    $novo_vencimento_historico = date('d/m/Y', strtotime('30/'.$mes.'/'.$ano));
                else:
                    $novo_vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$partes_novo_vencimento[0]));
                    $novo_vencimento_historico = date('d/m/Y', strtotime($partes_novo_vencimento[0].'/'.$mes.'/'.$ano));
                endif;

                adicionaHistorico(idUsuario(), idColega(), 'Contas a Pagar', 'Alteração', 'A conta a pagar com valor de R$ '.number_format($parcela->valor,2, ',', '.').' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' teve o vencimento alterado para '.$novo_vencimento_historico);

                $parcela = Contas_Pagar::find($id);
                $parcela->data_vencimento = $novo_vencimento;
                $parcela->save();

                $mes++;

            endif;
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;
