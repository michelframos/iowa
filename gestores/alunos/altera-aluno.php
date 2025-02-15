<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $registro = Alunos::find_by_id($id);
    $perfil = PerfisAlunosModel::find_by_id_aluno($id);
?>

<!-- MATRICULA -->
<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">CPF Duplicado</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" registro="" type="button" id="bt-continua-cadastro">Continuar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-cancela-cadastro">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="duplicidade-login-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Login Duplicado</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">O login digitado já está em uso por outro aluno. Por favor digite outro login.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="preencher-dados-responsavel-modal-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Dados do Responsável</h2>
            </div>
            <div class="modal-body">
                <p>O aluno que está sendo cadastrado é menor de idade. Por favor preencha os dados do responsável antes de salvar o cadastro.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="exclui-matricula-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Excluir Matrícula?</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Confirma a exclusão desta matrícula e suas parcelas?.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-modal-excluir-matricula" registro="">Excluir</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-modal-cancelar">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-exclusao-matricula-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão de Matrícula</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Não foi possível excluir esta matrícula, por já haver parcelas pagas e/ou boletos gerados.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="matricula-duplicada-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Matrícula Diplicada</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Esta aluno já possui uma matrícula nesta Turma.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- --------------------------------------------------------------------------------------------------------------- -->
<!-- PARCELAS -->
<div tabindex="-1" class="modal fade" id="altera-parcela-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterar Parcela(s)</h2>
                <p>Informe abaixo a porcentagem dos itens desejados</p>
            </div>
            <div class="modal-body">

                <form action="" method="post" name="formAlteraParcela" id="formAlteraParcela">

                    <!--
                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Juros %</label>
                        <input type="text" name="juros_porcentagem" id="juros_porcentagem" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Juros R$</label>
                        <input type="text" name="juros_reais" id="juros_reais" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Multa %</label>
                        <input type="text" name="multa_porcentagem" id="multa_porcentagem" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Multa R$</label>
                        <input type="text" name="multa_reais" id="multa_reais" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>
                    -->

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Acréscimo %</label>
                        <input type="text" name="acrescimo_porcentagem" id="acrescimo_porcentagem" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Acréscimo R$</label>
                        <input type="text" name="acrescimo_reais" id="acrescimo_reais" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Desconto %</label>
                        <input type="text" name="desconto_porcentagem" id="desconto_porcentagem" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Desconto R$</label>
                        <input type="text" name="desconto_reais" id="desconto_reais" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield">
                        <label class="control-label">Observação</label>
                        <textarea required class="form-control" name="observacao" id="observacao" style="height: 100px;"></textarea>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" parcelas="" type="button" id="bt-altera-parcelas">OK</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-cancela-altera-parcelas">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="quitar-parcela-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Recebimento de Parcela(s)</h2>
            </div>
            <div class="modal-body">

                <h2 class="h2">Valor total: <span style="color: #ff5722; font-size: 2em; font-weight: bold;" id="valor_total_parcelas">Calculando...</span> </h2>
                <div class="clear"></div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                    <label for="regular1" class="control-label">Data Pagamento</label>
                    <input type="text" name="data_pagamento" id="data_pagamento" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>
                <div class="clear"></div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna2">
                    <label>Forma de Pagamento</label>
                    <select name="id_forma_pagamento" id="id_forma_pagamento" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                        <option></option>
                        <?php
                        $formas = Formas_Pagamento::all();
                        if(!empty($formas)):
                            foreach($formas as $forma):
                                echo '<option value="'.$forma->id.'">'.$forma->forma_pagamento.'</option>';
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                </div>
                <div class="clear"></div>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" parcelas="" id="bt-modal-quitar-parcelas" registro="">Receber</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-cancelar-quitar-parcelas">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-caixa-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Caixa Não Identificado!</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Não existe caixa aberto para o usuario logado! Por favor, abra o caixa antes de prossegir.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="excluir-parcelar-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Excluir Parcela?</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Confirma a exclusão da(s) parcela(s) selecionada(s)? Esta ação é irreversível.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-modal-excluir-parcela" registro="" parcelas="">Excluir</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-canclar-exclusao">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="pausar-parcelas-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Pausar Parcela(s)</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Confirma a pausa da(s) parcela(s) selecionada(s)</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-primary" id="bt-pausar-parcelas">Sim</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" id="bt-cancelar-pausar-parcelas" type="button">Sair</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="cancelar-parcela-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Cancelar Parcela?</h2>
            </div>
            <div class="modal-body">

                <div class="form-group pmd-textfield">
                    <label class="control-label">Observação</label>
                    <textarea required class="form-control" name="observacao-cancelamento" id="observacao-cancelamento" style="height: 100px;"></textarea>
                </div>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-modal-cancelar-parcela" parcelas="" registro="">Cancelar Parcela</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-fecha-cancelar-parcela">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="erro-vencimento-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Parcela Vencida!</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal-vencimento"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="perfil-salvo-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Perfil</h2>
            </div>
            <div class="modal-body">
                Perfil salvo com sucesso!
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- --------------------------------------------------------------------------------------------------------------- -->


