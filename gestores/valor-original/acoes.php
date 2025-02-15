<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Valor Original da Parcela', 'a');

    if(is_array($dados['valor_parcela'])):
        foreach ($dados['valor_parcela'] as $id => $valor):
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor);

            $matricula = Matriculas::find($id);

            $aluno = Alunos::find($matricula->id_aluno);
            $turma = Turmas::find($matricula->id_turma);

            adicionaHistorico(idUsuario(), idColega(), 'Valor Original da Parcela', 'Alteração', 'O valor original da parcela do aluno '.$aluno->nome. ' da turma '.$turma->nome.' de R$ '.number_format($matricula->valor_parcela, 2, ',', '.').' para R$ '.number_format($valor, 2, ',', '.').'.');

            $matricula->valor_parcela = $valor;
            $matricula->save();

        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;
