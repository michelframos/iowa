<?php

class UnidadesBancosModel extends \ActiveRecord\Model
{

    public static $table_name = 'unidades_bancos';

    static public function salvar($dados)
    {

        $id_unidade = filtra_int($dados['id']);
        $codigo_banco = filtra_string($dados['codigo_banco']);
        $carteira = filtra_string($dados['carteira']);
        $especie = filtra_string($dados['especie']);
        $agencia = filtra_string($dados['agencia']);
        $conta = filtra_string($dados['conta']);
        $codigo_cliente = filtra_string($dados['codigo_cliente']);
        $juros = filtra_string($dados['juros']);
        $multa = filtra_string($dados['multa']);

        $juros = str_replace(".", "", $juros);
        $juros = str_replace(",", ".", $juros);

        $multa = str_replace(".", "", $multa);
        $multa = str_replace(",", ".", $multa);

        !UnidadesBancosModel::find_by_id_unidade_and_codigo_banco($id_unidade, $codigo_banco)
            ? $registro = new UnidadesBancosModel()
            : $registro = UnidadesBancosModel::find_by_id_unidade_and_codigo_banco($id_unidade, $codigo_banco);

        $banco = BancosModel::find_by_codigo($codigo_banco);

        $registro->id_unidade = $id_unidade;
        $registro->id_banco = $banco->id;
        $registro->codigo_banco = $banco->codigo;
        $registro->carteira = $carteira;
        $registro->especie = $especie;
        $registro->agencia = $agencia;
        $registro->conta = $conta;
        $registro->codigo_cliente = $codigo_cliente;
        $registro->juros = $juros;
        $registro->multa = $multa;
        $registro->save();

    }

}