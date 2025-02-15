<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'listar-alunos'):

    /*
    $alunos_turma = Alunos_Turmas::find_all_by_id_turma($dados['id_turma']);
    if(!empty($alunos_turma)):

        echo '<div class="table-responsive">';
        echo '<table class="table">';
        echo '<tbody>';

        foreach($alunos_turma as $aluno_turma):
            $aluno = Alunos::find($aluno_turma->id_aluno);

            echo '<tr>';
            echo '<td>';
                echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                    echo '<input type="checkbox" value="'.$aluno->id.'" class="alunos">';
                    echo '<span></span>';
                    echo '</label>';
                echo '</td>';

            echo '<td data-title="Idioma">'.$aluno->nome.'</td>';
            echo '</tr>';

        endforeach;

        echo '</tbody>';
        echo '</table>';
        echo '</div>';

    endif;
    */

    $turma = Alunos_Turmas::find_all_by_id_turma($dados['id_turma']);
    if(!empty($turma)):
        foreach($turma as $aluno_turma):
            $aluno = Alunos::find($aluno_turma->id_aluno);
            echo '<option value="'.$aluno->id.'">'.$aluno->nome.'</option>';
        endforeach;
    endif;

endif;


/*--------------------------------------------------------------------------------------------------------------------*/
/*Parcelas*/

if($dados['acao'] == 'alterar-parcelas'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Receber', 'a');

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

                endif;

            endif;
        endforeach;

        /*Inserindo a Observação*/
        $observacao = new Alunos_Observacoes();
        $observacao->id_aluno = $registro->id;
        $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: '.$dados['observacao'] .' - Observação inserida pelo usuario: '.$usuario->nome;
        dadosCriacao($observacao);
        $observacao->save();

    endif;

    echo json_encode(array('status' => 'ok'));

endif;



if($dados['acao'] == 'zerar-valores'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Receber', 'a');

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

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Receber', 'a');

    /*Verificando se existe caixa aberto*/
    $caixas = Caixas::find_all_by_situacao('aberto');

    if(!empty($caixas)):
        $caixa_selecionado = '';
        foreach($caixas as $caixa):
            /*Vendo se o usuario logado é responsável por algum caixa*/
            if(Responsaveis_Caixa::find_by_id_caixa_and_id_usuario($caixa->id, idUsuario())):
                $caixa_selecionado = Responsaveis_Caixa::find_by_id_caixa_and_id_usuario($caixa->id, idUsuario());
            endif;
        endforeach;
    endif;


    if(empty($caixa_selecionado)):

        echo json_encode(array('status' => 'erro-caixa'));
        exit();

    else:

        $total = 0;
        $id_parcela = explode('|', $dados['parcelas']);

        if(!empty($id_parcela)):
            foreach($id_parcela as $id):
                if(!empty($id)):

                    $parcela = Parcelas::find($id);

                    $data_atual = new DateTime();
                    $diferenca_dias = $parcela->data_vencimento->diff($data_atual);
                    $dias_atraso = $diferenca_dias->format('%R%a');

                    /*Verificando vencimento*/
                    if($dias_atraso > 0):
                        echo json_encode(array('status' => 'erro-vencimento', 'mensagem' => 'A parcela de '.$parcela->data_vencimento->format('d/m/Y').' está vencida e precisa ser renegociada para poder ser recebida.'));
                        exit();
                    endif;

                    $parcela->pago = 's';
                    $parcela->id_forma_pagamento = $dados['id_forma_pagamento'];
                    $parcela->data_pagamento = implode('-', array_reverse(explode('/', $dados['data_pagamento'])));
                    $parcela->save();

                    $total += $parcela->total;
                    $id_aluno = $parcela->id_aluno;

                endif;
            endforeach;
        endif;

        /*Gerando o Movimento*/
        $caixa = Caixas::find($caixa_selecionado->id_caixa);
        $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa->id), 'order' => 'numero desc', 'limit' => 1));
        $numero_movimento = $ultimo_movimento->numero+1;

        $movimento = new Movimentos_Caixa();
        $movimento->id_caixa = $caixa->id;
        $movimento->numero = $numero_movimento;
        $movimento->data = date('Y-m-d');
        $movimento->hora = date('H:i:s');
        $movimento->total = $total;
        $movimento->descricao = 'Pagamento de Mensalidade';
        $movimento->id_aluno = $id_aluno;
        $movimento->tipo = 'e';
        $movimento->id_forma_pagamento = $dados['id_forma_pagamento'];
        $movimento->save();

        $id_movimento = $movimento->id;
        /*Gerando detalhes do movimento*/
        if(!empty($id_parcela)):
            foreach($id_parcela as $id):
                if(!empty($id)):

                    $parcela = Parcelas::find($id);

                    $detalhe = new Detalhes_Movimento();
                    $detalhe->id_movimento = $id_movimento;
                    $detalhe->id_parcela = $parcela->id;
                    $detalhe->numero_movimento = $numero_movimento;
                    $detalhe->total = $parcela->total;
                    $detalhe->save();

                endif;
            endforeach;
        endif;

        echo json_encode(array('status' => 'ok'));

    endif;