<div tabindex="-1" class="modal fade" id="informe-senha-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Senha Obrigatória</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">É obrigatório informar a senha do aluno, caso contrário ele não terá acesso à area do aluno.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="confirma-senha-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Confirmação de Senha</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">A senha e a confirmação não são iguais.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="observacao-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Observação</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">O campo de observação está em branco.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="alterado-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterações</h2>
            </div>
            <div class="modal-body">
                <p>Alterações salvas com sucesso.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="copiar-endereco-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Endereço do Responsável</h2>
            </div>
            <div class="modal-body">
                <p>Copiar endereço do aluno para o responsável?</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-copiar-endereco">Copiar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="cpf-invalido-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">CPF Inválido!</h2>
            </div>
            <div class="modal-body">
                <p>O CPF informado é inválido.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="mensagem-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text" id="titulo-modal"></h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="permissao-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Permissão Negada</h2>
            </div>
            <div class="modal-body">
                <p id="msg-permissao-dialog"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="js/alunos.js"></script>
<script src="js/perfis-alunos.js"></script>

<?php
/*
$dia = new DateTime( '2018-06-15' );
$dia1 = new DateTime( '2018-06-15' );
//seg e qua
//$dia->modify( 'next sunday' );

for($i=1;$i<=8;$i++):
    $dia->modify('next monday');
    $dia1->modify('next wednesday');
    //$dia->modify('+7 day')->format('Y-m-d');
    echo $dia->format('d/m/Y').'<br>';
    echo $dia1->format('d/m/Y').'<br>';
endfor;

//echo $dia->format('d/m/Y');
*/
?>

