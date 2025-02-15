<?php
    include_once ('../config.php');
    $codigo = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
    $envio = EnviosPromocoes::find_by_codigo($codigo);
    $promocao = Promocoes::find($envio->id_promocao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>IOWA Promoções</title>

    <link rel="stylesheet" href="assets/css/boot.css">
    <link rel="stylesheet" href="assets/css/estilo.css">
</head>
<body>
    <?php
    $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);

    if($url == 'obrigado'):
        include_once('obrigado.php');
    else:
        if($promocao->tempo_indeterminado == 'n' && $promocao->data_termino->format('d/m/Y') < date('d/m/Y')):
            include_once('erro.php');
        else:
            $promocao = Promocoes::find($envio->id_promocao);
            if($promocao->para == 'aluno'):
                include_once('aluno.php');
            elseif($promocao->para == 'compartilhamento'):
                include_once('compartilhada.php');
            endif;
        endif;
    endif;
    ?>
</body>
</html>
