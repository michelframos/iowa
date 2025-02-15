<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Helps::find($dados['id']);

function intervalo( $entrada, $saida ) {
    $entrada = explode( ':', $entrada );
    $saida   = explode( ':', $saida );
    $minutos = ( $saida[0] - $entrada[0] ) * 60 + $saida[1] - $entrada[1];
    if( $minutos < 0 ) $minutos += 24 * 60;
    return sprintf( '%d:%d', $minutos / 60, $minutos % 60 );
}

function decimalHours($time)
{
    $tempo = explode(":", $time);
    return ($tempo[0] + ($tempo[1]/60) + ($tempo[2]/3600));
}

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Help', 'i');

    /*
    Os HELPs terão 3 estados: a = ativo, i = inativo, p = pendente.
    */

    $registro = new Helps();
    $registro->segunda = 'n';
    $registro->terca = 'n';
    $registro->quarta = 'n';
    $registro->quinta = 'n';
    $registro->sexta = 'n';
    $registro->sabado = 'n';
    $registro->domingo = 'n';
    $registro->status = 'p';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Help', 'Inclusão', 'Um novo Help foi cadastrado.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'lista-alunos'):

    $id_turma = $dados['id_turma'];
    $alunos = Alunos::find_by_sql("select * from alunos_turmas inner join alunos on alunos_turmas.id_aluno = alunos.id where alunos_turmas.id_turma = '{$id_turma}' order by nome asc");
    if(!empty($alunos)):
        foreach($alunos as $aluno):
            echo '<option value="'.$aluno->id_aluno.'">'.$aluno->nome.'</option>';
        endforeach;
    endif;

endif;


if($dados['acao'] == 'dia-semana'):

    switch($dados['dia'])
    {
        case 'segunda':
            if($registro->segunda == 'n'):
                $registro->segunda = 's';
                $registro->save();
                echo json_encode(array('status' => 's'));
            else:
                $registro->segunda = 'n';
                $registro->hora_inicio_segunda = '';
                $registro->hora_termino_segunda = '';
                $registro->save();
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'terca':
            if($registro->terca == 'n'):
                $registro->terca = 's';
                $registro->save();
                echo json_encode(array('status' => 's'));
            else:
                $registro->terca = 'n';
                $registro->hora_inicio_terca = '';
                $registro->hora_termino_terca = '';
                $registro->save();
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'quarta':
            if($registro->quarta == 'n'):
                $registro->quarta = 's';
                $registro->save();
                echo json_encode(array('status' => 's'));
            else:
                $registro->quarta = 'n';
                $registro->hora_inicio_quarta = '';
                $registro->hora_termino_quarta = '';
                $registro->save();
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'quinta':
            if($registro->quinta == 'n'):
                $registro->quinta = 's';
                $registro->save();
                echo json_encode(array('status' => 's'));
            else:
                $registro->quinta = 'n';
                $registro->hora_inicio_quinta = '';
                $registro->hora_termino_quinta = '';
                $registro->save();
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'sexta':
            if($registro->sexta == 'n'):
                $registro->sexta = 's';
                $registro->save();
                echo json_encode(array('status' => 's'));
            else:
                $registro->sexta = 'n';
                $registro->hora_inicio_sexta = '';
                $registro->hora_termino_sexta = '';
                $registro->save();
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'sabado':
            if($registro->sabado == 'n'):
                $registro->sabado = 's';
                $registro->save();
                echo json_encode(array('status' => 's'));
            else:
                $registro->sabado = 'n';
                $registro->hora_inicio_sabado = '';
                $registro->hora_termino_sabado = '';
                $registro->save();
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'domingo':
            if($registro->domingo == 'n'):
                $registro->domingo = 's';
                $registro->save();
                echo json_encode(array('status' => 's'));
            else:
                $registro->domingo = 'n';
                $registro->hora_inicio_domingo = '';
                $registro->hora_termino_domingo = '';
                $registro->save();
                echo json_encode(array('status' => 'n'));
            endif;
            break;
    }

endif;


