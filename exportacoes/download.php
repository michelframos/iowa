<?php
$arquivo = $_GET['arquivo'];

header('Content-Description: File Transfer');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"".basename($arquivo)."\"");
header("Content-Transfer-Encoding: binary");
header("Expires: 0");
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Length: ' . filesize($arquivo));

ob_clean();
flush();

readfile($arquivo);