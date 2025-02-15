<?php
/*
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
*/
/*
header("Cache-Control: no-cache, no-store, must-revalidate"); // limpa o cache
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
*/

include_once('../config.php');
include_once('autenticacao.php');

$acao = $url[1];

if($acao == 'listar'):

    $turmas = [];
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if(autenticaAluno($login, $senha)):

        $matriculas = Matriculas::all(array('conditions' => array('id_aluno = ? and status = ?', $id, 'a')));
        if(!empty($matriculas)):
            foreach($matriculas as $matricula):
                $turma = Turmas::find($matricula->id_turma);
                $idioma = Idiomas::find($turma->id_idioma);
                $total_aula = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula <> ?', $turma->id, 0)));
                $presencas_aluno = Aulas_Alunos::all(array('conditions' => array('id_turma = ? and id_aluno = ? and presente = ? or (presente = ? and abonada = ?)', $turma->id, $id, 's', 'n', 's')));
                $faltas_aluno = Aulas_Alunos::all(array('conditions' => array('id_turma = ? and id_aluno = ? and presente = ? and coalesce(abonada, "") <> ?', $turma->id, $id, 'n', 's')));

                $turmas[] = array('turma' => $turma->nome, 'id' => $turma->id, 'idioma' => $idioma->idioma, 'aulas' => count($total_aula), 'presencas' => count($presencas_aluno), 'faltas' => count($faltas_aluno));
            endforeach;
        endif;

        echo json_encode($turmas);

    else:
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Usuário Não está logado'));
    endif;

endif;


