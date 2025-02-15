<?php
include_once('../config.php');

$boletos = Boletos::all(array('conditions' => array('pago = ?', 's')));
if(!empty($boletos)):
    foreach ($boletos as $boleto):
        try{
            $parcela = Parcelas::find($boleto->id_parcela);
        } catch (\ActiveRecord\RecordNotFound $e){

        }

        if(!empty($parcela)):
            $parcela->valor_pago = $boleto->valor_pago;
            $parcela->save();
        endif;

    endforeach;
endif;

echo 'Processo finalizado...';
?>

<table style="border-collapse: collapse; border-color: #fff;"></table>
