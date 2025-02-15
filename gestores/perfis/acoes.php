<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Perfis::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Categorias de Usuários', 'i');

    $registro = new Perfis();
    $registro->perfil = 'Nova Categoria de Usuário';
    $registro->status = 'a';
    $registro->utilizado = 'n';
    $registro->listar_como_gerente = 'n';
    dadosCriacao($registro);
    $registro->save();

    $id_perfil = $registro->id;
    $permissoes = Permissoes_Perfil::all(array('conditions' => array('id_perfil = ?', $id), 'order' => 'ordem asc'));

    /*Lista de Permissões*/
    /*
     *Opções de permissão
     * p - permitido
     * a - alterar
     * i - incluir
     * e - excluir
     * c - consultar
     * ai - ativa/inativa
     * imp - imprimir
     */
    $array_permissoes = array(
        array('Categorias de Usuários' => 'a,i,e,c,ai'),
        array('Usuários' => 'a,i,e,c,ai'),
        array('Histórico de Ações dos Usuários' => 'c'),
        array('Idiomas' => 'a,i,e,c,ai'),
        array('Nomes de Provas' => 'a,i,e,c,ai'),
        array('Sistemade Notas' => 'a,i,e,c,ai'),
        array('Unidades' => 'a,i,e,c,ai'),
        array('Valores Hora/Aula' => 'a,i,e,c,ai'),
        array('Nomes de Produtos e Horas Semanais' => 'a,i,e,c,ai'),
        array('Programação e Conteúdo de Aulas' => 'a,i,e,c,ai'),
        array('Origem do Aluno' => 'a,i,e,c,ai'),
        array('Empresas' => 'a,i,e,c,ai'),
        array('Colegas IOWA' => 'a,i,e,c,ai'),

        array('Alunos' => 'a,i,e,c,ai'),
        array('Matriculas' => 'a,i,e,c,ai'),
        array('Promoções' => 'a,i,e,c,ai'),

        array('Motivos de Desistência' => 'a,i,e,c,ai'),
        array('Editor de Documentos' => 'a,i,e,c'),

        array('Turmas' => 'a,i,e,c,ai'),
        array('Visualizar Turmas Inativas' => 'c'),
        array('Mudança de Estágio' => 'a'),
        array('Transfereir Aluno' => 'i'),
        array('Alterar Notas dos Alunos' => 'a'),
        array('Registrar Aula no Diario de Classe' => 'a'),
        array('Registrar Aulas Somente nas Classes em que é Instrutor' => 'a'),
        //array('Adicionas Aulas' => 'i'),
        array('Mostrar Botão Adicionar Aulas' => 'p'), //adicionado em 02/12/2022
        array('Mostrar Botão Adicionar Pacote' => 'p'), //adicionado em 07/10/2023
        array('Alterar Dia' => 'a'),
        array('Alterar Horário' => 'a'),
        array('Alterar Programação' => 'a'),
        array('Alterar Instrutor' => 'a'),
        array('Alterar Valor Hora/Aula' => 'a'),
        array('Alterar Sistema de Notas' => 'a'),

        array('Abrir Caixa' => 'i'),
        array('Fechar Caixa' => 'i'),
        array('Fazer Transferência' => 'i'),
        array('Ver Todos os Caixas' => 'i'),

        array('Fornecedores' => 'a,i,e,c,ai'),
        array('Categorias de Lançamentos' => 'a,i,e,c,ai'),
        array('Formas de Recebimento/Pagamento' => 'a,i,e,c,ai'),
        array('Geração de Cobrança' => 'a,i,c,imp'),
        array('Gestão de Boletos' => 'i,c,imp'),
        array('Natureza de Contas a Pagar' => 'a,i,e,c,ai'),
        array('Pausar Parcela' => 'a'),
        array('Valor Original da Parcela' => 'a,c'),
        array('Contas a Receber' => 'a,i,e,c'),
        array('Quitar Parcela' => 'a'),
        array('Quitar Parcela Vencida' => 'a'),
        array('Remover Acréscimos' => 'a'),
        array('Contas a Pagar' => 'a,i,e,c'),
        array('Renovação de Contrato' => 'i,c'),

        array('Help' => 'i,c'),
        array('Aprovar Help' => 'a'),
        array('Cancelar Help' => 'a'),

        array('Coachs' => 'i,c'),
        array('Coachs - Criar Ata Para Turma' => 'i'),
        array('Coachs - Criar Ata Para Aluno' => 'i'),
        array('Coachs - Consultar Atas da Turma' => 'c'),
        array('Coachs - Consultar Atas do Aluno' => 'c'),
        array('Coachs - Altera Ata da Turma' => 'a'),
        array('Coachs - Altera Ata do Aluno' => 'a'),

        array('Relatório - Colegas IOWA' => 'c,imp'),
        array('Relatório - Folha de Pagamento' => 'c,imp'),
        array('Relatório - Folha de Pagamento Por Unidade' => 'c,imp'),
        array('Relatório - Turmas' => 'c,imp'),
        array('Relatório - Alunos / Turmas' => 'c,imp'), //adicionado em 02/12/2022
        array('Relatório - Alunos / Empresas' => 'c,imp'), //adicionado em 18/08/2023
        array('Relatório - Ocorrências ou Aulas' => 'c,imp'),
        array('Relatório - Frequencia' => 'c,imp'),
        array('Relatório - F7' => 'c,imp'), //adicionado em 24/10/2023
        array('Relatório - Consolidado de Faltas' => 'c,imp'),
        array('Relatório - Contas a Receber' => 'c,imp'),
        array('Relatório - Contas a Pagar' => 'c,imp'),
        array('Relatório - Faturamento' => 'c,imp'),
        array('Relatório - Matrículas Efetuadas' => 'c,imp'),
        array('Relatório - Matrículas Por Unidade' => 'c,imp'),
        array('Relatório - Inativação de Aluno' => 'c,imp'),
        array('Relatório - Helps' => 'c,imp'),
        array('Relatório - Aniversariantes' => 'c,imp'),
        array('Relatório - Aluno - Material' => 'c,imp'),
        array('Relatório - Alunos Por Unidade' => 'c,imp'),
        array('Relatório - Email Marketing' => 'c,imp'),
    );

    /*Criando Permissoes do Perfil conforme lista acima*/
    foreach($array_permissoes as $ordem => $permissoes):
        foreach($permissoes as $tela => $opcoes):

            if(!Permissoes_Perfil::find_by_id_perfil_and_tela($id_perfil, $tela)):
                $permissoes = new Permissoes_Perfil();
                $permissoes->id_perfil = $registro->id;
                $permissoes->ordem = $ordem;
                $permissoes->tela = $tela;
                $permissoes->opcoes = $opcoes;
                $permissoes->p = 'n';
                $permissoes->i = 'n';
                $permissoes->a = 'n';
                $permissoes->e = 'n';
                $permissoes->c = 'n';
                $permissoes->ai = 'n';
                $permissoes->imp = 'n';
                $permissoes->save();
            else:
                $altera_permissao = Permissoes_Perfil::find_by_tela($tela);
                $altera_permissao->ordem = $ordem;
                $altera_permissao->opcoes = $opcoes;
                $altera_permissao->save();
            endif;

        endforeach;
    endforeach;

    adicionaHistorico(idUsuario(), idColega(), 'Categorias de Usuário', 'Inclusão', 'Uma nova Categoriaa de Usuário foi cadastrada.');

    echo json_encode(array('status' => 'ok', 'id' => $id_perfil));