if($acao == 'notas'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    $aluno_turma = Alunos_Turmas::find_by_id_turma_and_id_aluno($id_turma, $id);

    $turma = Turmas::find($id_turma);
    $sistema_notas = Sistema_Notas::find($turma->id_sistema_notas);

    $array_notas = [];

    if(autenticaAluno($login, $senha)):

        //$array_provas = ['1', '2', '3', '4', '5', '6', '_oral'];

        //for($i=0;$i<7;$i++):
            $provas = Provas_Turmas::all(array('conditions' => array('id_turma = ?', $id_turma)));

            if(!empty($provas)):
                foreach($provas as $prova):
                    $nome_prova = Nome_Provas::find($prova->id_nome_prova);

                    try{
                        $nota = Notas_Provas::find(array('conditions' => array('id_prova_turma = ? and id_turma = ? and id_aluno_turma = ?', $prova->id, $id_turma, $aluno_turma->id)));
                    } catch(Exception $e){
                        $nota = '';
                    }

                    $array_notas[] = array('prova' => $nome_prova->nome, 'nota' => !empty($nota) ? $nota->nota : '');
                endforeach;
            endif;

            //$prova = Provas_Turmas::find_by_id_turma_and_prova($id_turma, $array_provas[$i]);

        //endfor;


        echo json_encode($array_notas);

    endif;

endif;



if($acao == 'data_faltas'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

    /*dados do aluno*/
    $aluno = Alunos::find($id);

    /*dados da matricula*/
    $matricula = Matriculas::find_by_id_turma_and_id_aluno($id_turma, $id);

    $faltas = [];

    if(autenticaAluno($login, $senha)):

        $turma = Turmas::find($id_turma);
        $faltas_aluno = Aulas_Alunos::all(array('conditions' => array('id_turma = ? and id_aluno = ? and presente = ? and coalesce(abonada,"n") <> ?', $turma->id, $id, 'n', 's')));

        if(!empty($faltas_aluno)):
            foreach($faltas_aluno as $falta_aluno):

                try{
                    $aula_turma = Aulas_Turmas::find($falta_aluno->id_aula);

                    if(
                        $aula_turma->id_situacao_aula != 0 &&
                        $aula_turma->id_situacao_aula != 2 &&
                        $aula_turma->id_situacao_aula != 3 &&
                        $aula_turma->id_situacao_aula != 5 &&
                        $aula_turma->id_situacao_aula != 10
                    ):

                        $faltas[] = array(
                            'id_aula_turma' => $falta_aluno->id_aula,
                            'id_aula_aluno' => $falta_aluno->id,
                            'data' => $aula_turma->data->format('d/m/Y'),
                            'responsavel_pedagogico' => $matricula->responsavel_pedagogico,
                            'id_empresa_pedagogico' => $matricula->id_empresa_pedagogico,
                            'email_gestor' => $aluno->email_gestor_pedagogico,
                            'id_turma' => $id_turma,
                            'abonada' => $falta_aluno->abonada
                        );

                    endif;
                } catch (Exception $e){

                }

            endforeach;
        endif;

        echo json_encode($faltas);

    else:
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Usuário Não está logado'));
    endif;

endif;


if($acao == 'frequencia'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

    if(autenticaAluno($login, $senha)):

        $turma = Turmas::find($id_turma);
        $idioma = Idiomas::find($turma->id_idioma);

        $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id, 0)));
        $numero_aulas = count($aulas);
        $matricula = Matriculas::find_by_id_aluno_and_id_turma($id, $turma->id);

        $total_aula = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id, 0)));
        $frequencia = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id} and aulas_alunos.id_aluno = {$id} and aulas_alunos.presente = 's' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");
        $faltas = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id} and aulas_alunos.id_aluno = {$id} and aulas_alunos.presente = 'n' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");
        $abonos = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id} and aulas_alunos.id_aluno = {$id} and aulas_alunos.presente = 'n' and coalesce(aulas_alunos.abonada, '') = 's' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");

        $numero_aulas = count($total_aula);
        $total_presencas = count($frequencia);
        $total_faltas = count($faltas);
        $total_abonos = count($abonos);

        /*
        if(!empty($aulas)):
            foreach ($aulas as $aula):

                $abonos = 0;
                if(!empty($aulas)):
                    foreach($aulas as $aula):
                        if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_abonada($aula->id, $id, 's')):
                            $abonos++;
                        endif;
                    endforeach;
                endif;

                $frequencia = 0;
                if(!empty($aulas)):
                    foreach($aulas as $aula):
                        if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $id, 's')):
                            $frequencia++;
                        endif;
                    endforeach;
                endif;

                $numero_faltas = 0;
                if(!empty($aulas)):
                    foreach($aulas as $aula):
                        if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $id, 'n')):
                            $numero_faltas++;
                        endif;
                    endforeach;
                endif;

            endforeach;;
        endif;
        */

        //$numero_faltas = $numero_faltas-$abonos;
        //$aproveitamento = (($frequencia+$abonos)/$numero_aulas)*100;

        if($considerar_abono == 'n'):
            $aproveitamento = (($numero_aulas-$total_faltas)/$numero_aulas)*100;
        elseif($considerar_abono == 's'):
            $aproveitamento = ((($numero_aulas-$total_faltas)+(int)$total_abonos)/$numero_aulas)*100;
        endif;

        echo json_encode(array('turma' => $turma->nome, 'id' => $turma->id, 'idioma' => $idioma->idioma, 'aulas' => $numero_aulas, 'presencas' => $total_presencas+$total_abonos, 'faltas' => $total_faltas, 'abonos' => $total_abonos, 'faltas_menos_abonos' => ($total_faltas-$total_abonos)));

    else:
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Usuário Não está logado'));
    endif;

endif;


