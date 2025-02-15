<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

$usuario = Usuarios::find(idUsuario());

if(!empty($dados)):

    try{
        $registro = Alunos::find($dados['id']);
    } catch (\ActiveRecord\RecordNotFound $e){
        $registro = '';
    }

    if($dados['acao'] == 'novo'):

        /*Verificando Permissões*/
        verificaPermissaoPost(idUsuario(), 'Alunos', 'i');

        $registro = new Alunos();
        $registro->id_situacao = 0;
        $registro->id_unidade = 0;
        $registro->id_origem = 0;
        $registro->nome = 'Novo Aluno';
        $registro->situacao_aluno = 1;
        $registro->status = 'a';
        $registro->utilizado = 'n';
        dadosCriacao($registro);
        $registro->save();

        adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos', 'Inclusão', 'Um novo Aluno foi adicionado');

        echo json_encode(array('status' => 'ok', 'id' => $registro->id));

    endif;


    if($dados['acao'] == 'verifica-cpf'):

        $cpf_aluno = str_replace('.', '', $dados['cpf_aluno']);
        $cpf_aluno = str_replace('-', '', $cpf_aluno);

        $cpf_responsavel = str_replace('.', '', $dados['cpf_responsavel']);
        $cpf_responsavel = str_replace('-', '', $cpf_responsavel);

        if(!empty($cpf_aluno)):
            if($registro->cpf != $cpf_aluno):
                if(Alunos::find_by_cpf($cpf_aluno)):
                    $registro_cpf = Alunos::find_by_cpf($cpf_aluno);
                    echo json_encode(array('status' => 'erro', 'mensagem' => 'O aluno '.$registro_cpf->nome.' já possui o CPF informado para o aluno. Deseja continuar?'));
                    exit;
                endif;
            endif;
        endif;

        if(!empty($cpf_aluno)):
            if($registro->cpf != $cpf_aluno):
                if(Alunos::find_by_cpf_responsavel($cpf_aluno)):
                    $registro_cpf = Alunos::find_by_cpf_responsavel($cpf_aluno);
                    echo json_encode(array('status' => 'erro', 'mensagem' => 'O Responsavel '.$registro_cpf->nome_responsavel.' já possui o CPF informado para o aluno. Deseja continuar?'));
                    exit;
                endif;
            endif;
        endif;

        if(!empty($cpf_responsavel)):
            if($registro->cpf_responsavel != $cpf_responsavel):
                if(Alunos::find_by_cpf($cpf_responsavel)):
                    $registro_cpf = Alunos::find_by_cpf($cpf_aluno);
                    echo json_encode(array('status' => 'erro', 'mensagem' => 'O aluno '.$registro_cpf->nome.' já possui o CPF informado para o responsavel. Deseja continuar?'));
                    exit;
                endif;
            endif;
        endif;

        if(!empty($cpf_responsavel)):
            if($registro->cpf_responsavel != $cpf_responsavel):
                if(Alunos::find_by_cpf_responsavel($cpf_responsavel)):
                    $registro_cpf = Alunos::find_by_cpf_responsavel($cpf_aluno);
                    echo json_encode(array('status' => 'erro', 'mensagem' => 'O Responsavel '.$registro_cpf->nome_responsavel.' já possui o CPF informado para o responsavel. Deseja continuar?'));
                    exit;
                endif;
            endif;
        endif;

        echo json_encode(array('status' => 'ok'));

    endif;


    if($dados['acao'] == 'salvar'):

        /*Verificando Permissões*/
        verificaPermissaoPost(idUsuario(), 'Alunos', 'a');

        if(!empty($dados['cpf'])):
            if($registro->cpf != $dados['cpf'] || $registro->id_unidade != $dados['unidade']):
                /*Verificando duplicidade*/
                if(Alunos::find_by_cpf_and_id_unidade($dados['cpf'], $dados['unidade'])):
                    echo json_encode(array('status' => 'erro'));
                    exit();
                endif;
            endif;
        endif;

        if(!empty($dados['login'])):
            if($registro->login != $dados['login']):
                /*Verificando duplicidade*/
                if(Alunos::find_by_login($dados['login'])):
                    echo json_encode(array('status' => 'erro-login'));
                    exit();
                endif;
            endif;
        endif;

        /*Salvando Alterações*/
        if(!empty($dados['situacao'])):
            $registro->id_situacao = $dados['situacao'];
        elseif(empty($dados['situacao'])):
            $registro->id_situacao = 1;
        endif;
        $registro->id_unidade = $dados['unidade'];
        $registro->id_origem = $dados['origem'];
        $registro->material = $dados['material'];

        if(!empty($dados['login'])):
            $registro->login = $dados['login'];
        endif;

        if(!empty($dados['senha'])):
            $registro->senha = md5($dados['senha']);
        endif;

        $registro->nome = $dados['nome'];
        $registro->data_nascimento = implode('-', array_reverse(explode('/', $dados['data_nascimento'])));
        $registro->rg = $dados['rg'];

        $cpf = str_replace('.', '', $dados['cpf']);
        $cpf = str_replace('-', '', $cpf);
        $registro->cpf = $cpf;

        $celular = str_replace('(', '', $dados['celular']);
        $celular = str_replace(')', '', $celular);
        $celular = str_replace('-', '', $celular);
        $registro->celular = $celular;

        $telefone1 = str_replace('(', '', $dados['telefone1']);
        $telefone1 = str_replace(')', '', $telefone1);
        $telefone1 = str_replace('-', '', $telefone1);
        $registro->telefone1 = $telefone1;

        $telefone2 = str_replace('(', '', $dados['telefone2']);
        $telefone2 = str_replace(')', '', $telefone2);
        $telefone2 = str_replace('-', '', $telefone2);
        $registro->telefone2 = $telefone2;

        $telefone3 = str_replace('(', '', $dados['telefone3']);
        $telefone3 = str_replace(')', '', $telefone3);
        $telefone3 = str_replace('-', '', $telefone3);
        $registro->telefone3 = $telefone3;

        $registro->endereco = $dados['endereco'];
        $registro->numero = $dados['numero'];
        $registro->bairro = $dados['bairro'];
        $registro->complemento = $dados['complemento'];
        $registro->estado = $dados['estado'];
        $registro->cidade = $dados['cidade'];
        $registro->nome_empresa = $dados['nome_empresa'];

        $cep = str_replace('.','', $dados['cep']);
        $cep = str_replace('-','', $cep);
        $registro->cep = $cep;

        $registro->email1 = $dados['email1'];
        $registro->email2 = $dados['email2'];
        $registro->facebook = $dados['facebook'];
        //$registro->menor = $dados[''];

        $registro->parentesco_responsavel = $dados['parentesco_responsavel'];
        $registro->nome_responsavel = $dados['nome_responsavel'];
        $registro->data_nascimento_responsavel = implode('-', array_reverse(explode('/', $dados['data_nascimento_responsavel'])));
        $registro->rg_responsavel = $dados['rg_responsavel'];

        $cpf_responsavel = str_replace('.', '', $dados['cpf_responsavel']);
        $cpf_responsavel = str_replace('-', '', $cpf_responsavel);
        $registro->cpf_responsavel = $cpf_responsavel;

        $celular_responsavel = str_replace('(', '', $dados['celular_responsavel']);
        $celular_responsavel = str_replace(')', '', $celular_responsavel);
        $celular_responsavel = str_replace('-', '', $celular_responsavel);
        $registro->celular_responsavel = $celular_responsavel;

        $telefone1 = str_replace('(', '', $dados['telefone1_responsavel']);
        $telefone1 = str_replace(')', '', $telefone1);
        $telefone1 = str_replace('-', '', $telefone1);
        $registro->telefone1_responsavel = $telefone1;

        $telefone2 = str_replace('(', '', $dados['telefone2_responsavel']);
        $telefone2 = str_replace(')', '', $telefone2);
        $telefone2 = str_replace('-', '', $telefone2);
        $registro->telefone2_responsavel = $telefone2;

        $telefone3 = str_replace('(', '', $dados['telefone3_responsavel']);
        $telefone3 = str_replace(')', '', $telefone3);
        $telefone3 = str_replace('-', '', $telefone3);
        $registro->telefone3_responsavel = $telefone3;

        $registro->endereco_responsavel = $dados['endereco_responsavel'];
        $registro->numero_responsavel = $dados['numero_responsavel'];
        $registro->bairro_responsavel = $dados['bairro_responsavel'];
        $registro->complemento_responsavel = $dados['complemento_responsavel'];
        $registro->estado_responsavel = $dados['estado_responsavel'];
        $registro->cidade_responsavel = $dados['cidade_responsavel'];

        $cep = str_replace('.','', $dados['cep_responsavel']);
        $cep = str_replace('-','', $cep);
        $registro->cep_responsavel = $cep;

        $registro->email1_responsavel = $dados['email1_responsavel'];
        $registro->email2_responsavel = $dados['email2_responsavel'];
        $registro->facebook_responsavel = $dados['facebook_responsavel'];

        $registro->email_gestor_pedagogico = $dados['email_gestor_pedagogico'];

        if(!empty($dados['situacao'])):
            $situacao = Situacao_Aluno::find_by_id($dados['situacao']);
        elseif(empty($dados['situacao'])):
            $situacao = Situacao_Aluno::find_by_id(1);
        endif;
        $registro->status = $situacao->status;

        dadosAlteracao($registro);

        $registro->save();

        $id_aluno = $registro->id;
        $dados = [
            'id_aluno' => $id_aluno,
            'caracteristicas' => $dados['caracteristicas'],
            'objetivo' => $dados['objetivo'],
            'historico' => $dados['historico'],
            'promessa' => $dados['promessa'],
        ];
        PerfisAlunosModel::salvar($dados);

        /*Verificando se situção das matrículas precisam ser alteradas*/
        if($situacao->status == 'i'):
            $matriculas = Matriculas::find_all_by_id_aluno($registro->id);
            if(!empty($matriculas)):
                foreach($matriculas as $matricula):
                    $matricula->status = 'i';
                    $matricula->save();
                endforeach;
            endif;
        endif;

        if($situacao->status == 's'):
            $matriculas = Matriculas::find_all_by_id_aluno_and_status($registro->id, 'a');
            if(!empty($matriculas)):
                foreach($matriculas as $matricula):
                    $matricula->status = 's';
                    $matricula->save();
                endforeach;
            endif;
        endif;


        adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos', 'Alteração', 'O aluno'. $registro->nome.' foi alterado .');
        echo json_encode(array('status' => 'ok'));

    endif;


    if($dados['acao'] == 'excluir'):

        /*Verificando Permissões*/
        verificaPermissaoPost(idUsuario(), 'Alunos', 'e');

        /*
        if($registro->utilizado == 's'):
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Origem do Aluno não pode ser excluída por já ter sido utilizada no sistema.'));
            exit();
        endif;
        */

        if(Matriculas::find_by_id_aluno($registro->id)):
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Origem do Aluno não pode ser excluída por já ter sido utilizada no sistema.'));
            exit();
        endif;

        if(Aulas_Alunos::find_by_id_aluno($registro->id)):
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Origem do Aluno não pode ser excluída por já ter sido utilizada no sistema.'));
            exit();
        endif;

        if(Parcelas::find_by_id_aluno($registro->id)):
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Origem do Aluno não pode ser excluída por já ter sido utilizada no sistema.'));
            exit();
        endif;

        /*excluindo observações*/
        $observacoes = Alunos_Observacoes::find_all_by_id_aluno($registro->id);
        if(!empty($observacoes)):
            foreach($observacoes as $observacao):
                $observacao->delete();
            endforeach;
        endif;

        adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos', 'Exclusão', 'O aluno '.$registro->nome.' foi excluído.');
        $registro->delete();
        echo json_encode(array('status' => 'ok', 'mensagem' => ''));

    endif;


    if($dados['acao'] == 'ativa-inativa'):

        /*Verificando Permissões*/
        verificaPermissaoPost(idUsuario(), 'Alunos', 'ai');

        if($registro->status == 'a'):
            $registro->status = 'i';
            $registro->save();
            adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos', 'Inativação', 'O aluno '.$registro->nome.' foi inativado.');
        else:
            $registro->status = 'a';
            $registro->save();
            adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos', 'Ativação', 'O aluno '.$registro->nome.' foi ativado.');
        endif;

    endif;

    if($dados['acao'] == 'calcula-idade'):

        $idade = 0;

        // Declara a data! :P
        $data = $dados['data'];

        // Separa em dia, mês e ano
        list($dia, $mes, $ano) = explode('/', $data);

        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

        // Depois apenas fazemos o cálculo já citado :)
        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

        if($idade<18):
            $registro->menor = 's';
            $registro->save();
        else:
            $registro->menor = 'n';
            $registro->save();
        endif;

        echo json_encode(array('status' => 'ok', 'idade' => $idade));

    endif;


    /*Observações*/
    if($dados['acao'] == 'salvar-observacao'):

        $observacao = new Alunos_Observacoes();
        $observacao->id_aluno = $registro->id;
        $observacao->observacao = $dados['observacao'].' - Observação inserida pelo usuario: '.$usuario->nome;
        $observacao->data_criacao = date('Y-m-d');
        dadosCriacao($observacao);
        $observacao->save();

        adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos - Observações', 'Inclusão', 'Uma nova obseração foi incluída para o aluno '.$registro->nome.'.');

        echo json_encode(array('status' => 'ok'));

    endif;

    /*Matrículas*/
    if($dados['acao'] == 'salvar-matricula'):

        /*Verificando Permissões*/
        verificaPermissaoPost(idUsuario(), 'Matriculas', 'i');

        if(Matriculas::find_by_id_aluno_and_id_turma($dados['id'], $dados['id_turma'])):
            echo json_encode(array('status' => 'erro-matricula'));
            exit();
        endif;

        $matricula = new Matriculas();
        $matricula->id_turma = $dados['id_turma'];
        $matricula->id_aluno = $dados['id'];
        $matricula->numero_parcelas = $dados['numero_parcelas'];

        $valor = str_replace(".", "", $dados['valor_parcela']);
        $valor = str_replace(",", ".", $valor);
        $matricula->valor_parcela = $valor;

        $matricula->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));
        $matricula->responsavel_financeiro = $dados['responsavel_financeiro'];
        $matricula->id_empresa_financeiro = $dados['id_empresa_financeiro'];

        $porcentagem_empresa = str_replace(".", "", $dados['porcentagem_empresa']);
        $porcentagem_empresa = str_replace(",", ".", $porcentagem_empresa);
        $matricula->porcentagem_empresa = $porcentagem_empresa;

        $matricula->responsavel_pedagogico = $dados['responsavel_pedagogico'];
        $matricula->id_empresa_pedagogico = $dados['id_empresa_pedagogico'];
        $matricula->email_gestor_pedagogico = $dados['email_gestor_pedagogico'];
        $matricula->data_matricula = date('Y-m-d');
        $matricula->id_situacao_aluno_turma = 1;
        $matricula->nova_matricula = 's';
        $matricula->status = 'a';
        dadosCriacao($matricula);
        $matricula->save();

        $id_matricula = $matricula->id;

        /*Caso o cadastro do alunos seja inativo, passa automaticamente a ser ativo*/
        $aluno = Alunos::find($dados['id']);
        $aluno->id_situacao = 1;
        $aluno->status = 'a';
        $aluno->save();

        /*Gerando ID em Alunos_Turmas*/
        $aluno_turma = new Alunos_Turmas();
        $aluno_turma->id_matricula = $id_matricula;
        $aluno_turma->id_aluno = $dados['id'];
        $aluno_turma->id_turma = $dados['id_turma'];
        $aluno_turma->save();
        /*Fim Gerando ID em Alunos_Turmas*/

        /*gerando mensalidades*/
        /*
        function somar_datas( $numero, $tipo ){
            switch ($tipo) {
                case 'd':
                    $tipo = ' day';
                    break;
                case 'm':
                    $tipo = ' month';
                    break;
                case 'y':
                    $tipo = ' year';
                    break;
            }
            return "+".$numero.$tipo;
        }
        */

        $turma = Turmas::find($dados['id_turma']);
        //$idioma = Idiomas::find($turma->id_idioma);

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
                /*
                $primeiro_vencimento = explode('/', $dados['data_vencimento']);
                $data_vencimento_empresa = $primeiro_vencimento[2].'-'.$primeiro_vencimento[1].'-'.'28';
                $vencimento = date_create($data_vencimento_empresa);
                date_add($vencimento, date_interval_create_from_date_string($i.' month'));
                */
                $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-28'));

            elseif(in_array($mes, $meses_30) && $primeiro_vencimento[0] > 30):
                /*
                $primeiro_vencimento = explode('/', $dados['data_vencimento']);
                $data_vencimento_empresa = $primeiro_vencimento[2].'-'.$primeiro_vencimento[1].'-'.'30';
                $vencimento = date_create($data_vencimento_empresa);
                date_add($vencimento, date_interval_create_from_date_string($i.' month'));
                */

                $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-30'));

            else:
                /*
                $vencimento = date_create($data_vencimento_empresa);
                date_add($vencimento, date_interval_create_from_date_string($i.' month'));
                */

                $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$primeiro_vencimento[0]));
            endif;


            if($dados['responsavel_financeiro'] == 2):

                $empresa = Empresas::find($dados['id_empresa_financeiro']);

                if($valor != 0 && !empty($valor)):
                    $valor_empresa = ($valor*$dados['porcentagem_empresa'])/100;
                    $valor_aluno = $valor-$valor_empresa;
                else:
                    $valor_empresa = 0;
                    $valor_aluno = 0;
                endif;

                /*Aluno*/
                $parcela = new Parcelas();
                $parcela->parcela = $i+1;
                $parcela->id_matricula = $id_matricula;
                $parcela->id_turma = $turma->id;
                $parcela->id_idioma = $turma->id_idioma;
                $parcela->id_empresa = $empresa->id;
                $parcela->id_aluno = $registro->id;
                $parcela->pagante = 'aluno';
                $parcela->data_vencimento = $vencimento;
                $parcela->valor = $valor_aluno;
                $parcela->total = $valor_aluno;
                $parcela->pago = 'n';
                $parcela->cancelada = 'n';
                $parcela->renegociada = 'n';
                $parcela->boleto = 'n';
                $parcela->save();

                /*Empresa*/
                if($empresa->dia_vencimento != 0 && !empty($empresa->dia_vencimento)):

                    $primeiro_vencimento = explode('/', $dados['data_vencimento']);
                    //$vencimento = date('d/m/Y', strtotime($meses));

                    /*
                    if($primeiro_vencimento[1] == 1 && $empresa->vencimento > 28):
                        $data_vencimento_empresa = $primeiro_vencimento[2].'-'.$primeiro_vencimento[1].'-'.'28';
                    else:
                        $data_vencimento_empresa = $primeiro_vencimento[2].'-'.$primeiro_vencimento[1].'-'.$empresa->dia_vencimento;
                    endif;
                    */

                    /*Verificando se o proximo mês será Fevereiro*/
                    if($mes == 2 && $empresa->dia_vencimento > 28):
                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-28'));

                    elseif(in_array($mes, $meses_30) && $empresa->dia_vencimento > 30):
                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-30'));

                    else:
                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$empresa->dia_vencimento));
                    endif;

                    /*
                    $vencimento = date_create($data_vencimento_empresa);
                    date_add($vencimento, date_interval_create_from_date_string($i.' month'));
                    */

                endif;

                $parcela = new Parcelas();
                $parcela->parcela = $i+1;
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
                $parcela->cancelada = 'n';
                $parcela->renegociada = 'n';
                $parcela->boleto = 'n';
                $parcela->save();

            else:

                /*Responsável - Aluno ou Parente*/
                $parcela = new Parcelas();
                $parcela->parcela = $i+1;
                $parcela->id_matricula = $id_matricula;
                $parcela->id_turma = $turma->id;
                $parcela->id_idioma = $turma->id_idioma;
                $parcela->id_empresa = 0;
                $parcela->id_aluno = $registro->id;
                $parcela->pagante = 'aluno';
                $parcela->data_vencimento = $vencimento;
                $parcela->valor = $valor;
                $parcela->total = $valor;
                $parcela->pago = 'n';
                $parcela->cancelada = 'n';
                $parcela->renegociada = 'n';
                $parcela->boleto = 'n';
                $parcela->save();

            endif;

            $mes++;
        endfor;

        adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos - Matrícula', 'Inclusão', 'Uma nova matrícula foi incluída para o aluno '.$registro->nome.' para a turma '.$turma->nome.'.');

        echo json_encode(array('status' => 'ok'));

    endif;


    if($dados['acao'] == 'alterar-matricula'):

        /*Verificando Permissões*/
        verificaPermissaoPost(idUsuario(), 'Matriculas', 'a');

        $matricula = Matriculas::find($dados['id_matricula']);

        /*Verificando Alunos_Turmas*/
        try{
            $aluno_turma = Alunos_Turmas::find_by_id_aluno_and_id_turma($dados['id'], $dados['id_turma']);
        } catch (\ActiveRecord\RecordNotFound $e){
            $aluno_turma = '';
        }

        if(empty($aluno_turma)):
            $aluno_turma = new Alunos_Turmas();
            $aluno_turma->id_aluno = $dados['id'];
            $aluno_turma->id_turma = $dados['id_turma'];
            $aluno_turma->save();
        endif;
        /*Fim Verificando Alunos_Turmas*/

        $matricula->id_turma = $dados['id_turma'];
        $matricula->id_aluno = $dados['id'];
        $matricula->numero_parcelas = $dados['numero_parcelas'];

        $valor = str_replace(".", "", $dados['valor_parcela']);
        $valor = str_replace(",", ".", $valor);
        $matricula->valor_parcela = $valor;

        $matricula->data_vencimento = implode('-', array_reverse(explode('/', $dados['data_vencimento'])));
        $matricula->responsavel_financeiro = $dados['responsavel_financeiro'];
        $matricula->id_empresa_financeiro = $dados['id_empresa_financeiro'];

        $porcentagem_empresa = str_replace(".", "", $dados['porcentagem_empresa']);
        $porcentagem_empresa = str_replace(",", ".", $porcentagem_empresa);
        $matricula->porcentagem_empresa = $porcentagem_empresa;

        $matricula->responsavel_pedagogico = $dados['responsavel_pedagogico'];
        $matricula->id_empresa_pedagogico = $dados['id_empresa_pedagogico'];
        $matricula->email_gestor_pedagogico = $dados['email_gestor_pedagogico'];

        if($dados['id_situacao_aluno_turma'] == 2 && $matricula->id_situacao_aluno_turma != 2):
            $matricula->data_desistencia = date('Y-m-d');
        endif;

        $matricula->id_situacao_aluno_turma = $dados['id_situacao_aluno_turma'];
        $matricula->id_motivo_desistencia = $dados['id_motivo_desistencia'];

        switch($dados['status_matricula']){
            case 'a':

                /*alterando a situação do aluno*/
                $situacao_aluno = Situacao_Aluno::find_by_status('a');
                $aluno = Alunos::find($dados['id']);
                $aluno->id_situacao = $situacao_aluno->id;
                $aluno->status = $situacao_aluno->status;
                $matricula->status = $dados['status_matricula'];

                /*salvando alterações do cadastro do aluno*/
                $aluno->save();

                $status_matricula = 1;

                break;
            case 'i':
                $matriculas = Matriculas::all(array('conditions' => array('id <> ? and id_aluno = ? and (status = ? or status = ?)', $dados['id_matricula'], $dados['id'], 'a', 's')));
                if(empty($matriculas)):

                    /*alterando a situação do aluno*/
                    $situacao_aluno = Situacao_Aluno::find_by_status('i');
                    $aluno = Alunos::find($dados['id']);
                    $aluno->id_situacao = $situacao_aluno->id;
                    $aluno->status = $situacao_aluno->status;
                    $matricula->status = $dados['status_matricula'];

                    /*salvando alterações do cadastro do aluno*/
                    $aluno->save();

                    $status_matricula = 2;
                else:
                    $matricula->status = $dados['status_matricula'];
                endif;
                break;
            case 's':
                $matriculas = Matriculas::all(array('conditions' => array('id <> ? and id_aluno = ? and (status = ? or status = ?)', $dados['id_matricula'], $dados['id'], 'a', 'i')));
                if(empty($matriculas)):
                    /*alterando a situação do aluno*/
                    $situacao_aluno = Situacao_Aluno::find_by_status('s');
                    $aluno = Alunos::find($dados['id']);
                    $aluno->id_situacao = $situacao_aluno->id;
                    $aluno->status = $situacao_aluno->status;
                    $matricula->status = $dados['status_matricula'];

                    /*salvando alterações do cadastro do aluno*/
                    $aluno->save();

                    $status_matricula = 3;
                else:
                    $matricula->status = $dados['status_matricula'];
                endif;
                break;
        }

        dadosAlteracao($matricula);
        $matricula->save();

        $turma = Turmas::find($matricula->id_turma);
        adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos - Matrícula', 'Alteração', 'A matrícula da turma '.$turma->nome.' do aluno '.$registro->nome.' foi alterada.');

        echo json_encode(array('status' => 'ok', 'status_matricula' => $status_matricula));

    endif;


    if($dados['acao'] == 'excluir-matricula'):

        /*Verificando Permissões*/
        verificaPermissaoPost(idUsuario(), 'Matriculas', 'e');

        $matricula = Matriculas::find($dados['id_matricula']);

        if(Parcelas::find_by_id_matricula_and_pago($matricula->id, 's')):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;

        $parcelas = Parcelas::find_all_by_id_matricula($matricula->id);
        if(!empty($parcelas)):
            foreach($parcelas as $parcela):
                $parcela->delete();
            endforeach;
        endif;

        $turma = Turmas::find($matricula->id_turma);
        adicionaHistorico(idUsuario(), $usuario->id_colega, 'Alunos - Matrícula', 'Exclusão', 'A matrícula da turma '.$turma->nome.' do aluno '.$registro->nome.' foi excluída.');
        $matricula->delete();

        echo json_encode(array('status' => 'ok'));

    endif;

    /*--------------------------------------------------------------------------------------------------------------------*/
    /*Parcelas*/

    if($dados['acao'] == 'alterar-parcelas'):

        $id_parcela = explode('|', $dados['parcelas']);

        if(!empty($id_parcela)):
            foreach($id_parcela as $id):
                if(!empty($id)):


                    //if(!empty($dados['juros'])): $juros = $dados['juros']; endif;
                    //if(!empty($dados['multa'])): $multa = $dados['multa']; endif;
                    //if(!empty($dados['acrescimo'])): $acrescimo = $dados['acrescimo']; endif;
                    //if(!empty($dados['desconto'])): $desconto = $dados['desconto']; endif;

                    //$juros_porcentagem = str_replace(',', '.', $dados['juros_porcentagem']);
                    //$multa_porcentagem = str_replace(',', '.', $dados['multa_porcentagem']);
                    $acrescimo_porcentagem = str_replace(',', '.', $dados['acrescimo_porcentagem']);
                    $desconto_porcentagem = str_replace(',', '.', $dados['desconto_porcentagem']);

                    if(empty($acrescimo_porcentagem)):
                        $acrescimo_porcentagem = 0;
                    endif;

                    if(empty($desconto_porcentagem)):
                        $desconto_porcentagem = 0;
                    endif;

                    /*
                    $juros_reais = str_replace(".", "", $dados['juros_reais']);
                    $juros_reais = str_replace(",", ".", $juros_reais);

                    $multa_reais = str_replace(".", "", $dados['multa_reais']);
                    $multa_reais = str_replace(",", ".", $multa_reais);
                    */

                    $acrescimo_reais = str_replace(".", "", $dados['acrescimo_reais']);
                    $acrescimo_reais = str_replace(",", ".", $acrescimo_reais);

                    $desconto_reais = str_replace(".", "", $dados['desconto_reais']);
                    $desconto_reais = str_replace(",", ".", $desconto_reais);

                    if(empty($acrescimo_reais)):
                        $acrescimo_reais = 0;
                    endif;

                    if(empty($desconto_reais)):
                        $desconto_reais = 0;
                    endif;

                    $parcela = Parcelas::find($id);

                    if($parcela->pago == 'n'):

                        //$juros_porcentagem = ($juros_porcentagem*$parcela->valor)/100;
                        //$multa_porcentagem = ($multa_porcentagem*$parcela->valor)/100;
                        $acrescimo_porcentagem = ($acrescimo_porcentagem*$parcela->total)/100;
                        $desconto_porcentagem = ($desconto_porcentagem*$parcela->total)/100;

                        //$juros = $juros_porcentagem+$juros_reais;
                        //$multa = $multa_porcentagem+$multa_reais;
                        $acrescimo = $acrescimo_porcentagem+$acrescimo_reais;
                        $desconto = $desconto_porcentagem+$desconto_reais;

                        /*
                        if(!empty($juros)):
                            $parcela->juros = $juros;
                        endif;

                        if(!empty($multa)):
                            $parcela->multa = $multa;
                        endif;
                        */

                        if(!empty($acrescimo)):
                            $parcela->acrescimo = $acrescimo;
                        endif;

                        if(!empty($desconto)):
                            $parcela->desconto = $desconto;
                        endif;


                        $total = ($parcela->total + $parcela->juros + $parcela->multa + $acrescimo)-$desconto;

                        $parcela->total = $total;
                        $parcela->save();

                    endif;

                endif;
            endforeach;

            /*Inserindo a Observação*/
            $observacao = new Alunos_Observacoes();
            $observacao->id_aluno = $registro->id;
            $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: '.$dados['observacao']. ' - Observação inserida pelo usuario: '.$usuario->nome;
            dadosCriacao($observacao);
            $observacao->save();

        endif;

        $turma = Turmas::find($parcela->id_turma);
        try{
            $aluno = Alunos::find($parcela->id_aluno);
        }catch (Exception $e){
            $aluno = '';
        }
        adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi alterada.');
        adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi alterada.');
        echo json_encode(array('status' => 'ok'));

    endif;

    if($dados['acao'] == 'alterar-vencimento'):

        $partes_novo_vencimento = explode('/', $dados['novo-vencimento']);
        $dia = $partes_novo_vencimento[0];
        $mes = $partes_novo_vencimento[1];
        $ano = $partes_novo_vencimento[2];

        $id_parcela = explode('|', $dados['parcelas']);

        $meses_30 = array(
            4 => 4,
            6 => 6,
            9 => 9,
            11 => 11
        );

        if(!empty($id_parcela)):
            foreach($id_parcela as $id):
                if(!empty($id)):

                    if($mes > 12):
                        $mes = 1;
                        $ano++;
                    endif;

                    //$novo_vencimento = $ano.'-'.$mes.'-'.$dia;

                    /*Verificando se o proximo mês será Fevereiro*/
                    if($mes == 2 && $partes_novo_vencimento[0] > 28):
                        $novo_vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-28'));
                        $vencimento_historico = date('d/m/Y', strtotime($ano.'-'.$mes.'-28'));
                    elseif(in_array($mes, $meses_30) && $partes_novo_vencimento[0] > 30):
                        $novo_vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-30'));
                        $vencimento_historico = date('d/m/Y', strtotime($ano.'-'.$mes.'-30'));
                    else:
                        $novo_vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$partes_novo_vencimento[0]));
                        $vencimento_historico = date('d/m/Y',strtotime($ano.'-'.$mes.'-'.$partes_novo_vencimento[0]));
                    endif;

                    $parcela = Parcelas::find($id);

                    /*historico*/
                    try{
                        $turma = Turmas::find($parcela->id_turma);
                    } catch (Exception $e){
                        $turma = '';
                    }

                    try{
                        $aluno = Alunos::find($parcela->id_aluno);
                    }catch (Exception $e){
                        $aluno = '';
                    }
                    adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' teve o vencimento alterado para '. $vencimento_historico.'.');
                    adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' teve o vencimento alterado para '. $vencimento_historico.'.');

                    $parcela->data_vencimento = $novo_vencimento;
                    $parcela->save();

                    $mes++;

                endif;
            endforeach;
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

                    /*historico*/
                    $turma = Turmas::find($parcela->id_turma);

                    try{
                        $aluno = Alunos::find($parcela->id_aluno);
                    }catch (Exception $e){
                        $aluno = '';
                    }

                    adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' teve os valores adicionais zerados.');
                    adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' teve os valores adicionais zerados.');

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
        verificaPermissaoPost(idUsuario(), 'Abrir Caixa', 'i');

        /*Verificando se existe caixa aberto*/
        $caixa_aberto = Caixas::find_by_id_colega_and_situacao(idUsuario(), 'aberto');

        /*
        if(!empty($caixas)):
            $caixa_selecionado = '';
            foreach($caixas as $caixa):
                if(Responsaveis_Caixa::find_by_id_caixa_and_id_usuario($caixa->id, idUsuario())):
                    $caixa_selecionado = Responsaveis_Caixa::find_by_id_caixa_and_id_usuario($caixa->id, idUsuario());
                endif;
            endforeach;
        endif;
        */

        //if(empty($caixa_selecionado)):
        if(empty($caixa_aberto)):

            echo json_encode(array('status' => 'erro-caixa'));
            exit();

        else:

            /*Verificando o total da parcela com o total de formas de pagamento*/
            $total_parcelas = str_replace('.', '', $dados['total_parcelas']);
            $total_parcelas = str_replace(',', '.', $total_parcelas);
            $total_formas_pagamento = 0;

            if(!empty($dados['forma_pagamento'])):
                foreach($dados['forma_pagamento'] as $forma):
                    $forma = str_replace('.', '', $forma);
                    $forma = str_replace(',', '.', $forma);

                    $total_formas_pagamento += $forma;
                endforeach;;
            endif;

            if($total_formas_pagamento < $total_parcelas):
                echo json_encode(array('status' => 'erro-valor', 'mensagem' => 'Valor adicionado é menor que o valor a ser pago'));
                exit();
            endif;

            if($total_formas_pagamento > $total_parcelas):
                echo json_encode(array('status' => 'erro-valor', 'mensagem' => 'Valor adicionado é maior que o valor a ser pago'));
                exit();
            endif;

            $total = 0;
            $id_parcela = explode('|', $dados['parcelas']);
            //$parcelas_recebidas = array();

            /*Contadores do Recibo*/
            $cont = 0;
            $sacado = '';
            $total = 0;
            $parcelas = '';

            if(!empty($id_parcela)):
                foreach(array_filter($id_parcela) as $id):
                    if(!empty($id)):

                        $parcela = Parcelas::find($id);

                        $data_atual = new DateTime();
                        $diferenca_dias = $parcela->data_vencimento->diff($data_atual);
                        $dias_atraso = $diferenca_dias->format('%R%a');

                        /*Verificando vencimento*/
                        if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Quitar Parcela Vencida', 'n')):
                            if($dias_atraso > 0):
                                echo json_encode(array('status' => 'erro-vencimento', 'mensagem' => 'A parcela de '.$parcela->data_vencimento->format('d/m/Y').' está vencida e precisa ser renegociada para poder ser recebida.'));
                                exit();
                            endif;
                        endif;

                        $parcela->pago = 's';
                        $parcela->valor_pago = $parcela->total;
                        $parcela->id_forma_pagamento = $dados['id_forma_pagamento'];
                        $parcela->data_pagamento = implode('-', array_reverse(explode('/', $dados['data_pagamento'])));
                        $parcela->save();

                        $turma = Turmas::find($parcela->id_turma);
                        try{
                            $aluno = Alunos::find($parcela->id_aluno);
                        }catch (Exception $e){
                            $aluno = '';
                        }
                        adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi quitada.');

                        try{
                            $boleto = Boletos::find_by_numero_boleto($parcela->numero_boleto);
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $boleto = '';
                        }

                        if(!empty($boleto)):
                            $boleto->pago = 's';
                            $boleto->data_pagamento = implode('-', array_reverse(explode('/', $dados['data_pagamento'])));
                            $boleto->valor_pago = $parcela->total;
                            $boleto->observacoes = 'Boleto pago direto na Unidade';
                            $boleto->save();
                        endif;

                        $id_aluno = $parcela->id_aluno;
                        //$parcelas_recebidas[] = $parcela->id;

                        /*Parte do Recibo*/
                        if($cont < 1):
                            $sacado = $parcela->id_aluno;
                        endif;

                        $total += $parcela->total;
                        $parcelas = $parcelas.','.$parcela->id;

                    endif;

                    $cont++;
                endforeach;

            endif;

            /*Gerando o Movimentode acordo com a quantia de formas de pagamento utilizadas*/
            if(!empty($dados['forma_pagamento'])):
                foreach ($dados['forma_pagamento'] as $id_forma_pagamento => $forma):
                    $forma = str_replace('.', '', $forma);
                    $forma = str_replace(',', '.', $forma);

                    $caixa = Caixas::find($caixa_aberto->id);
                    $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa->id), 'order' => 'numero desc', 'limit' => 1));
                    $numero_movimento = $ultimo_movimento->numero+1;

                    $movimento = new Movimentos_Caixa();
                    $movimento->id_caixa = $caixa->id;
                    $movimento->id_conta_pagar = $dados['parcelas'];
                    $movimento->numero = $numero_movimento;
                    $movimento->data = date('Y-m-d');
                    $movimento->hora = date('H:i:s');
                    //$movimento->total = $total;
                    $movimento->total = $forma;
                    $movimento->descricao = 'Pagamento de Mensalidade';
                    $movimento->id_aluno = $id_aluno;
                    $movimento->tipo = 'e';
                    //$movimento->id_forma_pagamento = $dados['id_forma_pagamento'];
                    $movimento->id_forma_pagamento = $id_forma_pagamento;
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
                                //$detalhe->total = $parcela->total;
                                $detalhe->total = $forma;
                                $detalhe->save();

                            endif;
                        endforeach;
                    endif;

                endforeach;
            endif;

            //echo json_encode(array('status' => 'ok'));

            /*Gravando dados na tabela recibos*/
            $recibo = new Recibos();
            $recibo->data = date('Y-m-d H:i:s');
            $recibo->parcelas = $parcelas;
            $recibo->total = $total;
            $recibo->id_aluno = $sacado;
            $recibo->id_usuario = $usuario->id;
            $recibo->save();

            $id_recibo = $recibo->id;

            echo json_encode(array('status' => 'ok', 'link_recibo' => HOME.'/gestores/contas-receber/imprime-recibo.php?recibo='.$id_recibo));

        endif;

    endif;


    if($dados['acao'] == 'excluir-parcela'):


        //$id_parcela = $dados['id_parcela'];

        $ids_parcelas = explode('|', $dados['parcelas']);
        if(!empty($ids_parcelas)):
            foreach($ids_parcelas as $id_parcela):

                if(!empty($id_parcela)):
                    $id_parcela = $id_parcela;
                    $parcela = Parcelas::find($id_parcela);

                    $turma = Turmas::find($parcela->id_turma);
                    try{
                        $aluno = Alunos::find($parcela->id_aluno);
                    }catch (Exception $e){
                        $aluno = '';
                    }
                    adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Exclusão', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi excluída.');
                    adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Exclusão', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi excluída.');

                    $parcela->delete();
                endif;
            endforeach;
        endif;


        echo json_encode(array('status' => 'ok'));

    endif;


    if($dados['acao'] == 'pausar-parcelas'):

        $ids_parcelas = explode('|', $dados['parcelas']);
        if(!empty($ids_parcelas)):
            foreach($ids_parcelas as $id_parcela):

                if(!empty($id_parcela)):
                    $id_parcela = $id_parcela;
                    $parcela = Parcelas::find($id_parcela);

                    $turma = Turmas::find($parcela->id_turma);
                    try{
                        $aluno = Alunos::find($parcela->id_aluno);
                    }catch (Exception $e){
                        $aluno = '';
                    }

                    if($parcela->pausada == 's'):
                        $parcela->pausada = 'n';
                        adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi despausada.');
                        adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi despausada.');
                    elseif($parcela->pausada != 's'):
                        $parcela->pausada = 's';
                        adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi pausada.');
                        adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi pausada.');
                    endif;

                    $parcela->save();
                endif;
            endforeach;
        endif;


        echo json_encode(array('status' => 'ok'));

    endif;


    if($dados['acao'] == 'cancelar-parcela'):

        //$id_parcela = $dados['id_parcela'];

        $ids_parcelas = explode('|', $dados['parcelas']);

        if(!empty($ids_parcelas)):
            foreach($ids_parcelas as $id_parcela):

                if(!empty($id_parcela)):
                    $parcela = Parcelas::find($id_parcela);
                    $parcela->cancelada = 's';
                    $parcela->save();

                    try{
                        $turma = Turmas::find($parcela->id_turma);
                    } catch (Exception $e)
                    {
                        $turma = "";
                    }

                    try{
                        $aluno = Alunos::find($parcela->id_aluno);
                    }catch (Exception $e){
                        $aluno = '';
                    }
                    adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi cancelada.');
                    adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi cancelada.');

                    /*Verificando se existe boleto para a parcela cancelada*/
                    if(!empty($parcela->numero_boleto)):
                        try{
                            $boleto = Boletos::find_by_numero_boleto_and_cancelado($parcela->numero_boleto,'n');
                        }catch (\ActiveRecord\RecordNotFound $e){
                            $boleto = '';
                        }

                        if(!empty($boleto)):
                            $boleto->cancelado = 's';
                            $boleto->save();
                        endif;

                    endif;

                    /*Inserindo a Observação*/
                    $observacao = new Alunos_Observacoes();
                    $observacao->id_aluno = $registro->id;
                    $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: CANCELAMENTO DE PARCELA - '.$dados['observacao'] .' - Observação inserida pelo usuario: '.$usuario->nome;
                    dadosCriacao($observacao);
                    $observacao->save();
                endif;

            endforeach;
        endif;

        echo json_encode(array('status' => 'ok'));

    endif;


    if($dados['acao'] == 'descancelar'):

        $id_parcela = $dados['id_parcela'];
        $parcela = Parcelas::find($id_parcela);
        $parcela->cancelada = 'n';
        $parcela->save();

        echo json_encode(['status' => 'ok']);

    endif;


    if($dados['acao'] == 'remover-pagamento'):

        /*Verificando Permissões*/
        verificaPermissaoPost(idUsuario(), 'Abrir Caixa', 'i');

        /*Verificando se existe caixa aberto*/
        $caixa_aberto = Caixas::find_by_id_colega_and_situacao(idUsuario(), 'aberto');

        if(empty($caixa_aberto)):

            echo json_encode(array('status' => 'erro-caixa'));
            exit();

        else:

            $id_parcela = $dados['parcela'];
            $parcela = Parcelas::find($id_parcela);
            $parcela->pago = 'n';
            $parcela->cancelada = 'n';
            $parcela->data_pagamento = '';
            $parcela->id_forma_pagamento = 0;
            $parcela->save();

            /*Gerando o Movimento*/
            $caixa = Caixas::find($caixa_aberto->id);
            $ultimo_movimento = Movimentos_Caixa::find(array('conditions' => array('id_caixa = ?', $caixa->id), 'order' => 'numero desc', 'limit' => 1));
            $numero_movimento = $ultimo_movimento->numero+1;

            $movimento = new Movimentos_Caixa();
            $movimento->id_caixa = $caixa->id;
            $movimento->numero = $numero_movimento;
            $movimento->data = date('Y-m-d');
            $movimento->hora = date('H:i:s');
            $movimento->total = $parcela->total;
            $movimento->descricao = 'Estorno de Pagamento de Mensalidade';
            $movimento->id_aluno = $parcela->id_aluno;
            $movimento->tipo = 's';
            //$movimento->id_forma_pagamento = $dados['id_forma_pagamento'];
            $movimento->save();

            $id_movimento = $movimento->id;

            /*Gerando detalhes do movimento*/
            $detalhe = new Detalhes_Movimento();
            $detalhe->id_movimento = $id_movimento;
            $detalhe->id_parcela = $parcela->id;
            $detalhe->numero_movimento = $numero_movimento;
            $detalhe->total = $parcela->total;
            $detalhe->save();

        endif;

        $turma = Turmas::find($parcela->id_turma);
        try{
            $aluno = Alunos::find($parcela->id_aluno);
        }catch (Exception $e){
            $aluno = '';
        }
        adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' teve seu pagamento removido.');
        adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' teve seu pagamento removido.');

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
        $observacao->observacao = 'OBSERVAÇÃO DO FINANCEIRO: '.$dados['observacao'] .' - Observação inserida pelo usuario: '.$usuario->nome;
        dadosCriacao($observacao);
        $observacao->save();

        $turma = Turmas::find($parcela->id_turma);
        try{
            $aluno = Alunos::find($parcela->id_aluno);
        }catch (Exception $e){
            $aluno = '';
        }
        adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi alterada.');
        adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Alteração', 'A parcela da turma '.$turma->nome.' do aluno '.$aluno->nome.' com vencimento em '.$parcela->data_vencimento->format('d/m/Y').' foi alterada.');

        echo json_encode(array('status' => 'ok'));

    endif;


    if($dados['acao'] == 'verifica-responsavel-financeiro'):

        $matricula = Matriculas::find($dados['id_matricula']);
        if($matricula->responsavel_financeiro == 2):
            $empresa = Empresas::find($matricula->id_empresa_financeiro);
        endif;
        echo json_encode(array('responsavel' => $matricula->responsavel_financeiro, 'empresa' => $empresa->nome_fantasia));

    endif;


    if($dados['acao'] == 'salvar-nova-parcela'):


        try{
            $matricula = Matriculas::find($dados['id_matricula']);
            $id_matricula = $matricula->id;
        } catch(\ActiveRecord\RecordNotFound $e){

        }

        if(empty($matricula)):
            $matricula = Matriculas::find_by_id_turma_and_id_aluno($dados['id_turma'], $dados['id_aluno']);
            $id_matricula = $matricula->id;
        endif;

        $numero_parcelas = $dados['numero_parcelas'];

        if(empty($numero_parcelas)):
            $numero_parcelas = 1;
        endif;

        $turma = Turmas::find($dados['id_turma']);
        $ultimo_numero = Parcelas::find(array('conditions' => array('id_turma = ? and id_matricula = ?', $turma->id, $id_matricula), 'order' => 'parcela desc', 'limit' => 1));

        //$idioma = Idiomas::find($turma->id_idioma);

        $id_aluno = $dados['id_aluno'];

        $valor = str_replace(".", "", $dados['valor_parcela']);
        $valor = str_replace(",", ".", $valor);

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

        for($i = 1;$i <= $numero_parcelas; $i++):

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

            if($matricula->responsavel_financeiro == 2):

                $valor_empresa = ($valor*$dados['porcentagem_empresa'])/100;
                $valor_aluno = $valor-$valor_empresa;

                /*Aluno*/
                $parcela = new Parcelas();
                $parcela->id_matricula = $id_matricula;
                $parcela->id_turma = $turma->id;
                $parcela->id_idioma = $turma->id_idioma;
                $parcela->id_empresa = $matricula->id_empresa_financeiro;

                if(empty($id_aluno) || $id_aluno == 'undefined'):
                    $parcela->id_aluno = $registro->id;
                else:
                    $parcela->id_aluno = $id_aluno;
                endif;

                $parcela->pagante = 'aluno';
                $parcela->parcela = $ultimo_numero+$i;
                $parcela->data_vencimento = $vencimento;
                $parcela->valor = $valor_aluno;
                $parcela->total = $valor_aluno;
                $parcela->pago = 'n';
                $parcela->id_motivo = $dados['id_motivo'];
                $parcela->cancelada = 'n';
                $parcela->renegociada = 'n';
                $parcela->boleto = 'n';
                $parcela->save();

                /*Empresa*/
                $empresa = Empresas::find($matricula->id_empresa_financeiro);
                if($empresa->dia_vencimento != 0 && !empty($empresa->dia_vencimento)):

                    if($mes == 2 && $empresa->dia_vencimento > 28):
                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-28'));

                    elseif(in_array($mes, $meses_30) && $empresa->dia_vencimento > 30):
                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-30'));

                    else:
                        $vencimento = date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$empresa->dia_vencimento));
                    endif;

                endif;

                $parcela = new Parcelas();
                $parcela->id_matricula = $id_matricula;
                $parcela->id_turma = $turma->id;
                $parcela->id_idioma = $turma->id_idioma;
                $parcela->id_empresa = $empresa->id;

                if(empty($id_aluno) || $id_aluno == 'undefined'):
                    $parcela->id_aluno = $registro->id;
                else:
                    $parcela->id_aluno = $id_aluno;
                endif;

                $parcela->pagante = 'empresa';
                $parcela->parcela = $ultimo_numero+$i;
                $parcela->data_vencimento = $vencimento;
                $parcela->valor = $valor_empresa;
                $parcela->total = $valor_empresa;
                $parcela->pago = 'n';
                $parcela->id_motivo = $dados['id_motivo'];
                $parcela->cancelada = 'n';
                $parcela->renegociada = 'n';
                $parcela->boleto = 'n';
                $parcela->save();

            else:

                /*Responsável - Aluno ou Parente*/
                $parcela = new Parcelas();
                $parcela->id_matricula = $id_matricula;
                $parcela->id_turma = $turma->id;
                $parcela->id_idioma = $turma->id_idioma;
                $parcela->id_empresa = 0;

                if(empty($id_aluno) || $id_aluno == 'undefined'):
                    $parcela->id_aluno = $registro->id;
                else:
                    $parcela->id_aluno = $id_aluno;
                endif;

                $parcela->pagante = 'aluno';
                $parcela->parcela = $ultimo_numero+$i;
                $parcela->data_vencimento = $vencimento;
                $parcela->valor = $valor;
                $parcela->total = $valor;
                $parcela->pago = 'n';
                $parcela->id_motivo = $dados['id_motivo'];
                $parcela->cancelada = 'n';
                $parcela->renegociada = 'n';
                $parcela->boleto = 'n';
                $parcela->save();

            endif;

            $mes++;

            $turma = Turmas::find($parcela->id_turma);
            try{
                $aluno = Alunos::find($parcela->id_aluno);
            }catch (Exception $e){
                $aluno = '';
            }
            adicionaHistorico(idUsuario(), idColega(), 'Alunos - Financeiro', 'Inclusão', 'Uma nova parcela para a turma '.$turma->nome.' do aluno '.$aluno->nome.' foi incluída.');
            adicionaHistorico(idUsuario(), idColega(), 'Contas a Receber', 'Inclusão', 'Uma nova parcela para a turma '.$turma->nome.' do aluno '.$aluno->nome.' foi incluída.');

        endfor;

        echo json_encode(array('status' => 'ok', 'id' => $dados['id']));

    endif;

