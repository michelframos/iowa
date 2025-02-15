<?php
namespace IowaPainel;

class BancosController
{

    static public function bancos()
    {
        $registros = \BancosModel::all(['order' => 'nome asc']);
        return $registros;
    }

}