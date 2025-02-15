<?php
include_once('../config.php');
$estado = filter_input(INPUT_POST, 'estado', FILTER_VALIDATE_INT);
$cidades = Cidades::all(array('conditions' => array('estado_id = ?', $estado), 'order' => 'nome asc'));

if(!empty($cidades)):
    foreach($cidades as $cidade):
        echo '<option value="'.$cidade->id.'">'.$cidade->nome.'</option>';
    endforeach;
endif;