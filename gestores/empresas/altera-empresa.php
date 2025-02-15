<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $registro = Empresas::find($id);
?>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já existe uma Empresa com este CNPJ.</p>
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


<div tabindex="-1" class="modal fade" id="cnpj-invalido-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">CNPJ Inválido!</h2>
            </div>
            <div class="modal-body">
                <p>O CNPJ informado é inválido.</p>
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


<script src="js/empresas.js"></script>

<!-- Start Content -->
    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">store</i>
        <h1>Cadastro / Alteração de Empresa</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar">Voltar</a>
        <div class="espaco20"></div>

        <form action="" name="formDados" id="formDados" method="post">

            <!-- --------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Abas -->
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-tabs pmd-tabs-bg">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#dados-cadastrais" aria-controls="home" role="tab" data-toggle="tab">Dados Cadastrais</a></li>
                        <li role="presentation"><a href="#financeiro" aria-controls="about" role="tab" data-toggle="tab">Financeiro</a></li>
                        <li role="presentation"><a href="#gerente" aria-controls="about" role="tab" data-toggle="tab">Gerente</a></li>
                    </ul>
                </div>

                <div class="pmd-card-body">
                    <div class="tab-content">

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane active" id="dados-cadastrais">

                            <div style="max-width: 800px;">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Nome da Unidade</label>
                                    <input type="text" name="nome_fantasia" id="nome_fantasia" value="<?php echo $registro->nome_fantasia; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Razão Social</label>
                                    <input type="text" name="razao_social" id="razao_social" value="<?php echo $registro->razao_social; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                                    <label for="regular1" class="control-label">CNPJ</label>
                                    <input type="text" name="cnpj" id="cnpj" value="<?php echo $registro->cnpj; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                                    <label for="regular1" class="control-label">Inscrição Estadual</label>
                                    <input type="text" name="ie" id="ie" value="<?php echo $registro->ie; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                                    <label for="regular1" class="control-label">Login</label>
                                    <input type="text" name="login" id="login" value="<?php echo $registro->login; ?>" class="form-control" <?php echo empty($registro->login) ? 'required' : ''; ?> <?php echo !empty($registro->login) ? 'readonly' : ''; ?>><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                                    <label for="regular1" class="control-label">Senha</label>
                                    <input type="text" name="senha" id="senha" value="" class="form-control" <?php echo empty($registro->senha) ? 'required' : ''; ?>><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                                    <label for="regular1" class="control-label">Confirma Senha</label>
                                    <input type="text" name="confirma_senha" id="confirma_senha" value="" class="form-control" <?php echo empty($registro->senha) ? 'required' : ''; ?>><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="coluna-3 float-left">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                        <label for="regular1" class="control-label">CEP</label>
                                        <input type="text" name="cep" id="cep" value="<?php echo $registro->cep; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <button type="button" name="busca-cep" id="busca-cep" value="Buscar Endereço" class="btn btn-info pmd-btn-raised">Buscar Endereço</button>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Endereço</label>
                                    <input type="text" name="rua" id="rua" value="<?php echo $registro->rua; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="coluna-1-3 float-left">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                        <label for="regular1" class="control-label">Número</label>
                                        <input type="text" name="numero" id="numero" value="<?php echo $registro->numero; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>

                                <div class="coluna-2-3 float-left">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                        <label for="regular1" class="control-label">Bairro</label>
                                        <input type="text" name="bairro" id="bairro" value="<?php echo $registro->bairro; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>
                                <div class="clear"></div>

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

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-5">
                                    <label for="regular1" class="control-label">Telefone 1</label>
                                    <input type="text" name="telefone1" id="telefone1" value="<?php echo $registro->telefone1; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>


                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-5">
                                    <label for="regular1" class="control-label">Telefone 2</label>
                                    <input type="text" name="telefone2" id="telefone2" value="<?php echo $registro->telefone2; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">E-mail</label>
                                    <input type="text" name="email" id="email" value="<?php echo $registro->email; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Dia de Vencimento das Mensalidade</label>
                                    <input type="text" name="dia_vencimento" id="dia_vencimento" value="<?php echo $registro->dia_vencimento; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Valor Hora Aula do Help</label>
                                    <input type="text" name="valor_hora_aula_help" id="valor_hora_aula_help" value="<?php echo number_format($registro->valor_hora_aula_help, 2, ',', '.'); ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="financeiro">

                            <div>

                                <button type="button" name="alterar-parcela" id="alterar-parcela" value="Alterar" class="btn btn-info pmd-btn-raised">Alterar</button>
                                <button type="button" name="zerar-valores" id="zerar-valores" value="Zerar Valores" class="btn btn-info pmd-btn-raised">Zerar Valores</button>
                                <button type="button" name="quitar-parcela" id="quitar-parcela" data-target="#quitar-parcela-dialog" data-toggle="modal" value="Quitar Parcela" class="btn btn-danger pmd-btn-raised">Quitar Parcela</button>
                                <button type="button" registro="<?php echo $registro->id ?>" name="adicionar-parcela" id="adicionar-parcela" value="Adicionar Parcela" class="btn btn-danger pmd-btn-raised">Adicionar Parcela</button>
                                <button type="button" name="excluir-parcelas" id="excluir-parcelas" value="Excluir Parcela(s)" data-target="#excluir-parcelar-dialog" data-toggle="modal" value="Excluir Parcelas Parcela" class="btn btn-warning pmd-btn-raised">Excluir Parcela(s)</button>
                                <button type="button" name="cancelar-parcelas" id="cancelar-parcelas" value="Cancelar Parcela(s)" data-target="#cancelar-parcela-dialog" data-toggle="modal" value="Cancelar Parcela" class="btn btn-warning pmd-btn-raised">Cancelar Parcela(s)</button>
                                <form action="" name="formParcelas" id="formParcelas" method="post">

                                    <?php
                                    $parcelas = Parcelas::all(array('conditions' => array('id_empresa = ? and pagante = ?', $registro->id, 'empresa'), 'order' => 'data_vencimento asc'));
                                    if(!empty($parcelas)):
                                        ?>
                                        <!-- Basic Table -->
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Data Vencimento</th>
                                                    <th>Aluno</th>
                                                    <!--<th>Idioma</th>-->
                                                    <th>Valor</th>
                                                    <th>Juros</th>
                                                    <th>Multa</th>
                                                    <th>Acrescimo</th>
                                                    <th>Desconto</th>
                                                    <th>Total</th>
                                                    <th>Pago</th>
                                                    <th colspan="4"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach($parcelas as $parcela):

                                                    try {
                                                        $aluno = Alunos::find($parcela->id_aluno);
                                                    } catch(\ActiveRecord\RecordNotFound $e){
                                                        $aluno = '';
                                                    }
                                                    /*
                                                    $turma = Turmas::find($parcela->id_turma);
                                                    $idioma = Idiomas::find($parcela->id_idioma);
                                                    */
                                                    echo '<tr>';

                                                    if($parcela->pago == 'n' && $parcela->cancelada == 'n'):
                                                        echo '<td>';
                                                        echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                                                        echo '<input type="checkbox" value="'.$parcela->id.'" class="parcela pm-ini" />';
                                                        echo '<span class="pmd-checkbox-label"></span>';
                                                        echo '</label>';
                                                        echo '</td>';
                                                    else:
                                                        echo '<td></td>';
                                                    endif;

                                                    echo '<td data-title="Data">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                                                    echo '<td data-title="Idioma">'.$aluno->nome.'</td>';
                                                    //echo '<td data-title="Idioma">'.$idioma->idioma.'</td>';
                                                    echo '<td data-title="Valor">R$ '.number_format($parcela->valor, 2, ',', '.').'</td>';
                                                    echo '<td data-title="Valor">R$ '.number_format($parcela->juros, 2, ',', '.').'</td>';
                                                    echo '<td data-title="Valor">R$ '.number_format($parcela->multa, 2, ',', '.').'</td>';
                                                    echo '<td data-title="Valor">R$ '.number_format($parcela->acrescimo, 2, ',', '.').'</td>';
                                                    echo '<td data-title="Valor">R$ '.number_format($parcela->desconto, 2, ',', '.').'</td>';
                                                    echo '<td data-title="Valor">R$ '.number_format($parcela->total, 2, ',', '.').'</td>';
                                                    echo $parcela->pago == 's' ? '<td data-title="Pago">SIM</td>' : '<td data-title="Pago">NÂO</td>';
                                                    echo $parcela->pago == 'n' ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-alterar-parcela" parcela="'.$parcela->id.'" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar Parcela"><i class="material-icons pmd-sm">mode_edit</i> </a></td>' : '<td></td>';
                                                    echo ($parcela->pago == 'n' && $parcela->cancelada == 'n') ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-cancelar-parcela" data-target="#cancelar-parcela-dialog" data-toggle="modal" parcela="'.$parcela->id.'" registro="'.$registro->id.'" title="Cencelar Parcela"><i class="material-icons pmd-sm">highlight_off</i> </a></td>' : '<td></td>';
                                                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-excluir-parcela" data-target="#exclui-parcela-dialog" data-toggle="modal" registro="'.$parcela->id.'"><i class="material-icons pmd-sm">delete_forever</i> </a></td>';
                                                    echo $parcela->pago == 's' || $parcela->cancelada == 's' ? '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-remover-pagamento-parcela" registro="'.$parcela->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Remover Pagamento"><i class="material-icons pmd-sm">undo</i> </a></td>' : '<td></td>';
                                                    echo '</tr>';
                                                endforeach;
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <?php
                                    else:
                                        echo '<h2 class="h2">Esta empresa não possue parcelas.</h2>';
                                    endif;
                                    ?>

                                </form>

                            </div>

                            <script>
                                $(function(){
                                    $('.bt-cancelar-parcela').click(function(){

                                        $('#bt-modal-cancelar-parcela').attr('registro', $(this).attr('parcela'));
                                    });
                                });
                            </script>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="gerente">

                            <div style="max-width: 800px;">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Gerente</label>
                                    <select name="id_gerente" id="id_gerente" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <option></option>
                                        <?php
                                        $perfis = Perfis::find_all_by_status_and_listar_como_gerente('a', 's');
                                        if(!empty($perfis)):
                                            foreach($perfis as $perfil):
                                                $usuarios = Usuarios::find_all_by_status_and_id_perfil('a', $perfil->id);
                                                if(!empty($usuarios)):
                                                    foreach($usuarios as $usuario):
                                                        echo $registro->id_gerente == $usuario->id ? '<option selected value="'.$usuario->id.'">'.$usuario->nome.'</option>' : '<option value="'.$usuario->id.'">'.$usuario->nome.'</option>';
                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>

                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                    </div>
                </div>

            </div>
            <div class="espaco20"></div>
            <!--Default tab example end-->

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-cnpj-invalido-modal" data-target="#cnpj-invalido-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-altera-parcela-modal" data-target="#altera-parcela-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-confirma-senha-modal" data-target="#confirma-senha-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

        </form>

    </section>

<script>
    $(function(){

        $("#data_pagamento").datetimepicker({
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

    });
</script>