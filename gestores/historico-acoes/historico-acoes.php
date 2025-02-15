<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Histórico de Ações dos Usuários', 'c', 'index');
?>

<script src="js/historico-acoes.js"></script>
<!-- Start Content -->


    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">history</i>
        <h1>Histórico de Ações dos Usuários</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <!-- Form de Pesquisa -->
        <form action="" name="formPesquisa" id="formPesquisa" method="post">
            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Usuário</label>
                <select name="usuario" id="usuario" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option></option>
                    <?php
                    $usuarios = Usuarios::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                    if(!empty($usuarios)):
                        foreach($usuarios as $usuario):
                            echo '<option value="'.$usuario->id.'">'.$usuario->nome.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Tela</label>
                <select name="tela" id="tela" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option></option>
                    <optgroup label="GERAL">
                        <option value="Perfi do Usuário">Perfi do Usuário</option>
                        <option value="Configurações de E-mail">Configurações de E-mail</option>
                        <option value="Categorias de Usuário">Categorias de Usuário</option>
                        <option value="Usuários">Usuários</option>
                        <option value="Categorias de Usuário">Categorias de Usuário</option>
                        <option value="Idiomas">Idiomas</option>
                        <option value="Nomes de Provas">Nomes de Provas</option>
                        <option value="Sistema de Notas">Sistema de Notas</option>
                        <option value="Unidades">Unidades</option>
                        <option value="Valores Hora/Aula">Valores Hora/Aula</option>
                        <option value="Nomes de Produtos e Horas Semanais">Nomes de Produtos e Horas Semanais</option>
                        <option value="Programação e Conteúdo de Aulas">Programação e Conteúdo de Aulas</option>
                        <option value="Programação e Conteúdo de Aulas - Conteúdo">Programação e Conteúdo de Aulas - Conteúdo</option>
                        <option value="Origem do Aluno">Origem do Aluno</option>
                        <option value="Motivos de Desistência">Motivos de Desistência</option>
                        <option value="Editor de Documentos">Editor de Documentos</option>
                    </optgroup>
                    <optgroup label="CADASTROS">
                        <option value="Empresas">Empresas</option>
                        <option value="Empresas - Financeiro">Empresas - Financeiro</option>
                        <option value="Colegas IOWA">Colegas IOWA</option>
                        <option value="Turmas">Turmas</option>
                        <option value="Turmas - Transfereir Aluno">Turmas - Transfereir Aluno</option>
                        <option value="Turmas - Diário de Classe">Turmas - Diário de Classe</option>
                        <option value="Turmas - Notas de Provas">Turmas - Notas de Provas</option>
                        <option value="Alunos">Alunos</option>
                        <option value="Alunos - Observações">Alunos - Observações</option>
                        <option value="Alunos - Matrícula">Alunos - Matrícula</option>
                        <option value="Alunos - Financeiro">Alunos - Financeiro</option>
                    </optgroup>
                    <optgroup label="FINANCEIRO">
                        <option value="Fornecedores">Fornecedores</option>
                        <option value="Categorias de Lançamentos">Categorias de Lançamentos</option>
                        <option value="Formas de Recebimento/Pagamento">Formas de Recebimento/Pagamento</option>
                        <option value="Caixa">Caixa</option>
                        <option value="Geração de Cobrança">Geração de Cobrança</option>
                        <option value="Boletos">Boletos</option>
                        <option value="Natureza de Contas a Pagar">Natureza de Contas a Pagar</option>
                        <option value="Valor Original da Parcela">Valor Original da Parcela</option>
                        <option value="Contas a Receber">Contas a Receber</option>
                        <option value="Contas a Pagar">Contas a Pagar</option>
                        <option value="Renovação de Contrato">Renovação de Contrato</option>
                    </optgroup>
                    <optgroup label="HELP & COACH">
                        <option value="Help">Help</option>
                        <option value="Help - Diário de Classe">Help - Diário de Classe</option>
                        <option value="Coach - Ata">Coach - Ata</option>
                    </optgroup>
                </select>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Ação</label>
                <select name="acao" id="acao" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option></option>
                    <option value="Inclusão">Inclusão</option>
                    <option value="Alteração">Alteração</option>
                    <option value="Exclusão">Exclusão</option>
                    <option value="Inativação">Inativação</option>
                    <option value="Ativação">Ativação</option>
                </select>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
                <label for="regular1" class="control-label">Data Inicial</label>
                <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
                <label for="regular1" class="control-label">Data Final</label>
                <input type="text" name="data_final" id="data_final" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <button type="button" name="pesquisar" id="pesquisar" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
            <div class="espaco20"></div>
        </form>
        <!-- Form de Pesquisa -->

        <div id="listagem">
            <?php include_once('listagem.php'); ?>
        </div>

    </section>

    <div class="oculto" id="ms-nao-exclusao-modal" data-target="#nao-exclusao-dialog" data-toggle="modal"></div>
    <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

    <script type="text/javascript">
        $("#data_inicial, #data_final").datetimepicker({
            format: "DD/MM/YYYY"
        });
    </script>
