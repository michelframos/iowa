<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Contas a Receber', 'c', 'index');
?>

<script src="js/contas-receber.js"></script>

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

                <form action="" name="formQuitar" id="formQuitar" method="post">

                <h2 class="h2">Valor total: <span style="color: #ff5722; font-size: 2em; font-weight: bold;" id="valor_total_parcelas">Calculando...</span> </h2>
                <div class="clear"></div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                    <label for="regular1" class="control-label">Data Pagamento</label>
                    <input type="text" name="data_pagamento" id="data_pagamento" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>
                <div class="clear"></div>

                <div id="box-formas-recebimento">
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


                        <div id="box-nova-forma-recebimento">
                            <table id="nova-forma-recebimento">
                                <tr>
                                    <td width="350">Forma de Pagamento</td>
                                    <td width="50">Valor</td>
                                    <td width="50"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="espaco20"></div>


                    <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-adicionar-forma-pagamento">Adicionar Forma de Pagamento</button>

                </div>

            </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" parcelas="" total_parcelas="" id="bt-modal-quitar-parcelas" registro="">Receber</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-cancelar-quitar-parcelas">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-valor-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Valor incorreto!</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-erro-valor"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
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
                <p id="mensagem-modal">O usuário logado não é responsável por nenhum caixa aberto ou não existe nenhum caixa aberto!</p>
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


