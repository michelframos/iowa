<?php
namespace IowaPainel;

class UnidadesController
{

    static public function buscaDadosBanco()
    {
        $id_unidade = filter_input(INPUT_POST, 'id_unidade', FILTER_VALIDATE_INT);
        $codigo_banco = filter_input(INPUT_POST, 'codigo_banco', FILTER_SANITIZE_STRING);

        $registro = \UnidadesBancosModel::find_by_id_unidade_and_codigo_banco($id_unidade, $codigo_banco);
        echo json_encode([
            'status' => 'ok',
            'carteira' => $registro->carteira,
            'especie' => $registro->especie,
            'agencia' => $registro->agencia,
            'conta' => $registro->conta,
            'codigo_cliente' => $registro->codigo_cliente,
            'juros' => number_format($registro->juros, 1, ',', ''),
            'multa' => number_format($registro->multa, 1, ',', ''),
        ]);
    }

    static public function getDadosBanco($id_unidade, $codigo_banco)
    {
        $registro = \UnidadesBancosModel::find_by_id_unidade_and_codigo_banco($id_unidade, $codigo_banco);
        return $registro;
    }

}
