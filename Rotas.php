<?php
include_once('classes/funcoes.php');

define('SCRIPTS', dirname(__FILE__).'/scripts');

/*Configuração de URL*/
$getUrl = strip_tags(trim(filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING)));
$setUrl = (empty($getUrl) ? 'home' : $getUrl);
$Url = explode('/', $setUrl);

/*rotas*/
$rotas = [];

/*Unidades*/
$rotas[] = ['link' => 'busca-dados-banco', 'nivel' => 'painel', 'pasta' => '', 'arquivo' => '', 'controller' => 'UnidadesController@buscaDadosBanco'];

