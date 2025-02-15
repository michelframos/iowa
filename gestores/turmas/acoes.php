<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Turmas::find($dados['id']);

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
    verificaPermissaoPost(idUsuario(), 'Turmas', 'i');

    $registro = new Turmas();
    $registro->nome = 'Nova Turma';
    $registro->segunda = 'n';
    $registro->terca = 'n';
    $registro->quarta = 'n';
    $registro->quinta = 'n';
    $registro->sexta = 'n';
    $registro->sabado = 'n';
    $registro->domingo = 'n';
    $registro->status = 'a';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Inclusão', 'Uma nova Turma foi cadastrada.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;

if($dados['acao'] == 'alterar_adicionar_aulas'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Mostrar Botão Adicionar Aulas', 'p');

    $registro = Turmas::find_by_id($dados['id']);
    $registro->adicionar_aulas = $dados['adicionar_aulas'];
    $registro->save();

    echo json_encode(['status' => 'ok']);

endif;

if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Turmas', 'a');

    $alterar_pogramacao = 'n';


    if($registro->segunda == 'n' && $registro->terca == 'n' && $registro->quarta == 'n' && $registro->quinta == 'n' && $registro->sexta == 'n' && $registro->sabado == 'n' && $registro->domingo == 'n'):
        echo json_encode(array('status' => 'erro_dias'));
        exit();
    endif;


    if($registro->nome != $dados['nome'] || $registro->id_unidade != $dados['id_unidade']):
        /*Verificando duplicidade*/
        if(Turmas::find_by_nome_and_id_unidade($dados['nome'], $dados['id_unidade'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;


    /*----------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------*/
    /*VERIFICANDO SE HAVERÁ ALTERAÇÃO NO CRONOGRAMA DE AULAS*/

            /*Verificando se o professor foi alterado*/
            if(!empty($registro->id_colega)):
                if($registro->id_colega != $dados['id_colega']):
                    $alterar_pogramacao = 's';
                endif;
            endif;

            /*Verificando se a programação foi alterada*/
            if(!empty($registro->id_produto)):
                if($registro->id_produto != $dados['id_produto']):
                    $alterar_pogramacao = 's';
                endif;
            endif;

            /*Verificando se o valor hora aula foi alterado*/
            if(!empty($registro->id_valor_hora_aula)):
                if($registro->id_valor_hora_aula != $dados['id_valor_hora_aula']):
                    $alterar_pogramacao = 's';
                endif;
            endif;

            /*Verificando se algum dia ou horário foi alterado*/
            $dias = array('segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo');

            foreach($dias as $i => $dia):
                if($registro->$dia == 's'):
                    $dias_semana++;
                    $dias_selecionados[] = $dia;
                endif;
            endforeach;

            if(!empty($dias_selecionados)):
                foreach($dias_selecionados as $dia_selecionado):

                    switch($dia_selecionado)
                    {
                        case 'segunda':
                            if(
                                ($registro->segunda == 'n') ||
                                ($registro->hora_inicio_segunda != $dados['hora_inicio_segunda']) ||
                                ($registro->hora_termino_segunda != $dados['hora_termino_segunda'])
                            ):
                                $alterar_pogramacao = 's';
                            endif;
                            break;

                        case 'terca':
                            if(
                                ($registro->terca == 'n') ||
                                ($registro->hora_inicio_terca != $dados['hora_inicio_terca']) ||
                                ($registro->hora_termino_terca != $dados['hora_termino_terca'])
                            ):
                                $alterar_pogramacao = 's';
                            endif;
                            break;

                        case 'quarta':
                            if(
                                ($registro->quarta == 'n') ||
                                ($registro->hora_inicio_quarta != $dados['hora_inicio_quarta']) ||
                                ($registro->hora_termino_quarta != $dados['hora_termino_quarta'])
                            ):
                                $alterar_pogramacao = 's';
                            endif;
                            break;

                        case 'quinta':
                            if(
                                ($registro->quinta == 'n') ||
                                ($registro->hora_inicio_quinta != $dados['hora_inicio_quinta']) ||
                                ($registro->hora_termino_quinta != $dados['hora_termino_quinta'])
                            ):
                                $alterar_pogramacao = 's';
                            endif;
                            break;

                        case 'sexta':
                            if(
                                ($registro->sexta == 'n') ||
                                ($registro->hora_inicio_sexta != $dados['hora_inicio_sexta']) ||
                                ($registro->hora_termino_sexta != $dados['hora_termino_sexta'])
                            ):
                                $alterar_pogramacao = 's';
                            endif;
                            break;

                        case 'sabado':
                            if(
                                ($registro->sabado == 'n') ||
                                ($registro->hora_inicio_sabado != $dados['hora_inicio_sabado']) ||
                                ($registro->hora_termino_sabado != $dados['hora_termino_sabado'])
                            ):
                                $alterar_pogramacao = 's';
                            endif;
                            break;

                        case 'domingo':
                            if(
                                ($registro->domingo == 'n') ||
                                ($registro->hora_inicio_domingo != $dados['hora_inicio_domingo']) ||
                                ($registro->hora_termino_domingo != $dados['hora_termino_domingo'])
                            ):
                                $alterar_pogramacao = 's';
                            endif;
                            break;
                    }

                endforeach;
            endif;

            /*Verificando se o programa atual de aulas será excluído*/
            if($alterar_pogramacao == 's'):
                $aulas_turma = Aulas_Turmas::find_all_by_id_turma_and_id_situacao_aula($registro->id, 0);
                if(!empty($aulas_turma)):
                    foreach($aulas_turma as $aula_turma):
                        $aula_turma->delete();
                    endforeach;
                endif;
            endif;
            /*Fim Verificação*/


            /*Contando aulas dadas*/
            if($alterar_pogramacao == 's'):
                $aulas_turma = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula <> ? and id_situacao_aula <> ? and id_situacao_aula <> ?', $registro->id, 0, 2, 5)));
                if(!empty($aulas_turma)):
                    $aulas_dadas = 0;
                    foreach($aulas_turma as $aula_turma):
                        $data_inicial = $aula_turma->data;
                        $aulas_dadas++;
                    endforeach;
                endif;
            endif;
            /*Fim Contando aulas dadas*/

    /*----------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------*/



    /*Salvando Alterações*/
    $registro->nome = $dados['nome'];
    $registro->id_unidade = $dados['id_unidade'];
    $registro->id_idioma = $dados['id_idioma'];
    $registro->id_produto = $dados['id_produto'];
    $registro->id_sistema_notas = $dados['id_sistema_notas'];
    $registro->id_colega = $dados['id_colega'];
    $registro->id_valor_hora_aula = $dados['id_valor_hora_aula'];

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

    $data_inicio = implode('-', array_reverse(explode('/', $dados['data_inicio'])));
    $registro->data_inicio = $data_inicio;
    $registro->data_termino = $dados['data_termino'];
    $registro->id_situacao_turma = $dados['id_situacao_turma'];
    $registro->limite_faltas = $dados['limite_faltas'];
    dadosAlteracao($registro);
    $registro->save();


    /*Verificando dias da semana*/
    $dias = array('segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo');

    foreach($dias as $i => $dia):
        if($registro->$dia == 's'):
            $dias_semana++;
            $dias_selecionados[] = $dia;
        endif;
    endforeach;

    $produto = Nomes_Produtos::find($registro->id_produto);


    if(empty($aulas_dadas) || $aulas_dadas == 0):
        $qtd = ceil($produto->numero_aulas/$dias_semana);
    else:
        $novo_numero_aulas = $produto->numero_aulas - $aulas_dadas;
        $qtd = ceil($novo_numero_aulas/$dias_semana);
    endif;

    /*
    if(empty($data_inicial)):
        $data = new DateTime($registro->data_inicio->format('Y-m-d'));
    else:
        $data = new DateTime($data_inicial->format('Y-m-d'));
    endif;
    */


    /*Verificando se já existem aulas para esta turma*/
    $aulas = Aulas_Turmas::find_all_by_id_turma($registro->id);

    if($alterar_pogramacao == 'n' && count($aulas) < 1 ):


        if(!empty($data_inicio)):
            $data = new DateTime($data_inicio);
        else:
            $data = $registro->data_inicio;
        endif;


        if(!empty($qtd) && $qtd != 0):
            $numero_aula = 1;
            for($i=1;$i<=$qtd;$i++):
                if(!empty($dias_selecionados)):
                    foreach($dias_selecionados as $dias_selecionado):

                        /*Criando Programação*/
                        $conteudo_padrao = Programa_Aulas::find_by_id_nome_produto_and_aula($dados['id_produto'], $numero_aula);

                        /*No primeiro laço a data inicial definida no cadastro de turmas é mantida*/
                        if($i < 2):
                            $aula = new Aulas_Turmas();
                            $aula->id_turma = $registro->id;
                            $aula->id_nome_produto = $registro->id_produto;
                            $aula->data = $data->format('Y-m-d');
                            $aula->id_situacao_aula = 0;
                            $aula->numero_aula = $numero_aula;
                            $aula->conteudo_padrao = $conteudo_padrao->conteudo;
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


                        $data->modify('next '.$nome_dia);


                        /*Após o primeiro laço começa a ver qual a próxima data a partir da data inicial*/
                        if($i >= 2):
                            $aula = new Aulas_Turmas();
                            $aula->id_turma = $registro->id;
                            $aula->id_nome_produto = $registro->id_produto;
                            $aula->data = $data->format('Y-m-d');
                            $aula->id_situacao_aula = 0;
                            $aula->numero_aula = $numero_aula;
                            $aula->conteudo_padrao = $conteudo_padrao->conteudo;
                            $aula->save();
                        endif;

                        $numero_aula++;

                    endforeach;

                    $registro->data_termino = $data->format('Y-m-d');
                    $registro->save();

                endif;
            endfor;
        endif;

    endif;


    /*Em caso de alteração de programaçao*/
    if($alterar_pogramacao == 's'):

        $ultima_aula_dada = Aulas_Turmas::find(array('conditions' => array('id_turma = ?', $registro->id), 'order' => 'data desc', 'limit' => 1));
        if(!empty($ultima_aula_dada)):
            $data = $ultima_aula_dada->data;
        else:
            $data = new DateTime($data_inicio);
        endif;

        if(!empty($qtd) && $qtd != 0):
            $numero_aula = 1;
            for($i=1;$i<=$qtd;$i++):
                if(!empty($dias_selecionados)):
                    foreach($dias_selecionados as $dias_selecionado):

                        /*Criando Programação*/
                        $conteudo_padrao = Programa_Aulas::find_by_id_nome_produto_and_aula($dados['id_produto'], $numero_aula);

                        /*No primeiro laço a data inicial definida no cadastro de turmas é mantida*/
                        if($i < 2):
                            $aula = new Aulas_Turmas();
                            $aula->id_turma = $registro->id;
                            $aula->id_nome_produto = $registro->id_produto;
                            $aula->data = $data->format('Y-m-d');
                            $aula->id_situacao_aula = 0;
                            $aula->numero_aula = $numero_aula;
                            $aula->conteudo_padrao = $conteudo_padrao->conteudo;
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


                        $data->modify('next '.$nome_dia);

                        if($i >= 2):
                            $aula = new Aulas_Turmas();
                            $aula->id_turma = $registro->id;
                            $aula->id_nome_produto = $registro->id_produto;
                            $aula->data = $data->format('Y-m-d');
                            $aula->id_situacao_aula = 0;
                            $aula->numero_aula = $numero_aula;
                            $aula->conteudo_padrao = $conteudo_padrao->conteudo;
                            $aula->save();
                        endif;


                        $numero_aula++;

                    endforeach;

                    $registro->data_termino = $data->format('Y-m-d');
                    $registro->save();

                endif;
            endfor;
        endif;

    endif;

    if(!empty($registro->data_termino)):
        $data_termino = $registro->data_termino->format('d/m/Y');
    else:
        $data_termino = '';
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' foi alterada.');

    echo json_encode(array('status' => 'ok', 'aulas' => $qtd, 'data_termino' => $data_termino));

endif;


/*--------------------------------------------------------------------*/
/*Salvando limite de faltas*/
if($dados['acao'] == 'salvar-limite-faltas'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Turmas', 'a');

    $registro->limite_faltas = $dados['limite_faltas'];
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve o limite de faltas alterada para '.$dados['limite_faltas']);

    echo json_encode(array('status' => 'ok'));

endif;


/*--------------------------------------------------------------------*/
/*Alteração de Estágio*/
if($dados['acao'] == 'mudar-estagio'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Mudança de Estágio', 'a');

    /*Inativando turma atual*/
    $registro->status = 'i';
    $registro->save();

    /*criando nova turma*/
    $novo_estagio = new Turmas();
    $novo_estagio->nome = $dados['nome'];
    $novo_estagio->segunda = $registro->segunda;
    $novo_estagio->terca = $registro->terca;
    $novo_estagio->quarta = $registro->quarta;
    $novo_estagio->quinta = $registro->quinta;
    $novo_estagio->sexta = $registro->sexta;
    $novo_estagio->sabado = $registro->sabado;
    $novo_estagio->domingo = $registro->domingo;
    $novo_estagio->status = 'a';
    dadosCriacao($novo_estagio);
    $novo_estagio->save();

    $id_novo_estagio = $novo_estagio->id;

    $novo_estagio = Turmas::find($novo_estagio->id);

    /*Verificando duplicidade*/
    if(Turmas::find_by_nome_and_id_unidade($dados['nome'], $dados['id_unidade'])):
        echo json_encode(array('status' => 'erro'));
        exit();
    endif;

    /*Salvando Alterações*/
    $novo_estagio->nome = $dados['nome'];
    $novo_estagio->id_unidade = $dados['id_unidade'];
    $novo_estagio->id_idioma = $dados['id_idioma'];
    $novo_estagio->id_produto = $dados['id_produto'];
    $novo_estagio->id_sistema_notas = $dados['id_sistema_notas'];
    $novo_estagio->id_colega = $dados['id_colega'];
    $novo_estagio->id_valor_hora_aula = $dados['id_valor_hora_aula'];

    /*Horarios*/
    $novo_estagio->hora_inicio_segunda = $dados['hora_inicio_segunda'];
    $novo_estagio->hora_termino_segunda = $dados['hora_termino_segunda'];

    $novo_estagio->hora_inicio_terca = $dados['hora_inicio_terca'];
    $novo_estagio->hora_termino_terca = $dados['hora_termino_terca'];

    $novo_estagio->hora_inicio_quarta = $dados['hora_inicio_quarta'];
    $novo_estagio->hora_termino_quarta = $dados['hora_termino_quarta'];

    $novo_estagio->hora_inicio_quinta = $dados['hora_inicio_quinta'];
    $novo_estagio->hora_termino_quinta = $dados['hora_termino_quinta'];

    $novo_estagio->hora_inicio_sexta = $dados['hora_inicio_sexta'];
    $novo_estagio->hora_termino_sexta = $dados['hora_termino_sexta'];

    $novo_estagio->hora_inicio_sabado = $dados['hora_inicio_sabado'];
    $novo_estagio->hora_termino_sabado = $dados['hora_termino_sabado'];

    $novo_estagio->hora_inicio_domingo = $dados['hora_inicio_domingo'];
    $novo_estagio->hora_termino_domingo = $dados['hora_termino_domingo'];
    /*Horarios*/

    $data_inicio = implode('-', array_reverse(explode('/', $dados['data_inicio'])));
    $novo_estagio->data_inicio = $data_inicio;
    $novo_estagio->data_termino = $dados['data_termino'];
    $novo_estagio->id_situacao_turma = $dados['id_situacao_turma'];
    dadosAlteracao($novo_estagio);
    $novo_estagio->save();



    /*Contando aulas dadas*/
    if($alterar_pogramacao == 's'):
        //$aulas_turma = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula <> ?', $novo_estagio->id, 0)));
        $aulas_turma = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula <> ?', $id_novo_estagio->id, 0)));
        if(!empty($aulas_turma)):
            $aulas_dadas = 0;
            foreach($aulas_turma as $aula_turma):
                $data_inicial = $aula_turma->data;
                $aulas_dadas++;
            endforeach;
        endif;
    endif;
    /*Fim Contando aulas dadas*/

    /*Verificando dias da semana*/
    $dias = array('segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo');

    foreach($dias as $i => $dia):
        if($novo_estagio->$dia == 's'):
            $dias_semana++;
            $dias_selecionados[] = $dia;
        endif;
    endforeach;


    $produto = Nomes_Produtos::find($novo_estagio->id_produto);

    if(empty($aulas_dadas) || $aulas_dadas == 0):
        $qtd = ceil($produto->numero_aulas/$dias_semana);
    else:
        $novo_numero_aulas = $produto->numero_aulas - $aulas_dadas;
        $qtd = ceil($novo_numero_aulas/$dias_semana);
    endif;


    if(empty($data_inicial)):
        $data = new DateTime($novo_estagio->data_inicio->format('Y-m-d'));
    else:
        $data = new DateTime($data_inicial->format('Y-m-d'));
    endif;


    if(!empty($qtd) && $qtd != 0):
        $numero_aula = 1;
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

                    $data->modify('next '.$nome_dia);

                    /*Criando Programação*/
                    $conteudo_padrao = Programa_Aulas::find_by_id_nome_produto_and_aula($dados['id_produto'], $numero_aula);

                    //if($alterar_pogramacao == 's'):
                    $aula = new Aulas_Turmas();
                    $aula->id_turma = $novo_estagio->id;
                    $aula->id_nome_produto = $novo_estagio->id_produto;
                    $aula->data = $data->format('Y-m-d');
                    $aula->id_situacao_aula = 0;
                    $aula->numero_aula = $numero_aula;
                    $aula->conteudo_padrao = $conteudo_padrao->conteudo;
                    $aula->save();
                    //endif;

                    $numero_aula++;

                endforeach;

                $novo_estagio->data_termino = $data->format('Y-m-d');
                $novo_estagio->save();

            endif;
        endfor;
    endif;

    /*Inserindo alunos da antiga turma na nova turma*/
    $alunos_turma = Alunos_Turmas::find_all_by_id_turma($registro->id);
    if(!empty($alunos_turma)):
        foreach($alunos_turma as $aluno_turma):
            $novo_aluno = new Alunos_Turmas();
            $novo_aluno->id_matricula = $aluno_turma->id_matricula;
            $novo_aluno->id_aluno = $aluno_turma->id_aluno;
            $novo_aluno->id_turma = $id_novo_estagio;
            $novo_aluno->save();
        endforeach;
    endif;

    $turma = $id_novo_estagio;

    $alunos_turma = Alunos_Turmas::find_by_sql('select alunos_turmas.id_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula as matricula_aluno_turma, matriculas.id_turma, matriculas.id_aluno, matriculas.`status` from alunos_turmas inner join matriculas on alunos_turmas.id_matricula = matriculas.id where matriculas.`status` = "a" and alunos_turmas.id_turma = '.$turma);

    if(!empty($alunos_turma)):
        foreach ($alunos_turma as $aluno_turma):

            if(!Matriculas::find(array('conditions' => array('id_turma = ? and id_aluno = ?', $turma, $aluno_turma->id_aluno)))):
                //echo $aluno_turma->id_aluno.'<br>';

                $id_aluno = $aluno_turma->id_aluno;
                $id_matricula_original = $aluno_turma->matricula_aluno_turma.'<br>';

                $aluno_turma = Alunos_Turmas::find($aluno_turma->id_aluno);
                $matricula = Matriculas::find($id_matricula_original);
                $turma_destino = Turmas::find($turma);
                $turma_origem = Turmas::find($aluno_turma->id_turma);

                /*marcando matricula de origem como transferida*/
                $matricula->status = 't';
                $matricula->save();

                //$numero_parcelas = Parcelas::find_all_by_id_matricula_and_pago($matricula->id, 'n');
                $numero_parcelas = Parcelas::find_all_by_id_matricula($matricula->id);

                /*criando nova matricula*/
                $nova_matricula = new Matriculas();
                $nova_matricula->id_turma = $turma_destino->id;
                $nova_matricula->id_aluno = $id_aluno;
                $nova_matricula->numero_parcelas = count($numero_parcelas);

                $nova_matricula->valor_parcela = $matricula->valor_parcela;

                $nova_matricula->data_vencimento = $matricula->data_vencimento;
                $nova_matricula->responsavel_financeiro = $matricula->responsavel_financeiro;
                $nova_matricula->id_empresa_financeiro = $matricula->id_empresa_financeiro;

                $nova_matricula->porcentagem_empresa = $matricula->porcentagem_empresa;

                $nova_matricula->responsavel_pedagogico = $matricula->responsavel_pedagogico;
                $nova_matricula->id_empresa_pedagogico = $matricula->id_empresa_pedagogico;
                $nova_matricula->data_matricula = date('Y-m-d');
                $nova_matricula->id_situacao_aluno_turma = 1;
                $nova_matricula->nova_matricula = 'n';
                $nova_matricula->status = 'a';
                dadosCriacao($nova_matricula);
                $nova_matricula->save();

                $id_nova_matricula = $nova_matricula->id;

                /*atualizando matricula em alunos_turmas*/

                //echo $id_nova_matricula;
                try{
                    $atualiza_aluno_turma = Alunos_Turmas::find(array('conditions' => array('id_aluno = ? and id_turma = ?', $id_aluno, $turma)));
                    if(!empty($atualiza_aluno_turma)):
                        $atualiza_aluno_turma->id_matricula = $id_nova_matricula;
                        $atualiza_aluno_turma->save();
                    endif;
                } catch (Exception $e){

                }

                /*Alterando parcelas não pagas para nova matricula*/
                if(!empty($numero_parcelas)):
                    foreach($numero_parcelas as $parcela):
                        $parcela->id_matricula = $id_nova_matricula;
                        $parcela->id_turma = $turma_destino->id;
                        $parcela->id_idioma = $turma_destino->id_idioma;
                        $parcela->save();
                    endforeach;
                endif;

            endif;

        endforeach;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve o estágio alterado para '.$dados['nome']);

    echo json_encode(array('status' => 'ok', 'id' => $novo_estagio->id));

endif;
/*--------------------------------------------------------------------*/


/*
if($dados['acao'] == 'excluir'):

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este idioma não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;
*/


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Turmas', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Inativação', 'A Turma '.$registro->nome.' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Ativação', 'A Turma '.$registro->nome.' foi ativada.');
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
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a segunda-feira acrescentada aos seus horarios.');
            else:
                $registro->segunda = 'n';
                $registro->hora_inicio_segunda = '';
                $registro->hora_termino_segunda = '';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a segunda-feira removida de seus horarios.');
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'terca':
            if($registro->terca == 'n'):
                $registro->terca = 's';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a terça-feira acrescentada aos seus horarios.');
                echo json_encode(array('status' => 's'));
            else:
                $registro->terca = 'n';
                $registro->hora_inicio_terca = '';
                $registro->hora_termino_terca = '';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a terça-feira removida de seus horarios.');
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'quarta':
            if($registro->quarta == 'n'):
                $registro->quarta = 's';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a quarta-feira acrescentada aos seus horarios.');
                echo json_encode(array('status' => 's'));
            else:
                $registro->quarta = 'n';
                $registro->hora_inicio_quarta = '';
                $registro->hora_termino_quarta = '';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a quarta-feira removida de seus horarios.');
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'quinta':
            if($registro->quinta == 'n'):
                $registro->quinta = 's';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a quinta-feira acrescentada aos seus horarios.');
                echo json_encode(array('status' => 's'));
            else:
                $registro->quinta = 'n';
                $registro->hora_inicio_quinta = '';
                $registro->hora_termino_quinta = '';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a quinta-feira removida de seus horarios.');
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'sexta':
            if($registro->sexta == 'n'):
                $registro->sexta = 's';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a sexta-feira acrescentada aos seus horarios.');
                echo json_encode(array('status' => 's'));
            else:
                $registro->sexta = 'n';
                $registro->hora_inicio_sexta = '';
                $registro->hora_termino_sexta = '';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve a sexta-feira removida de seus horarios.');
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'sabado':
            if($registro->sabado == 'n'):
                $registro->sabado = 's';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve o sábado acrescentado aos seus horarios.');
                echo json_encode(array('status' => 's'));
            else:
                $registro->sabado = 'n';
                $registro->hora_inicio_sabado = '';
                $registro->hora_termino_sabado = '';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve o sabado removido de seus horarios.');
                echo json_encode(array('status' => 'n'));
            endif;
            break;

        case 'domingo':
            if($registro->domingo == 'n'):
                $registro->domingo = 's';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve o domingo acrescentado aos seus horarios.');
                echo json_encode(array('status' => 's'));
            else:
                $registro->domingo = 'n';
                $registro->hora_inicio_domingo = '';
                $registro->hora_termino_domingo = '';
                $registro->save();
                adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Alteração', 'A Turma '.$registro->nome.' teve o domingo removido de seus horarios.');
                echo json_encode(array('status' => 'n'));
            endif;
            break;
    }

endif;


/*-------------------------------------------------------------------------*/
/*Integrantes da Turma*/

if($dados['acao'] == 'transferir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Transfereir Aluno', 'i');

    $aluno_turma = Alunos_Turmas::find($dados['id_aluno_turma']);
    $matricula = Matriculas::find($aluno_turma->id_matricula);
    $turma_destino = Turmas::find($dados['id_turma_destino']);
    $turma_origem = Turmas::find($registro->id);

    /*marcando matricula de origem como transferida*/
    $matricula->status = 't';
    $matricula->save();

    //$numero_parcelas = Parcelas::find_all_by_id_matricula_and_pago($matricula->id, 'n');
    $numero_parcelas = Parcelas::find_all_by_id_matricula($matricula->id);

    /*criando nova matricula*/
    $nova_matricula = new Matriculas();
    $nova_matricula->id_turma = $turma_destino->id;
    $nova_matricula->id_aluno = $aluno_turma->id_aluno;
    $nova_matricula->numero_parcelas = count($numero_parcelas);

    $nova_matricula->valor_parcela = $matricula->valor_parcela;

    $nova_matricula->data_vencimento = $matricula->data_vencimento;
    $nova_matricula->responsavel_financeiro = $matricula->responsavel_financeiro;
    $nova_matricula->id_empresa_financeiro = $matricula->id_empresa_financeiro;

    $nova_matricula->porcentagem_empresa = $matricula->porcentagem_empresa;

    $nova_matricula->responsavel_pedagogico = $matricula->responsavel_pedagogico;
    $nova_matricula->id_empresa_pedagogico = $matricula->id_empresa_pedagogico;
    $nova_matricula->data_matricula = date('Y-m-d');
    $nova_matricula->id_situacao_aluno_turma = 1;
    $nova_matricula->nova_matricula = 'n';
    $nova_matricula->status = 'a';
    dadosCriacao($nova_matricula);
    $nova_matricula->save();

    $id_nova_matricula = $nova_matricula->id;

    /*Alterando parcelas não pagas para nova matricula*/
    /*Alterando parcelas para nova matricula*/
    if(!empty($numero_parcelas)):
        foreach($numero_parcelas as $parcela):
            $parcela->id_matricula = $id_nova_matricula;
            $parcela->id_turma = $turma_destino->id;
            $parcela->id_idioma = $turma_destino->id_idioma;
            $parcela->save();
        endforeach;
    endif;

    /*Gerando aluno_turma*/
    $novo_aluno_turma = new Alunos_Turmas();
    $novo_aluno_turma->id_matricula = $id_nova_matricula;
    $novo_aluno_turma->id_aluno = $aluno_turma->id_aluno;
    $novo_aluno_turma->id_turma = $turma_destino->id;
    $novo_aluno_turma->save();

    $id_aluno_turma_turma = $novo_aluno_turma->id;

    /*Transferindo notas*/
    $provas_turmas = Provas_Turmas::find_all_by_id_turma($turma_origem->id);
    if(!empty($provas_turmas)):
        foreach($provas_turmas as $prova_turma):

            echo $prova_turma->id.'<br>';

            if($prova_turma->prova == 1):
                if(!empty($prova_turma->data)):
                    if(Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno)):
                        $nota_prova = Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno);


                        if(Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 1)):
                            $prova_destino = Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 1);
                            $transfere_nota = new Notas_Provas();
                            $transfere_nota->id_prova_turma = $prova_destino->id;
                            $transfere_nota->id_turma = $turma_destino->id;
                            $transfere_nota->id_aluno_turma = $id_aluno_turma_turma;
                            $transfere_nota->id_aluno = $aluno_turma->id_aluno;
                            $transfere_nota->nota = $nota_prova->nota;
                            $transfere_nota->save();
                        endif;

                    endif;
                endif;
            endif;

            if($prova_turma->prova == 2):
                if(!empty($prova_turma->data)):
                    if(Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno)):
                        $nota_prova = Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno);


                        if(Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 2)):
                            $prova_destino = Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 2);
                            $transfere_nota = new Notas_Provas();
                            $transfere_nota->id_prova_turma = $prova_destino->id;
                            $transfere_nota->id_turma = $turma_destino->id;
                            $transfere_nota->id_aluno_turma = $id_aluno_turma_turma;
                            $transfere_nota->id_aluno = $aluno_turma->id_aluno;
                            $transfere_nota->nota = $nota_prova->nota;
                            $transfere_nota->save();
                        endif;

                    endif;
                endif;
            endif;

            if($prova_turma->prova == 3):
                if(!empty($prova_turma->data)):
                    if(Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno)):
                        $nota_prova = Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno);


                        if(Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 3)):
                            $prova_destino = Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 3);
                            $transfere_nota = new Notas_Provas();
                            $transfere_nota->id_prova_turma = $prova_destino->id;
                            $transfere_nota->id_turma = $turma_destino->id;
                            $transfere_nota->id_aluno_turma = $id_aluno_turma_turma;
                            $transfere_nota->id_aluno = $aluno_turma->id_aluno;
                            $transfere_nota->nota = $nota_prova->nota;
                            $transfere_nota->save();
                        endif;

                    endif;
                endif;
            endif;

            if($prova_turma->prova == 4):
                if(!empty($prova_turma->data)):
                    if(Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno)):
                        $nota_prova = Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno);

                        if(Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 4)):
                            $prova_destino = Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 4);
                            $transfere_nota = new Notas_Provas();
                            $transfere_nota->id_prova_turma = $prova_destino->id;
                            $transfere_nota->id_turma = $turma_destino->id;
                            $transfere_nota->id_aluno_turma = $id_aluno_turma_turma;
                            $transfere_nota->id_aluno = $aluno_turma->id_aluno;
                            $transfere_nota->nota = $nota_prova->nota;
                            $transfere_nota->save();
                        endif;

                    endif;
                endif;
            endif;

            if($prova_turma->prova == 5):
                if(!empty($prova_turma->data)):
                    if(Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno)):
                        $nota_prova = Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno);

                        if(Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 5)):
                            $prova_destino = Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 5);
                            $transfere_nota = new Notas_Provas();
                            $transfere_nota->id_prova_turma = $prova_destino->id;
                            $transfere_nota->id_turma = $turma_destino->id;
                            $transfere_nota->id_aluno_turma = $id_aluno_turma_turma;
                            $transfere_nota->id_aluno = $aluno_turma->id_aluno;
                            $transfere_nota->nota = $nota_prova->nota;
                            $transfere_nota->save();
                        endif;

                    endif;
                endif;
            endif;

            if($prova_turma->prova == 6):
                if(!empty($prova_turma->data)):
                    if(Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno)):
                        $nota_prova = Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno);

                        if(Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 6)):
                            $prova_destino = Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, 6);
                            $transfere_nota = new Notas_Provas();
                            $transfere_nota->id_prova_turma = $prova_destino->id;
                            $transfere_nota->id_turma = $turma_destino->id;
                            $transfere_nota->id_aluno_turma = $id_aluno_turma_turma;
                            $transfere_nota->id_aluno = $aluno_turma->id_aluno;
                            $transfere_nota->nota = $nota_prova->nota;
                            $transfere_nota->save();
                        endif;

                    endif;
                endif;
            endif;

            if($prova_turma->prova == '_oral'):
                if(!empty($prova_turma->data)):
                    if(Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno)):
                        $nota_prova = Notas_Provas::find_by_id_prova_turma_and_id_turma_and_id_aluno($prova_turma->id, $turma_origem->id, $aluno_turma->id_aluno);

                        if(Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, '_oral')):
                            $prova_destino = Provas_Turmas::find_by_id_turma_and_prova($turma_destino->id, '_oral');
                            $transfere_nota = new Notas_Provas();
                            $transfere_nota->id_prova_turma = $prova_destino->id;
                            $transfere_nota->id_turma = $turma_destino->id;
                            $transfere_nota->id_aluno_turma = $id_aluno_turma_turma;
                            $transfere_nota->id_aluno = $aluno_turma->id_aluno;
                            $transfere_nota->nota = $nota_prova->nota;
                            $transfere_nota->save();
                        endif;

                    endif;
                endif;
            endif;

        endforeach;
    endif;


    /*Gerando Transferencia*/
    $transferencia = new Transferencias();
    $transferencia->id_aluno = $aluno_turma->id_aluno;
    $transferencia->id_matricula = $matricula->id;
    $transferencia->id_tuma_origem = $turma_origem->id;
    $transferencia->id_turma_destino = $turma_destino->id;
    $transferencia->data = date('Y-m-d');
    dadosCriacao($transferencia);
    $transferencia->save();

    $aluno = Alunos::find($aluno_turma->id_aluno);
    $turma_origem = Turmas::find($turma_origem->id);
    $turma_destino = Turmas::find($turma_destino->id);

    adicionaHistorico(idUsuario(), idColega(), 'Turmas - Transfereir Aluno', 'Alteração', 'O aluno '.$aluno->nome.' foi transferido da turma '.$turma_origem->nome. ' para a turma '.$turma_destino->nome.'.');

    echo json_encode(array('status' => 'ok'));