if($dados['acao'] == 'salvar'):

    /*verificando se algum dia foi selecionado*/
    if($registro->segunda == 'n' && $registro->terca == 'n' && $registro->quarta == 'n' && $registro->quinta == 'n' && $registro->sexta == 'n' && $registro->sabado == 'n' && $registro->domingo == 'n'):
        echo json_encode(array('status' => 'erro_dias'));
        exit();
    endif;


    /*Verificando se professor já tem HELP marcado em alguma das datas*/
    /*Verificando dias da semana*/
    $dias = array('segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo');

    $primeira_data = array(1 => 'segunda', 2 => 'terca', 3 => 'quarta', 4 => 'quinta', 5 => 'sexta', 6 => 'sabado', 0 => 'domingo');
    $proxima_data = array(1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 0 => 'sunday');

    foreach($dias as $i => $dia):
        if($registro->$dia == 's'):
            $dias_semana++;
            $dias_selecionados[] = $dia;
        endif;
    endforeach;

    $data_inicio = implode('-', array_reverse(explode('/', $dados['data_inicio'])));
    $data = new DateTime($data_inicio);

    if($primeira_data[date('w', strtotime($data_inicio))] != $dias_selecionados[0]):
        $data->modify('next '.$proxima_data[array_search($dias_selecionados[0], $primeira_data)]);
    endif;

    $verifica_datas = Aulas_Help::find_all_by_id_colega_and_data($dados['id_colega'], $data_inicio);
    if(!empty($verifica_datas)):

        foreach($verifica_datas as $verifica_data):

            $help = Helps::find($verifica_data->id_help);

            if($help->status == 'a'):

                /*Verificando qual o dia da semana e horários*/

                if($registro->segunda == 's'):
                    if(
                        (strtotime($registro->hora_inicio_segunda) < strtotime($help->hora_inicio_segunda)) && (strtotime($registro->hora_termino_segunda) <= strtotime($help->hora_inicio_segunda)) ||
                        (strtotime($registro->hora_inicio_segunda) >= strtotime($help->hora_termino_segunda)) && (strtotime($registro->hora_termino_segunda) > strtotime($help->hora_termino_segunda))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->terca == 's'):
                    if(
                        (strtotime($registro->hora_inicio_terca) < strtotime($help->hora_inicio_terca)) && (strtotime($registro->hora_termino_terca) <= strtotime($help->hora_inicio_terca)) ||
                        (strtotime($registro->hora_inicio_terca) >= strtotime($help->hora_termino_terca)) && (strtotime($registro->hora_termino_terca) > strtotime($help->hora_termino_terca))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->quarta == 's'):
                    if(
                        (strtotime($registro->hora_inicio_quarta) < strtotime($help->hora_inicio_quarta)) && (strtotime($registro->hora_termino_quarta) <= strtotime($help->hora_inicio_quarta)) ||
                        (strtotime($registro->hora_inicio_quarta) >= strtotime($help->hora_termino_quarta)) && (strtotime($registro->hora_termino_quarta) > strtotime($help->hora_termino_quarta))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->quinta == 's'):
                    if(
                        (strtotime($registro->hora_inicio_quinta) < strtotime($help->hora_inicio_quinta)) && (strtotime($registro->hora_termino_quinta) <= strtotime($help->hora_inicio_quinta)) ||
                        (strtotime($registro->hora_inicio_quinta) >= strtotime($help->hora_termino_quinta)) && (strtotime($registro->hora_termino_quinta) > strtotime($help->hora_termino_quinta))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->sexta == 's'):
                    if(
                        (strtotime($registro->hora_inicio_sexta) < strtotime($help->hora_inicio_sexta)) && (strtotime($registro->hora_termino_sexta) <= strtotime($help->hora_inicio_sexta)) ||
                        (strtotime($registro->hora_inicio_sexta) >= strtotime($help->hora_termino_sexta)) && (strtotime($registro->hora_termino_sexta) > strtotime($help->hora_termino_sexta))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->sabado == 's'):
                    if(
                        (strtotime($registro->hora_inicio_sabado) < strtotime($help->hora_inicio_sabado)) && (strtotime($registro->hora_termino_sabado) <= strtotime($help->hora_inicio_sabado)) ||
                        (strtotime($registro->hora_inicio_sabado) >= strtotime($help->hora_termino_sabado)) && (strtotime($registro->hora_termino_sabado) > strtotime($help->hora_termino_sabado))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->domingo == 's'):
                    if(
                        (strtotime($registro->hora_inicio_domingo) < strtotime($help->hora_inicio_domingo)) && (strtotime($registro->hora_termino_domingo) <= strtotime($help->hora_inicio_domingo)) ||
                        (strtotime($registro->hora_inicio_domingo) >= strtotime($help->hora_termino_domingo)) && (strtotime($registro->hora_termino_domingo) > strtotime($help->hora_termino_domingo))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                /*
                echo json_encode(array('status' => 'erro_data', 'data' => $data->format('d/m/Y')));
                exit();
                */
            endif;

        endforeach;

    endif;

    $qtd = ceil($dados['quantidade_helps']/count($dias_selecionados));
    $j = 0;
    if(!empty($dias_selecionados)):
        for($i=1;$i<=$qtd;$i++):
        foreach($dias_selecionados as $dias_selecionado):
            switch($dias_selecionado)
            {
                case 'segunda':
                    $nome_dia = 'monday';
                    break;

                case 'terca':
                    $nome_dia = 'tuesday';
                    break;

                case 'quarta':
                    $nome_dia = 'wednesday';
                    break;

                case 'quinta':
                    $nome_dia = 'thursday';
                    break;

                case 'sexta':
                    $nome_dia = 'friday';
                    break;

                case 'sabado':
                    $nome_dia = 'saturday';
                    break;

                case 'domingo':
                    $nome_dia = 'sunday';
                    break;
            }

            if($j < 1):
                $verifica_datas = Aulas_Help::find_all_by_id_colega_and_data($dados['id_colega'], $data->format('Y-m-d'));

                if(!empty($verifica_datas)):

                    foreach($verifica_datas as $verifica_data):

                        $help = Helps::find($verifica_data->id_help);

                        if($help->status == 's'):
                            /*Verificando qual o dia da semana e horários*/

                            if($registro->segunda == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_segunda) < strtotime($help->hora_inicio_segunda)) && (strtotime($registro->hora_termino_segunda) <= strtotime($help->hora_inicio_segunda)) ||
                                    (strtotime($registro->hora_inicio_segunda) >= strtotime($help->hora_termino_segunda)) && (strtotime($registro->hora_termino_segunda) > strtotime($help->hora_termino_segunda))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->terca == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_terca) < strtotime($help->hora_inicio_terca)) && (strtotime($registro->hora_termino_terca) <= strtotime($help->hora_inicio_terca)) ||
                                    (strtotime($registro->hora_inicio_terca) >= strtotime($help->hora_termino_terca)) && (strtotime($registro->hora_termino_terca) > strtotime($help->hora_termino_terca))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->quarta == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_quarta) < strtotime($help->hora_inicio_quarta)) && (strtotime($registro->hora_termino_quarta) <= strtotime($help->hora_inicio_quarta)) ||
                                    (strtotime($registro->hora_inicio_quarta) >= strtotime($help->hora_termino_quarta)) && (strtotime($registro->hora_termino_quarta) > strtotime($help->hora_termino_quarta))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->quinta == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_quinta) < strtotime($help->hora_inicio_quinta)) && (strtotime($registro->hora_termino_quinta) <= strtotime($help->hora_inicio_quinta)) ||
                                    (strtotime($registro->hora_inicio_quinta) >= strtotime($help->hora_termino_quinta)) && (strtotime($registro->hora_termino_quinta) > strtotime($help->hora_termino_quinta))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->sexta == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_sexta) < strtotime($help->hora_inicio_sexta)) && (strtotime($registro->hora_termino_sexta) <= strtotime($help->hora_inicio_sexta)) ||
                                    (strtotime($registro->hora_inicio_sexta) >= strtotime($help->hora_termino_sexta)) && (strtotime($registro->hora_termino_sexta) > strtotime($help->hora_termino_sexta))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->sabado == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_sabado) < strtotime($help->hora_inicio_sabado)) && (strtotime($registro->hora_termino_sabado) <= strtotime($help->hora_inicio_sabado)) ||
                                    (strtotime($registro->hora_inicio_sabado) >= strtotime($help->hora_termino_sabado)) && (strtotime($registro->hora_termino_sabado) > strtotime($help->hora_termino_sabado))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->domingo == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_domingo) < strtotime($aula_existente->hora_inicio_domingo)) && (strtotime($registro->hora_termino_domingo) <= strtotime($aula_existente->hora_inicio_domingo)) ||
                                    (strtotime($registro->hora_inicio_domingo) >= strtotime($aula_existente->hora_termino_domingo)) && (strtotime($registro->hora_termino_domingo) > strtotime($aula_existente->hora_termino_domingo))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            /*Fim Verificando qual o dia da semana e horários*/
                        endif;

                        /*
                        echo json_encode(array('status' => 'erro_data', 'data' => $data->format('d/m/Y')));
                        exit();
                        */

                    endforeach;

                endif;

            endif;

            if($j >= 1):
                $data->modify('next '.$nome_dia);

                $verifica_datas = Aulas_Help::find_all_by_id_colega_and_data($dados['id_colega'], $data->format('Y-m-d'));
                if(!empty($verifica_datas)):

                    foreach($verifica_datas as $verifica_data):

                        $help = Helps::find($verifica_data->id_help);

                        if($help->status == 's'):
                            /*Verificando qual o dia da semana e horários*/

                            if($registro->segunda == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_segunda) < strtotime($help->hora_inicio_segunda)) && (strtotime($registro->hora_termino_segunda) <= strtotime($help->hora_inicio_segunda)) ||
                                    (strtotime($registro->hora_inicio_segunda) >= strtotime($help->hora_termino_segunda)) && (strtotime($registro->hora_termino_segunda) > strtotime($help->hora_termino_segunda))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->terca == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_terca) < strtotime($help->hora_inicio_terca)) && (strtotime($registro->hora_termino_terca) <= strtotime($help->hora_inicio_terca)) ||
                                    (strtotime($registro->hora_inicio_terca) >= strtotime($help->hora_termino_terca)) && (strtotime($registro->hora_termino_terca) > strtotime($help->hora_termino_terca))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->quarta == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_quarta) < strtotime($help->hora_inicio_quarta)) && (strtotime($registro->hora_termino_quarta) <= strtotime($help->hora_inicio_quarta)) ||
                                    (strtotime($registro->hora_inicio_quarta) >= strtotime($help->hora_termino_quarta)) && (strtotime($registro->hora_termino_quarta) > strtotime($help->hora_termino_quarta))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->quinta == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_quinta) < strtotime($help->hora_inicio_quinta)) && (strtotime($registro->hora_termino_quinta) <= strtotime($help->hora_inicio_quinta)) ||
                                    (strtotime($registro->hora_inicio_quinta) >= strtotime($help->hora_termino_quinta)) && (strtotime($registro->hora_termino_quinta) > strtotime($help->hora_termino_quinta))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->sexta == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_sexta) < strtotime($help->hora_inicio_sexta)) && (strtotime($registro->hora_termino_sexta) <= strtotime($help->hora_inicio_sexta)) ||
                                    (strtotime($registro->hora_inicio_sexta) >= strtotime($help->hora_termino_sexta)) && (strtotime($registro->hora_termino_sexta) > strtotime($help->hora_termino_sexta))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->sabado == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_sabado) < strtotime($help->hora_inicio_sabado)) && (strtotime($registro->hora_termino_sabado) <= strtotime($help->hora_inicio_sabado)) ||
                                    (strtotime($registro->hora_inicio_sabado) >= strtotime($help->hora_termino_sabado)) && (strtotime($registro->hora_termino_sabado) > strtotime($help->hora_termino_sabado))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            if($registro->domingo == 's'):
                                if(
                                    (strtotime($registro->hora_inicio_domingo) < strtotime($aula_existente->hora_inicio_domingo)) && (strtotime($registro->hora_termino_domingo) <= strtotime($aula_existente->hora_inicio_domingo)) ||
                                    (strtotime($registro->hora_inicio_domingo) >= strtotime($aula_existente->hora_termino_domingo)) && (strtotime($registro->hora_termino_domingo) > strtotime($aula_existente->hora_termino_domingo))
                                ):
                                    echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                    exit();
                                endif;
                            endif;

                            /*Fim Verificando qual o dia da semana e horários*/
                        endif;

                        /*
                        echo json_encode(array('status' => 'erro_data', 'data' => $data->format('d/m/Y')));
                        exit();
                        */
                    endforeach;

                endif;

            endif;


            $j++;
        endforeach;
        endfor;
    endif;
    /*Termino da verificação de datas*/


    /*enviando e-mail para o gerente*/
    include_once('../../classes/PHPMailer/class.phpmailer.php');

    try{
        $configuracao_email = Envio_Emails::find(1);
    } catch (Exception $e) {
        $configuracao_email = '';
    }

    $unidade = Unidades::find($dados['id_unidade']);
    $gerente = Usuarios::find($unidade->id_gerente);
    $aluno = Alunos::find($dados['id_aluno']);

    $instrutor = Colegas::find($dados['id_colega']);

    $mensagem  = "Olá {$gerente->nome}, um novo HELP para aprovação requer sua atenção. ";
    $mensagem .= "O HELP é para o aluno(a) {$aluno->nome} com o instrutor(a) {$instrutor->nome}, tendo início previsto para o dia {$dados['data_inicio']}";

    $mail = new PHPMailer();

    $mail->SMTPDebug = 1;
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
    //$mail->Port = $configuracao_email->porta_smtp;
    $mail->Username = $configuracao_email->usuario_smtp; // Usuário do servidor SMTP
    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

    $mail->From = $configuracao_email->email;
    $mail->FromName = 'Agendamento de HELP - IOWA Idiomas';

    $mail->AddAddress($gerente->email, $gerente->nome);
    //$mail->AddBCC($aluno->email, $aluno->nome);

    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    $mail->Subject  = 'Agendamento de HELP - IOWA Idiomas'; // Assunto da mensagem
    $mail->Body = $mensagem;

    if(!$mail->Send()):

        echo json_encode(array('status' => 'erro'));

    else:

        /*Salvando dados do HELP*/
        $registro->id_unidade = $dados['id_unidade'];
        $registro->id_empresa = $dados['id_empresa'];
        $registro->id_colega = $dados['id_colega'];
        $registro->tipo_help = $dados['tipo_help'];
        $registro->id_aluno = $dados['id_aluno'];
        //$registro->data_inicio = implode('-', array_reverse(explode('/', $dados['data_inicio'])));

        $data_inicio = implode('-', array_reverse(explode('/', $dados['data_inicio'])));
        $data = new DateTime($data_inicio);

        if($primeira_data[date('w', strtotime($data_inicio))] != $dias_selecionados[0]):
            $data->modify('next '.$proxima_data[array_search($dias_selecionados[0], $primeira_data)]);
        endif;

        $registro->data_inicio = $data->format('Y-m-d');
        $registro->quantidade_helps = $dados['quantidade_helps'];
        $registro->status = 'p';

        /*Horarios*/
        $registro->hora_inicio_segunda = $dados['hora_inicio_segunda'];
        $registro->hora_termino_segunda = $dados['hora_termino_segunda'];

        $registro->hora_inicio_terca = $dados['hora_inicio_terca'];
        $registro->hora_termino_terca = $dados['hora_termino_terca'];

        $registro->hora_inicio_quarta = $dados['hora_inicio_quarta'];
        $registro->hora_termino_quarta = $dados['hora_termino_quarta'];

        $registro->hora_inicio_quinta = $dados['hora_inicio_quinta'];
        $registro->hora_termino_quinta = $dados['hora_termino_quinta'];

        $registro->hora_inicio_sexta = $dados['hora_inicio_sexta'];
        $registro->hora_termino_sexta = $dados['hora_termino_sexta'];

        $registro->hora_inicio_sabado = $dados['hora_inicio_sabado'];
        $registro->hora_termino_sabado = $dados['hora_termino_sabado'];

        $registro->hora_inicio_domingo = $dados['hora_inicio_domingo'];
        $registro->hora_termino_domingo = $dados['hora_termino_domingo'];
        /*Horarios*/

        $registro->save();

        $aluno = Alunos::find($registro->id_aluno);
        $colega = Colegas::find($registro->id_colega);
        ///adicionaHistorico(idUsuario(), idColega(), 'Help', 'Alteração', 'O Help do aluno '.$aluno->nome.' e instrutor '.$colega->nome.' com início em '.$registro->data_inicio->format('d/m/Y').' e término em '.$registro->data_termino->format('d/m/Y').' do tipo '.$registro->tipo_help.' foi alterado.');
        adicionaHistorico(idUsuario(), idColega(), 'Help', 'Alteração', 'O Help do aluno '.$aluno->nome.' e instrutor '.$colega->nome.' com início em '.$registro->data_inicio->format('d/m/Y').' do tipo '.$registro->tipo_help.' foi alterado.');

        echo json_encode(array('status' => 'ok'));

    endif;

    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