<div tabindex="-1" class="modal fade" id="alterar_vencimento-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterar Vencimento</h2>
            </div>
            <div class="modal-body">

                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                    <label for="regular1" class="control-label">Novo Vencimento</label>
                    <input type="text" name="novo-vencimento" id="novo-vencimento" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>
                <div class="clear"></div>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-alterar-vencimento">OK</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="vencimento-alterado-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Vencimento Alterado</h2>
            </div>
            <div class="modal-body">

                <p>As parcelas selecionadas tiveram sua data de vencimento alterada.</p>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="gerar-recibo-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Geração de Recibo</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Confirma a geração de recibo para as parcelas selecionadas?</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" id="bt-gerar-recibo" parcelas="" type="button">Gerar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cencelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-recibo-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Multiplos Sacados</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Você selecionou pacelas que foram pagas por pessoas diferentes, por favor selecione parcelas de uma mesmo pagador por vez.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="imprimir-recibo-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Imprimir Recibo</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Recibo gerado, desejo abri-lo para impressão?</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <a href="" target="_blank" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" id="link_recibo">Sim</a>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Sair</button>
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

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">receipt</i>
        <h1>Contas a Receber</h1>
    </div>

    <div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        Este Registro não pode ser excluído por já ter sido utilizado no sistema.
    </div>


    <section class="pmd-card pmd-z-depth padding-10">

        <!-- --------------------------------------------------------------------------------------------------- -->
        <!-- Inicio Abas -->
        <div class="pmd-card pmd-z-depth">
            <div class="pmd-tabs pmd-tabs-bg">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a id="tab-selecionar-parcelas" href="#selecionar-parcelas" aria-controls="home" role="tab" data-toggle="tab">Selecionar Parcelas</a></li>
                    <li role="presentation" class="oculto"><a id="tab-renegociar" href="#renegociar" aria-controls="home" role="tab" data-toggle="tab"></a></li>
                </ul>
            </div>

            <div class="pmd-card-body">
                <div class="tab-content">

                    <!-- --------------------------------------------------------------------------------------- -->
                    <!-- Conteúdo de Uma Aba -->
                    <div role="tabpanel" class="tab-pane active" id="selecionar-parcelas">

                        <div class="espaco20"></div>

                        <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')): ?>
                            <button type="button" name="alterar-parcela" id="alterar-parcela" value="Alterar" class="btn btn-info pmd-btn-raised">Alterar</button>
                            <button type="button" name="alterar-vencimento" id="alterar-vencimento" value="Alterar Vencimento"  data-target="#alterar_vencimento-dialog" data-toggle="modal" class="btn btn-info pmd-btn-raised">Alterar Vencimento</button>
                            <button type="button" name="zerar-valores" id="zerar-valores" value="Zerar Valores" class="btn btn-info pmd-btn-raised">Zerar Valores</button>
                        <?php endif; ?>

                        <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Quitar Parcela', 's')): ?>
                            <button type="button" name="quitar-parcela" id="quitar-parcela" data-target="#quitar-parcela-dialog" data-toggle="modal" value="Quitar Parcela" class="btn btn-danger pmd-btn-raised">Quitar Parcela</button>
                        <?php endif; ?>

                        <?php if(Permissoes::find_by_id_usuario_and_tela_and_i(idUsuario(), 'Contas a Receber', 's')): ?>
                            <button type="button" registro="<?php echo $registro->id ?>" name="adicionar-parcela" id="adicionar-parcela" value="Adicionar Parcela" class="btn btn-danger pmd-btn-raised">Adicionar Parcela</button>
                        <?php endif; ?>

                        <?php if(Permissoes::find_by_id_usuario_and_tela_and_e(idUsuario(), 'Contas a Receber', 's')): ?>
                            <button type="button" name="excluir-parcelas" id="excluir-parcelas" value="Excluir Parcela(s)" data-target="#excluir-parcelar-dialog" data-toggle="modal" value="Excluir Parcelas Parcela" class="btn btn-warning pmd-btn-raised">Excluir Parcela(s)</button>
                        <?php endif; ?>

                        <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Contas a Receber', 's')): ?>
                            <button type="button" name="cancelar-parcelas" id="cancelar-parcelas" value="Cancelar Parcela(s)" data-target="#cancelar-parcela-dialog" data-toggle="modal" value="Cancelar Parcela" class="btn btn-warning pmd-btn-raised">Cancelar Parcela(s)</button>
                        <?php endif; ?>

                        <button type="button" name="gerar-recibo" id="gerar-recibo" value="Gerar Recibo" data-target="#gerar-recibo-dialog" data-toggle="modal" value="Gerar Recibo" class="btn btn-danger pmd-btn-raised">Gerar Recibo</button>

                        <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Pausar Parcela', 's')): ?>
                        <button type="button" name="pausar-parcelas" id="pausar-parcelas" value="Pausar Parcelas" data-target="#pausar-parcelas-dialog" data-toggle="modal" value="Pausar Parcelas" class="btn btn-danger pmd-btn-raised">Pausar/Despausar Parcela(s)</button>
                        <?php endif; ?>

                        <div class="espaco20"></div>

                        <!-- Form de Pesquisa -->
                        <form action="" name="formPesquisa" id="formPesquisa" method="post">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
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

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
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

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
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

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                                <label>Sacado</label>
                                <select name="sacado" id="sacado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                    <option value="%">Todos</option>
                                    <option value="aluno">Aluno</option>
                                    <option value="empresa">Cliente</option>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>
                            <div class="clear"></div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
                                <label for="regular1" class="control-label">Nome do Aluno</label>
                                <input type="text" name="nome_aluno" id="nome_aluno" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>


                            <div class="margin-right-10 coluna-3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Data Inicio</label>
                                    <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                                <label for="regular1" class="control-label">Data Final</label>
                                <input type="text" name="data_final" id="data_final" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                            </div>

                            <div class="clear"></div>
                            <?php if(Permissoes::find_by_id_usuario_and_tela_and_c(idUsuario(), 'Contas a Receber', 's')): ?>
                                <button type="button" name="pesquisar-parcelas" id="pesquisar-parcelas" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
                            <?php endif; ?>
                            <div class="espaco20"></div>
                        </form>
                        <!-- Form de Pesquisa -->

                        <div class="clear"></div>

                        <div id="listagem-parcelas">
                            <?php include_once('listagem-parcelas.php'); ?>
                        </div>

                        <div class="oculto" id="ms-altera-parcela-modal" data-target="#altera-parcela-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-vencimento-alterado-dialog" data-target="#vencimento-alterado-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-erro-vencimento-dialog" data-target="#erro-vencimento-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-erro-caixa-modal" data-target="#erro-caixa-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-erro-recibo-dialog" data-target="#erro-recibo-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-imprimir-recibo-dialog" data-target="#imprimir-recibo-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-erro-valor-dialog" data-target="#erro-valor-dialog" data-toggle="modal"></div>

                    </div>
                    <!-- Conteúdo de Uma Aba -->
                    <!-- --------------------------------------------------------------------------------------- -->


                    <!-- --------------------------------------------------------------------------------------- -->
                    <!-- Conteúdo de Uma Aba -->
                    <div role="tabpanel" class="tab-pane" id="renegociar">

                        <form name="formParcelas" id="formParcelas" method="post">

                            <button type="button" class="btn btn-danger pmd-btn-raised" id="voltar"> Voltar </button >

                            <div id="box-renegociar">
                                <?php include_once('renegociar.php') ?>
                            </div>

                        </form>

                    </div>
                    <!-- Conteúdo de Uma Aba -->
                    <!-- --------------------------------------------------------------------------------------- -->

                </div>
            </div>

        </div>


    </section>

<!--
<script type="text/javascript">
    $("#data_inicial, #data_final, #data_pagamento").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>
-->