<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Empresas::find(idEmpresa());


if($dados['acao'] == 'atualizar-dados'):

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

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'salvar-senha'):

    if(empty($dados['nova_senha'])):
        echo json_encode(array('status' => 'senha em branco'));
        exit();
    endif;

    if(md5($dados['senha_atual']) == $registro->senha):

        if($dados['nova_senha'] != $dados['confirma_senha']):
            echo json_encode(array('status' => 'senhas não correspondem'));
            exit();

        else:
            $registro->senha = md5($dados['nova_senha']);
            $registro->save();
            echo json_encode(array('status' => 'ok'));

        endif;

    else:

        echo json_encode(array('status' => 'senha atual incorreta'));

    endif;

endif;
