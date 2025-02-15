<?php
namespace IowaHelpers;

class BancoHelper
{

    static public function impresso($codigo_banco)
    {
        switch ($codigo_banco):
            case '001':
                return 'boleto_bb.php';
                break;

            case '756':
                return 'boleto.php';
                break;
        endswitch;
    }

}