if($acao == 'relatorio-frequencia'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $considerar_abono = filter_input(INPUT_POST, 'considerar_abono', FILTER_SANITIZE_NUMBER_INT);
    $aluno = Alunos::find($id);
    $relatorio = [];
    $datas_faltas = [];

    if(autenticaAluno($login, $senha)):

        $turmas = Turmas::find_by_sql(" 
            select 
            turmas.*, 
            turmas.nome as nome_turma, 
            aulas_alunos.*, 
            alunos.*, 
            alunos.nome as nome_aluno 
            from 
            aulas_alunos 
            inner join turmas on aulas_alunos.id_turma = turmas.id 
            inner join alunos on aulas_alunos.id_aluno = alunos.id 
            where 
            alunos.id like '".$id."' 
            and turmas.status = 'a'
            group by turmas.id;
        ");

        if(!empty($turmas)):
            foreach($turmas as $turma):

                try{
                    $unidade = Unidades::find($turma->id_unidade);
                } catch (\ActiveRecord\RecordNotFound $e){
                    $unidade = '';
                }

                try{
                    $idioma = Idiomas::find($turma->id_idioma);
                } catch (\ActiveRecord\RecordNotFound $e){
                    $idioma = '';
                }

                if(empty($data_final)):
                    $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id_turma, 0)));
                else:
                    $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10) and (data between ? and ?)', $turma->id_turma, 0, $data_inicial, $data_final)));
                endif;

                if(!empty($turma->limite_faltas)):
                    $limite_faltas = $turma->limite_faltas;
                else:
                    $limite_faltas = 0;
                endif;

                //$total_aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id_turma, 0)));
                $dados_aluno = Alunos::find($id);
                $matricula = Matriculas::find_by_id_aluno_and_id_turma($id, $turma->id_turma);

                $idioma = Idiomas::find($turma->id_idioma);
                $total_aula = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id_turma, 0)));
                $frequencia = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id_turma} and aulas_alunos.id_aluno = {$id} and aulas_alunos.presente = 's' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");
                $faltas = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id_turma} and aulas_alunos.id_aluno = {$id} and aulas_alunos.presente = 'n' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");
                $abonos = Aulas_Alunos::find_by_sql("select aulas_alunos.*, aulas_turmas.id_situacao_aula from aulas_alunos inner join aulas_turmas on aulas_alunos.id_aula = aulas_turmas.id where aulas_alunos.id_turma = {$turma->id_turma} and aulas_alunos.id_aluno = {$id} and aulas_alunos.presente = 'n' and coalesce(aulas_alunos.abonada, '') = 's' and (aulas_turmas.id_situacao_aula <> 0 and aulas_turmas.id_situacao_aula <> 2 and aulas_turmas.id_situacao_aula <> 3 and aulas_turmas.id_situacao_aula <> 5 and aulas_turmas.id_situacao_aula <> 10)");

                $numero_aulas = count($total_aula);
                $total_faltas = count($faltas);
                $total_abonos = count($abonos);

                $datas_faltas = [];
                if($numero_aulas > 0):

                    if(!empty($aulas)):
                        foreach($aulas as $aula):
                            if($considerar_abono == 'n' && $matricula->responsavel_pedagogico == 2):
                                if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $id, 'n')):
                                    $datas_faltas[] = array(
                                        'data' => $aula->data->format('d/m/Y').' - '
                                    );
                                endif;
                            else:
                                if(Aulas_Alunos::all(array('conditions' => array('id_aula = ? and id_aluno = ? and presente = ? and coalesce(abonada, "n") = ?', $aula->id, $id, 'n', 'n')))):
                                    $datas_faltas[] = array(
                                        'data' => $aula->data->format('d/m/Y').' - '
                                    );
                                endif;
                            endif;
                        endforeach;
                    endif;

                    if($considerar_abono == 's'):
                        $total_faltas = $numero_faltas-$abonos;
                    endif;

                    if($considerar_abono == 'n'):
                        $aproveitamento = (($numero_aulas-$total_faltas)/$numero_aulas)*100;
                    elseif($considerar_abono == 's'):
                        $aproveitamento = ((($numero_aulas-$total_faltas)+(int)$total_abonos)/$numero_aulas)*100;
                    endif;

                $relatorio[] = array(
                    //'unidades' => $unidade->nome_fantasia,
                    'id' => $turma->id_turma,
                    'turma' => $turma->nome_turma,
                    'idioma' => $idioma->idioma,
                    'aulas_dadas' => $numero_aulas,
                    'limite_faltas' => $limite_faltas,
                    'responsavel_pedagogico' => $matricula->responsavel_pedagogico,
                    'abonos' => $total_abonos,
                    'frequencia' => count($frequencia),
                    'numero_faltas' => $total_faltas,
                    'aproveitamento' => number_format($aproveitamento, 2, '.', ''),
                    'datas_faltas' => $datas_faltas
                );

                endif;

            endforeach;
        endif;

        echo json_encode($relatorio);

    endif;

