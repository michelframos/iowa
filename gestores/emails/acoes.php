<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'salvar'):

    $email = Envio_Emails::find(1);
    $email->email = $dados['email'];
    $email->senha = $dados['senha'];
    $email->usuario_smtp = $dados['usuario_smtp'];
    $email->smtp = $dados['smtp'];
    $email->porta_smtp = $dados['porta_smtp'];
    $email->requer_autenticacao = $dados['requer_autenticacao'];
    $email->tipo_autenticacao = $dados['tipo_autenticacao'];
    $email->save();

    adicionaHistorico(idUsuario(), idColega(), 'Configurações de E-mail', 'Alteração', 'As configurações para envio de e-mails foram alteradas.');

    echo json_encode(array('status' => 'ok'));

endif;
