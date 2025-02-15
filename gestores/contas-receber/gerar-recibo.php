<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

$usuario = Usuarios::find(idUsuario());

if($dados['acao'] == 'gerar-recibo'):


    $ids_parcelas = explode('|', $dados['parcelas']);
    $cont = 0;
    $sacado = '';
    $total = 0;
    $parcelas = '';
    if(!empty($ids_parcelas)):
        foreach(array_filter($ids_parcelas) as $id_parcela):

            if(!empty($id_parcela)):

                $parcela = Parcelas::find($id_parcela);

                if($cont < 1):
                    $sacado = $parcela->id_aluno;
                else:
                    if($parcela->id_aluno != $sacado):
                        echo json_encode(array('status' => 'erro-sacado'));
                        exit();
                    endif;
                endif;

                $total += !empty($parcela->valor_pago) ? $parcela->valor_pago : $parcela->total;
                $parcelas = $parcelas.','.$parcela->id;

            endif;

            $cont++;
        endforeach;
    endif;

    /*Gravando dados na tabela recibos*/
    $recibo = new Recibos();
    $recibo->data = date('Y-m-d H:i:s');
    $recibo->parcelas = $parcelas;
    $recibo->total = $total;
    $recibo->id_aluno = $sacado;
    $recibo->id_usuario = $usuario->id;
    $recibo->save();

    $id_recibo = $recibo->id;

    echo json_encode(array('status' => 'ok', 'link_recibo' => HOME.'/gestores/contas-receber/imprime-recibo.php?recibo='.$id_recibo));

endif;