<!-- Start Content -->
    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">school</i>
        <h1>Cadastro / Alteração de Aluno</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>

        <h2 class="h2">Aluno: <?php echo $registro->nome ?></h2>

        <form action="" name="formDados" id="formDados" method="post">

            <!-- --------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Abas -->
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-tabs pmd-tabs-bg">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#dados-cadastrais" aria-controls="home" role="tab" data-toggle="tab">Dados Cadastrais</a></li>
                        <li id="aba-responsavel" <?php //echo $registro->menor == 'n' ? 'class="oculto"' : ''; ?> role="presentation"><a href="#responsavel" aria-controls="about" role="tab" data-toggle="tab">Responsavel</a></li>

                        <?php if($registro->nome != 'Novo Aluno'): ?>
                        <li id="aba-observacoes" role="presentation"><a href="#observacoes" aria-controls="about" role="tab" data-toggle="tab">Observações</a></li>
                        <li id="aba-matricula" role="presentation"><a href="#matricula" aria-controls="about" role="tab" data-toggle="tab">Matrícula</a></li>
                        <li id="aba-documentos" role="presentation"><a href="#documentos" aria-controls="about" role="tab" data-toggle="tab">Documentos</a></li>
                        <li role="presentation"><a href="#financeiro" aria-controls="about" role="tab" data-toggle="tab" id="aba-financeiro">Financeiro</a></li>
                        <li role="presentation"><a href="#configuracoes" aria-controls="configuracoes" role="tab" data-toggle="tab" id="aba-financeiro">Configurações</a></li>
                        <?php endif; ?>

                        <li role="presentation"><a href="#perfil" aria-controls="configuracoes" role="tab" data-toggle="tab" id="aba-perfil">Perfil</a></li>
                    </ul>
                </div>

                <div class="pmd-card-body">
                    <div class="tab-content">

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane active" id="dados-cadastrais">

                            <div style="max-width: 800px;">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed <?php echo (Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Matriculas', 'n')) ? 'oculto' : '' ?>" >
                                    <label>Situação do Aluno</label>
                                    <select name="situacao" id="situacao" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                        <option></option>
                                        <?php
                                        $situacoes = Situacao_Aluno::all(array('order' => 'situacao asc'));
                                        if(!empty($situacoes)):
                                            foreach($situacoes as $situacao):
                                                echo $registro->id_situacao == $situacao->id ? '<option selected value="'.$situacao->id.'">'.$situacao->situacao.'</option>' : '<option value="'.$situacao->id.'">'.$situacao->situacao.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Unidade</label>
                                    <select name="unidade" id="unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                        <option></option>
                                        <?php
                                        $unidades = Unidades::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_unidade), 'order' => 'nome_fantasia asc'));
                                        if(!empty($unidades)):
                                            foreach($unidades as $unidade):
                                                echo $registro->id_unidade == $unidade->id ? '<option selected value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>' : '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Origem</label>
                                    <select name="origem" id="origem" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                        <option></option>
                                        <?php
                                        $origens = Origem_Aluno::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_origem), 'order' => 'origem asc'));
                                        if(!empty($origens)):
                                            foreach($origens as $origem):
                                                echo $registro->id_origem == $origem->id ? '<option selected value="'.$origem->id.'">'.$origem->origem.'</option>' : '<option value="'.$origem->id.'">'.$origem->origem.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Material</label>
                                    <select name="material" id="material" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                        <option value=""></option>
                                        <option <?php echo $registro->material == 'Paga Material' ? 'selected' : ''; ?> value="Paga Material">Paga Material</option>
                                        <option <?php echo $registro->material == 'Não Paga Material na 1 Fase' ? 'selected' : ''; ?> value="Não Paga Material na 1 Fase">Não Paga Material na 1 Fase</option>
                                        <option <?php echo $registro->material == 'Não Paga Material' ? 'selected' : ''; ?> value="Não Paga Material">Não Paga Material</option>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>

                                <h4 class="h2">Dados de Acesso</h4>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Login</label>
                                    <input type="text" name="login" id="login" value="<?php echo $registro->login; ?>" class="form-control" <?php echo empty($registro->login) ? 'required' : ''; ?> <?php echo !empty($registro->login) ? 'readonly' : ''; ?> ><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Senha</label>
                                    <input type="text" name="senha" id="senha" value="" class="form-control" <?php echo empty($registro->senha) ? 'required' : ''; ?>><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Confirme a Senha</label>
                                    <input type="text" name="confirma_senha" id="confirma_senha" value="" class="form-control" <?php echo empty($registro->senha) ? 'required' : ''; ?>><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <h4 class="h2">Dados Pessoais</h4>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Nome</label>
                                    <input type="text" name="nome" id="nome" value="<?php echo $registro->nome; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Data de Nascimento</label>
                                    <input type="text" name="data_nascimento" id="data_nascimento" value="<?php echo !empty($registro->data_nascimento) ? $registro->data_nascimento->format('d/m/Y') : ''; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">RG</label>
                                    <input type="text" name="rg" id="rg" value="<?php echo $registro->rg; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">CPF</label>
                                    <input type="text" name="cpf" id="cpf" value="<?php echo $registro->cpf; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">CEP</label>
                                    <input type="text" name="cep" id="cep" value="<?php echo $registro->cep; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <button type="button" name="busca-cep" id="busca-cep" value="Buscar Endereço" class="btn btn-info pmd-btn-raised">Buscar Endereço</button>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Endereço</label>
                                    <input type="text" name="endereco" id="endereco" value="<?php echo $registro->endereco; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Número</label>
                                    <input type="text" name="numero" id="numero" value="<?php echo $registro->numero; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" value="<?php echo $registro->bairro; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Complemento</label>
                                    <input type="text" name="complemento" id="complemento" value="<?php echo $registro->complemento; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="coluna-3 float-left margin-right-5">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                        <label>Estado</label>
                                        <select name="estado" id="estado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <?php
                                            $estados = Estados::all();
                                            if(!empty($estados)):
                                                foreach($estados as $estado):
                                                    echo $registro->estado == $estado->estado_id ? '<option selected value="'.$estado->estado_id.'">'.$estado->uf.'</option>' : '<option value="'.$estado->estado_id.'">'.$estado->uf.'</option>';
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>

                                <div class="coluna-3 float-left">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                        <label>Cidade</label>
                                        <select name="cidade" id="cidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Nome da Empresa</label>
                                    <input type="text" name="nome_empresa" id="nome_empresa" value="<?php echo $registro->nome_empresa; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <h4 class="h2">Dados Para Contato</h4>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Celular</label>
                                    <input type="text" name="celular" id="celular" value="<?php echo $registro->celular; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Telefone 1</label>
                                    <input type="text" name="telefone1" id="telefone1" value="<?php echo $registro->telefone1; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Telefone 2</label>
                                    <input type="text" name="telefone2" id="telefone2" value="<?php echo $registro->telefone2; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Telefone 3</label>
                                    <input type="text" name="telefone3" id="telefone3" value="<?php echo $registro->telefone3; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">E-mail 1</label>
                                    <input type="text" name="email1" id="email1" value="<?php echo $registro->email1; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">E-mail 2</label>
                                    <input type="text" name="email2" id="email2" value="<?php echo $registro->email2; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Facebook</label>
                                    <input type="text" name="facebook" id="facebook" value="<?php echo $registro->facebook; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="responsavel">

                            <div style="max-width: 800px;">

                                <h4 class="h2">Dados Pessoais</h4>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Parentesco</label>
                                    <input type="text" name="parentesco_responsavel" id="parentesco_responsavel" value="<?php echo $registro->parentesco_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Nome</label>
                                    <input type="text" name="nome_responsavel" id="nome_responsavel" value="<?php echo $registro->nome_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Data de Nascimento</label>
                                    <input type="text" name="data_nascimento_responsavel" id="data_nascimento_responsavel" value="<?php echo !empty($registro->data_nascimento_responsavel) ? $registro->data_nascimento_responsavel->format('d/m/Y') : ''; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">RG</label>
                                    <input type="text" name="rg_responsavel" id="rg_responsavel" value="<?php echo $registro->rg_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">CPF</label>
                                    <input type="text" name="cpf_responsavel" id="cpf_responsavel" value="<?php echo $registro->cpf_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">CEP</label>
                                    <input type="text" name="cep_responsavel" id="cep_responsavel" value="<?php echo $registro->cep_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <button type="button" name="busca-cep-responsavel" id="busca-cep-responsavel" value="Buscar Endereço" class="btn btn-info pmd-btn-raised">Buscar Endereço</button>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <button type="button" name="endereco-aluno" id="endereco-aluno" value="Usar Endereço e Telefone do Aluno" class="btn btn-info pmd-btn-raised">Usar Endereço e Telefone do Aluno</button>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Endereço</label>
                                    <input type="text" name="endereco_responsavel" id="endereco_responsavel" value="<?php echo $registro->endereco_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Número</label>
                                    <input type="text" name="numero_responsavel" id="numero_responsavel" value="<?php echo $registro->numero_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Bairro</label>
                                    <input type="text" name="bairro_responsavel" id="bairro_responsavel" value="<?php echo $registro->bairro_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Complemento</label>
                                    <input type="text" name="complemento_responsavel" id="complemento_responsavel" value="<?php echo $registro->complemento_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="coluna-3 float-left margin-right-5">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                        <label>Estado</label>
                                        <select name="estado_responsavel" id="estado_responsavel" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <?php
                                            $estados = Estados::all();
                                            if(!empty($estados)):
                                                foreach($estados as $estado):
                                                    echo $registro->estado_responsavel == $estado->estado_id ? '<option selected value="'.$estado->estado_id.'">'.$estado->uf.'</option>' : '<option value="'.$estado->estado_id.'">'.$estado->uf.'</option>';
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>

                                <div class="coluna-3 float-left">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                        <label>Cidade</label>
                                        <select name="cidade_responsavel" id="cidade_responsavel" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>
                                <div class="clear"></div>

                                <h4 class="h2">Dados Para Contato</h4>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Celular</label>
                                    <input type="text" name="celular_responsavel" id="celular_responsavel" value="<?php echo $registro->celular_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Telefone 1</label>
                                    <input type="text" name="telefone1_responsavel" id="telefone1_responsavel" value="<?php echo $registro->telefone1_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Telefone 2</label>
                                    <input type="text" name="telefone2_responsavel" id="telefone2_responsavel" value="<?php echo $registro->telefone2_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">Telefone 3</label>
                                    <input type="text" name="telefone3_responsavel" id="telefone3_responsavel" value="<?php echo $registro->telefone3_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">E-mail 1</label>
                                    <input type="text" name="email1_responsavel" id="email1_responsavel" value="<?php echo $registro->email1_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">E-mail 2</label>
                                    <input type="text" name="email2_responsavel" id="email2_responsavel" value="<?php echo $registro->email2_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Facebook</label>
                                    <input type="text" name="facebook_responsavel" id="facebook_responsavel" value="<?php echo $registro->facebook_responsavel; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->


                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="observacoes">

                            <div style="max-width: 800px;">

                                <div id="content-observacoes">



                                </div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->


                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="matricula">

                            <div style="max-width: 800px;">

                                <div id="content-matriculas">



                                </div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->


                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="documentos">

                            <div style="max-width: 1200px;">

                                <div id="content-documentos">



                                </div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->


                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="financeiro">

                            <div>

                                <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')): ?>
                                <button type="button" name="alterar-parcela" id="alterar-parcela" value="Alterar" class="btn btn-info pmd-btn-raised">Alterar</button>
                                <button type="button" name="zerar-valores" id="zerar-valores" value="Zerar Valores" class="btn btn-info pmd-btn-raised">Zerar Valores</button>
                                <?php endif; ?>

                                <!-- <button type="button" name="quitar-parcela" id="quitar-parcela" data-target="#quitar-parcela-dialog" data-toggle="modal" value="Quitar Parcela" class="btn btn-danger pmd-btn-raised">Quitar Parcela</button> -->
                                <?php if(Permissoes::find_by_id_usuario_and_tela_and_i(idUsuario(), 'Contas a Receber', 's')): ?>
                                    <button type="button" registro="<?php echo $registro->id ?>" name="adicionar-parcela" id="adicionar-parcela" value="Adicionar Parcela" class="btn btn-danger pmd-btn-raised">Adicionar Parcela</button>
                                <?php endif; ?>

                                <?php if(Permissoes::find_by_id_usuario_and_tela_and_e(idUsuario(), 'Contas a Receber', 's')): ?>
                                <button type="button" name="excluir-parcelas" id="excluir-parcelas" value="Excluir Parcela(s)" data-target="#excluir-parcelar-dialog" data-toggle="modal" value="Excluir Parcelas Parcela" class="btn btn-warning pmd-btn-raised">Excluir Parcela(s)</button>
                                <?php endif; ?>

                                <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')): ?>
                                <button type="button" name="cancelar-parcelas" id="cancelar-parcelas" value="Cancelar Parcela(s)" data-target="#cancelar-parcela-dialog" data-toggle="modal" value="Cancelar Parcela" class="btn btn-warning pmd-btn-raised">Cancelar Parcela(s)</button>
                                <?php endif; ?>

                                <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Pausar Parcela', 's')): ?>
                                <button type="button" name="pausar-parcelas" id="pausar-parcelas" value="Pausar Parcelas" data-target="#pausar-parcelas-dialog" data-toggle="modal" value="Pausar Parcelas" class="btn btn-danger pmd-btn-raised">Pausar/Despausar Parcela(s)</button>
                                <?php endif; ?>

                                <div class="espaco20"></div>

                                <!-- Form de Pesquisa -->
                                <form action="" name="formPesquisa" id="formPesquisa" method="post">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                        <label>Turma</label>
                                        <select name="id_turma" id="id_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                            <option value="%">Todas</option>
                                            <?php
                                            $turmas = Turmas::all(array('order' => 'nome asc'));
                                            if(!empty($turmas)):
                                                foreach($turmas as $turma):
                                                    echo '<option matricula="'.$matricula->id.'" value="'.$turma->id.'">'.$turma->nome.'</option>';
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>

                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                        <label>Idioma</label>
                                        <select name="id_idioma" id="id_idioma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                            <option value="%">Todos</option>
                                            <?php
                                            $idiomas = Idiomas::all(array('order' => 'idioma asc'));
                                            if(!empty($idiomas)):
                                                foreach($idiomas as $idioma):
                                                    echo '<option value="'.$idioma->id.'">'.$idioma->idioma.'</option>';
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>

                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                        <label>Status da Parcela</label>
                                        <select name="status_parcela" id="status_parcela" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option value="pago = 'n'">Não Paga</option>
                                            <option value="pago = 's'">Paga</option>
                                            <option value="cancelada = 's'">Cancelada</option>
                                            <option value="renegociada = 's'">Renegociada</option>
                                            <option value="">Todas</option>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>
                                    <div class="clear"></div>

                                    <!--
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                        <label>Empresa</label>
                                        <select name="id_empresa" id="id_empresa" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option value="%">Todas</option>
                                            <?php
                                            $empresas = Empresas::all(array('order' => 'nome_fantasia asc'));
                                            if(!empty($empresas)):
                                                foreach($empresas as $empresa):
                                                    echo '<option value="'.$empresa->id.'">'.$empresa->nome_fantasia.'</option>';
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>
                                    <div class="clear"></div>
                                    -->

                                    <?php if(Permissoes::find_by_id_usuario_and_tela_and_c(idUsuario(), 'Contas a Receber', 's')): ?>
                                    <button type="button" name="pesquisar-parcelas" id="pesquisar-parcelas" registro="<?php echo $registro->id ?>" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
                                    <?php endif; ?>
                                    <div class="espaco20"></div>
                                </form>
                                <!-- Form de Pesquisa -->

                                <div class="clear"></div>

                                <div id="listagem-parcelas">
                                    <?php include_once('listagem-parcelas.php'); ?>
                                </div>

                            </div>

                            <script>
                                /*
                                $(function(){
                                    /*CANCELAR PARCELA*/
                                /*
                                    $('.bt-cancelar-parcela').click(function(){

                                        $('#bt-modal-cancelar-parcela').attr('registro', $(this).attr('parcela'));

                                    });
                                });
                                */
                            </script>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="configuracoes">

                            <div style="max-width: 1200px;">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">E-mail do Gestor Pedagógico</label>
                                    <input type="text" name="email_gestor_pedagogico" id="email_gestor_pedagogico" value="<?php echo $registro->email_gestor_pedagogico; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="perfil">

                            <div style="max-width: 1200px;">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Características</label>
                                    <textarea type="text" name="caracteristicas" id="caracteristicas" class="form-control" style="height: 200px;"><?php echo $perfil->caracteristicas; ?></textarea>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Objetivo</label>
                                    <textarea type="text" name="objetivo" id="objetivo" class="form-control" style="height: 200px;"><?php echo $perfil->objetivo; ?></textarea>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Histório</label>
                                    <textarea type="text" name="historico" id="historico" class="form-control" style="height: 200px;"><?php echo $perfil->historico; ?></textarea>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Promessa</label>
                                    <textarea type="text" name="promessa" id="promessa" class="form-control" style="height: 200px;"><?php echo $perfil->promessa; ?></textarea>
                                </div>
                                <div class="espaco20"></div>

                                <!-- <button type="button" name="salvar_perfil" id="salvar_perfil" value="Salvar Perfil" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar Perfil</button> -->
                                <button type="button" name="exportar_pdf" id="exportar_pdf" value="Exportar PDF" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Exportar Perfil</button>
                                <div class="espaco20"></div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                        <div class="clear"></div>

                    </div>
                </div>

            </div>
            <div class="espaco20"></div>

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-login-dp-modal" data-target="#duplicidade-login-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-senha-modal" data-target="#informe-senha-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-confirma-senha-modal" data-target="#confirma-senha-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-observacao-modal" data-target="#observacao-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-matricula-duplicada-modal" data-target="#matricula-duplicada-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-erro-exclusao-matricula-dialog" data-target="#erro-exclusao-matricula-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-altera-parcela-modal" data-target="#altera-parcela-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-copiar-endereco-modal" data-target="#copiar-endereco-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-cpf-invalido-modal" data-target="#cpf-invalido-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-preencher-dados-responsavel-modal" data-target="#preencher-dados-responsavel-modal-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-erro-caixa-modal" data-target="#erro-caixa-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-erro-vencimento-dialog" data-target="#erro-vencimento-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-perfil-salvo-dialog" data-target="#perfil-salvo-dialog" data-toggle="modal"></div>

            <div class="oculto" id="ms-mensagem-dialog" data-target="#mensagem-dialog" data-toggle="modal"></div>

        </form>

    </section>

