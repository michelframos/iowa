<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);


if($dados['acao'] == 'busca-turmas'):

    $turmas = Turmas::all(array('conditions' => array('id_unidade = ?', $dados['unidade']), 'order' => 'nome asc'));

    if(!empty($turmas)):
        echo '<option value="%">Todas</option>';
        foreach($turmas as $turma):
            echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
        endforeach;
    else:
        echo '<option value="%">Selecione uma Turma</option>';
    endif;

endif;


if($dados['acao'] == 'busca-alunos'):

    $status = $dados['situacao_aluno'];
    $alunos = Alunos::all(array('conditions' => array('status = ?', $status), 'order' => 'nome asc'));

    if(!empty($alunos)):
        echo '<option value=""></option>';
        foreach($alunos as $aluno):
            echo '<option value="'.$aluno->nome.'">'.$aluno->nome.'</option>';
        endforeach;
    endif;

endif;



if($dados['acao'] == 'gerar-relatorio'):

    /*situação da parcela*/
    if(isset($dados['a_receber'])):
        $a_receber = 'n';
    else:
        $a_receber = '';
    endif;

    if(isset($dados['recebidas'])):
        $recebidas = 's';
    else:
        $recebidas = '';
    endif;

    if(isset($dados['canceladas'])):
        $canceladas = 's';
    else:
        $canceladas = 'n';
    endif;


    /*situação da parcelas*/
    if(!empty($a_receber) && !empty($recebidas)):
        $situacao_parcela = ' and (pago = "'.$a_receber.'" or pago = "'.$recebidas.'") ';
    elseif(!empty($a_receber) && empty($recebidas)):
        $situacao_parcela = ' and (pago = "'.$a_receber.'") ';
    elseif(empty($a_receber) && !empty($recebidas)):
        $situacao_parcela = ' and (pago = "'.$recebidas.'") ';
    endif;

    /*Cancelada*/
    if($canceladas == 's'):
        $parcelas_canceladas = ' and (cancelada = "s" and pago like "%") ';
    elseif($canceladas == 'n'):
        $parcelas_canceladas = ' and cancelada = "n" ';
    endif;


    if(isset($dados['vencidas'])):
        $vencidas = ' and data_vencimento < now() and pago = "n" ';
    else:
        $vencidas = '';
    endif;



    if(!empty($dados['unidade'])):
        $unidade = $dados['unidade'];
    endif;

    if(!empty($dados['id_turma'])):
        $id_turma = $dados['id_turma'];
    endif;

    if(!empty($dados['id_idioma'])):
        $id_idioma = $dados['id_idioma'];
    endif;

    if(!empty($dados['id_empresa'])):
        $id_empresa = $dados['id_empresa'];
    endif;



    /*Data Vencimento*/
    if(!empty($dados['data_inicial'])):
        $data_inicial = implode('-', array_reverse(explode('/', $dados['data_inicial'])));
    endif;

    if(!empty($dados['data_final'])):
        $data_final = implode('-', array_reverse(explode('/', $dados['data_final'])));;
    endif;

    if(!empty($data_inicial) && empty($data_final)):
        $data_final = $data_inicial;
    endif;

    if(!empty($data_inicial)):
        $sql_data_vencimento = ' and data_vencimento between "'.$data_inicial.'" and "'.$data_final.'" ';
    else:
        $sql_data_vencimento = '';
    endif;
    /*Fim Data Vencimento*/



    /*Data Pagamento*/
    if(!empty($dados['data_inicial_pagamento'])):
        $data_inicial_pagamento = implode('-', array_reverse(explode('/', $dados['data_inicial_pagamento'])));
    endif;

    if(!empty($dados['data_final_pagamento'])):
        $data_final_pagamento = implode('-', array_reverse(explode('/', $dados['data_final_pagamento'])));;
    endif;

    if(!empty($data_inicial_pagamento) && empty($data_final_pagamento)):
        $data_final_pagamento = $data_inicial_pagamento;
    endif;

    if(!empty($data_inicial_pagamento)):
        $sql_data_pagamento = ' and data_pagamento between "'.$data_inicial_pagamento.'" and "'.$data_final_pagamento.'" ';
    else:
        $sql_data_pagamento = '';
    endif;
    /*Fim Data Pagamento*/



    /*tipo sacado*/
    if($dados['tipo_sacado'] == 'aluno'):
        $sacado = 'aluno';
    elseif($dados['tipo_sacado'] == 'empresa'):
        $sacado = 'empresa';
    elseif(empty($dados['tipo_sacado'])):
        $sacado = '%';
    endif;
    /*fim tipo sacado*/


    /*Situação da Matricula*/
    $situacao_matricula = $dados['situacao_aluno'];
    /*Fim Situação da Matricula*/


    /*forma de recebimento*/
    if(!empty($dados['forma_pagamento'])):
        $forma_pagamento = $dados['forma_pagamento'];
    else:
        $forma_pagamento = '%';
    endif;

    $nome_aluno = $dados['nome_aluno'];

    //$parcelas = V_Parcelas::all(array('conditions' => array('COALESCE(nome, "") like ? and pagante like ? and COALESCE(id_unidade, "") like ? and COALESCE(id_turma, "") like ? and COALESCE(id_idioma, "") like ? and id_empresa like ? and (COALESCE(pago, "") like ? or COALESCE(pago, "") like ?) and cancelada = ? and COALESCE(status_matricula, "") like ? '.$sql_data_vencimento.$sql_data_pagamento.$vencidas, $nome_aluno.'%', $sacado, $unidade, $id_turma, $id_idioma, $id_empresa, $a_receber, $recebidas, $canceladas, $situacao_matricula), 'order' => 'id_idioma, data_vencimento asc'));
    //$parcelas = V_Parcelas2::all(array('conditions' => array('COALESCE(nome, "") like ? and pagante like ? and COALESCE(id_unidade, "") like ? and COALESCE(id_turma, "") like ? and COALESCE(id_idioma, "") like ? and id_empresa like ? '.$situacao_parcela.$parcelas_canceladas.' and COALESCE(status_matricula, "") like ? and renegociada = "n" '.$sql_data_vencimento.$sql_data_pagamento.$vencidas, $nome_aluno.'%', $sacado, $unidade, $id_turma, $id_idioma, $id_empresa, $situacao_matricula), 'order' => 'COALESCE(nome_empresa, ""), COALESCE(nome, "") asc'));
    //$parcelas = V_Parcelas2::all(array('conditions' => array('COALESCE(nome, "") like ? and pagante like ? and COALESCE(id_unidade, "") like ? and COALESCE(id_turma, "") like ? and COALESCE(id_idioma, "") like ? and id_empresa like ? and COALESCE(status_matricula, "") like ?  and (COALESCE(renegociada, "") = "n" or COALESCE(renegociada, "") = "") and COALESCE(id_forma_pagamento, "") like ? '.$situacao_parcela.$parcelas_canceladas.$sql_data_vencimento.$sql_data_pagamento.$vencidas, $nome_aluno.'%', $sacado, $unidade, $id_turma, $id_idioma, $id_empresa, $situacao_matricula, $forma_pagamento ),  'order' => 'COALESCE(nome_empresa, ""), COALESCE(nome, "") asc'));
    $parcelas = V_Parcelas2::all(array('conditions' => array('COALESCE(nome, "") like ? and pagante like ? and COALESCE(id_unidade, "") like ? and COALESCE(id_turma, "") like ? and id_empresa like ? and COALESCE(status_matricula, "") like ?  and (COALESCE(renegociada, "") = "n" or COALESCE(renegociada, "") = "") and COALESCE(id_forma_pagamento, "") like ? '.$situacao_parcela.$parcelas_canceladas.$sql_data_vencimento.$sql_data_pagamento.$vencidas, $nome_aluno.'%', $sacado, $unidade, $id_turma, $id_empresa, $situacao_matricula, $forma_pagamento ),  'order' => 'COALESCE(nome_empresa, ""), COALESCE(nome, "") asc'));

    $numero_registros = 0;
    $total = 0;
    $total_pago = 0;

    echo '<div id="relatorio">';

        if(!empty($parcelas)):

            if(!empty($data_inicial)):
               echo '<h2 class="texto text-center">Data de Vencimento entre: '.$dados['data_inicial'].' e '.$dados['data_final'].'</h2>';
            endif;

    ?>
            <!--
            <h2 class="titulo">Data de Vencimento entre: <?php echo $dados['data_inicial'] ?> e <?php echo $dados['data_final'] ?></h2>
            -->

            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th width="150" class="texto-center">Data Vencto.</th>
                        <th width="150" class="texto-center">Data Pagto.</th>
                        <th class="texto-center">Sacado</th>
                        <th class="texto-center">Situação Aluno</th>
                        <th class="texto-center">Email Responsável</th>
                        <th class="texto-center">Celular</th>
                        <th class="texto-center">Turma</th>
                        <th class="texto-center">Vr</th>
                        <th class="texto-center">Vr Pago</th>
                        <th class="texto-center">Núm. Boleto</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(!empty($parcelas)):

                        foreach($parcelas as $parcela):

                            try{
                                $boleto = Boletos::find_by_sql("select * from boletos where id_parcela = {$parcela->id} order by id desc limit 1");
                            } catch(\ActiveRecord\RecordNotFound $e) {
                                $boleto = '';
                            }

                            try{
                                $turma = Turmas::find($parcela->id_turma);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $turma = '';
                            }

                            try{
                                $aluno = Alunos::find($parcela->id_aluno);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $aluno = '';
                            }

                            try{
                                $matricula = Matriculas::find($parcela->id_matricula);
                            }catch (\ActiveRecord\RecordNotFound $e){
                                $matricula = '';
                            }


                            /*Pegando dados da empresa*/
                            if($matricula->responsavel_financeiro == 2 && $matricula->id_empresa_financeiro != 0):
                                $empresa = Empresas::find($matricula->id_empresa_financeiro);
                            endif;


                            $emails = '';
                            $telefones = '';
                            /*Verificando quem é o responsavel para puxar dados*/
                            if($sacado == 'aluno' || $sacado == '%'):

                                if($matricula->responsavel_financeiro == 3):
                                    $emails = $aluno->email1;
                                    $telefones = mascara($aluno->celular, '(##)#########');
                                elseif($matricula->responsavel_financeiro == 1):
                                    $emails = $aluno->email1_responsavel;
                                    $telefones = mascara($aluno->celular_responsavel, '(##)#########');
                                endif;

                                /*
                                if(!empty($aluno->email1)):
                                    $emails.= 'E-mail Aluno: <b>'.$aluno->email1.'</b><br/>';
                                endif;

                                if(!empty($aluno->telefone1)):
                                    $telefones.= 'Tel. Aluno: <b>'.mascara($aluno->telefone1, '(##)#########').'</b><br/>';
                                endif;

                                if(!empty($aluno->email1_responsavel)):
                                    $emails.='E-mail Responsável: <b>'.$aluno->email1_responsavel.'</b><br/>';
                                endif;

                                if(!empty($aluno->telefone1_responsavel)):
                                    $telefones.= 'Tel. Responsável: <b>'.mascara($aluno->telefone1_responsavel, '(##)#########').'</b><br/>';
                                endif;
                                */
                            endif;

                            if($sacado == 'empresa' || $sacado == '%'):
                                if(!empty($empresa->email)):
                                    $emails.= 'E-mail Empresa: <b>'.$empresa->email.'</b><br/>';
                                endif;

                                if(!empty($empresa->telefone1)):
                                    $telefones.= 'Tel. Empresa: <b>'.mascara($empresa->telefone1, '(##)#########').'</b><br/>';
                                endif;
                            endif;


                            echo '<tr>';
                                echo '<td class="text-center">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                                echo !empty($parcela->data_pagamento) ? '<td class="text-center">'.$parcela->data_pagamento->format('d/m/Y').'</td>' : '<td></td>';

                                if($parcela->pagante == 'aluno'):
                                    echo '<td class="text-center">'.$parcela->nome.'</td>';
                                else:
                                    echo '<td class="text-center">'.$parcela->nome_empresa.'</td>';
                                endif;

                                if($parcela->pagante == 'aluno'):
                                    echo ($parcela->status_matricula == 'a') ? '<td class="text-center">Ativo</td>' : ($parcela->status_matricula == 'i' ? '<td class="text-center">Inativo</td>' : '<td class="text-center">Transferido</td>');
                                else:
                                    echo '<td></td>';
                                endif;


                                if($parcela->pagante == 'aluno'):
                                    echo '<td>'.$emails.'</td>';
                                elseif($parcela->pagante == 'empresa'):
                                    echo '<td>'.$empresa->email.'</td>';
                                endif;


                                echo !empty($telefones) ? '<td>'.$telefones.'</td>' : '<td></td>';
                                echo '<td class="text-center">'.$turma->nome.'</td>';


                                echo '<td class="text-right"> '.number_format($parcela->total, 2, ',','.').'</td>';
                                echo '<td class="text-right"> '.number_format($parcela->valor_pago, 2, ',','.').'</td>';

                                echo '<td class="text-center">'.$boleto[0]->numero_boleto.'</td>';
                            echo '</tr>';


                            $numero_registros++;
                            $total += $parcela->total;
                            $total_pago += $parcela->valor_pago;

                        endforeach;

                        echo '<tr>';
                        echo '<td colspan="3" class="bold size-1-5" id="total-registros">Total de Registro: '.$numero_registros.'</td>';
                        echo '<td colspan="3" class="bold size-1-5" id="total-valor">Total: R$ '.number_format($total, 2, ',', '.').'</td>';
                        echo '<td colspan="4" class="bold size-1-5" id="total-valor">Total Pago: R$ '.number_format($total_pago, 2, ',', '.').'</td>';
                        echo '</tr>';


                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
            <div class="espaco20"></div>
            <?php

    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUMA CONTA A RECEBER ENCONTRADA.</div>';

    endif;

    echo '</div>';

endif;