endif;


if($acao == 'turmas-finalizadas'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $considerar_abono = filter_input(INPUT_POST, 'considerar_abono', FILTER_SANITIZE_NUMBER_INT);
    $aluno = Alunos::find($id);
    $relatorio = [];
    $datas_faltas = [];

    if(autenticaAluno($login, $senha)):

        $turmas = Turmas::find_by_sql(" 
            select 
            turmas.*, 
            turmas.nome as nome_turma, 
            aulas_alunos.*, 
            alunos.*, 
            alunos.nome as nome_aluno 
            from 
            aulas_alunos 
            inner join turmas on aulas_alunos.id_turma = turmas.id 
            inner join alunos on aulas_alunos.id_aluno = alunos.id 
            where 
            alunos.id like '".$id."'
            and turmas.status = 'i'
            group by turmas.id;
        ");

        if(!empty($turmas)):
            foreach($turmas as $turma):

                try{
                    $unidade = Unidades::find($turma->id_unidade);
                } catch (\ActiveRecord\RecordNotFound $e){
                    $unidade = '';
                }

                try{
                    $idioma = Idiomas::find($turma->id_idioma);
                } catch (\ActiveRecord\RecordNotFound $e){
                    $idioma = '';
                }

                if(empty($data_final)):
                    $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id_turma, 0)));
                else:
                    $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10) and (data between ? and ?)', $turma->id_turma, 0, $data_inicial, $data_final)));
                endif;

                if(!empty($turma->limite_faltas)):
                    $limite_faltas = $turma->limite_faltas;
                else:
                    $limite_faltas = 0;
                endif;

                $numero_aulas = count($aulas);
                $dados_aluno = Alunos::find($id);
                $matricula = Matriculas::find_by_id_aluno_and_id_turma($id, $turma->id_turma);

                $abonos = 0;
                if(!empty($aulas)):
                    foreach($aulas as $aula):
                        if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_abonada($aula->id, $id, 's')):
                            $abonos++;
                        endif;
                    endforeach;
                endif;

                $frequencia = 0;
                if(!empty($aulas)):
                    foreach($aulas as $aula):
                        if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $id, 's')):
                            $frequencia++;
                        endif;
                    endforeach;
                endif;

                $numero_faltas = 0;
                if(!empty($aulas)):
                    foreach($aulas as $aula):
                        if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $id, 'n')):
                            $numero_faltas++;
                        endif;
                    endforeach;
                endif;

                if($considerar_abono == 'n'):
                    if($numero_aulas > 0):
                        //$aproveitamento = ($frequencia/$numero_aulas)*100;
                        $aproveitamento = (($numero_aulas-$numero_faltas)/$numero_aulas)*100;
                    endif;
                else:
                    if($numero_aulas > 0):
                        //$aproveitamento = (($frequencia+(int)$abonos)/$numero_aulas)*100;
                        $aproveitamento = ((($numero_aulas-$numero_faltas)+(int)$abonos)/$numero_aulas)*100;
                    endif;
                endif;

                /*pegando datas das faltas*/
                $numero_faltas = 0;
                if(!empty($aulas)):
                    foreach($aulas as $aula):
                        if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $id, 'n')):
                            $numero_faltas++;
                        endif;
                    endforeach;
                endif;


                $datas_faltas = [];
                if($numero_aulas > 0):

                    if(!empty($aulas)):
                        foreach($aulas as $aula):
                            if($considerar_abono == 'n' && $matricula->responsavel_pedagogico == 2):
                                if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $id, 'n')):
                                    $datas_faltas[] = array(
                                        'data' => $aula->data->format('d/m/Y').' - '
                                    );
                                endif;
                            else:
                                if(Aulas_Alunos::all(array('conditions' => array('id_aula = ? and id_aluno = ? and presente = ? and coalesce(abonada, "n") = ?', $aula->id, $id, 'n', 'n')))):
                                    $datas_faltas[] = array(
                                        'data' => $aula->data->format('d/m/Y').' - '
                                    );
                                endif;
                            endif;
                        endforeach;
                    endif;

                    $numero_faltas = $numero_faltas-$abonos;
                    //$aproveitamento = (($frequencia+$abonos)/$numero_aulas)*100;
                    $aproveitamento = ((($numero_aulas-$numero_faltas)+(int)$abonos)/$numero_aulas)*100;

                $relatorio[] = array(
                    //'unidades' => $unidade->nome_fantasia,
                    'id' => $turma->id_turma,
                    'turma' => $turma->nome_turma,
                    'idioma' => $idioma->idioma,
                    'aulas_dadas' => $numero_aulas,
                    'limite_faltas' => $limite_faltas,
                    'responsavel_pedagogico' => $matricula->responsavel_pedagogico,
                    'abonos' => $abonos,
                    'frequencia' => $frequencia,
                    'numero_faltas' => $numero_faltas,
                    'aproveitamento' => number_format($aproveitamento, 2, '.', ''),
                    'datas_faltas' => $datas_faltas
                );

                endif;

            endforeach;
        endif;

        echo json_encode($relatorio);

    endif;

