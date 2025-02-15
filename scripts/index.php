<?php
include_once ('../config.php');
use IowaGeral\Rotas;
$rota = new Rotas();
$rota->VerificaRota('scripts', $getUrl, $rotas);