endif;

//------------------------------------------------
//Perfil

if(empty($dados)):
    /*
    if($_POST['acao'] == 'salvar-perfil'):

        $retorno = PerfisAlunosModel::salvar($_POST);
        echo json_encode(['status' => $retorno]);

    endif;
    */

    if($_POST['acao'] == 'exportar-pdf-perfil'):

        $id_aluno = filter_input(INPUT_POST, 'id_aluno', FILTER_VALIDATE_INT);

        $aluno = Alunos::find_by_id($id_aluno);
        $matriculas = Matriculas::all(['conditions' => ['id_aluno = ?', $id_aluno], 'order' => 'data_matricula desc', 'limit' => 1]);
        $perfil = PerfisAlunosModel::find_by_id_aluno($id_aluno);

        $lista_matriculas = '';
        if(!empty($matriculas)):
            $lista_matriculas .= '
                <table class="tabela">   
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Turma</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
            ';
            foreach ($matriculas as $matricula):

                $lista_matriculas .= '
                    <tr>
                        <td>'.(!empty($matricula->data_matricula) ? $matricula->data_matricula->format('d/m/Y') : '').'</td>
                        <td>'.(Turmas::find_by_id($matricula->id_turma)->nome).'</td>
                        <td>'.($matricula->status == 'a' ? 'Ativa' : ($matricula->status == 'i' ? 'Inativa' : 'Transferência')).'</td>
                    </tr>
                ';

            endforeach;

            $lista_matriculas .= '</table>';
        endif;

        $html = '
            <div style="font-size: 18px; font-weight: bold;">'.$aluno->nome.'</div>
            <div style="height: 20px;"></div>
            
            <div style="font-size: 18px; font-weight: bold;">Matrícula</div>
            '.$lista_matriculas.'
            <div style="height: 20px;"></div>
            
            <div style="font-size: 18px; font-weight: bold;">Perfil</div>
            <div style="height: 20px;"></div>
            
            <div style="font-size: 16px; font-weight: bold;">Características</div>
            '.$perfil->caracteristicas.'
            <div style="height: 20px;"></div>
            
            <div style="font-size: 16px; font-weight: bold;">Objetivo</div>
            '.$perfil->objetivo.'
            <div style="height: 20px;"></div>
            
            <div style="font-size: 16px; font-weight: bold;">Histórico</div>
            '.$perfil->historico.'
            <div style="height: 20px;"></div>
            
            <div style="font-size: 16px; font-weight: bold;">Promessa</div>
            '.$perfil->promessa.'
            <div style="height: 20px;"></div>
            
        ';

        if(!file_exists('../../perfis-pdf')):
            mkdir('../../perfis-pdf', 0777, true);
        endif;

        $mpdf = new \Mpdf\Mpdf();
        $stylesheet = file_get_contents('../../assets/css/impressos.css');
        $mpdf->SetFooter("impresso em " . date("d/m/Y") . " às " . date("H:i:s") . ' - Página: {PAGENO}');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($html,2);
        $mpdf->Output('../../perfis-pdf/perfil-'.(URLify::slug($aluno->nome)).'.pdf');

        echo json_encode(['status' => 'ok', 'url' => HOME.'/perfis-pdf/perfil-'.(URLify::slug($aluno->nome)).'.pdf']);

    endif;
endif;