endif;


if($acao == 'abonar'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $aluno = Alunos::find($id);

    $id_aula_turma = filter_input(INPUT_POST, 'id_aula_turma', FILTER_SANITIZE_NUMBER_INT);
    $aula_turma = Aulas_Turmas::find($id_aula_turma);

    $id_aula_aluno = filter_input(INPUT_POST, 'id_aula_aluno', FILTER_SANITIZE_NUMBER_INT);
    $aula_aluno = Aulas_Alunos::find($id_aula_aluno);

    if(autenticaAluno($login, $senha)):

        try{
            $configuracao_email = Envio_Emails::find(1);
        } catch (Exception $e) {
            $configuracao_email = '';
        }

        $justificativa = filter_input(INPUT_POST, 'justificativa', FILTER_SANITIZE_STRING);

        /*Salvando justificativa*/
        $aula_aluno->justificativa = $justificativa;
        $aula_aluno->save();

        $turma = Turmas::find($aula_turma->id_turma);
        $email_gestor = $aluno->email_gestor_pedagogico;

        if(empty($email_gestor)):
            echo json_encode(array('status' => 'erro-email', 'mensagem' => 'Desuculpe, não foi possível enviar o email ao seu gestor, tente novamente mais tarde. Caso o problema persista entre em contato com sua unidade.'));
            exit();
        endif;

        $pedido = new Pedidos_Abono();
        $pedido->token = md5($id.date('d/m/Y H:i:s').$id_aula_turma.$id_aula_aluno);
        $pedido->id_aula_turma = $id_aula_turma;
        $pedido->id_aula_aluno = $id_aula_aluno;
        $pedido->justificativa = $justificativa;
        $pedido->abonada = 'n';
        $pedido->save();
        $id_pedido = $pedido->id;

        $pedido = Pedidos_Abono::find($id_pedido);

        $mensagem = "
            <table style='font-family: Arial' width='100%'>
            <tr>
                <td align='center'><img src='".HOME."/assets/imagens/logo-iowa-idiomas.png' width='150'></td>
            </tr>
            <tr>
                <td colspan='2' height='30px'></td>
            </tr>
            <tr>
                <td align='center'>Olá gestor, o aluno {$aluno->nome} solicitou o abono da aula do dia {$aula_turma->data->format('d/m/Y')} da turma {$turma->nome}</td>
            </tr>
            <tr>
                <td colspan='2' height='30px'></td>
            </tr>
            <tr>
                <td align='center' style='background: #ebe4e7; padding: 10px; border: 1px solid #d2ccce;'>Justificativa: {$justificativa}</td>
            </tr>
            <tr>
                <td colspan='2' height='30px'></td>
            </tr>
            <tr>
                <td align='center'><a href='".HOME."/abonar-aula.php?pedido={$pedido->token}' style='display: inline-block; background: #e1e1d4; padding: 10px; text-decoration: none; color: #000000;'>Abornar Falta</a></td>
            </tr>
            <tr>
                <td colspan='2' height='30px'></td>
            </tr>
            <tr>
                <td align='center'>IOWA Idiomas</td>
            </tr>
            </table>
            ";

        include_once('../classes/PHPMailer/class.phpmailer.php');
        $mail = new PHPMailer();

        //$mail->SMTPDebug = 1;
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
        $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
        //$mail->Port = $configuracao_email->porta_smtp;
        $mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
        $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

        $mail->From = $configuracao_email->email;
        $mail->FromName = 'Abono de Falta - IOWA Idiomas';

        $mail->AddAddress($email_gestor);

        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

        $mail->Subject  = 'Abono de Falta - IOWA Idiomas'; // Assunto da mensagem
        $mail->Body = $mensagem;

        if($mail->Send()):

            if(!empty($aluno->email1)):

            /*##############################################################################*/
            /*Notificando o aluno que o pedido foi enviado sucesso*/
            $mensagem = "
            <table style='font-family: Arial' width='100%'>
            <tr>
                <td align='center'><img src='".HOME."/assets/imagens/logo-iowa-idiomas.png' width='150'></td>
            </tr>
            <tr>
                <td colspan='2' height='30px'></td>
            </tr>
            <tr>
                <td align='center'>Olá {$aluno->nome} A sua solicitação de abono de falta para o gestor {$email_gestor} foi enviada com sucesso.</td>
            </tr>
            <tr>
                <td colspan='2' height='30px'></td>
            </tr>
            <tr>
                <td align='center' style='background: #ebe4e7; padding: 10px; border: 1px solid #d2ccce;'>Justificativa: {$justificativa}</td>
            </tr>
            <tr>
                <td colspan='2' height='30px'></td>
            </tr>
            <tr>
                <td align='center'>IOWA Idiomas</td>
            </tr>
            </table>
            ";

            $mail->IsSMTP(); // Define que a mensagem será SMTP
            $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
            $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
            //$mail->Port = $configuracao_email->porta_smtp;
            $mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
            $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

            $mail->From = $configuracao_email->email;
            $mail->FromName = 'Confirmação de Pedido de Abono de Falta - IOWA Idiomas';

            $mail->AddAddress($aluno->email1);

            $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
            $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

            $mail->Subject  = 'Confirmação de Pedido de Abono de Falta - IOWA Idiomas'; // Assunto da mensagem
            $mail->Body = $mensagem;
            /*##############################################################################*/

            endif;

        endif;

        $mail->ClearAllRecipients();
        $mail->ClearAttachments();

        echo json_encode(array('status' => 'ok'));

    endif;

endif;


if($acao == 'boletos_pagar'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $aluno = Alunos::find($id);

    if(autenticaAluno($login, $senha)):

        $boletos_a_pagar = Boletos::find_by_sql("select * from parcelas inner join boletos on parcelas.id = boletos.id_parcela where parcelas.id_aluno = '{$id}' and parcelas.pagante = 'aluno' and boletos.pago = 'n' and boletos.cancelado = 'n' and boletos.renegociado = 'n' ");

        $boletos = [];

        if(!empty($boletos_a_pagar)):
            foreach($boletos_a_pagar as $boleto):
                $boletos[] = array('boleto' => $boleto->chave, 'valor' => $boleto->valor, 'data' => !(empty($boleto->data_vencimento)) ? $boleto->data_vencimento->format('d/m/Y') : '');
            endforeach;
        endif;

        echo json_encode($boletos);

    endif;

endif;


if($acao == 'boletos_pagos'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $aluno = Alunos::find($id);

    if(autenticaAluno($login, $senha)):

        $boletos_pagos = Boletos::find_by_sql("select * from parcelas inner join boletos on parcelas.id = boletos.id_parcela where parcelas.id_aluno = '{$id}' and parcelas.pagante = 'aluno' and boletos.pago = 's' and boletos.cancelado = 'n' and boletos.renegociado = 'n' ");

        $boletos = [];

        if(!empty($boletos_pagos)):
            foreach($boletos_pagos as $boleto):
                $boletos[] = array('boleto' => $boleto->chave, 'valor' => 'R$ '.number_format($boleto->valor, 2, ',', '.'), 'data' => !(empty($boleto->data_pagamento)) ? $boleto->data_pagamento->format('d/m/Y') : '');
            endforeach;
        endif;

        echo json_encode($boletos);

    endif;

endif;


if($acao == 'busca-turma'):

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

    if(autenticaAluno($login, $senha)):

        $turma = Turmas::find($id_turma);
        echo json_encode(array('nome' => $turma->nome));

    endif;

endif;



if($acao == 'recuperar-senha-aluno'):

    $data_nascimento = implode('-', array_reverse(explode('/', filter_input(INPUT_POST, 'data-nascimento', FILTER_SANITIZE_STRING))));
    $email = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL);

    if(empty($email) || empty($data_nascimento)):
        echo json_encode(array('status' => 'erro-data',  'mensagem' => 'Informe sua data de nascimento'));
        exit();
    endif;

    if(!Alunos::find(array('conditions' => array('data_nascimento = ? and (email1 = ? or email2 = ?)', $data_nascimento, $email, $email)))):
        echo json_encode(array('status' => 'erro-email',  'mensagem' => 'Desculpe, não encontramos nenhum cadastro com o email informado.'));
        exit();
    endif;

    $usuario = Alunos::find(array('conditions' => array('data_nascimento = ? and (email1 = ? or email2 = ?)', $data_nascimento, $email, $email)));

    /*Criando registro de recuperação de senha*/
    $recuperacao = new Recuperacao_Senha();
    $recuperacao->tipo = 'aluno';
    $recuperacao->id_usuario = $usuario->id;
    $recuperacao->email = $email;
    $recuperacao->data = date('Y-m-d H:i:s');
    $recuperacao->utilizado = 'n';
    $recuperacao->save();

    /*criando hash*/
    $id_recuperacao = $recuperacao->id;
    $recuperacao = Recuperacao_Senha::find($id_recuperacao);
    $recuperacao->hash = md5($id_recuperacao);
    $recuperacao->save();

    try{
        $configuracao_email = Envio_Emails::find(1);
    } catch (Exception $e) {
        $configuracao_email = '';
    }

    /*Enviando instruções*/
    $mensagem  = "Olá {$usuario->nome}, você solicitou uma recuperação de senha no sistema IOWA IDIOMAS. Siga as instruções abaixo:\r\n";
    $mensagem .= "<a href='".HOME."/nova-senha.php?recuperacao={$recuperacao->hash}'>Clique aqui</a> para abrir a página de recuperação de senha, em seguida digite sua nova senha e confirme-a no campo de baixo, depois clique em 'Salvar Nova Senha'.\r\n";
    $mensagem .= "Esta solicitação de recuperação de senha tem a validade de 24 horas, após isso, uma nova solicitação deve ser realizada.";

    include_once('../classes/PHPMailer/class.phpmailer.php');

    $mail = new PHPMailer();

    //$mail->SMTPDebug = 1;
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = $configuracao_email->smtp; // Endereço do servidor SMTP
    $mail->SMTPAuth = $configuracao_email->requer_autenticacao; // Autenticação
    //$mail->Port = $configuracao_email->porta_smtp;
    $mail->Username = $configuracao_email->email; // Usuário do servidor SMTP
    $mail->Password = $configuracao_email->senha; // Senha da caixa postal utilizada

    $mail->From = $configuracao_email->email;
    $mail->FromName = 'Recuperação de Senha - IOWA Idiomas';

    $mail->AddAddress($email, $usuario->nome);
    //$mail->AddBCC($aluno->email, $aluno->nome);

    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    $mail->Subject  = 'Recuperação de Senha - IOWA Idiomas'; // Assunto da mensagem
    $mail->Body = $mensagem;

    if($mail->Send()):
        echo json_encode(array('status' => 'ok'));
    endif;

    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

endif;
