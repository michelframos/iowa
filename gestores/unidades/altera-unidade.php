<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $registro = Unidades::find($id);
?>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Este nome de Prova para este Idioma já existe.</p>
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

<script src="js/unidades.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">place</i>
    <h1>Cadastro / Alteração de Unidade</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
    <div class="espaco20"></div>

    <form action="" name="formDados" id="formDados" method="post">

        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">

        <!--Default tab example -->
        <div class="pmd-card pmd-z-depth">
            <div class="pmd-tabs pmd-tabs-bg">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#unidade" aria-controls="home" role="tab" data-toggle="tab">Dados Da Unidade</a></li>
                    <li role="presentation"><a href="#banco" aria-controls="about" role="tab" data-toggle="tab" id="aba-dados-bancarios">Dados Bancários</a></li>
                    <li role="presentation"><a href="#campos-banco" aria-controls="about" role="tab" data-toggle="tab">Campos do Banco</a></li>
                    <li role="presentation"><a href="#gerente" aria-controls="about" role="tab" data-toggle="tab">Gerente</a></li>
                </ul>
            </div>
            <div class="pmd-card-body">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="unidade">

                        <div style="max-width: 800px;">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Nome da Unidade</label>
                                <input type="text" name="nome_fantasia" id="nome_fantasia" value="<?php echo $registro->nome_fantasia; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                            </div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Razão Social</label>
                                <input type="text" name="razao_social" id="razao_social" value="<?php echo $registro->razao_social; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">CNPJ</label>
                                <input type="text" name="cnpj" id="cnpj" value="<?php echo $registro->cnpj; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
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

                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">E-mail</label>
                                <input type="text" name="email" id="email" value="<?php echo $registro->email; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
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
                                <label for="regular1" class="control-label">Valor Hora Aula do Help</label>
                                <input type="text" name="valor_hora_aula_help" id="valor_hora_aula_help" value="<?php echo number_format($registro->valor_hora_aula_help, 2, ',', '.'); ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>

                        </div>

                    </div>

                    <div role="tabpanel" class="tab-pane" id="banco">

                        <div style="max-width: 800px;">
                            <!--
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Próximo Boleto</label>
                                <input type="text" name="proximo_boleto" id="proximo_boleto" value="<?php echo $registro->proximo_boleto ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>
                            -->

                            <div class="coluna-3">
                                <div class="form-group pmd-textfield">
                                    <label for="banco">Banco</label>
                                    <select name="codigo_banco" id="codigo_banco" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <?php
                                        $bancos = IowaPainel\BancosController::bancos();
                                        if(!empty($bancos)):
                                            foreach ($bancos as $banco):
                                                echo '<option value="'.$banco->codigo.'">'.$banco->nome.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!--
                            <div class="coluna-3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Banco</label>
                                    <input type="text" name="numero_banco" id="numero_banco" value="<?php //echo $registro->numero_banco ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                            -->

                            <div class="coluna-3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Carteira</label>
                                    <input type="text" name="carteira" id="carteira" value="<?php echo $registro->carteira ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>

                            <div class="coluna-3">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Espécie</label>
                                <input type="text" name="especie" id="especie" value="<?php echo $registro->especie ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>
                            </div>
                            <div class="clear"></div>

                            <div class="coluna-3">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Agência</label>
                                <input type="text" name="agencia" id="agencia" value="<?php echo $registro->agencia ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>
                            </div>

                            <div class="coluna-3">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Conta</label>
                                <input type="text" name="conta" id="conta" value="<?php echo $registro->conta ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>
                            </div>

                            <div class="coluna-3">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Código do Cliente</label>
                                <input type="text" name="codigo_cliente" id="codigo_cliente" value="<?php echo $registro->codigo_cliente ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>
                            </div>
                            <div class="clear"></div>

                            <div class="coluna-3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Juros</label>
                                    <input type="text" name="juros" id="juros" value="<?php echo $registro->juros ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>

                            <div class="coluna-3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Multa</label>
                                    <input type="text" name="multa" id="multa" value="<?php echo $registro->multa ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                            <div class="clear"></div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label class="control-label">Local Pagamento Antes do Vencimento</label>
                                <textarea name="local_pag_antes_vencto" id="local_pag_antes_vencto" class="form-control"><?php echo $registro->local_pag_antes_vencto ?></textarea>
                            </div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label class="control-label">Local Pagamento Antes Após Vencimento</label>
                                <textarea name="local_pag_depois_vencto" id="local_pag_depois_vencto" class="form-control"><?php echo $registro->local_pag_depois_vencto ?></textarea>
                            </div>

                        </div>

                    </div>

                    <div role="tabpanel" class="tab-pane" id="campos-banco">

                        <div style="max-width: 800px;">

                            <div class="coluna-3">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label class="control-label">Número Sequencial</label>
                                <input type="text" name="numero_sequencial" id="numero_sequencial" value="<?php echo $registro->numero_sequencial ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>
                            </div>

                            <div class="coluna-3">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label class="control-label">Impressão Boleto</label>
                                <input type="text" name="impressao_bolelto" id="impressao_bolelto" value="<?php echo $registro->impressao_bolelto ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>
                            </div>
                            <div class="clear"></div>

                            <div class="float-left margin-right-5">
                                <div class="pmd-switch">
                                    <label>
                                        <input type="checkbox" <?php echo $registro->desconto_ate_vencimento == 's' ? 'checked' : '' ?>>
                                        <span class="pmd-switch-label desconto_ate_vencimento" registro="<?php echo $registro->id ?>"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="float-left">
                                <label>Conceder desconto até o vencimento</label>
                            </div>
                            <div class="clear"></div>

                            <div class="float-left margin-right-5">
                                <div class="pmd-switch">
                                    <label>
                                        <input type="checkbox" <?php echo $registro->incluir_mora_multa == 's' ? 'checked' : '' ?>>
                                        <span class="pmd-switch-label incluir_mora_multa" registro="<?php echo $registro->id ?>"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="float-left">
                                <label>Incluir valor de Mora/Multa na remessa</label>
                            </div>
                            <div class="clear"></div>

                            <div class="float-left margin-right-5">
                                <div class="pmd-switch">
                                    <label>
                                        <input type="checkbox" <?php echo $registro->protestar_atrasados == 's' ? 'checked' : '' ?>>
                                        <span class="pmd-switch-label protestar_atrasados" registro="<?php echo $registro->id ?>"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="float-left">
                                <label>Protestar títulos atrasados</label>
                            </div>
                            <div class="clear"></div>

                            <div class="float-left margin-right-5">
                                <div class="pmd-switch">
                                    <label>
                                        <input type="checkbox" <?php echo $registro->informar_descontos_adicionais == 's' ? 'checked' : '' ?>>
                                        <span class="pmd-switch-label informar_descontos_adicionais" registro="<?php echo $registro->id ?>"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="float-left">
                                <label>Informar descontos adicionais na remessa</label>
                            </div>
                            <div class="clear"></div>

                            <div class="coluna-3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label class="control-label">Dias para protestar o título (mín 5 dias)</label>
                                    <input type="text" name="dias_para_protestar" id="dias_para_protestar" value="<?php echo $registro->dias_para_protestar ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                            <div class="clear"></div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label class="control-label">Beneficiário</label>
                                <input type="text" name="beneficiario" id="beneficiario" value="<?php echo $registro->beneficiario ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>

                            <div class="clear"></div>

                            <div class="pmd-card-title">
                                <h2 class="pmd-card-title-text">Posição de Leitura do Número do Boleto</h2>
                            </div>

                            <div class="coluna-3 margin-right-5">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label class="control-label">Posição inicial de leitura</label>
                                    <input type="text" name="boleto_posicao_inicial_leitura" id="boleto_posicao_inicial_leitura" value="<?php echo $registro->boleto_posicao_inicial_leitura ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>

                            <div class="coluna-3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label class="control-label">Nº de Caracteres</label>
                                    <input type="text" name="boleto_numero_caracteres" id="boleto_numero_caracteres" value="<?php echo $registro->boleto_numero_caracteres ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                            <div class="clear"></div>

                            <div class="pmd-card-title">
                                <h2 class="pmd-card-title-text">Posição de Leitura da Data de Pagamento</h2>
                            </div>

                            <div class="coluna-3 margin-right-5">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label class="control-label">Posição inicial de leitura</label>
                                    <input type="text" name="data_pag_posicao_inicial_leitura" id="data_pag_posicao_inicial_leitura" value="<?php echo $registro->data_pag_posicao_inicial_leitura ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>

                            <div class="coluna-3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label class="control-label">Nº de Caracteres</label>
                                    <input type="text" name="data_pag_numero_caracteres" id="data_pag_numero_caracteres" value="<?php echo $registro->data_pag_numero_caracteres ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                            <div class="clear"></div>

                        </div>

                    </div>

                    <!-- Divisão de Abas -->

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
        <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

    </form>

</section>

<script>
    $(function(){

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