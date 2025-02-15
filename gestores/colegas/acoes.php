<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Colegas::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Colegas IOWA', 'i');

    $registro = new Colegas();
    $registro->nome = 'Novo Colega';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Colegas IOWA', 'Inclusão', 'Um novo colega iowa foi cadastrado.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Colegas IOWA', 'a');

    /*Verificando duplicidade*/
    /*
    if($registro->cpf != $dados['cpf'] || $registro->id_idioma != $dados['idioma']):
        if(Nome_Provas::find_by_nome_and_id_idioma($dados['nome-prova'], $dados['idioma'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;
    */

    /*Salvando Alterações*/
    $registro->id_funcao = $dados['funcao'];
    $registro->id_unidade = $dados['unidade'];
    $registro->apelido = $dados['apelido'];
    $registro->nome = $dados['nome'];
    $registro->rg = $dados['rg'];

    $cpf = str_replace(".", "", $dados['cpf']);
    $cpf = str_replace("-", "", $cpf);
    $registro->cpf = $cpf;

    if(!empty($dados['data_nascimento'])):
        $registro->data_nascimento = implode('-', array_reverse(explode('/', $dados['data_nascimento'])));
    else:
        $registro->data_nascimento = null;
    endif;

    $telefone = str_replace("(", "", $dados['telefone']);
    $telefone = str_replace(")", "", $telefone);
    $telefone = str_replace("-", "", $telefone);
    $registro->telefone = $telefone;

    $celular = str_replace("(", "", $dados['celular']);
    $celular = str_replace(")", "", $celular);
    $celular = str_replace("-", "", $celular);
    $registro->celular = $celular;

    $registro->email = $dados['email'];
    $registro->endereco = $dados['endereco'];
    $registro->numero = $dados['numero'];
    $registro->bairro = $dados['bairro'];
    $registro->complemento = $dados['complemento'];
    $registro->estado = $dados['estado'];
    $registro->cidade = $dados['cidade'];
    $registro->cep = $dados['cep'];
    $registro->data_admissao = $dados['data_admissao'];
    $registro->data_demissao = $dados['data_demissao'];
    $registro->banco = $dados['banco'];
    $registro->agencia = $dados['agencia'];
    $registro->conta = $dados['conta'];

    $adm_contabil = str_replace(".", "", $dados['adm_contabil']);
    $adm_contabil = str_replace(",", ".", $adm_contabil);
    $registro->adm_contabil = $adm_contabil;

    $adm_valor_iowa = str_replace(".", "", $dados['adm_valor_iowa']);
    $adm_valor_iowa = str_replace(",", ".", $adm_valor_iowa);
    $registro->adm_valor_iowa = $adm_valor_iowa;

    $choach_valor_hora = str_replace(".", "", $dados['choach_valor_hora']);
    $choach_valor_hora = str_replace(",", ".", $choach_valor_hora);
    $registro->choach_valor_hora = $choach_valor_hora;

    $registro->coach_id_choach = $dados['coach_id_choach'];

    $instrutor_categoria = str_replace(",", ".", $dados['instrutor_categoria']);
    $registro->instrutor_categoria = $instrutor_categoria;

    $registro->instrutor_id_coach = $dados['instrutor_id_coach'];

    //dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Colegas IOWA', 'Alteração', 'O colega iowa '.$registro->nome.' com apelido '.$registro->apelido.' foi alterado.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Colegas IOWA', 'e');

    /*
    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este Colega IOWA não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;
    */

    if(Turmas::find_by_id_colega($registro->id)):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este Colega IOWA não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Colegas IOWA', 'Exclusão', 'O colega iowa '.$registro->nome.' com apelido '.$registro->apelido.' foi excluído.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Colegas IOWA', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Colegas IOWA', 'Inativação', 'O colega iowa '.$registro->nome.' com apelido '.$registro->apelido.' foi inativado.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Colegas IOWA', 'Ativação', 'O colega iowa '.$registro->nome.' com apelido '.$registro->apelido.' foi ativado.');
    endif;

endif;
