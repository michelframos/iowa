<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Contas a Pagar', 'c', 'index');
?>

<script src="js/contas_pagar.js"></script>

<div tabindex="-1" class="modal fade" id="delete-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão desta Conta a Pagar? Esta ação é irreversível! </p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="cancelar-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Cancelar Conta a Pagar?</h2>
            </div>
            <div class="modal-body">

                <form name="formCancelarConta" id="formCancelarConta">

                    <div class="form-group pmd-textfield">
                        <label class="control-label">Observação</label>
                        <textarea required class="form-control" name="observacao-cancelamento" id="observacao-cancelamento" style="height: 100px;"></textarea>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-modal-cancelar" registro="">Cancelar Conta a Pagar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-fecha-cancelar-parcela">Cancelar</button>
            </div>
        </div>
    </div>
</div>



<div tabindex="-1" class="modal fade" id="quitar-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Quitar Conta a Pagar</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formQuitar" id="formQuitar" method="">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Quitar a Parti de</label>
                        <select name="quitar_de" id="quitar_de" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value="caixa">Um Caixa</option>
                            <option value="conta_bancaria">Uma Conta Bancária</option>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <div id="box-caixa" class="coluna-3">
                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                            <label>Caixas</label>
                            <select name="caixa" id="caixa" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php
                                $caixas_abertos = Caixas::all(array('conditions' => array('situacao = ?', 'aberto'),'order' => 'data_abertura, hora_abertura asc'));
                                if(!empty($caixas_abertos)):
                                    foreach($caixas_abertos as $caixa_aberto):
                                        /*
                                        $usuario = Usuarios::find($caixa_aberto->id_colega);
                                        echo '<option value="'.$caixa_aberto->id.'">'.$usuario->login.'</option>';
                                        */
                                        echo '<option value="'.$caixa_aberto->id.'">'.$caixa_aberto->nome.'</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div id="box-conta-bancaria" class="coluna-3">
                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                            <label>Contas Bancárias</label>
                            <select name="conta_bancaria" id="conta_bancaria" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php
                                $contas_bancarias = Unidades::all(array('conditions' => array('numero_banco <> ?', ''),'order' => 'nome_fantasia asc'));
                                if(!empty($contas_bancarias)):
                                    foreach($contas_bancarias as $conta_bancaria):
                                        echo '<option value="'.$conta_bancaria->id.'">Conta da Unidade'.$conta_bancaria->nome_fantasia.'</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                        <label for="regular1" class="control-label">Data do Pagamento</label>
                        <input type="text" name="data_pagamento" id="data_pagamento" value="" class="form-control"><span class="pmd-textfield-focused" required></span>
                    </div>

                    <div class="espaco20"></div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                        <label>Forma de Pagamento / Recebimento</label>
                        <select name="id_forma_pagamento" id="id_forma_pagamento" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option value=""></option>
                            <?php
                            $formas = Formas_Pagamento::all(array('conditions' => array('status = ?', 'a'),'order' => 'forma_pagamento asc'));
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

                    <form name="formFormasPagamento" id="formFormasPagamento" method="post">
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
                    </form>

                    <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-adicionar-forma-pagamento">Adicionar Forma de Pagamento</button>
                    <div class="espaco20"></div>

                    <div id="dados-conta"></div>
                    <div class="espaco20"></div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" registro="" id="bt-quitar" type="button">Quitar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" id="bt-cancelar-quitar" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="quitar-selecinadas-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Quitar Selecionadas</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formQuitarSelecionadas" id="formQuitarSelecionadas" method="post">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Quitar a Parti de</label>
                        <select name="quitar_selecionadas_de" id="quitar_selecionadas_de" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value="caixa">Um Caixa</option>
                            <option value="conta_bancaria">Uma Conta Bancária</option>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <div id="box-caixa-selecionadas" class="coluna-3">
                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                            <label>Caixas</label>
                            <select name="caixa-selecionadas" id="caixa-selecionadas" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php
                                $caixas_abertos = Caixas::all(array('conditions' => array('situacao = ?', 'aberto'),'order' => 'data_abertura, hora_abertura asc'));
                                if(!empty($caixas_abertos)):
                                    foreach($caixas_abertos as $caixa_aberto):
                                        /*
                                        $usuario = Usuarios::find($caixa_aberto->id_colega);
                                        echo '<option value="'.$caixa_aberto->id.'">'.$usuario->login.'</option>';
                                        */
                                        echo '<option value="'.$caixa_aberto->id.'">'.$caixa_aberto->nome.'</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div id="box-conta-bancaria-selecionadas" class="coluna-3">
                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                            <label>Contas Bancárias</label>
                            <select name="conta_bancaria_selecionadas" id="conta_bancaria_selecionadas" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php
                                $contas_bancarias = Unidades::all(array('conditions' => array('numero_banco <> ?', ''),'order' => 'nome_fantasia asc'));
                                if(!empty($contas_bancarias)):
                                    foreach($contas_bancarias as $conta_bancaria):
                                        echo '<option value="'.$conta_bancaria->id.'">Conta da Unidade'.$conta_bancaria->nome_fantasia.'</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                    <div class="espaco20"></div>

                    <h2 class="h2">Valor total: <span style="color: #ff5722; font-size: 2em; font-weight: bold;" id="valor_total_parcelas">Calculando...</span> </h2>
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                        <label for="regular1" class="control-label">Data Pagamento</label>
                        <input type="text" name="data_pagamento_selecionadas" id="data_pagamento_selecionadas" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>

                    <div id="box-formas-recebimento">
                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna2">
                            <label>Forma de Pagamento</label>
                            <select name="id_forma_pagamento_selecionadas" id="id_forma_pagamento_selecionadas" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
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
                            <table id="nova-forma-recebimento-selecionadas">
                                <tr>
                                    <td width="350">Forma de Pagamento</td>
                                    <td width="50">Valor</td>
                                    <td width="50"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="espaco20"></div>


                        <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-adicionar-forma-pagamento-selecionadas">Adicionar Forma de Pagamento</button>

                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" parcelas="" total_parcelas="" id="bt-modal-quitar-selecionadas" registro="">Quitar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-cancelar-quitar-parcelas-selecionadas">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="alterar-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterar Conta(s) a Pagar</h2>
            </div>
            <div class="modal-body">

                <form name="formAlterarConta" id="formAlterarConta">

                    <h3 class="titulo">Alteração de Conta(s) a Pagar</h3>
                    <p>Informa abaixo o novo valor para a(s) conta(s) a pagar selecionada(s)</p>
                    <div class="espaco20"></div>

                    <div class="form-group pmd-textfield">
                        <label class="control-label">Valor</label>
                        <input type="text" name="novo-valor" id="novo-valor" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-modal-alterar" parcelas="">Alterar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-fecha-modal-alterar">Cancelar</button>
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


