<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Turmas::find($dados['id']);

if($dados['acao'] == 'alterar-dia'):

    /*pegando total de horas do estágio*/
    $id_programacao = $dados['id_produto'];
    $programacao = Nomes_Produtos::find($id_programacao);
    $horas_programacao = $programacao->horas_estagio;
    $horas_semanais = $programacao->horas_semanais;

    /*pegando total de aulas dadas*/
    $turma = Turmas::find($dados['id_turma']);
    $aulas_dadas = Aulas_Turmas::all(['conditions' => ['id_turma = ? and id_situacao_aula <> 0 and id_colega > 0', $turma->id]]);
    $horas_dadas = 0;
    if(!empty($aulas_dadas)):
        foreach ($aulas_dadas as $aula_dada):
            $horas_dadas += arredondaHora(intervalo($aula_dada->hora_inicio, $aula_dada->hora_termino));
        endforeach;
    endif;

    $numero_aulas_dadas = count($aulas_dadas);
    //$horas_dadas = $numero_aulas_dadas*$horas_semanais;

    $horas_restantes = $horas_programacao-$horas_dadas;
    $novo_numero_aulas = ceil($horas_restantes/$horas_semanais);

    /*salvando novos dias da semana da turma*/
    if(is_array($dados['dia-semana'])):

        if(in_array('segunda', $dados['dia-semana'])):
            $turma->segunda = 's';
            $turma->hora_inicio_segunda = $dados['hora_inicio_segunda'];
            $turma->hora_termino_segunda = $dados['hora_termino_segunda'];
            $turma->save();
        else:
            $turma->segunda = 'n';
            $turma->hora_inicio_segunda = '';
            $turma->hora_termino_segunda = '';
            $turma->save();
        endif;

        if(in_array('terca', $dados['dia-semana'])):
            $turma->terca = 's';
            $turma->hora_inicio_terca = $dados['hora_inicio_terca'];
            $turma->hora_termino_terca = $dados['hora_termino_terca'];
            $turma->save();
        else:
            $turma->terca = 'n';
            $turma->hora_inicio_terca = '';
            $turma->hora_termino_terca = '';
            $turma->save();
        endif;

        if(in_array('quarta', $dados['dia-semana'])):
            $turma->quarta = 's';
            $turma->hora_inicio_quarta = $dados['hora_inicio_quarta'];
            $turma->hora_termino_quarta = $dados['hora_termino_quarta'];
            $turma->save();
        else:
            $turma->quarta = 'n';
            $turma->hora_inicio_quarta = '';
            $turma->hora_termino_quarta = '';
            $turma->save();
        endif;

        if(in_array('quinta', $dados['dia-semana'])):
            $turma->quinta = 's';
            $turma->hora_inicio_quinta = $dados['hora_inicio_quinta'];
            $turma->hora_termino_quinta = $dados['hora_termino_quinta'];
            $turma->save();
        else:
            $turma->quinta = 'n';
            $turma->hora_inicio_quinta = '';
            $turma->hora_termino_quinta = '';
            $turma->save();
        endif;

        if(in_array('sexta', $dados['dia-semana'])):
            $turma->sexta = 's';
            $turma->hora_inicio_sexta = $dados['hora_inicio_sexta'];
            $turma->hora_termino_sexta = $dados['hora_termino_sexta'];
            $turma->save();
        else:
            $turma->sexta = 'n';
            $turma->hora_inicio_sexta = '';
            $turma->hora_termino_sexta = '';
            $turma->save();
        endif;

        if(in_array('sabado', $dados['dia-semana'])):
            $turma->sabado = 's';
            $turma->hora_inicio_sabado = $dados['hora_inicio_sabado'];
            $turma->hora_termino_sabado = $dados['hora_termino_sabado'];
            $turma->save();
        else:
            $turma->sabado = 'n';
            $turma->hora_inicio_sabado = '';
            $turma->hora_termino_sabado = '';
            $turma->save();
        endif;

    endif;

    /*apagando aulas não dadas*/
    $aulas_nao_dadas = Aulas_Turmas::all(['conditions' => ['id_turma = ? and id_situacao_aula = 0 and id_colega is null', $turma->id]]);
    if(!empty($aulas_nao_dadas)):
        foreach ($aulas_nao_dadas as $aula_nao_dada):
            $aula_nao_dada->delete();
        endforeach;
    endif;

    /*pegando o numero da ultima aula*/
    $numero_ultima_aula = Aulas_Turmas::find(['conditions' => ['id_turma = ?', $turma->id], 'order' => 'data desc', 'limit' => 1]);
    $numero_aula = $numero_ultima_aula->numero_aula;

    /*gerando novas datas*/
    $data = $numero_ultima_aula->data;

    for($i = 0; $i < $novo_numero_aulas; $i++):
        /*passando os dias da semana a cada laço*/
        if(is_array($dados['dia-semana'])):
            foreach ($dados['dia-semana'] as $index => $dia):

                switch($dia)
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

                $data->modify('next '.$nome_dia);

                $nova_aula = new Aulas_Turmas();
                $nova_aula->id_turma = $turma->id;
                $nova_aula->id_nome_produto = $turma->id_produto;
                $nova_aula->data = $data->format('Y-m-d');
                $nova_aula->id_situacao_aula = 0;
                $nova_aula->numero_aula = $numero_aula;
                $nova_aula->save();

                $numero_aula++;

            endforeach;
        endif;
    endfor;

    /*alterando data de término*/
    $turma->data_termino = $data->format('Y-m-d');
    $turma->save();

    echo json_encode(['status' => 'ok']);

