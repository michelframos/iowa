<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'renovar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Renovação de Contrato', 'i');

    $meses_30 = array(
        4 => 4,
        6 => 6,
        9 => 9,
        11 => 11
    );

    $reajuste = $dados['reajuste'];
    $numero_parcelas = $dados['numero_parcelas'];
    $ids_parcelas = explode('|', $dados['parcelas']);

    if(!empty($ids_parcelas)):
        foreach($ids_parcelas as $id_parcela):
            if(!empty($id_parcela)):
                $parcela = Parcelas::find($id_parcela);
                $matricula = Matriculas::find($parcela->id_matricula);
                $turma = Turmas::find($matricula->id_turma);

                /*Vencimento Aluno*/
                $primeiro_vencimento = explode('/', $parcela->data_vencimento->format('d/m/Y'));
                $data_vencimento_empresa = $primeiro_vencimento[2].'-'.$primeiro_vencimento[1].'-'.$primeiro_vencimento[0];
                $mes = $primeiro_vencimento[1]+1;
                $ano = $primeiro_vencimento[2];
                $valor_reajuste = ($matricula->valor_parcela*$reajuste)/100;
                $valor = $matricula->valor_parcela+$valor_reajuste;

                /*Ajustando valor original da parcela*/
                $matricula->valor_parcela = $valor;
                $matricula->save();

                for($i=0;$i<$dados['numero_parcelas'];$i++):

                    if($mes > 12):
                        $mes = 1;
                        $ano++;
                    endif;

                    /*
                    $verifica_data = date_create($data_vencimento_empresa);
                    date_add($verifica_data, date_interval_create_from_date_string($i.' month'));

                    echo $verifica_data->format('m').'<br>';
                    */

                    /*Verificando se o proximo mês será Fevereiro*/
                    if($mes == 2 && $primeiro_vencimento[0] > 28):

                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-28'));

                    elseif(in_array($mes, $meses_30) && $primeiro_vencimento[0] > 30):

                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-30'));

                    else:

                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$primeiro_vencimento[0]));

                    endif;


                    if($matricula->responsavel_financeiro == 2):

                        $empresa = Empresas::find($matricula->id_empresa_financeiro);

                        if($valor != 0 && !empty($valor)):
                            $valor_empresa = ($valor*$matricula->porcentagem_empresa)/100;
                            $valor_aluno = $valor-$valor_empresa;
                        else:
                            $valor_empresa = 0;
                            $valor_aluno = 0;
                        endif;

                        /*Aluno*/
                        $parcela = new Parcelas();
                        $parcela->parcela = $i+1;
                        $parcela->id_matricula = $matricula->id;
                        $parcela->id_turma = $turma->id;
                        $parcela->id_idioma = $turma->id_idioma;
                        $parcela->id_empresa = $empresa->id;
                        $parcela->id_aluno = $matricula->id_aluno;
                        $parcela->pagante = 'aluno';
                        $parcela->data_vencimento = $vencimento;
                        $parcela->valor = $valor_aluno;
                        $parcela->total = $valor_aluno;
                        $parcela->pago = 'n';
                        $parcela->boleto = 'n';
                        $parcela->cancelada = 'n';
                        $parcela->renegociada = 'n';
                        $parcela->save();

                        /*Empresa*/
                        if($empresa->dia_vencimento != 0 && !empty($empresa->dia_vencimento)):

                            //$primeiro_vencimento = explode('/', $dados['data_vencimento']);
                            //$vencimento = date('d/m/Y', strtotime($meses));

                            /*Verificando se o proximo mês será Fevereiro*/
                            if($mes == 2 && $empresa->dia_vencimento > 28):
                                $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-28'));

                            elseif(in_array($mes, $meses_30) && $empresa->dia_vencimento > 30):
                                $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-30'));

                            else:
                                $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$empresa->dia_vencimento));
                            endif;


                        endif;

                        $parcela = new Parcelas();
                        $parcela->parcela = $i+1;

                        if(!empty($dados['id_motivo_renovacao'])):
                            $parcela->id_motivo = $dados['id_motivo_renovacao'];
                        else:
                            $parcela->id_motivo = null;
                        endif;

                        $parcela->id_matricula = $matricula->id;
                        $parcela->id_turma = $turma->id;
                        $parcela->id_idioma = $turma->id_idioma;
                        $parcela->id_empresa = $empresa->id;
                        $parcela->id_aluno = $matricula->id_aluno;
                        $parcela->pagante = 'empresa';
                        $parcela->data_vencimento = $vencimento;
                        $parcela->valor = $valor_empresa;
                        $parcela->total = $valor_empresa;
                        $parcela->pago = 'n';
                        $parcela->boleto = 'n';
                        $parcela->cancelada = 'n';
                        $parcela->renegociada = 'n';
                        $parcela->save();

                    else:

                        /*Responsável - Aluno ou Parente*/
                        $parcela = new Parcelas();
                        $parcela->parcela = $i+1;

                        if(!empty($dados['id_motivo_renovacao'])):
                            $parcela->id_motivo = $dados['id_motivo_renovacao'];
                        else:
                            $parcela->id_motivo = null;
                        endif;

                        $parcela->id_matricula = $matricula->id;
                        $parcela->id_turma = $turma->id;
                        $parcela->id_idioma = $turma->id_idioma;
                        $parcela->id_empresa = 0;
                        $parcela->id_aluno = $matricula->id_aluno;
                        $parcela->pagante = 'aluno';
                        $parcela->data_vencimento = $vencimento;
                        $parcela->valor = $valor;
                        $parcela->total = $valor;
                        $parcela->pago = 'n';
                        $parcela->boleto = 'n';
                        $parcela->cancelada = 'n';
                        $parcela->renegociada = 'n';
                        $parcela->save();

                    endif;

                    $mes++;
                endfor;

            endif;
        endforeach;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Renovação de Contrato', 'Alteração', 'Os contratos do mes de '.$dados['mes'].' do ano de '.$dados['ano'].' foram renovados.');

    echo json_encode(array('status' => 'ok'));

endif;