endif;


if($dados['acao'] == 'excluir-parcela'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Receber', 'e');

    $id_parcela = $dados['parcela'];
    $parcela = Parcelas::find($id_parcela);
    $parcela->delete();

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'cancelar-parcela'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Receber', 'a');

    $id_parcela = $dados['id_parcela'];
    $parcela = Parcelas::find($id_parcela);
    $parcela->cancelada = 's';
    $parcela->save();

    /*Inserindo a Observação*/
    $observacao = new Alunos_Observacoes();
    $observacao->id_aluno = $registro->id;
    $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: CANCELAMENTO DE PARCELA - '.$dados['observacao'] .' - Observação inserida pelo usuario: '.$usuario->nome;;
    dadosCriacao($observacao);
    $observacao->save();

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'remover-pagamento'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Receber', 'a');

    $id_parcela = $dados['parcela'];
    $parcela = Parcelas::find($id_parcela);
    $parcela->pago = 'n';
    $parcela->cancelada = 'n';
    $parcela->data_pagamento = '';
    $parcela->id_forma_pagamento = 0;
    $parcela->save();

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'alterar-parcela'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Receber', 'a');

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
    $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: '.$dados['observacao'] .' - Observação inserida pelo usuario: '.$usuario->nome;;
    dadosCriacao($observacao);
    $observacao->save();

    echo json_encode(array('status' => 'ok'));

endif;



if($dados['acao'] == 'verifica-responsavel-financeiro'):

    $matricula = Matriculas::find($dados['id_matricula']);
    if($matricula->responsavel_financeiro == 2):
        $empresa = Empresas::find($matricula->responsavel_financeiro);
    endif;
    echo json_encode(array('responsavel' => $matricula->responsavel_financeiro, 'empresa' => $empresa->nome_fantasia));

endif;



if($dados['acao'] == 'salvar-nova-parcela'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Contas a Receber', 'i');

    $matricula = Matriculas::find($dados['id_matricula']);
    $id_matricula = $matricula->id;

    $turma = Turmas::find($dados['id_turma']);
    //$idioma = Idiomas::find($turma->id_idioma);

    $valor = str_replace(".", "", $dados['valor_parcela']);
    $valor = str_replace(",", ".", $valor);

    if($matricula->responsavel_financeiro == 2):

        $valor_empresa = ($valor*$dados['porcentagem_empresa'])/100;
        $valor_aluno = $valor-$valor_empresa;

        /*Aluno*/
        $parcela = new Parcelas();
        $parcela->id_matricula = $id_matricula;
        $parcela->id_turma = $turma->id;
        $parcela->id_idioma = $turma->id_idioma;
        $parcela->id_aluno = $registro->id;
        $parcela->pagante = 'aluno';
        $parcela->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));
        $parcela->valor = $valor_aluno;
        $parcela->total = $valor_aluno;
        $parcela->pago = 'n';
        $parcela->id_motivo = $dados['id_motivo'];
        $parcela->cancelada = 'n';
        $parcela->boleto = 'n';
        $parcela->save();

        /*Empresa*/
        $empresa = Empresas::find($matricula->id_empresa_financeiro);
        if($empresa->dia_vencimento != 0 && !empty($empresa->dia_vencimento)):

            $primeiro_vencimento = explode('/', $dados['data_vencimento']);
            //$vencimento = date('d/m/Y', strtotime($meses));
            $data_vencimento_empresa = $primeiro_vencimento[2].'-'.$primeiro_vencimento[1].'-'.$empresa->dia_vencimento;

            /*
            $dia = $empresa->dia_vencimento;
            $mes = date('m');
            $ano = date('Y');

            $data_vencimento_empresa = $ano.'-'.$mes.'-'.$dia;
            */

            $vencimento = date_create($data_vencimento_empresa);

        else:

            $vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));

        endif;

        $parcela = new Parcelas();
        $parcela->id_matricula = $id_matricula;
        $parcela->id_turma = $turma->id;
        $parcela->id_idioma = $turma->id_idioma;
        $parcela->id_empresa = $empresa->id;
        $parcela->id_aluno = $registro->id;
        $parcela->pagante = 'empresa';
        $parcela->data_vencimento = $vencimento;
        $parcela->valor = $valor_empresa;
        $parcela->total = $valor_empresa;
        $parcela->pago = 'n';
        $parcela->id_motivo = $dados['id_motivo'];
        $parcela->cancelada = 'n';
        $parcela->boleto = 'n';
        $parcela->save();

    else:

        /*Responsável - Aluno ou Parente*/
        $parcela = new Parcelas();
        $parcela->id_matricula = $id_matricula;
        $parcela->id_turma = $turma->id;
        $parcela->id_idioma = $turma->id_idioma;
        $parcela->id_aluno = $registro->id;
        $parcela->pagante = 'aluno';
        $parcela->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));
        $parcela->valor = $valor;
        $parcela->total = $valor;
        $parcela->pago = 'n';
        $parcela->id_motivo = $dados['id_motivo'];
        $parcela->cancelada = 'n';
        $parcela->boleto = 'n';
        $parcela->save();

    endif;


    echo json_encode(array('status' => 'ok'));