endif;


if($dados['acao'] == 'alterar-programacao'):

    /*pegando total de horas do estágio*/
    $turma = Turmas::find($dados['id_turma']);

    $atual_programacao = Nomes_Produtos::find($turma->id_produto);
    $nova_programacao = Nomes_Produtos::find($dados['id_produto']);

    $horas_programacao = $atual_programacao->horas_estagio;
    $horas_semanais = $atual_programacao->horas_semanais;

    /*pegando total de aulas dadas*/
    $horas_dadas = 0;
    $aulas_dadas = Aulas_Turmas::all(['conditions' => ['id_turma = ? and id_situacao_aula <> 0 and id_colega > 0', $turma->id]]);
    $cont = 0;
    $log_aulas = [];
    if(!empty($aulas_dadas)):
        foreach ($aulas_dadas as $aula_dada):
            $horas_dadas += arredondaHora(intervalo($aula_dada->hora_inicio, $aula_dada->hora_termino));
            $cont++;
            $log_aulas[] = [
                'numero_aula' => $cont,
                'hora_inicio' => $aula_dada->hora_inicio,
                'hora_termino' => $aula_dada->hora_termino,
                'data' => $aula_dada->data->format('d/m/Y'),
                'horas_dadas' => $horas_dadas
            ];
        endforeach;
    endif;

    $numero_aulas_dadas = count($aulas_dadas);
    //$horas_dadas = $numero_aulas_dadas*$horas_semanais;

    $horas_nova_programacao = $nova_programacao->horas_estagio;
    $horas_semanais_nova_programacao = $nova_programacao->horas_semanais;

    $horas_restantes = $horas_nova_programacao-$horas_dadas;
    $novo_numero_aulas = ceil($horas_restantes/$horas_semanais_nova_programacao);


    /*
    echo 'Programação atual = '.$atual_programacao->nome_material.'<br>';
    echo 'Horas total = '.$atual_programacao->horas_estagio.'<br>';
    echo 'Horas semanais = '.$atual_programacao->horas_semanais.'<br>';

    echo 'Programação nova = '.$nova_programacao->nome_material.'<br>';
    echo 'Horas total = '.$nova_programacao->horas_estagio.'<br>';
    echo 'Horas semanais = '.$nova_programacao->horas_semanais.'<br>';

    echo 'Horas dadas = '.$horas_dadas.'<br>';
    echo 'Horas restantes = '.$horas_restantes.'<br>';
    echo 'Numero de aulas = '.$novo_numero_aulas.'<br>';
    */

    $log_detalhes = [
        'programacao_atual' => $atual_programacao->nome_material,
        'horas_estagio_atual' => $atual_programacao->horas_estagio,
        'horas_semainais_atual' => $atual_programacao->horas_semanais,

        'nova_programacao' => $nova_programacao->nome_material,
        'horas_estagio_nova' => $nova_programacao->horas_estagio,
        'horas_semainais_nova' => $nova_programacao->horas_semanais,

        'horas_dadas' => $horas_dadas,
        'horas_restantes' => $horas_restantes,
        'numero_aulas' => $novo_numero_aulas
    ];

    //echo json_encode(['status' => 'ok', 'log_aulas' => $log_aulas, 'log_detalhes' => $log_detalhes]);
    //die();


    /*salvando novo id_produto(Programação)*/
    $turma->id_produto = $dados['id_produto'];
    $turma->save();

    /*salvando novos dias da semana da turma*/
    if(is_array($dados['dia-semana'])):

        if(in_array('segunda', $dados['dia-semana'])):
            $turma->segunda = 's';
            $turma->hora_inicio_segunda = $dados['hora_inicio_segunda'];
            $turma->hora_termino_segunda = $dados['hora_termino_segunda'];
            $turma->save();
        else:
            $turma->segunda = 'n';
            $turma->hora_inicio_segunda = '';
            $turma->hora_termino_segunda = '';
            $turma->save();
        endif;

        if(in_array('terca', $dados['dia-semana'])):
            $turma->terca = 's';
            $turma->hora_inicio_terca = $dados['hora_inicio_terca'];
            $turma->hora_termino_terca = $dados['hora_termino_terca'];
            $turma->save();
        else:
            $turma->terca = 'n';
            $turma->hora_inicio_terca = '';
            $turma->hora_termino_terca = '';
            $turma->save();
        endif;

        if(in_array('quarta', $dados['dia-semana'])):
            $turma->quarta = 's';
            $turma->hora_inicio_quarta = $dados['hora_inicio_quarta'];
            $turma->hora_termino_quarta = $dados['hora_termino_quarta'];
            $turma->save();
        else:
            $turma->quarta = 'n';
            $turma->hora_inicio_quarta = '';
            $turma->hora_termino_quarta = '';
            $turma->save();
        endif;

        if(in_array('quinta', $dados['dia-semana'])):
            $turma->quinta = 's';
            $turma->hora_inicio_quinta = $dados['hora_inicio_quinta'];
            $turma->hora_termino_quinta = $dados['hora_termino_quinta'];
            $turma->save();
        else:
            $turma->quinta = 'n';
            $turma->hora_inicio_quinta = '';
            $turma->hora_termino_quinta = '';
            $turma->save();
        endif;

        if(in_array('sexta', $dados['dia-semana'])):
            $turma->sexta = 's';
            $turma->hora_inicio_sexta = $dados['hora_inicio_sexta'];
            $turma->hora_termino_sexta = $dados['hora_termino_sexta'];
            $turma->save();
        else:
            $turma->sexta = 'n';
            $turma->hora_inicio_sexta = '';
            $turma->hora_termino_sexta = '';
            $turma->save();
        endif;

        if(in_array('sabado', $dados['dia-semana'])):
            $turma->sabado = 's';
            $turma->hora_inicio_sabado = $dados['hora_inicio_sabado'];
            $turma->hora_termino_sabado = $dados['hora_termino_sabado'];
            $turma->save();
        else:
            $turma->sabado = 'n';
            $turma->hora_inicio_sabado = '';
            $turma->hora_termino_sabado = '';
            $turma->save();
        endif;

    endif;

    /*apagando aulas não dadas*/
    $aulas_nao_dadas = Aulas_Turmas::all(['conditions' => ['id_turma = ? and id_situacao_aula = 0 and id_colega is null', $turma->id]]);
    if(!empty($aulas_nao_dadas)):
        foreach ($aulas_nao_dadas as $aula_nao_dada):
            $aula_nao_dada->delete();
        endforeach;
    endif;

    /*pegando o numero da ultima aula*/
    $numero_ultima_aula = Aulas_Turmas::find(['conditions' => ['id_turma = ?', $turma->id], 'order' => 'data desc', 'limit' => 1]);
    $numero_aula = $numero_ultima_aula->numero_aula;

    /*gerando novas datas*/
    $data = $numero_ultima_aula->data;

    for($i = 0; $i < $novo_numero_aulas; $i++):
        /*passando os dias da semana a cada laço*/
        if(is_array($dados['dia-semana'])):
            foreach ($dados['dia-semana'] as $index => $dia):

                switch($dia)
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

                $data->modify('next '.$nome_dia);

                $nova_aula = new Aulas_Turmas();
                $nova_aula->id_turma = $turma->id;
                $nova_aula->id_nome_produto = $turma->id_produto;
                $nova_aula->data = $data->format('Y-m-d');
                $nova_aula->id_situacao_aula = 0;
                $nova_aula->numero_aula = $numero_aula;
                $nova_aula->save();

                $numero_aula++;

            endforeach;
        endif;
    endfor;

    /*alterando data de término*/
    $turma->data_termino = $data->format('Y-m-d');
    $turma->save();

    //echo json_encode(['status' => 'ok', 'log_aulas' => $log_aulas]);
    echo json_encode(['status' => 'ok', 'log_aulas' => $log_aulas, 'log_detalhes' => $log_detalhes]);