<div tabindex="-1" class="modal fade" id="excluir-contas-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Excluir Conta(s)?</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Confirma a exclusão da(s) conta(s) selecionada(s)? Esta ação é irreversível.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-modal-excluir-conta" registro="" parcelas="">Excluir</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="bt-canclar-exclusao">Cancelar</button>
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
<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">receipt</i>
        <h1>Contas a Pagar</h1>
    </div>

    <div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        Este Registro não pode ser excluído por já ter sido utilizado no sistema.
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <!-- <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" data-target="#nova-conta-dialog" data-toggle="modal" id="bt-novo"> Nova Conta a Pagar</a> -->
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-novo"> Nova Conta a Pagar</a>
        <button type="button" name="alterar-contas" id="alterar-contas" data-target="#alterar-dialog" data-toggle="modal" value="Alterar Conta(s)" class="btn btn-danger pmd-btn-raised">Alterar Conta(s)</button>
        <button type="button" name="alterar-vencimento" id="alterar-vencimento" value="Alterar Vencimento"  data-target="#alterar_vencimento-dialog" data-toggle="modal" class="btn btn-info pmd-btn-raised">Alterar Vencimento</button>
        <button type="button" name="quitar-selecionadas" id="quitar-selecionadas" data-target="#quitar-selecinadas-dialog" data-toggle="modal" value="Quitar Selecionadas" class="btn btn-danger pmd-btn-raised">Quitar Selecionadas</button>
        <a href="javascript:void(0);" class="btn btn-default pmd-btn-raised" id="bt-contas-pagas"> Ver Contas Pagas</a>
        <button type="button" name="excluir-contas" id="excluir-contas" value="Excluir Conta(s)" data-target="#excluir-contas-dialog" data-toggle="modal" value="Excluir Conta(s) Parcela" class="btn btn-warning pmd-btn-raised">Excluir Conta(s)</button>
        <div class="espaco20"></div>

        <!-- Form de Pesquisa -->
        <form action="" name="formPesquisa" id="formPesquisa" method="post">
            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
                <label for="regular1" class="control-label">Data Inicial</label>
                <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
                <label for="regular1" class="control-label">Data Final</label>
                <input type="text" name="data_final" id="data_final" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Natureza</label>
                <select name="id_natureza" id="id_natureza" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="%">Todas</option>
                    <?php
                    $naturezas = Natureza_Conta::all(array('conditions' => array('status = ?', 'a'), 'order' => 'natureza asc'));
                    if(!empty($naturezas)):
                        foreach($naturezas as $natureza):
                            echo '<option value="'.$natureza->id.'">'.$natureza->natureza.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Categoria</label>
                <select name="id_categoria" id="id_categoria" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="%">Todas</option>
                    <?php
                    $categorias = Categorias_Lancamentos::all(array('conditions' => array('status = ?', 'a'), 'order' => 'categoria asc'));
                    if(!empty($categorias)):
                        foreach($categorias as $categoria):
                            echo '<option value="'.$categoria->id.'">'.$categoria->categoria.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Unidade</label>
                <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="%">Todas</option>
                    <?php
                    $unidades = Unidades::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome_fantasia asc'));
                    if(!empty($unidades)):
                        foreach($unidades as $unidade):
                            echo '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                <label>Fornecedor</label>
                <select name="id_fornecedor" id="id_fornecedor" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="%">Todas</option>
                    <?php
                    $fornecedores = Fornecedores::all(array('conditions' => array('status = ?', 'a'), 'order' => 'fornecedor asc'));
                    if(!empty($fornecedores)):
                        foreach($fornecedores as $fornecedore):
                            echo '<option value="'.$fornecedore->id.'">'.$fornecedore->fornecedor.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <button type="button" name="pesquisar" id="pesquisar" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
            <div class="espaco20"></div>
        </form>
        <!-- Form de Pesquisa -->

        <div id="listagem">
            <?php include_once('listagem.php'); ?>
        </div>

        <div class="oculto" id="ms-quitar-modal" data-target="#quitar-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-erro-valor-dialog" data-target="#erro-valor-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-vencimento-alterado-dialog" data-target="#vencimento-alterado-dialog" data-toggle="modal"></div>

    </section>

<script type="text/javascript">
    $("#data_inicial, #data_final, #data_pagamento, #data_pagamento_selecionadas, #novo-vencimento").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>