endif;


if($dados['acao'] == 'atualiza-permissoes'):

    $id_perfil = $registro->id;
    $permissoes = Permissoes_Perfil::all(array('conditions' => array('id_perfil = ?', $id), 'order' => 'ordem asc'));

    /*Lista de Permissões*/
    /*
     *Opções de permissão
     * p - permitido
     * a - alterar
     * i - incluir
     * e - excluir
     * c - consultar
     * ai - ativa/inativa
     * imp - imprimir
     */
    $array_permissoes = array(
        array('Categorias de Usuários' => 'a,i,e,c,ai'),
        array('Usuários' => 'a,i,e,c,ai'),
        array('Histórico de Ações dos Usuários' => 'c'),
        array('Idiomas' => 'a,i,e,c,ai'),
        array('Nomes de Provas' => 'a,i,e,c,ai'),
        array('Sistemade Notas' => 'a,i,e,c,ai'),
        array('Unidades' => 'a,i,e,c,ai'),
        array('Valores Hora/Aula' => 'a,i,e,c,ai'),
        array('Nomes de Produtos e Horas Semanais' => 'a,i,e,c,ai'),
        array('Programação e Conteúdo de Aulas' => 'a,i,e,c,ai'),
        array('Origem do Aluno' => 'a,i,e,c,ai'),
        array('Empresas' => 'a,i,e,c,ai'),
        array('Colegas IOWA' => 'a,i,e,c,ai'),

        array('Alunos' => 'a,i,e,c,ai'),
        array('Matriculas' => 'a,i,e,c,ai'),
        array('Promoções' => 'a,i,e,c,ai'),

        array('Motivos de Desistência' => 'a,i,e,c,ai'),
        array('Editor de Documentos' => 'a,i,e,c'),

        array('Turmas' => 'a,i,e,c,ai'),
        array('Visualizar Turmas Inativas' => 'c'),
        array('Mudança de Estágio' => 'a'),
        array('Transfereir Aluno' => 'i'),
        array('Alterar Notas dos Alunos' => 'a'),
        array('Registrar Aula no Diario de Classe' => 'a'),
        array('Registrar Aulas Somente nas Classes em que é Instrutor' => 'a'),
        //array('Adicionas Aulas' => 'i'),
        array('Mostrar Botão Adicionar Aulas' => 'p'), //adicionado em 02/12/2022
        array('Mostrar Botão Adicionar Pacote' => 'p'), //adicionado em 07/10/2023
        array('Alterar Dia' => 'a'),
        array('Alterar Horário' => 'a'),
        array('Alterar Programação' => 'a'),
        array('Alterar Instrutor' => 'a'),
        array('Alterar Valor Hora/Aula' => 'a'),
        array('Alterar Sistema de Notas' => 'a'),

        array('Abrir Caixa' => 'i'),
        array('Fechar Caixa' => 'i'),
        array('Fazer Transferência' => 'i'),
        array('Ver Todos os Caixas' => 'i'),

        array('Fornecedores' => 'a,i,e,c,ai'),
        array('Categorias de Lançamentos' => 'a,i,e,c,ai'),
        array('Formas de Recebimento/Pagamento' => 'a,i,e,c,ai'),
        array('Geração de Cobrança' => 'a,i,c,imp'),
        array('Gestão de Boletos' => 'i,c,imp'),
        array('Natureza de Contas a Pagar' => 'a,i,e,c,ai'),
        array('Pausar Parcela' => 'a'),
        array('Valor Original da Parcela' => 'a,c'),
        array('Contas a Receber' => 'a,i,e,c'),
        array('Quitar Parcela' => 'a'),
        array('Quitar Parcela Vencida' => 'a'),
        array('Contas a Pagar' => 'a,i,e,c'),
        array('Remover Acréscimos' => 'a'),
        array('Renovação de Contrato' => 'i,c'),

        array('Help' => 'i,c'),
        array('Aprovar Help' => 'a'),
        array('Cancelar Help' => 'a'),

        array('Coachs' => 'i,c'),
        array('Coachs - Criar Ata Para Turma' => 'i'),
        array('Coachs - Criar Ata Para Aluno' => 'i'),
        array('Coachs - Consultar Atas da Turma' => 'c'),
        array('Coachs - Consultar Atas do Aluno' => 'c'),
        array('Coachs - Altera Ata da Turma' => 'a'),
        array('Coachs - Altera Ata do Aluno' => 'a'),

        array('Relatório - Colegas IOWA' => 'c,imp'),
        array('Relatório - Folha de Pagamento' => 'c,imp'),
        array('Relatório - Folha de Pagamento Por Unidade' => 'c,imp'),
        array('Relatório - Turmas' => 'c,imp'),
        array('Relatório - Alunos / Turmas' => 'c,imp'), //adicionada em 02/12/2022
        array('Relatório - Alunos / Empresas' => 'c,imp'), //adicionado em 18/08/2023
        array('Relatório - Ocorrências ou Aulas' => 'c,imp'),
        array('Relatório - Frequencia' => 'c,imp'),
        array('Relatório - F7' => 'c,imp'), //adicionado em 24/10/2023
        array('Relatório - Consolidado de Faltas' => 'c,imp'),
        array('Relatório - Contas a Receber' => 'c,imp'),
        array('Relatório - Contas a Pagar' => 'c,imp'),
        array('Relatório - Faturamento' => 'c,imp'),
        array('Relatório - Matrículas Efetuadas' => 'c,imp'),
        array('Relatório - Matrículas Por Unidade' => 'c,imp'),
        array('Relatório - Inativação de Aluno' => 'c,imp'),
        array('Relatório - Helps' => 'c,imp'),
        array('Relatório - Aniversariantes' => 'c,imp'),
        array('Relatório - Aluno - Material' => 'c,imp'),
        array('Relatório - Alunos Por Unidade' => 'c,imp'),
        array('Relatório - Email Marketing' => 'c,imp'),
    );

    /*Criando Permissoes do Perfil conforme lista acima*/
    foreach($array_permissoes as $ordem => $permissoes):
        foreach($permissoes as $tela => $opcoes):

            if(!Permissoes_Perfil::find_by_id_perfil_and_tela($id_perfil, $tela)):
                $permissoes = new Permissoes_Perfil();
                $permissoes->id_perfil = $registro->id;
                $permissoes->ordem = $ordem;
                $permissoes->tela = $tela;
                $permissoes->opcoes = $opcoes;
                $permissoes->p = 'n';
                $permissoes->i = 'n';
                $permissoes->a = 'n';
                $permissoes->e = 'n';
                $permissoes->c = 'n';
                $permissoes->ai = 'n';
                $permissoes->imp = 'n';
                $permissoes->save();
            else:
                $altera_permissao = Permissoes_Perfil::find_by_id_perfil_and_tela($id_perfil, $tela);
                $altera_permissao->ordem = $ordem;
                $altera_permissao->opcoes = $opcoes;
                $altera_permissao->save();


                $lista_opcoes = explode(',', $opcoes);
                if(!in_array('p', $lista_opcoes)):
                    $altera_permissao->p = 'n';
                    $altera_permissao->save();
                endif;

                if(!in_array('i', $lista_opcoes)):
                    $altera_permissao->i = 'n';
                    $altera_permissao->save();
                endif;

                if(!in_array('a', $lista_opcoes)):
                    $altera_permissao->a = 'n';
                    $altera_permissao->save();
                endif;

                if(!in_array('e', $lista_opcoes)):
                    $altera_permissao->e = 'n';
                    $altera_permissao->save();
                endif;

                if(!in_array('c', $lista_opcoes)):
                    $altera_permissao->c = 'n';
                    $altera_permissao->save();
                endif;

                if(!in_array('ai', $lista_opcoes)):
                    $altera_permissao->ai = 'n';
                    $altera_permissao->save();
                endif;

                if(!in_array('imp', $lista_opcoes)):
                    $altera_permissao->imp = 'n';
                    $altera_permissao->save();
                endif;

            endif;

        endforeach;
    endforeach;

    echo json_encode(array('status' => 'ok', 'id' => $id_perfil));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Categorias de Usuários', 'a');

    if($registro->perfil != $dados['perfil']):
        /*Verificando duplicidade*/
        if(Perfis::find_by_perfil($dados['perfil'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $registro->perfil = $dados['perfil'];
    //dadosAlteracao($registro);
    $registro->save();

    /*Atualizando permissões dos usuários que estão sob este perfil*/
    $permissoes_perfil = Permissoes_Perfil::find_all_by_id_perfil($registro->id);
    if(!empty($permissoes_perfil)):
        foreach($permissoes_perfil as $permissao_perfil):

            $usuarios = Usuarios::find_all_by_id_perfil($permissao_perfil->id_perfil);

            if(!empty($usuarios)):
                foreach($usuarios as $usuario):

                    if(!Permissoes::find_by_tela_and_id_usuario($permissao_perfil->tela, $usuario->id)):

                        $permissao = new Permissoes();
                        $permissao->id_usuario = $usuario->id;
                        $permissao->ordem = $permissao_perfil->ordem;
                        $permissao->tela = $permissao_perfil->tela;
                        $permissao->opcoes = $permissao_perfil->opcoes;
                        $permissao->p = $permissao_perfil->p;
                        $permissao->i = $permissao_perfil->i;
                        $permissao->a = $permissao_perfil->a;
                        $permissao->e = $permissao_perfil->e;
                        $permissao->c = $permissao_perfil->c;
                        $permissao->ai = $permissao_perfil->ai;
                        $permissao->imp = $permissao_perfil->imp;
                        $permissao->save();

                    else:

                        $permissao = Permissoes::find_by_tela_and_id_usuario($permissao_perfil->tela, $usuario->id);
                        $permissao->ordem = $permissao_perfil->ordem;
                        $permissao->tela = $permissao_perfil->tela;
                        $permissao->opcoes = $permissao_perfil->opcoes;
                        $permissao->p = $permissao_perfil->p;
                        $permissao->i = $permissao_perfil->i;
                        $permissao->a = $permissao_perfil->a;
                        $permissao->e = $permissao_perfil->e;
                        $permissao->c = $permissao_perfil->c;
                        $permissao->ai = $permissao_perfil->ai;
                        $permissao->imp = $permissao_perfil->imp;
                        $permissao->save();

                    endif;

                endforeach;
            endif;

        endforeach;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Categorias de Usuário', 'Alteração', 'A Categoriaa de Usuário '.$registro->perfil.' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'altera-permissao'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Categorias de Usuários', 'a');

    $id_permissao_perfil = $dados['id_permissao_perfil'];
    $permissao = $dados['permissao'];

    $altera_permissao = Permissoes_Perfil::find($id_permissao_perfil);

    if($altera_permissao->$permissao == 's'):
        $altera_permissao->$permissao = 'n';

    elseif ($altera_permissao->$permissao == 'n'):
        $altera_permissao->$permissao = 's';

    endif;
    $altera_permissao->save();

    adicionaHistorico(idUsuario(), idColega(), 'Categorias de Usuário', 'Alteração', 'A Categoriaa de Usuário '.$registro->perfil.' foi alterada.');

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Categorias de Usuários', 'e');

    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Este idioma não pode ser excluído por já ter sido utilizado no sistema.'));
        exit();
    endif;

    $permissoes = Permissoes_Perfil::find_all_by_id_perfil($registro->id);
    if(!empty($permissoes)):
        foreach($permissoes as $permissao):
            $permissao->delete();
        endforeach;
    endif;

    adicionaHistorico(idUsuario(), idColega(), 'Categorias de Usuário', 'Exclusão', 'A Categoriaa de Usuário '.$registro->perfil.' foi excluída.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'lista-gerente'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Categorias de Usuários', 'ai');

    if($registro->listar_como_gerente == 's'):
        $registro->listar_como_gerente = 'n';
        $registro->save();

        adicionaHistorico(idUsuario(), idColega(), 'Categorias de Usuário', 'Alteração', 'A Categoriaa de Usuário '.$registro->perfil.' foi desmarcada como Listar como Gerente.');
    else:
        $registro->listar_como_gerente = 's';
        $registro->save();

        adicionaHistorico(idUsuario(), idColega(), 'Categorias de Usuário', 'Alteração', 'A Categoriaa de Usuário '.$registro->perfil.' foi marcada como Listar como Gerente.');
    endif;

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    //verificaPermissaoPost(idUsuario(), 'Categorias de Usuários', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Categorias de Usuário', 'Inativação', 'A Categoriaa de Usuário '.$registro->perfil.' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Categorias de Usuário', 'Ativação', 'A Categoriaa de Usuário '.$registro->perfil.' foi ativada.');
    endif;

endif;