endif;



if($dados['acao'] == 'alterar-horario'):

    $turma = Turmas::find($dados['id_turma']);
    /*pegando dia da turma*/
    if($turma->segunda == 's'):
        $turma->hora_inicio_segunda = $dados['hora_inicio_segunda'];
        $turma->hora_termino_segunda = $dados['hora_termino_segunda'];
        $turma->save();
    endif;

    if($turma->terca == 's'):
        $turma->hora_inicio_terca = $dados['hora_inicio_terca'];
        $turma->hora_termino_terca = $dados['hora_termino_terca'];
        $turma->save();
    endif;

    if($turma->quarta == 's'):
        $turma->hora_inicio_quarta = $dados['hora_inicio_quarta'];
        $turma->hora_termino_quarta = $dados['hora_termino_quarta'];
        $turma->save();
    endif;

    if($turma->quinta == 's'):
        $turma->hora_inicio_quinta = $dados['hora_inicio_quinta'];
        $turma->hora_termino_quinta = $dados['hora_termino_quinta'];
        $turma->save();
    endif;

    if($turma->sexta == 's'):
        $turma->hora_inicio_sexta = $dados['hora_inicio_sexta'];
        $turma->hora_termino_sexta = $dados['hora_termino_sexta'];
        $turma->save();
    endif;

    if($turma->sabado == 's'):
        $turma->hora_inicio_sabado = $dados['hora_inicio_sabado'];
        $turma->hora_termino_sabado = $dados['hora_termino_sabado'];
        $turma->save();
    endif;

    echo json_encode(['status' => 'ok']);