endif;


/*Diário de Classe*/

if($dados['acao'] == 'verifica-aula'):

    $aula = Aulas_Turmas::find($dados['id_aula']);
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

    $alunos_turmas = Alunos_Turmas::find_all_by_id_turma($registro->id);

    if(!empty($alunos_turmas)):
        foreach($alunos_turmas as $aluno_turma):

            if(!Aulas_Alunos::find_by_id_aula_and_id_aluno_turma($aula->id, $aluno_turma->id)):
                $aula_aluno = new Aulas_Alunos();
                $aula_aluno->id_aula = $aula->id;
                $aula_aluno->id_aluno_turma = $aluno_turma->id;
                $aula_aluno->id_aluno = $aluno_turma->id_aluno;
                $aula_aluno->id_turma = $aluno_turma->id_turma;
                $aula_aluno->presente = 's';
                $aula_aluno->tarefa = 'n';
                $aula_aluno->save();
            endif;

        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'salvar-dados-aula'):

    $aula = Aulas_Turmas::find($dados['id_aula']);
    $aula->data = implode('-', array_reverse(explode('/', $dados['data'])));
    $aula->id_situacao_aula = $dados['id_situacao_aula'];
    $aula->id_colega = $dados['id_colega'];
    $aula->conteudo_dado = $dados['conteudo_dado'];
    $aula->hora_inicio = $dados['hora_inicio'];
    $aula->hora_termino = $dados['hora_termino'];

    /*Calculando o valor da Aula*/
    $situacao_aula = Situacao_Aulas::find($aula->id_situacao_aula);
    if($situacao_aula->pagar == 's'):
        $valor_hora_aula = Valores_Hora_Aula::find($registro->id_valor_hora_aula);
        $periodo = intervalo($aula->hora_inicio, $aula->hora_termino);
        $periodo_decimal = decimalHours($periodo);
        $valor_aula = $valor_hora_aula->valor*$periodo_decimal;

        $aula->valor_hora_aula = $valor_aula;
    else:
        $aula->valor_hora_aula = 0;
    endif;
    //$aula->tarefa = $dados['tarefa'];
    $aula->save();

    /*Verificando se é necessário gerar automaticamente uma nova aula*/
    $situacao_aula = Situacao_Aulas::find($dados['id_situacao_aula']);
    if($situacao_aula->gera_programacao == 's'):

        $ultima_aula = Aulas_Turmas::find(array('conditions' => array('id_turma = ?', $dados['id']), 'order' => 'data desc', 'limit' => 1));

        $dia_da_semana = date('w', strtotime($ultima_aula->data->format('Y-m-d')));

        //echo $dia_da_semana;

        /*Verificando dias da semana*/
        $dias = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');
        for($i=0;$i<=6;$i++):
            if($i!=$dia_da_semana):
                unset($dias[$dia_da_semana]);
            endif;
        endfor;

        //print_r($dias);


        foreach($dias as $i => $dia):
            if($registro->$dia == 's'):
                $dias_semana++;
                $dias_selecionados[] = $dia;
            endif;
        endforeach;


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

                        $data = new DateTime($ultima_aula->data->format('Y-m-d'));
                        $data->modify('next '.$nome_dia);

                        /*Criando Programação*/
                        //$conteudo_padrao = Programa_Aulas::find_by_id_nome_produto_and_aula($dados['id_produto'], $numero_aula);


                        $aula = new Aulas_Turmas();
                        $aula->id_turma = $registro->id;
                        $aula->id_nome_produto = $registro->id_produto;
                        $aula->data = $data->format('Y-m-d');
                        $aula->id_situacao_aula = 0;
                        //$aula->numero_aula = $numero_aula;
                        //$aula->conteudo_padrao = $conteudo_padrao->conteudo;
                        $aula->save();

                        break;

                    endforeach;

                endif;


    endif;

    $turma = Turmas::find($aula->id_turma);
    adicionaHistorico(idUsuario(), idColega(), 'Turmas - Diário de Classe', 'Alteração', 'O dados da aula da turma '.$turma->nome. ' do dia '.$aula->data->format('d/m/Y').' foram alterados.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'presente'):

    $id_aluno_aula = $dados['id_aula_aluno'];
    $aula_aluno = Aulas_Alunos::find($id_aluno_aula);
    $aluno = Alunos::find($aula_aluno->id_aluno);
    $aula = Aulas_Turmas::find($aula_aluno->id_aula);
    $turma = Turmas::find($aula->id_turma);

    if($aula_aluno->presente == 'n'):
        $aula_aluno->presente = 's';
        $aula_aluno->save();
        adicionaHistorico(idUsuario(), idColega(), 'Turmas - Diário de Classe', 'Alteração', 'O aluno '.$aluno->nome. ' recebeu presença na aula do dia '.$aula->data->format('d/m/Y').' da turma '.$turma->nome);
    else:
        $aula_aluno->presente = 'n';
        $aula_aluno->save();
        adicionaHistorico(idUsuario(), idColega(), 'Turmas - Diário de Classe', 'Alteração', 'O aluno '.$aluno->nome. ' recebeu falta na aula do dia '.$aula->data->format('d/m/Y').' da turma '.$turma->nome);
    endif;
endif;


if($dados['acao'] == 'tarefa'):

    $id_aluno_aula = $dados['id_aula_aluno'];
    $aula_aluno = Aulas_Alunos::find($id_aluno_aula);
    $aluno = Alunos::find($aula_aluno->id_aluno);
    $aula = Aulas_Turmas::find($aula_aluno->id_aula);
    $turma = Turmas::find($aula->id_turma);

    if($aula_aluno->tarefa == 'n'):
        $aula_aluno->tarefa = 's';
        $aula_aluno->save();
        adicionaHistorico(idUsuario(), idColega(), 'Turmas - Diário de Classe', 'Alteração', 'A aula do dia '.$aula->data->format('d/m/Y').' da turma '.$turma->nome.' foi marcada como tendo tarefa.');
    else:
        $aula_aluno->tarefa = 'n';
        $aula_aluno->save();
        adicionaHistorico(idUsuario(), idColega(), 'Turmas - Diário de Classe', 'Alteração', 'A aula do dia '.$aula->data->format('d/m/Y').' da turma '.$turma->nome.' foi marcada como não tendo tarefa.');
    endif;
endif;


if($dados['acao'] == 'verifica-provas'):

    try{
        $sistema_notas = Sistema_Notas::find($registro->id_sistema_notas);
    } catch (\ActiveRecord\RecordNotFound $e){
        $sistema_notas = '';
    }

    if(!empty($sistema_notas)):

        if(!empty($sistema_notas->id_nome_prova_oral) && $sistema_notas->id_nome_prova_oral != 0):
            if(!Provas_Turmas::find_by_id_turma_and_id_sistema_nota_and_id_nome_prova($registro->id, $sistema_notas->id,  $sistema_notas->id_nome_prova_oral)):
                $prova = new Provas_Turmas();
                $prova->id_turma = $registro->id;
                $prova->id_sistema_nota = $sistema_notas->id;
                $prova->id_nome_prova = $sistema_notas->id_nome_prova_oral;
                $prova->prova = '_oral';
                $prova->save();
            endif;
        endif;


        if(!empty($sistema_notas->id_nome_prova1) && $sistema_notas->id_nome_prova1 != 0):
            if(!Provas_Turmas::find_by_id_turma_and_id_sistema_nota_and_id_nome_prova($registro->id, $sistema_notas->id,  $sistema_notas->id_nome_prova1)):
                $prova = new Provas_Turmas();
                $prova->id_turma = $registro->id;
                $prova->id_sistema_nota = $sistema_notas->id;
                $prova->id_nome_prova = $sistema_notas->id_nome_prova1;
                $prova->prova = '1';
                $prova->save();
            endif;
        endif;


        if(!empty($sistema_notas->id_nome_prova2) && $sistema_notas->id_nome_prova2 != 0):
            if(!Provas_Turmas::find_by_id_turma_and_id_sistema_nota_and_id_nome_prova($registro->id, $sistema_notas->id,  $sistema_notas->id_nome_prova2)):
                $prova = new Provas_Turmas();
                $prova->id_turma = $registro->id;
                $prova->id_sistema_nota = $sistema_notas->id;
                $prova->id_nome_prova = $sistema_notas->id_nome_prova2;
                $prova->prova = '2';
                $prova->save();
            endif;
        endif;


        if(!empty($sistema_notas->id_nome_prova3) && $sistema_notas->id_nome_prova3 != 0):
            if(!Provas_Turmas::find_by_id_turma_and_id_sistema_nota_and_id_nome_prova($registro->id, $sistema_notas->id,  $sistema_notas->id_nome_prova3)):
                $prova = new Provas_Turmas();
                $prova->id_turma = $registro->id;
                $prova->id_sistema_nota = $sistema_notas->id;
                $prova->id_nome_prova = $sistema_notas->id_nome_prova3;
                $prova->prova = '3';
                $prova->save();
            endif;
        endif;


        if(!empty($sistema_notas->id_nome_prova4) && $sistema_notas->id_nome_prova4 != 0):
            if(!Provas_Turmas::find_by_id_turma_and_id_sistema_nota_and_id_nome_prova($registro->id, $sistema_notas->id,  $sistema_notas->id_nome_prova4)):
                $prova = new Provas_Turmas();
                $prova->id_turma = $registro->id;
                $prova->id_sistema_nota = $sistema_notas->id;
                $prova->id_nome_prova = $sistema_notas->id_nome_prova4;
                $prova->prova = '4';
                $prova->save();
            endif;
        endif;


        if(!empty($sistema_notas->id_nome_prova5) && $sistema_notas->id_nome_prova5 != 0):
            if(!Provas_Turmas::find_by_id_turma_and_id_sistema_nota_and_id_nome_prova($registro->id, $sistema_notas->id,  $sistema_notas->id_nome_prova5)):
                $prova = new Provas_Turmas();
                $prova->id_turma = $registro->id;
                $prova->id_sistema_nota = $sistema_notas->id;
                $prova->id_nome_prova = $sistema_notas->id_nome_prova5;
                $prova->prova = '5';
                $prova->save();
            endif;
        endif;


        if(!empty($sistema_notas->id_nome_prova6) && $sistema_notas->id_nome_prova6 != 0):
            if(!Provas_Turmas::find_by_id_turma_and_id_sistema_nota_and_id_nome_prova($registro->id, $sistema_notas->id,  $sistema_notas->id_nome_prova6)):
                $prova = new Provas_Turmas();
                $prova->id_turma = $registro->id;
                $prova->id_sistema_nota = $sistema_notas->id;
                $prova->id_nome_prova = $sistema_notas->id_nome_prova6;
                $prova->prova = '6';
                $prova->save();
            endif;
        endif;


    endif;

    echo json_encode(array('status' => 'ok'));

endif;



if($dados['acao'] == 'notas-provas'):

    $prova = Provas_Turmas::find($dados['id_prova']);
    //$alunos_turmas = Alunos_Turmas::all(array('conditions' => array('id_turma = ?', $registro->id)));
    //$alunos_turmas = Alunos_Turmas::find_by_sql("select alunos_turmas.* from alunos_turmas inner join matriculas on matriculas.id = alunos_turmas.id_matricula where (matriculas.status = 'a' or matriculas.status = 't') and alunos_turmas.id_turma = {$registro->id} ");
    $alunos_turmas = Alunos_Turmas::find_by_sql("select alunos_turmas.* from alunos_turmas inner join matriculas on matriculas.id = alunos_turmas.id_matricula where matriculas.status <> 'i' and alunos_turmas.id_turma = {$registro->id} ");

    if(!empty($alunos_turmas)):
        foreach($alunos_turmas as $aluno_turma):
            if(!Notas_Provas::find_by_id_prova_turma_and_id_aluno_turma($prova->id, $aluno_turma->id)):
                $nota_prova = new Notas_Provas();
                $nota_prova->id_prova_turma = $prova->id;
                $nota_prova->id_turma = $registro->id;
                $nota_prova->id_aluno_turma = $aluno_turma->id;
                $nota_prova->id_aluno = $aluno_turma->id_aluno;
                $nota_prova->save();
            endif;
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'salvar-notas'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Alterar Notas dos Alunos', 'a');

    $prova = Provas_Turmas::find($dados['id_prova']);
    $turma = Turmas::find($prova->id_turma);
    $notas = Notas_Provas::find_all_by_id_turma_and_id_prova_turma($registro->id, $prova->id);

    $data_prova = implode('-', array_reverse(explode('/', $dados['data'])));
    $prova->data = $data_prova;
    $prova->save();

    if(!empty($notas)):
        foreach($notas as $nota):
            $valor_nota = str_replace(',', '.', $dados['nota_'.$nota->id]);
            $nota->nota = $valor_nota;
            $nota->save();
        endforeach;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Turmas - Notas de Provas', 'Alteração', 'As notas da turma '.$turma->nome.' foram alteradas.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'adicionar-aula'):

    $turma = Turmas::find($dados['id']);

    $aula = new Aulas_Turmas();
    $aula->id_turma = $dados['id'];
    $aula->id_nome_produto = $registro->id_produto;
    $aula->data = implode('-', array_reverse(explode('/', $dados['data'])));
    $aula->id_situacao_aula = 0;
    $aula->save();

    adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Inclusão', 'Uma aula foi adicionada a turma '.$turma->nome.' para o dia '.$dados['data']);

    echo json_encode(array('status' => 'ok'));

