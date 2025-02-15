<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
try{
    $registro = Colegas::find($dados['id']);
} catch (\ActiveRecord\RecordNotFound $e){
    $registro = '';
}


if($dados['acao'] == 'busca-instrutores'):

    if(!empty($dados['id_coach'])):
        $instrutores = Colegas::find_all_by_id_funcao_and_instrutor_id_coach_and_status(3, $dados['id_coach'], 'a');
        if(!empty($instrutores)):
            echo '<option value=""></option>';
            foreach($instrutores as $instrutor):
                echo '<option value="'.$instrutor->id.'">'.$instrutor->nome.'</option>';
            endforeach;
        endif;

    else:
        echo '<option value=""></option>';

    endif;

endif;


if($dados['acao'] == 'busca-ata'):

    $id_ata = $dados['ata'];
    $ata = Atas_Coach::find($id_ata);
    echo json_encode(array('status' => 'ok', 'ata' => $ata->ata));

endif;


if($dados['acao'] == 'salvar-ata-turma'):

    $turma = Turmas::find($dados['id_turma']);
    $colega = Colegas::find($turma->id_colega);
    $texto = $dados['nova-ata-turma'];

    $ata = new Atas_Coach();
    $ata->id_turma = $turma->id;
    $ata->id_colega = $turma->id_colega;
    $ata->id_coach = $colega->instrutor_id_coach;
    $ata->data = date('Y-m-d H:i:s');
    $ata->ata = filter_var($dados['nova-ata-turma'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $ata->save();

    adicionaHistorico(idUsuario(), idColega(), 'Coach - Ata', 'Inclusão', 'Uma nova ata para a turma '.$turma->nome. ' foi incluída.');

    /*Para o Aluno*/
    try{
        $configuracao_email = Envio_Emails::find(1);
    } catch (Exception $e) {
        $configuracao_email = '';
    }

    include_once('../../classes/PHPMailer/class.phpmailer.php');

    $mensagem  = "Olá {$colega->nome}, uma nova ata para a turma {$turma->nome} está disponível.\r\n";
    $mensagem .= "ATA:\n\r";
    $mensagem .= "{$dados['nova-ata-turma']}";

    $mail = new PHPMailer();

    //$mail->SMTPDebug = 1;
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
    //$mail->Port = $configuracao_email->porta_smtp;
    //$mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
    $mail->Username = $configuracao_email->usuario_smtp; // Usuário do servidor SMTP
    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

    $mail->From = $configuracao_email->email;
    $mail->FromName = 'Nova Ata - Couch';

    $mail->AddAddress($colega->email, $colega->nome);
    //$mail->AddBCC($aluno->email, $aluno->nome);

    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    $mail->Subject  = 'Nova Ata - Couch'; // Assunto da mensagem
    $mail->Body = $mensagem;

    $mail->Send();

    echo json_encode(array('status' => 'ok'));

    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

endif;


if($dados['acao'] == 'alterar-ata-turma'):

    $turma = Turmas::find($dados['id_turma']);
    $colega = Colegas::find($turma->id_colega);
    $texto = $dados['alterar-ata-turma'];

    $ata = Atas_Coach::find($dados['ata']);
    $ata->id_turma = $turma->id;
    $ata->id_colega = $turma->id_colega;
    $ata->id_coach = $colega->instrutor_id_coach;
    $ata->data = date('Y-m-d H:i:s');
    $ata->ata = $dados['alterar-ata-turma'];
    $ata->save();

    adicionaHistorico(idUsuario(), idColega(), 'Coach - Ata', 'Alteração', 'A ata da turma '.$turma->nome. ' da data de '.$ata->data->format('d/m/Y').' foi alterada.');

    /*Para o Aluno*/
    try{
        $configuracao_email = Envio_Emails::find(1);
    } catch (Exception $e) {
        $configuracao_email = '';
    }

    include_once('../../classes/PHPMailer/class.phpmailer.php');

    $mensagem  = "Olá {$colega->nome}, uma ata foi alterada: turma {$turma->nome}.\r\n";
    $mensagem .= "ATA:\n\r";
    $mensagem .= "{$dados['alterar-ata-turma']}";

    $mail = new PHPMailer();

    //$mail->SMTPDebug = 1;
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
    //$mail->Port = $configuracao_email->porta_smtp;
    //$mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
    $mail->Username = $configuracao_email->usuario_smtp; // Usuário do servidor SMTP
    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

    $mail->From = $configuracao_email->email;
    $mail->FromName = 'Alteração Ata - Couch';

    $mail->AddAddress($colega->email, $colega->nome);
    //$mail->AddBCC($aluno->email, $aluno->nome);

    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    $mail->Subject  = 'Alteração Ata - Couch'; // Assunto da mensagem
    $mail->Body = $mensagem;

    $mail->Send();

    echo json_encode(array('status' => 'ok'));

    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

endif;


if($dados['acao'] == 'salvar-ata'):

    $turma = Turmas::find($dados['id_turma']);
    $aluno = Alunos::find($dados['id_aluno']);;
    $colega = Colegas::find($turma->id_colega);
    $texto = $dados['nova-ata'];

    $ata = new Atas_Coach();
    $ata->id_turma = $turma->id;
    $ata->id_aluno = $aluno->id;
    $ata->id_colega = $turma->id_colega;
    $ata->id_coach = $colega->instrutor_id_coach;
    $ata->data = date('Y-m-d H:i:s');
    $ata->ata = $dados['nova-ata'];
    $ata->save();

    adicionaHistorico(idUsuario(), idColega(), 'Coach - Ata', 'Inclusão', 'Uma nova ata para o aluno '.$aluno->nome. ' foi incluída.');

    /*Para o Aluno*/
    try{
        $configuracao_email = Envio_Emails::find(1);
    } catch (Exception $e) {
        $configuracao_email = '';
    }

    include_once('../../classes/PHPMailer/class.phpmailer.php');

    $mensagem  = "Olá {$colega->nome}, uma nova ata para a turma {$turma->nome}, Aluno {$aluno->nome} está disponível.\r\n";
    $mensagem .= "ATA:\n\r";
    $mensagem .= "{$dados['nova-ata']}";

    $mail = new PHPMailer();

    //$mail->SMTPDebug = 1;
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
    //$mail->Port = $configuracao_email->porta_smtp;
    $mail->Username = $configuracao_email->usuario_smtp;
    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

    $mail->From = $configuracao_email->email;
    $mail->FromName = 'Nova Ata - Couch';

    $mail->AddAddress($colega->email, $colega->nome);
    //$mail->AddBCC($aluno->email, $aluno->nome);

    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    $mail->Subject  = 'Nova Ata - Couch'; // Assunto da mensagem
    $mail->Body = $mensagem;

    $mail->Send();

    echo json_encode(array('status' => 'ok'));

    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

endif;


if($dados['acao'] == 'alterar-ata'):

    $turma = Turmas::find($dados['id_altera_turma']);
    $colega = Colegas::find($turma->id_colega);
    $texto = $dados['alterar-ata'];

    $ata = Atas_Coach::find($dados['ata']);
    $ata->id_turma = $turma->id;
    $ata->id_colega = $turma->id_colega;
    $ata->id_coach = $colega->instrutor_id_coach;
    $ata->data = date('Y-m-d H:i:s');
    $ata->ata = $dados['alterar-ata'];
    $ata->save();

    adicionaHistorico(idUsuario(), idColega(), 'Coach - Ata', 'Alteração', 'A ata do aluno '.$aluno->nome. ' da data de '.$ata->data->format('d/m/Y').' foi alterada.');

    /*Para o Aluno*/
    try{
        $configuracao_email = Envio_Emails::find(1);
    } catch (Exception $e) {
        $configuracao_email = '';
    }

    include_once('../../classes/PHPMailer/class.phpmailer.php');

    $mensagem  = "Olá {$colega->nome}, uma ata foi alterada: turma {$turma->nome}.\r\n";
    $mensagem .= "ATA:\n\r";
    $mensagem .= "{$dados['alterar-ata']}";

    $mail = new PHPMailer();

    //$mail->SMTPDebug = 1;
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
    //$mail->Port = $configuracao_email->porta_smtp;
    //$mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
    $mail->Username = $configuracao_email->usuario_smtp; // Usuário do servidor SMTP
    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

    $mail->From = $configuracao_email->email;
    $mail->FromName = 'Alteração Ata - Couch';

    $mail->AddAddress($colega->email, $colega->nome);
    //$mail->AddBCC($aluno->email, $aluno->nome);

    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    $mail->Subject  = 'Alteração Ata - Couch'; // Assunto da mensagem
    $mail->Body = $mensagem;

    $mail->Send();

    echo json_encode(array('status' => 'ok'));

    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

endif;