endif;


if($dados['acao'] == 'alterar-instrutor'):

    $turma = Turmas::find($dados['id_turma']);
    $turma->id_colega = $dados['id_colega'];
    $turma->save();

    echo json_encode(['status' => 'ok']);

endif;


if($dados['acao'] == 'alterar-valor-hora-aula'):

    $turma = Turmas::find($dados['id_turma']);
    $turma->id_valor_hora_aula = $dados['id_valor_hora_aula'];
    $turma->save();

    echo json_encode(['status' => 'ok']);

endif;


if($dados['acao'] == 'alterar-sistema-notas'):

    $turma = Turmas::find($dados['id_turma']);
    $turma->id_sistema_notas = $dados['id_sistema_notas'];
    $turma->save();

    echo json_encode(['status' => 'ok']);

endif;

function intervalo( $entrada, $saida ) {
    $entrada = explode( ':', $entrada );
    $saida   = explode( ':', $saida );
    $minutos = ( $saida[0] - $entrada[0] ) * 60 + $saida[1] - $entrada[1];
    if( $minutos < 0 ) $minutos += 24 * 60;
    return sprintf( '%d:%d', $minutos / 60, $minutos % 60 );
}

/*Arredondando a nota*/
function arredondaHora($nota){

    $decimal = explode(':', $nota);

    if(!empty($decimal[1])):

        if(($decimal[1] >= 1) and ($decimal[1] <= 30)):
            $decimal[1] = 5;
        elseif(($decimal[1] > 30)):
            $decimal[1] = 0;
            $decimal[0]++;
        endif;

    endif;


    return $decimal[0].'.'.$decimal[1];
    //return $decimal[1];

}