endif;

if($dados['acao'] == 'adicionar-pacote'):

    $registro = Turmas::find_by_id($dados['id']);
    $numero_aulas = ($dados['numero_aulas']+1);

    //$ultima_aula = Aulas_Turmas::find_by_sql("select data, numero_aula from aulas_turmas where id_turma = '{$registro->id}' order by data desc limit 1 ");

    /*Verificando dias da semana*/
    $numero_dias = [
        0 => 'sunday',
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
    ];
    $dias = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');
    $dias_semana = 0;
    foreach($dias as $i => $dia):
        if($registro->$dia == 's'):
            $dias_semana++;
            $dias_selecionados[] = $dia;
            $numero_dias_selecionados[] = $i;
        endif;
    endforeach;

    $ultima_aula = Aulas_Turmas::find_by_sql("select data, numero_aula from aulas_turmas where id_turma = '{$registro->id}' order by data desc limit 1 ");
    $data_inicio = !empty($ultima_aula[0]->data) ? $ultima_aula[0]->data->format('Y-m-d') : date('Y-m-d');
    $nome_dia_inicio_aula = date('l', strtotime($data_inicio));
    $numero_dia_inicio_aula = date('w', strtotime($data_inicio));

    if(!empty($data_inicio)):
        $data = new DateTime($data_inicio);
    else:
        $data = $registro->data_inicio;
    endif;

    $count_aula = 0;
    $posicao_array = array_search($numero_dia_inicio_aula, $numero_dias_selecionados);

    if(!empty($numero_aulas) && $numero_aulas != 0):
        $numero_aula = $ultima_aula[0]->numero_aula;
        for($i = 0; $i < $numero_aulas; $i++):

            if(!empty($numero_dias_selecionados)):

                if($posicao_array == count($numero_dias_selecionados)):
                    $posicao_array = 0;
                endif;

                if($count_aula >= 1):
                    $data->modify('next '.$numero_dias[$numero_dias_selecionados[$posicao_array]]);

                    $aula = new Aulas_Turmas();
                    $aula->id_turma = $registro->id;
                    $aula->id_nome_produto = $registro->id_produto;
                    $aula->data = $data->format('Y-m-d');
                    $aula->id_situacao_aula = 0;
                    $aula->numero_aula = $numero_aula;
                    $aula->conteudo_padrao = null;
                    $aula->save();

                    $registro->data_termino = $data->format('Y-m-d');
                    $registro->save();
                endif;


                /*Após o primeiro laço começa a ver qual a próxima data a partir da data inicial*/



                $posicao_array++;
                $numero_aula++;
                $count_aula++;

                if($count_aula == $numero_aulas):
                    break;
                endif;

            endif;

        endfor;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Inclusão', 'Uma aula foi adicionada a turma '.$turma->nome.' para o dia '.$dados['data']);

    echo json_encode(array('status' => 'ok'));