endif;


if($dados['acao'] == 'aprovar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Aprovar Help', 'a');

    /*Verificando dias da semana*/
    $dias = array('segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo');

    foreach($dias as $i => $dia):
        if($registro->$dia == 's'):
            $dias_semana++;
            $dias_selecionados[] = $dia;
        endif;
    endforeach;

    $data_inicio = $registro->data_inicio->format('Y-m-d');
    $data = new DateTime($data_inicio);
    $verifica_data = new DateTime($data_inicio);
    $qtd = ceil($registro->quantidade_helps/count($dias_selecionados));

    /*Verificando se as datas estão disponíveis de datas*/
    if(Aulas_Help::find_all_by_id_colega_and_data($registro->id_colega, $verifica_data->format('Y-m-d'))):

        $aulas_existentes = Aulas_Help::find_all_by_id_colega_and_data($registro->id_colega, $verifica_data->format('Y-m-d'));
        foreach($aulas_existentes as $aula_existente):

            $help = Helps::find($aula_existente->id_help);

            if($help->status == 'a'):
                /*Verificando qual o dia da semana e horários*/

                if($registro->segunda == 's'):
                    if(
                        (strtotime($registro->hora_inicio_segunda) < strtotime($help->hora_inicio_segunda)) && (strtotime($registro->hora_termino_segunda) <= strtotime($help->hora_inicio_segunda)) ||
                        (strtotime($registro->hora_inicio_segunda) >= strtotime($help->hora_termino_segunda)) && (strtotime($registro->hora_termino_segunda) > strtotime($help->hora_termino_segunda))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->terca == 's'):
                    if(
                        (strtotime($registro->hora_inicio_terca) < strtotime($help->hora_inicio_terca)) && (strtotime($registro->hora_termino_terca) <= strtotime($help->hora_inicio_terca)) ||
                        (strtotime($registro->hora_inicio_terca) >= strtotime($help->hora_termino_terca)) && (strtotime($registro->hora_termino_terca) > strtotime($help->hora_termino_terca))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->quarta == 's'):
                    if(
                        (strtotime($registro->hora_inicio_quarta) < strtotime($help->hora_inicio_quarta)) && (strtotime($registro->hora_termino_quarta) <= strtotime($help->hora_inicio_quarta)) ||
                        (strtotime($registro->hora_inicio_quarta) >= strtotime($help->hora_termino_quarta)) && (strtotime($registro->hora_termino_quarta) > strtotime($help->hora_termino_quarta))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->quinta == 's'):
                    if(
                        (strtotime($registro->hora_inicio_quinta) < strtotime($help->hora_inicio_quinta)) && (strtotime($registro->hora_termino_quinta) <= strtotime($help->hora_inicio_quinta)) ||
                        (strtotime($registro->hora_inicio_quinta) >= strtotime($help->hora_termino_quinta)) && (strtotime($registro->hora_termino_quinta) > strtotime($help->hora_termino_quinta))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->sexta == 's'):
                    if(
                        (strtotime($registro->hora_inicio_sexta) < strtotime($help->hora_inicio_sexta)) && (strtotime($registro->hora_termino_sexta) <= strtotime($help->hora_inicio_sexta)) ||
                        (strtotime($registro->hora_inicio_sexta) >= strtotime($help->hora_termino_sexta)) && (strtotime($registro->hora_termino_sexta) > strtotime($help->hora_termino_sexta))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->sabado == 's'):
                    if(
                        (strtotime($registro->hora_inicio_sabado) < strtotime($help->hora_inicio_sabado)) && (strtotime($registro->hora_termino_sabado) <= strtotime($help->hora_inicio_sabado)) ||
                        (strtotime($registro->hora_inicio_sabado) >= strtotime($help->hora_termino_sabado)) && (strtotime($registro->hora_termino_sabado) > strtotime($help->hora_termino_sabado))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                if($registro->domingo == 's'):
                    if(
                        (strtotime($registro->hora_inicio_domingo) < strtotime($help->hora_inicio_domingo)) && (strtotime($registro->hora_termino_domingo) <= strtotime($help->hora_inicio_domingo)) ||
                        (strtotime($registro->hora_inicio_domingo) >= strtotime($help->hora_termino_domingo)) && (strtotime($registro->hora_termino_domingo) > strtotime($help->hora_termino_domingo))
                    ):
                        $status = 'ok';
                    else:
                        echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                        exit();
                    endif;
                endif;

                /*Fim Verificando qual o dia da semana e horários*/
                //exit();
            endif;

        endforeach;

    endif;

    $j = 0;
    if(!empty($qtd) && $qtd != 0):
        for($i=1;$i<=$qtd;$i++):
            if(!empty($dias_selecionados)):
                foreach($dias_selecionados as $dias_selecionado):
                    switch($dias_selecionado)
                    {
                        case 'segunda':
                            $nome_dia = 'monday';
                            break;

                        case 'terca':
                            $nome_dia = 'tuesday';
                            break;

                        case 'quarta':
                            $nome_dia = 'wednesday';
                            break;

                        case 'quinta':
                            $nome_dia = 'thursday';
                            break;

                        case 'sexta':
                            $nome_dia = 'friday';
                            break;

                        case 'sabado':
                            $nome_dia = 'saturday';
                            break;

                        case 'domingo':
                            $nome_dia = 'sunday';
                            break;
                    }

                    if($j < 1):
                        if(Aulas_Help::find_all_by_id_colega_and_data($registro->id_colega, $verifica_data->format('Y-m-d'))):
                            $aulas_existentes = Aulas_Help::find_all_by_id_colega_and_data($registro->id_colega, $verifica_data->format('Y-m-d'));

                            foreach($aulas_existentes as $aula_existente):

                                $help = Helps::find($aula_existente->id_help);

                                if($help->status == 'a'):
                                    /*Verificando qual o dia da semana e horários*/

                                    if($registro->segunda == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_segunda) < strtotime($help->hora_inicio_segunda)) && (strtotime($registro->hora_termino_segunda) <= strtotime($help->hora_inicio_segunda)) ||
                                            (strtotime($registro->hora_inicio_segunda) >= strtotime($help->hora_termino_segunda)) && (strtotime($registro->hora_termino_segunda) > strtotime($help->hora_termino_segunda))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->terca == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_terca) < strtotime($help->hora_inicio_terca)) && (strtotime($registro->hora_termino_terca) <= strtotime($help->hora_inicio_terca)) ||
                                            (strtotime($registro->hora_inicio_terca) >= strtotime($help->hora_termino_terca)) && (strtotime($registro->hora_termino_terca) > strtotime($help->hora_termino_terca))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->quarta == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_quarta) < strtotime($help->hora_inicio_quarta)) && (strtotime($registro->hora_termino_quarta) <= strtotime($help->hora_inicio_quarta)) ||
                                            (strtotime($registro->hora_inicio_quarta) >= strtotime($help->hora_termino_quarta)) && (strtotime($registro->hora_termino_quarta) > strtotime($help->hora_termino_quarta))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->quinta == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_quinta) < strtotime($help->hora_inicio_quinta)) && (strtotime($registro->hora_termino_quinta) <= strtotime($help->hora_inicio_quinta)) ||
                                            (strtotime($registro->hora_inicio_quinta) >= strtotime($help->hora_termino_quinta)) && (strtotime($registro->hora_termino_quinta) > strtotime($help->hora_termino_quinta))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->sexta == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_sexta) < strtotime($help->hora_inicio_sexta)) && (strtotime($registro->hora_termino_sexta) <= strtotime($help->hora_inicio_sexta)) ||
                                            (strtotime($registro->hora_inicio_sexta) >= strtotime($help->hora_termino_sexta)) && (strtotime($registro->hora_termino_sexta) > strtotime($help->hora_termino_sexta))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->sabado == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_sabado) < strtotime($help->hora_inicio_sabado)) && (strtotime($registro->hora_termino_sabado) <= strtotime($help->hora_inicio_sabado)) ||
                                            (strtotime($registro->hora_inicio_sabado) >= strtotime($help->hora_termino_sabado)) && (strtotime($registro->hora_termino_sabado) > strtotime($help->hora_termino_sabado))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->domingo == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_domingo) < strtotime($help->hora_inicio_domingo)) && (strtotime($registro->hora_termino_domingo) <= strtotime($help->hora_inicio_domingo)) ||
                                            (strtotime($registro->hora_inicio_domingo) >= strtotime($help->hora_termino_domingo)) && (strtotime($registro->hora_termino_domingo) > strtotime($help->hora_termino_domingo))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    /*Fim Verificando qual o dia da semana e horários*/
                                    //exit();
                                endif;

                            endforeach;

                        endif;

                    endif;

                    if($j >= 1):
                        $verifica_data->modify('next '.$nome_dia);

                        if(Aulas_Help::find_all_by_id_colega_and_data($registro->id_colega, $verifica_data->format('Y-m-d'))):

                            $aulas_existentes = Aulas_Help::find_all_by_id_colega_and_data($registro->id_colega, $verifica_data->format('Y-m-d'));

                            foreach($aulas_existentes as $aula_existente):

                                $help = Helps::find($aula_existente->id_help);

                                if($help->status == 'a'):
                                    /*Verificando qual o dia da semana e horários*/

                                    if($registro->segunda == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_segunda) < strtotime($help->hora_inicio_segunda)) && (strtotime($registro->hora_termino_segunda) <= strtotime($help->hora_inicio_segunda)) ||
                                            (strtotime($registro->hora_inicio_segunda) >= strtotime($help->hora_termino_segunda)) && (strtotime($registro->hora_termino_segunda) > strtotime($help->hora_termino_segunda))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->terca == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_terca) < strtotime($help->hora_inicio_terca)) && (strtotime($registro->hora_termino_terca) <= strtotime($help->hora_inicio_terca)) ||
                                            (strtotime($registro->hora_inicio_terca) >= strtotime($help->hora_termino_terca)) && (strtotime($registro->hora_termino_terca) > strtotime($help->hora_termino_terca))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->quarta == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_quarta) < strtotime($help->hora_inicio_quarta)) && (strtotime($registro->hora_termino_quarta) <= strtotime($help->hora_inicio_quarta)) ||
                                            (strtotime($registro->hora_inicio_quarta) >= strtotime($help->hora_termino_quarta)) && (strtotime($registro->hora_termino_quarta) > strtotime($help->hora_termino_quarta))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->quinta == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_quinta) < strtotime($help->hora_inicio_quinta)) && (strtotime($registro->hora_termino_quinta) <= strtotime($help->hora_inicio_quinta)) ||
                                            (strtotime($registro->hora_inicio_quinta) >= strtotime($help->hora_termino_quinta)) && (strtotime($registro->hora_termino_quinta) > strtotime($help->hora_termino_quinta))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->sexta == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_sexta) < strtotime($help->hora_inicio_sexta)) && (strtotime($registro->hora_termino_sexta) <= strtotime($help->hora_inicio_sexta)) ||
                                            (strtotime($registro->hora_inicio_sexta) >= strtotime($help->hora_termino_sexta)) && (strtotime($registro->hora_termino_sexta) > strtotime($help->hora_termino_sexta))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->sabado == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_sabado) < strtotime($help->hora_inicio_sabado)) && (strtotime($registro->hora_termino_sabado) <= strtotime($help->hora_inicio_sabado)) ||
                                            (strtotime($registro->hora_inicio_sabado) >= strtotime($help->hora_termino_sabado)) && (strtotime($registro->hora_termino_sabado) > strtotime($help->hora_termino_sabado))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    if($registro->domingo == 's'):
                                        if(
                                            (strtotime($registro->hora_inicio_domingo) < strtotime($help->hora_inicio_domingo)) && (strtotime($registro->hora_termino_domingo) <= strtotime($help->hora_inicio_domingo)) ||
                                            (strtotime($registro->hora_inicio_domingo) >= strtotime($help->hora_termino_domingo)) && (strtotime($registro->hora_termino_domingo) > strtotime($help->hora_termino_domingo))
                                        ):
                                            $status = 'ok';
                                        else:
                                            echo json_encode(array('status' => 'erro_data', 'data' => $verifica_data->format('d/m/Y')));
                                            exit();
                                        endif;
                                    endif;

                                    /*Fim Verificando qual o dia da semana e horários*/
                                    //exit();
                                endif;

                            endforeach;

                        endif;
                    endif;


                    $j++;
                endforeach;
            endif;
        endfor;
    endif;
    /*Termino da verificação de datas*/


    /*--------------------------------------------------------------------------------------------*/
    /*Após verificar se as datas estão livre, ocorre os envios dos emails para o Aluno e Instrutor*/
    $aluno = Alunos::find($registro->id_aluno);
    $instrutor = Colegas::find($registro->id_colega);

    include_once('../../classes/PHPMailer/class.phpmailer.php');

    try{
        $configuracao_email = Envio_Emails::find(1);
    } catch (Exception $e) {
        $configuracao_email = '';
    }


    $dias_horarios = array();
    /*Pegando data e horarios*/
    if($registro->segunda == 's'):
        $dias_horarios['Segunda-Feira']['inicio'] = $registro->hora_inicio_segunda;
        $dias_horarios['Segunda-Feira']['termino'] = $registro->hora_termino_segunda;
    endif;

    if($registro->terca == 's'):
        $dias_horarios['Terca-Feira']['inicio'] = $registro->hora_inicio_terca;
        $dias_horarios['Terca-Feira']['termino'] = $registro->hora_termino_terca;
    endif;

    if($registro->quarta == 's'):
        $dias_horarios['Quarta-Feira']['inicio'] = $registro->hora_inicio_quarta;
        $dias_horarios['Quarta-Feira']['termino'] = $registro->hora_termino_quarta;
    endif;

    if($registro->quinta == 's'):
        $dias_horarios['Quinta-Feira']['inicio'] = $registro->hora_inicio_quinta;
        $dias_horarios['Quinta-Feira']['termino'] = $registro->hora_termino_quinta;
    endif;

    if($registro->sexta == 's'):
        $dias_horarios['Sexta-Feira']['inicio'] = $registro->hora_inicio_sexta;
        $dias_horarios['Sexta-Feira']['termino'] = $registro->hora_termino_sexta;
    endif;

    if($registro->sabado == 's'):
        $dias_horarios['Sábado']['inicio'] = $registro->hora_inicio_sabado;
        $dias_horarios['Sábado']['termino'] = $registro->hora_termino_sabado;
    endif;

    if($registro->domingo == 's'):
        $dias_horarios['Domingo']['inicio'] = $registro->hora_inicio_domingo;
        $dias_horarios['Domingo']['termino'] = $registro->hora_termino_domingo;
    endif;

    if(!empty($dias_horarios)):
        $horario = '';
        foreach($dias_horarios as $i => $v):
            $horario .= $i.' das '.$v['inicio'].' às '.$v['termino'] . ' - ';
        endforeach;
    endif;

    /*Fim Pegando data e horarios*/


    /*Para o Aluno*/
    $mensagem  = "Olá {$instrutor->nome}, seu HELP com o aluno {$aluno->nome} terá inicio no dia {$registro->data_inicio->format('d-m-Y')}.\r\n";
    $mensagem .= "Horário(s): {$horario}";

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
    $mail->FromName = 'HELP - IOWA Idiomas';

    $mail->AddAddress($aluno->email1, $aluno->nome);
    //$mail->AddBCC($aluno->email, $aluno->nome);

    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    $mail->Subject  = 'HELP - IOWA Idiomas'; // Assunto da mensagem
    $mail->Body = $mensagem;

    $mail->Send();


    /*Para o Instrutor*/
    $mensagem  = "Olá {$instrutor->nome}, seu HELP com o aluno {$aluno->nome} terá inicio no dia {$registro->data_inicio->format('d-m-Y')}.\r\n";
    $mensagem .= "Horário(s): {$horario}";

    $mail = new PHPMailer();

    //$mail->SMTPDebug = 1;
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
    //$mail->Port = $configuracao_email->porta_smtp;
    $mail->Username = $configuracao_email->usuario_smtp; // Usuário do servidor SMTP
    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

    $mail->From = $configuracao_email->email;
    $mail->FromName = 'HELP - IOWA Idiomas';

    $mail->AddAddress($instrutor->email, $instrutor->nome);
    //$mail->AddBCC($aluno->email, $aluno->nome);

    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    $mail->Subject  = 'HELP - IOWA Idiomas'; // Assunto da mensagem
    $mail->Body = $mensagem;

    $mail->Send();

    /*Fim do envio dos emails*/
    /*--------------------------------------------------------------------------------------------*/

    $j = 0;
    if(!empty($qtd) && $qtd != 0):
        $numero_aula = 1;
        for($i=1;$i<=$qtd;$i++):
            if(!empty($dias_selecionados)):
                foreach($dias_selecionados as $dias_selecionado):

                    /*No primeiro laço a data inicial é mantida*/
                    if($j < 1):
                        $aula = new Aulas_Help();
                        $aula->id_help = $registro->id;
                        $aula->id_colega = $registro->id_colega;
                        $aula->data = $data->format('Y-m-d');
                        $aula->id_situacao_aula = 0;
                        $aula->numero_aula = $numero_aula;
                        $aula->save();
                    endif;

                    switch($dias_selecionado)
                    {
                        case 'segunda':
                            $nome_dia = 'monday';
                            break;

                        case 'terca':
                            $nome_dia = 'tuesday';
                            break;

                        case 'quarta':
                            $nome_dia = 'wednesday';
                            break;

                        case 'quinta':
                            $nome_dia = 'thursday';
                            break;

                        case 'sexta':
                            $nome_dia = 'friday';
                            break;

                        case 'sabado':
                            $nome_dia = 'saturday';
                            break;

                        case 'domingo':
                            $nome_dia = 'sunday';
                            break;
                    }

                    /*Após o primeiro laço começa a ver qual a próxima data a partir da data inicial*/
                    if($j >= 1):
                        $data->modify('next '.$nome_dia);

                        $aula = new Aulas_Help();
                        $aula->id_help = $registro->id;
                        $aula->id_colega = $registro->id_colega;
                        $aula->data = $data->format('Y-m-d');
                        $aula->id_situacao_aula = 0;
                        $aula->numero_aula = $numero_aula;
                        $aula->save();
                    endif;

                    $numero_aula++;
                    $j++;

                endforeach;

                $registro->data_termino = $data->format('Y-m-d');
                $registro->save();

            endif;
        endfor;
    endif;

    $registro->status = 'a';
    $registro->save();

    $aluno = Alunos::find($registro->id_aluno);
    $colega = Colegas::find($registro->id_colega);
    adicionaHistorico(idUsuario(), idColega(), 'Help', 'Alteração', 'O Help do aluno '.$aluno->nome.' e instrutor '.$colega->nome.' com início em '.$registro->data_inicio->format('d/m/Y').' e término em '.$registro->data_termino->format('d/m/Y').' do tipo '.$registro->tipo_help.' foi aprovado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'reprovar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Cancelar Help', 'a');

    $registro->status = 'i';
    $registro->save();

    $aluno = Alunos::find($registro->id_aluno);
    $colega = Colegas::find($registro->id_colega);
    ///adicionaHistorico(idUsuario(), idColega(), 'Help', 'Alteração', 'O Help do aluno '.$aluno->nome.' e instrutor '.$colega->nome.' com início em '.$registro->data_inicio->format('d/m/Y').' e término em '.$registro->data_termino->format('d/m/Y').' do tipo '.$registro->tipo_help.' foi reprovado.');
    adicionaHistorico(idUsuario(), idColega(), 'Help', 'Alteração', 'O Help do aluno '.$aluno->nome.' e instrutor '.$colega->nome.' com início em '.$registro->data_inicio->format('d/m/Y').' do tipo '.$registro->tipo_help.' foi reprovado.');

    echo json_encode(array('status' => 'ok'));

endif;


/*Diário de Classe*/

if($dados['acao'] == 'verifica-aula'):

    $aula = Aulas_Help::find($dados['id_aula']);
    //$aula->id_nome_produto = $registro->id_produto;

    $dia_semana_numero = date('w', strtotime($aula->data->format('Y-m-d')));

    switch($dia_semana_numero){
        case 0:
            if(empty($aula->hora_inicio)):
                $aula->hora_inicio = $registro->hora_inicio_domingo;
            endif;

            if(empty($aula->hora_termino)):
                $aula->hora_termino = $registro->hora_termino_domingo;
            endif;

            break;
        case 1:
            if(empty($aula->hora_inicio)):
                $aula->hora_inicio = $registro->hora_inicio_segunda;
            endif;

            if(empty($aula->hora_termino)):
                $aula->hora_termino = $registro->hora_termino_segunda;
            endif;
            break;
        case 2:
            if(empty($aula->hora_inicio)):
                $aula->hora_inicio = $registro->hora_inicio_terca;
            endif;

            if(empty($aula->hora_termino)):
                $aula->hora_termino = $registro->hora_termino_terca;
            endif;
            break;
        case 3:
            if(empty($aula->hora_inicio)):
                $aula->hora_inicio = $registro->hora_inicio_quarta;
            endif;

            if(empty($aula->hora_termino)):
                $aula->hora_termino = $registro->hora_termino_quarta;
            endif;
            break;
        case 4:
            if(empty($aula->hora_inicio)):
                $aula->hora_inicio = $registro->hora_inicio_quinta;
            endif;

            if(empty($aula->hora_termino)):
                $aula->hora_termino = $registro->hora_termino_quinta;
            endif;
            break;
        case 5:
            if(empty($aula->hora_inicio)):
                $aula->hora_inicio = $registro->hora_inicio_sexta;
            endif;

            if(empty($aula->hora_termino)):
                $aula->hora_termino = $registro->hora_termino_sexta;
            endif;
            break;
        case 6:
            if(empty($aula->hora_inicio)):
                $aula->hora_inicio = $registro->hora_inicio_sabado;
            endif;

            if(empty($aula->hora_termino)):
                $aula->hora_termino = $registro->hora_termino_sabado;
            endif;
            break;

    }

    //$aula->hora_inicio = $registro->hora_inicio_quarta;
    //$aula->hora_termino = $registro->hora_termino_quarta;
    $aula->save();

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'salvar-dados-aula'):

    $id_unidade = Unidades::find($registro->id_unidade);

    $aula = Aulas_Help::find($dados['id_aula']);
    $aula->data = implode('-', array_reverse(explode('/', $dados['data'])));
    $aula->id_situacao_aula = $dados['id_situacao_aula'];
    $aula->id_colega = $dados['id_colega'];
    $aula->conteudo_dado = $dados['conteudo_dado'];
    $aula->hora_inicio = $dados['hora_inicio'];
    $aula->hora_termino = $dados['hora_termino'];

    /*Calculando o valor da Aula*/
    $situacao_aula = Situacao_Aulas::find($aula->id_situacao_aula);
    if($situacao_aula->pagar == 's'):
        $periodo = intervalo($aula->hora_inicio, $aula->hora_termino);
        $periodo_decimal = decimalHours($periodo);
        $valor_aula = $id_unidade->valor_hora_aula_help*$periodo_decimal;

        $aula->valor_hora_aula = $valor_aula;
    else:
        $aula->valor_hora_aula = 0;
    endif;
    //$aula->tarefa = $dados['tarefa'];
    $aula->save();

    $aluno = Alunos::find($registro->id_aluno);
    $colega = Colegas::find($registro->id_colega);
    adicionaHistorico(idUsuario(), idColega(), 'Help - Diário de Classe', 'Alteração', 'A aula do dia '.$aula->data->format('d/m/Y').' do Help do aluno '.$aluno->nome.' e instrutor '.$colega->nome.' com início em '.$registro->data_inicio->format('d/m/Y').' e término em '.$registro->data_termino->format('d/m/Y').' do tipo '.$registro->tipo_help.' teve seus dados alterados.');

    echo json_encode(array('status' => 'ok'));

endif;
