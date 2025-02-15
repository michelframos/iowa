<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
try{
    $registro = Empresas::find($dados['id']);
} catch (\ActiveRecord\RecordNotFound $e){
    $registro = '';
}


if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Empresas', 'i');

    $registro = new Empresas();
    $registro->nome_fantasia = 'Nova Empresa';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Empresas', 'Inclusão', 'Uma nova empresa foi cadastrada.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Empresas', 'a');

    if(!empty($dados['login'])):
        $registro->login = $dados['login'];
    endif;

    if(!empty($dados['senha'])):
        $registro->senha = md5($dados['senha']);
    endif;

    $cnpj = str_replace('.', '', $dados['cnpj']);
    $cnpj = str_replace('/', '', $cnpj);
    $cnpj = str_replace('-', '', $cnpj);

    if($registro->cnpj != $cnpj):
        /*Verificando duplicidade*/
        if(Empresas::find_by_cnpj($cnpj)):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/

    $cep = str_replace('.', '', $dados['cep']);
    $cep = str_replace('-', '', $cep);

    $registro->nome_fantasia = $dados['nome_fantasia'];
    $registro->razao_social = $dados['razao_social'];
    $registro->cnpj = $cnpj;
    $registro->ie = $dados['ie'];
    $registro->rua = $dados['rua'];
    $registro->numero = $dados['numero'];
    $registro->bairro = $dados['bairro'];
    $registro->complemento = $dados['complemento'];
    $registro->estado = $dados['estado'];
    $registro->cidade = $dados['cidade'];
    $registro->cep = $cep;

    $telefone1 = str_replace('(', '', $dados['telefone1']);
    $telefone1 = str_replace(')', '', $telefone1);
    $telefone1 = str_replace('-', '', $telefone1);
    $registro->telefone1 = $telefone1;

    $telefone2 = str_replace('(', '', $dados['telefone2']);
    $telefone2 = str_replace(')', '', $telefone2);
    $telefone2 = str_replace('-', '', $telefone2);
    $registro->telefone2 = $telefone2;

    $valor_hora_aula_help = str_replace(".", "", $dados['valor_hora_aula_help']);
    $valor_hora_aula_help = str_replace(",", ".", $valor_hora_aula_help);
    $registro->valor_hora_aula_help = $valor_hora_aula_help;

    $registro->id_gerente = $dados['id_gerente'];

    $registro->email = $dados['email'];
    $registro->dia_vencimento = $dados['dia_vencimento'];
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Empresas', 'Alteração', 'A empresa '.$registro->nome_fantasia.' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Empresas', 'e');

    /*
    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Origem do Aluno não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;
    */

    if(Alunos::find_by_id_empresa_financeiro($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Empresa não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    if(Alunos::find_by_id_empresa_pedagogico($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Empresa não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    if(Matriculas::find_by_id_empresa_financeiro($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Empresa não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    if(Matriculas::find_by_id_empresa_pedagogico($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Empresa não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    if(Parcelas::find_by_id_empresa($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Empresa não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Empresas', 'Exclusão', 'A empresa '.$registro->nome_fantasia.' foi excluída.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Empresas', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Empresas', 'Inativação', 'A empresa '.$registro->nome_fantasia.' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Empresas', 'Ativação', 'A empresa '.$registro->nome_fantasia.' foi ativada.');
    endif;

endif;

/*--------------------------------------------------------------------------------------------------------------------*/
/*Parcelas*/
if($dados['acao'] == 'alterar-parcelas'):

    $id_parcela = explode('|', $dados['parcelas']);

    if(!empty($id_parcela)):
        foreach($id_parcela as $id):
            if(!empty($id)):

                /*
                if(!empty($dados['juros'])): $juros = $dados['juros']; endif;
                if(!empty($dados['multa'])): $multa = $dados['multa']; endif;
                if(!empty($dados['acrescimo'])): $acrescimo = $dados['acrescimo']; endif;
                if(!empty($dados['desconto'])): $desconto = $dados['desconto']; endif;
                */

                $juros_porcentagem = str_replace(',', '.', $dados['juros_porcentagem']);
                $multa_porcentagem = str_replace(',', '.', $dados['multa_porcentagem']);
                $acrescimo_porcentagem = str_replace(',', '.', $dados['acrescimo_porcentagem']);
                $desconto_porcentagem = str_replace(',', '.', $dados['desconto_porcentagem']);

                $juros_reais = str_replace(".", "", $dados['juros_reais']);
                $juros_reais = str_replace(",", ".", $juros_reais);

                $multa_reais = str_replace(".", "", $dados['multa_reais']);
                $multa_reais = str_replace(",", ".", $multa_reais);

                $acrescimo_reais = str_replace(".", "", $dados['acrescimo_reais']);
                $acrescimo_reais = str_replace(",", ".", $acrescimo_reais);

                $desconto_reais = str_replace(".", "", $dados['desconto_reais']);
                $desconto_reais = str_replace(",", ".", $desconto_reais);

                $parcela = Parcelas::find($id);

                if($parcela->pago == 'n'):

                    $juros_porcentagem = ($juros_porcentagem*$parcela->valor)/100;
                    $multa_porcentagem = ($multa_porcentagem*$parcela->valor)/100;
                    $acrescimo_porcentagem = ($acrescimo_porcentagem*$parcela->valor)/100;
                    $desconto_porcentagem = ($desconto_porcentagem*$parcela->valor)/100;

                    $juros = $juros_porcentagem+$juros_reais;
                    $multa = $multa_porcentagem+$multa_reais;
                    $acrescimo = $acrescimo_porcentagem+$acrescimo_reais;
                    $desconto = $desconto_porcentagem+$desconto_reais;

                    if(!empty($juros)):
                        $parcela->juros = $juros;
                    endif;

                    if(!empty($multa)):
                        $parcela->multa = $multa;
                    endif;

                    if(!empty($acrescimo)):
                        $parcela->acrescimo = $acrescimo;
                    endif;

                    if(!empty($desconto)):
                        $parcela->desconto = $desconto;
                    endif;


                    $total = ($parcela->valor+$juros+$multa+$acrescimo)-$desconto;

                    $parcela->total = $total;
                    $parcela->save();

                    adicionaHistorico(idUsuario(), idColega(), 'Empresas - Financeiro', 'Alteração', 'A parcela com valor de R$ '.number_format($parcela->total, 2, ',', '.').' e vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi alterada.');

                endif;

            endif;
        endforeach;

        /*Inserindo a Observação*/
        $observacao = new Alunos_Observacoes();
        $observacao->id_aluno = $registro->id;
        $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: '.$dados['observacao'];
        dadosCriacao($observacao);
        $observacao->save();

    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'zerar-valores'):

    $id_parcela = explode('|', $dados['parcelas']);

    if(!empty($id_parcela)):
        foreach($id_parcela as $id):
            if(!empty($id)):

                $parcela = Parcelas::find($id);
                $parcela->juros = 0;
                $parcela->multa = 0;
                $parcela->acrescimo = 0;
                $parcela->desconto = 0;
                $parcela->total = $parcela->valor;
                $parcela->save();

                adicionaHistorico(idUsuario(), idColega(), 'Empresas - Financeiro', 'Alteração', 'A parcela com valor de R$ '.number_format($parcela->total, 2, ',', '.').' e vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi teve seus valores adicionais zerados.');

            endif;
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'calcular-parcelas'):

    $id_parcela = explode('|', $dados['parcelas']);
    $total = 0;

    if(!empty($id_parcela)):
        foreach($id_parcela as $id):
            if(!empty($id)):

                $parcela = Parcelas::find($id);
                $total += $parcela->total;

            endif;
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok', 'total' => number_format($total, 2, ',', '.')));

endif;


if($dados['acao'] == 'quitar-parcelas'):

    $id_parcela = explode('|', $dados['parcelas']);

    if(!empty($id_parcela)):
        foreach($id_parcela as $id):
            if(!empty($id)):

                $parcela = Parcelas::find($id);
                $parcela->pago = 's';
                $parcela->id_forma_pagamento = $dados['id_forma_pagamento'];
                $parcela->data_pagamento = implode('-', array_reverse(explode('/', $dados['data_pagamento'])));
                $parcela->save();

                adicionaHistorico(idUsuario(), idColega(), 'Empresas - Financeiro', 'Alteração', 'A parcela com valor de R$ '.number_format($parcela->total, 2, ',', '.').' e vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi quitada.');

            endif;
        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir-parcela'):

    $ids_parcelas = explode('|', $dados['parcelas']);
    if(!empty($ids_parcelas)):
        foreach($ids_parcelas as $id_parcela):

            if(!empty($id_parcela)):
            $parcela = Parcelas::find($id_parcela);
            adicionaHistorico(idUsuario(), idColega(), 'Empresas - Financeiro', 'Exclusão', 'A parcela com valor de R$ '.number_format($parcela->total, 2, ',', '.').' e vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi excluída.');
            $parcela->delete();
            endif;

        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'cancelar-parcela'):

    $ids_parcelas = explode('|', $dados['parcelas']);
    if(!empty($ids_parcelas)):
        foreach($ids_parcelas as $id_parcela):

            if(!empty($id_parcela)):
                $parcela = Parcelas::find($id_parcela);
                $parcela->cancelada = 's';
                $parcela->save();

                adicionaHistorico(idUsuario(), idColega(), 'Empresas - Financeiro', 'Alteração', 'A parcela com valor de R$ '.number_format($parcela->total, 2, ',', '.').' e vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi cancelada.');

                /*Inserindo a Observação*/
                $observacao = new Empresas_Observacoes();
                $observacao->id_empresa = $registro->id;
                $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: CANCELAMENTO DE PARCELA - '.$dados['observacao'];
                dadosCriacao($observacao);
                $observacao->save();
            endif;

        endforeach;
    endif;

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'remover-pagamento'):

    $id_parcela = $dados['parcela'];
    $parcela = Parcelas::find($id_parcela);
    $parcela->pago = 'n';
    $parcela->cancelada = 'n';
    $parcela->data_pagamento = '';
    $parcela->id_forma_pagamento = 0;
    $parcela->save();

    adicionaHistorico(idUsuario(), idColega(), 'Empresas - Financeiro', 'Alteração', 'A parcela com valor de R$ '.number_format($parcela->total, 2, ',', '.').' e vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi teve seu pagamento removido.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'alterar-parcela'):

    $parcela = Parcelas::find($dados['id_parcela']);

    $valor = str_replace(".", "", $dados['valor_parcela']);
    $valor = str_replace(",", ".", $valor);

    /*Aluno*/
    $parcela->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));
    $parcela->valor = $valor;

    $total = ($parcela->valor+$parcela->juros+$parcela->multa+$parcela->acrescimo)-$parcela->desconto;
    $parcela->total = $total;
    $parcela->save();

    /*Inserindo a Observação*/
    $observacao = new Alunos_Observacoes();
    $observacao->id_aluno = $registro->id;
    $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: '.$dados['observacao'];
    dadosCriacao($observacao);
    $observacao->save();

    adicionaHistorico(idUsuario(), idColega(), 'Empresas - Financeiro', 'Alteração', 'A parcela com valor de R$ '.number_format($parcela->total, 2, ',', '.').' e vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'salvar-nova-parcela'):

    /*
    $matricula = Matriculas::find($dados['id_matricula']);
    $id_matricula = $matricula->id;
    */

    //$turma = Turmas::find($dados['id_turma']);
    //$idioma = Idiomas::find($turma->id_idioma);

    $valor = str_replace(".", "", $dados['valor_parcela']);
    $valor = str_replace(",", ".", $valor);


    $empresa = Empresas::find($registro->id);
    $numero_parcelas = $dados['numero_parcelas'];

    $meses_30 = array(
        4 => 4,
        6 => 6,
        9 => 9,
        11 => 11
    );

    /*Vencimento Aluno*/
    $primeiro_vencimento = explode('/', $dados['data_vencimento']);
    $data_vencimento_empresa = $primeiro_vencimento[2].'-'.$primeiro_vencimento[1].'-'.$primeiro_vencimento[0];

    $mes = $primeiro_vencimento[1];
    $ano = $primeiro_vencimento[2];

    for($i=1;$i<=$numero_parcelas;$i++):

        if($mes > 12):
            $mes = 1;
            $ano++;
        endif;

        /*Verificando se o proximo mês será Fevereiro*/
        if($mes == 2 && $primeiro_vencimento[0] > 28):
            $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-28'));

        elseif(in_array($mes, $meses_30) && $primeiro_vencimento[0] > 30):
            $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-30'));

        else:
            $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$primeiro_vencimento[0]));
        endif;

        $parcela = new Parcelas();
        $parcela->id_matricula = 0;
        $parcela->id_turma = 0;
        $parcela->id_idioma = 0;
        $parcela->id_empresa = $empresa->id;
        $parcela->id_aluno = 0;
        $parcela->pagante = 'empresa';
        $parcela->data_vencimento = $vencimento;
        $parcela->valor = $valor;
        $parcela->total = $valor;
        $parcela->pago = 'n';
        //$parcela->id_motivo = $dados['id_motivo'];
        $parcela->boleto = 'n';
        $parcela->cancelada = 'n';
        $parcela->save();

        $id_parcela = $parcela->id;
        $parcela = Parcelas::find($id_parcela);

        adicionaHistorico(idUsuario(), idColega(), 'Empresas - Financeiro', 'Inclusão', 'Uma parcela com valor de R$ '.number_format($parcela->total, 2, ',', '.').' e vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi incluída para a empresa '.$empresa->nome_fantasia);

        $mes++;

    endfor;

    echo json_encode(array('status' => 'ok'));

endif;