endif;

/*Fim Diario de Classe*/


/*Observações dos Professores*/

if($dados['acao'] == 'salvar-observacao'):

    /*Verificando se usuário está relacionado com algum Colega IOWA*/
    $usuario = Usuarios::find($dados['id_usuario']);
    if(empty($usuario->id_colega)):
        echo json_encode(array('status' => 'erro-id_colega'));
        exit();
    endif;

    $observacao = new Observacoes_Professores();
    $observacao->id_turma = $dados['id'];
    $observacao->id_aluno = $dados['id_aluno'];
    $observacao->id_colega = $usuario->id_colega;
    $observacao->data = date('Y-m-d H:i:s');
    $observacao->observacao = $dados['observacao'];
    $observacao->save();

    $aluno = Alunos::find($dados['id_aluno']);
    $turma = Turmas::find($dados['id']);

    adicionaHistorico(idUsuario(), idColega(), 'Turmas', 'Inclusão', 'Uma observação foi incluída para o aluno '.$aluno->nome.' da turma '.$turma->nome);

    echo json_encode(array('status' => 'ok'));

endif;

/*Fim Observações dos Professores*/


if($dados['acao'] == 'atualizar-matricula'):

    $turma = $dados['id_turma'];

    $alunos_turma = Alunos_Turmas::find_by_sql('select alunos_turmas.id_turma, alunos_turmas.id_aluno, alunos_turmas.id_matricula as matricula_aluno_turma, matriculas.id_turma, matriculas.id_aluno, matriculas.`status` from alunos_turmas inner join matriculas on alunos_turmas.id_matricula = matriculas.id where matriculas.`status` = "a" and alunos_turmas.id_turma = '.$turma);

    if(!empty($alunos_turma)):
        foreach ($alunos_turma as $aluno_turma):

            if(!Matriculas::find(array('conditions' => array('id_turma = ? and id_aluno = ?', $turma, $aluno_turma->id_aluno)))):
                //echo $aluno_turma->id_aluno.'<br>';;

                $id_aluno = $aluno_turma->id_aluno;
                $id_matricula_original = $aluno_turma->matricula_aluno_turma.'<br>';

                $aluno_turma = Alunos_Turmas::find($aluno_turma->id_aluno);
                $matricula = Matriculas::find($id_matricula_original);
                $turma_destino = Turmas::find($turma);
                $turma_origem = Turmas::find($aluno_turma->id_turma);

                /*marcando matricula de origem como transferida*/
                $matricula->status = 't';
                $matricula->save();

                //$numero_parcelas = Parcelas::find_all_by_id_matricula_and_pago($matricula->id, 'n');
                $numero_parcelas = Parcelas::find_all_by_id_matricula($matricula->id);

                /*criando nova matricula*/
                $nova_matricula = new Matriculas();
                $nova_matricula->id_turma = $turma_destino->id;
                $nova_matricula->id_aluno = $id_aluno;
                $nova_matricula->numero_parcelas = count($numero_parcelas);

                $nova_matricula->valor_parcela = $matricula->valor_parcela;

                $nova_matricula->data_vencimento = $matricula->data_vencimento;
                $nova_matricula->responsavel_financeiro = $matricula->responsavel_financeiro;
                $nova_matricula->id_empresa_financeiro = $matricula->id_empresa_financeiro;

                $nova_matricula->porcentagem_empresa = $matricula->porcentagem_empresa;

                $nova_matricula->responsavel_pedagogico = $matricula->responsavel_pedagogico;
                $nova_matricula->id_empresa_pedagogico = $matricula->id_empresa_pedagogico;
                $nova_matricula->data_matricula = date('Y-m-d');
                $nova_matricula->id_situacao_aluno_turma = 1;
                $nova_matricula->status = 'a';
                dadosCriacao($nova_matricula);
                $nova_matricula->save();

                $id_nova_matricula = $nova_matricula->id;

                /*atualizando matricula em alunos_turmas*/

                //echo $id_nova_matricula;
                try{
                    $atualiza_aluno_turma = Alunos_Turmas::find(array('conditions' => array('id_aluno = ? and id_turma = ?', $id_aluno, $turma)));
                    if(!empty($atualiza_aluno_turma)):
                        $atualiza_aluno_turma->id_matricula = $id_nova_matricula;
                        $atualiza_aluno_turma->save();
                    endif;
                } catch (Exception $e){

                }


                /*Alterando parcelas não pagas para nova matricula*/
                if(!empty($numero_parcelas)):
                    foreach($numero_parcelas as $parcela):
                        $parcela->id_matricula = $id_nova_matricula;
                        $parcela->id_turma = $turma_destino->id;
                        $parcela->id_idioma = $turma_destino->id_idioma;
                        $parcela->save();
                    endforeach;
                endif;

            endif;

        endforeach;
    endif;


    echo json_encode(array('status' => 'ok'));

endif;