endif;


/*renegociar parcela*/
if($dados['acao'] == 'gerar'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Gestão de Boletos', 'i');

    /*Gerar novo boleto e arquivo cnab*/
    try{
        $opcoes_cobranca = Opcoes_Cobranca::find(1);
    } catch(\ActiveRecord\RecordNotFound $e){
        $opcoes_cobranca = '';
    }

    $parcela = Parcelas::find($dados['id_parcela']);

    $nova_parcela = new Parcelas();
    $nova_parcela->id_matricula = $parcela->id_matricula;
    $nova_parcela->id_turma = $parcela->id_turma;
    $nova_parcela->id_idioma = $parcela->id_idioma;
    $nova_parcela->id_aluno = $parcela->id_aluno;
    $nova_parcela->id_empresa = $parcela->id_empresa;
    $nova_parcela->pagante = $parcela->pagante;
    $nova_parcela->id_motivo = $parcela->id_motivo;
    $nova_parcela->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));

    $data_atual = new DateTime("now");
    $dias = $parcela->data_vencimento->diff($data_atual);
    //$dias_atraso = $dias->d;
    $dias_atraso = $dias->format('%R%a');

    $valor = str_replace(".", "", $dados['valor_parcela']);
    $valor = str_replace(",", ".", $valor);
    $nova_parcela->valor = $valor;

    if($dados['importar_acrescimos'] == 's'):
        if($dias_atraso > 0):
            $multa = $valor*($opcoes_cobranca->multa/100);
        else:
            $multa = 0;
        endif;

        if($dias_atraso > 0):
            $juros_mora = ($valor*($opcoes_cobranca->juros/100))*$dias_atraso;
        else:
            $juros_mora = 0;
        endif;
    elseif($dados['importar_acrescimos'] == 'n'):
        $multa = 0;
        $juros_mora = 0;
    endif;

    $nova_parcela->juros = $juros_mora;


    /*importação de acrescimos*/
    if($dados['importar_acrescimos'] == 's'):
        $nova_parcela->juros = $juros_mora;
        $nova_parcela->multa = $multa;
        $nova_parcela->acrescimo = $parcela->acrescimo;
        $nova_parcela->total = $valor /*+ $parcela->acrescimo + $multa + $juros_mora*/;
    else:
        $nova_parcela->juros = 0;
        $nova_parcela->multa = 0;
        $nova_parcela->acrescimo = 0;
        $nova_parcela->total = $valor;
    endif;

    $nova_parcela->desconto = 0;
    $nova_parcela->pago = 'n';
    $nova_parcela->cancelada = 'n';
    $nova_parcela->renegociada = 'n';
    $nova_parcela->boleto = 'n';
    $nova_parcela->save();

    $id_nova_parcela = $nova_parcela->id;

    /*Cenceslando Parcela Original*/
    $parcela->cancelada = 's';
    $parcela->renegociada = 's';
    $parcela->observacoes = 'Parcela renegociada em '.date('d/m/Y H:i:s');
    $parcela->save();
    //criar observação dizendo que a parcela foi cancelada

    /*verificando se exite boleto e cancelando*/
    if(!empty($parcela->numero_boleto)):
        if(Boletos::find(array('conditions' => array('numero_boleto = ? and pago = ? and cancelado = ? and renegociado = ?', $parcela->numero_boleto, 'n', 'n', 'n')))):
            $boleto = Boletos::find(array('conditions' => array('numero_boleto = ? and pago = ? and cancelado = ? and renegociado = ?', $parcela->numero_boleto, 'n', 'n', 'n')));
            $boleto->renegociado = 's';
            $boleto->save();
        endif;
    endif;

    echo json_encode(array('status' => 'ok'));

    /*-----------------------------------------------------------------------------*/
    /*-----------------------------------------------------------------------------*/

endif;