<script>
    $(function(){

        $('#data_pagamento').mask('00/00/0000');
        $("#data_pagamento, #data_nascimento, #data_nascimento_responsavel").datetimepicker({
            format: "DD/MM/YYYY"
        });

        <?php if(!empty($registro->estado)): ?>
        $.post('../includes/lista-cidades.php', {estado: <?php echo $registro->estado ?>}, function(data){

            $('#cidade').html(data);

            <?php
                if(!empty($registro->cidade)):
            ?>
                $('#cidade option[value="'+<?php echo $registro->cidade ?>+'"]').prop("selected", true);
            <?php
                else:
             ?>
                $('#cidade').html('');
             <?php
                endif;
            ?>


        });
        <?php endif; ?>

        $('#estado').change(function(){

            $.post('../includes/lista-cidades.php', {estado: $('#estado').val()}, function(data){

                $('#cidade').html(data);

            });
        });

        <?php if(!empty($registro->estado_responsavel)): ?>
        $.post('../includes/lista-cidades.php', {estado: <?php echo $registro->estado_responsavel ?>}, function(data){

            $('#cidade_responsavel').html(data);
            $('#cidade_responsavel option[value="'+<?php echo $registro->cidade_responsavel ?>+'"]').prop("selected", true);

        });
        <?php endif; ?>

        $('#estado_responsavel').change(function(){

            $.post('../includes/lista-cidades.php', {estado: $('#estado_responsavel').val()}, function(data){

                $('#cidade_responsavel').html(data);

            });
        });

    });
</script>