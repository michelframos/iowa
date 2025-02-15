<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Unidades::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Unidades', 'i');

    $registro = new Unidades();
    $registro->nome_fantasia = 'Nova Unidade';
    $registro->desconto_ate_vencimento = 'n';
    $registro->incluir_mora_multa = 'n';
    $registro->informar_descontos_adicionais = 'n';
    $registro->protestar_atrasados = 'n';
    $registro->usar_dados_boleto = 'n';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    $id_unidade = $registro->id;
    $unidade = Unidades::find($id_unidade);
    $unidade->chave = md5($id_unidade);
    $unidade->save();

    adicionaHistorico(idUsuario(), idColega(), 'Unidades', 'Inclusão', 'Uma nova Unidade foi cadastrada.');

    echo json_encode(array('status' => 'ok', 'id' => $id_unidade));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Unidades', 'a');

    $cnpj = str_replace('.', '', $dados['cnpj']);
    $cnpj = str_replace('/', '', $cnpj);
    $cnpj = str_replace('-', '', $cnpj);

    /*
    if($registro->cnpj != $cnpj):
        if(Unidades::find_by_cnpj($cnpj)):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;
    */

    /*Salvando Alterações*/
    $cep = str_replace('.', '', $dados['cep']);
    $cep = str_replace('-', '', $cep);

    $juros = str_replace(".", "", $dados['juros']);
    $juros = str_replace(",", ".", $juros);

    $multa = str_replace(".", "", $dados['multa']);
    $multa = str_replace(",", ".", $multa);

    $registro->cnpj = $cnpj;
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

    $registro->proximo_boleto = $dados['proximo_boleto'];
    $registro->numero_banco = $dados['numero_banco'];
    $registro->carteira = $dados['carteira'];
    $registro->especie = $dados['especie'];
    $registro->agencia = $dados['agencia'];
    $registro->conta = $dados['conta'];
    $registro->codigo_cliente = $dados['codigo_cliente'];
    $registro->juros = $juros;
    $registro->multa = $multa;
    $registro->razao_social = $dados['razao_social'];
    $registro->nome_fantasia = $dados['nome_fantasia'];
    $registro->local_pag_antes_vencto = $dados['local_pag_antes_vencto'];
    $registro->local_pag_depois_vencto = $dados['local_pag_depois_vencto'];

    $registro->numero_sequencial = $dados['numero_sequencial'];
    $registro->impressao_bolelto = $dados['impressao_bolelto'];
    $registro->dias_para_protestar = $dados['dias_para_protestar'];
    $registro->beneficiario = $dados['beneficiario'];
    $registro->boleto_posicao_inicial_leitura = $dados['boleto_posicao_inicial_leitura'];
    $registro->boleto_numero_caracteres = $dados['boleto_numero_caracteres'];
    $registro->data_pag_posicao_inicial_leitura = $dados['data_pag_posicao_inicial_leitura'];
    $registro->data_pag_numero_caracteres = $dados['data_pag_numero_caracteres'];

    $registro->save();

    UnidadesBancosModel::salvar($dados);

    adicionaHistorico(idUsuario(), idColega(), 'Unidades', 'Alteração', 'A Unidade '.$registro->nome_fantasia.' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Unidades', 'e');

    /*
    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este idioma não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;
    */

    if(Colegas::find_by_id_unidade($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Unidade não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    if(Turmas::find_by_id_unidade($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Unidade não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    if(Alunos::find_by_id_unidade($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Unidade não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Unidades', 'Exclusão', 'A Unidade '.$registro->nome_fantasia.' foi excluída.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Unidades', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Unidades', 'Inativação', 'A Unidade '.$registro->nome_fantasia.' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Unidades', 'Ativação', 'A Unidade '.$registro->nome_fantasia.' foi ativada.');
    endif;

endif;

if($dados['acao'] == 'usar-dados-boleto'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Unidades', 'ai');

    $usar_dados = Unidades::find_all_by_usar_dados_boleto('s');

    if($registro->usar_dados_boleto == 's'):
        $registro->usar_dados_boleto = 'n';
        $registro->save();
    else:
        if(count($usar_dados) < 1):
            $registro->usar_dados_boleto = 's';
            $registro->save();
        else:
            echo json_encode(array('status' => 'erro'));
        endif;
    endif;

endif;


if($dados['acao'] == 'desconto_ate_vencimento'):

    if($registro->desconto_ate_vencimento == 'n'):
        $registro->desconto_ate_vencimento = 's';
        $registro->save();
    else:
        $registro->desconto_ate_vencimento = 'n';
        $registro->save();
    endif;

endif;


if($dados['acao'] == 'incluir_mora_multa'):

    if($registro->incluir_mora_multa == 'n'):
        $registro->incluir_mora_multa = 's';
        $registro->save();
    else:
        $registro->incluir_mora_multa = 'n';
        $registro->save();
    endif;

endif;


if($dados['acao'] == 'protestar_atrasados'):

    if($registro->protestar_atrasados == 'n'):
        $registro->protestar_atrasados = 's';
        $registro->save();
    else:
        $registro->protestar_atrasados = 'n';
        $registro->save();
    endif;

endif;


if($dados['acao'] == 'informar_descontos_adicionais'):

    if($registro->informar_descontos_adicionais == 'n'):
        $registro->informar_descontos_adicionais = 's';
        $registro->save();
    else:
        $registro->informar_descontos_adicionais = 'n';
        $registro->save();
    endif;

endif;

