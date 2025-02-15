<?php
/*
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
*/

$getUrl = strip_tags(trim(filter_input(INPUT_GET, 'Url', FILTER_SANITIZE_STRING)));
$setUrl = (empty($getUrl) ? 'api' : $getUrl);
$url = explode('/', $setUrl);

if(!file_exists($url[0].'.php')):
    include_once('404,.php');
else:
    include_once($url[0].'.php');
endif;
