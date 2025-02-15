<?php
$data_inicio = '2018-12-26';
$primeira_data = array(1 => 'segunda', 2 => 'terca', 3 => 'quarta', 4 => 'quinta', 5 => 'sexta', 6 => 'sabado', 0 => 'domingo');
$proxima_data = array(1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 0 => 'sunday');
$primeiro_dia = array('segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo');

$dias = array('segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo');
$dias_selecionados = ['quinta', 'sexta'];

echo array_search($dias_selecionados[0], $primeira_data);

$i = 1;
if(!empty($dias_selecionados)):
    foreach($dias_selecionados as $dia):

        if($i < 2):
            if($primeira_data[date('w', strtotime($data_inicio))] == $dia):
                echo 'Dia Certo';
            else:

                $data = new DateTime($data_inicio);
                $data->modify('next '.$proxima_data[array_search($dias_selecionados[0], $primeira_data)]);
                echo $data->format('d/m/Y');

            endif;
        endif;
        $i++;

    endforeach;
endif;
