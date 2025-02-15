<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    $abrir_caixa = Permissoes::find_by_id_usuario_and_tela(idUsuario(), 'Abrir Caixa');
    $fechar_caixa = Permissoes::find_by_id_usuario_and_tela(idUsuario(), 'Fechar Caixa');
    $fazer_transferencia = Permissoes::find_by_id_usuario_and_tela(idUsuario(), 'Fazer Transferência');
    $ver_caixas = Permissoes::find_by_id_usuario_and_tela(idUsuario(), 'Ver Todos os Caixas');

    $caixa = Caixas::find_by_id_colega_and_situacao(idUsuario(), 'aberto');
?>

<script src="js/caixas.js"></script>

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


<div tabindex="-1" class="modal fade" id="fechar-caixa-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Fechamento de Caixa</h2>
            </div>
            <div class="modal-body">
                <p>Confirma o fechamento deste caixa?</p>
                <div class="espaco20"></div>
                <div id="totais-caixa"></div>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" registro="" id="bt-fechar-caixa" type="button">Fechar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-primary">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="novo-caixa-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Novo Caixa</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formDados" id="formDados" method="post">

                    <!--
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Data de Abertura</label>
                        <input type="text" name="data_abertura" id="data_abertura" value="<?php echo date('d/m/Y') ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                    -->

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Nome do Caixa</label>
                        <input type="text" name="nome" id="nome" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Saldo Inicial</label>
                        <input type="text" name="saldo_inicial" id="saldo_inicial" value="<?php echo number_format(0, 2, ',', '.'); ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <!--
                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Dono do Caixa</label>
                        <select name="responsavel" id="responsavel" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value=""></option>
                            <?php
                            $colegas = Colegas::all(array('conditions' => array('status = ? and id_funcao = ?', 'a', 1), 'order' => 'apelido asc'));
                            if(!empty($colegas)):
                                foreach($colegas as $colega):
                                    echo '<option value="'.$colega->id.'">'.$colega->apelido.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>
                    -->
                    <div class="clear"></div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" id="criar" type="button">Abrir</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>



<div tabindex="-1" class="modal fade" id="tranferencia-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Tranferir Saldo</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formTransferencia" id="formTransferencia" method="">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Saldo em Caixa</label>
                        <input type="text" name="saldo_caixa" id="saldo_caixa" value="" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Transferir Para</label>
                        <select name="transferir_para" id="transferir_para" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value="outro_caixa">Outro Caixa</option>
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
                                    //$usuario = Usuarios::find($caixa_aberto->id_colega);
                                    //echo '<option value="'.$caixa_aberto->id.'">'.$usuario->login.'</option>';
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
                    <div class="espaco20"></div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Valor da Tranferência</label>
                        <input type="text" name="valor_transferencia" id="valor_transferencia" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" registro="" id="bt-tranferir" type="button">Transferir</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-transferencia-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Problema com a Transferência</h2>
            </div>
            <div class="modal-body">
                <p>O valor solicitado é maior do que o disponível em caixa no momento.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="lancamentos-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">LANÇAMENTO</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formLancamento" id="formLancamento">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Tipo de Lançamento</label>
                        <select name="tipo" id="tipo" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value="e">Entrada</option>
                            <option value="s">Saída</option>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>

                    <!--
                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                        <label>Categoria de Lançamento</label>
                        <select name="id_categoria" id="id_categoria" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value=""></option>
                            <?php
                            $categorias = Categorias_Lancamentos::all(array('conditions' => array('status = ?', 'a'),'order' => 'categoria asc'));
                            if(!empty($categorias)):
                                foreach($categorias as $categoria):
                                    echo '<option value="'.$categoria->id.'">'.$categoria->categoria.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>
                    -->

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                        <label>Forma de Pagamento / Recebimento</label>
                        <select name="id_forma_pagamento" id="id_forma_pagamento" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
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

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Valor do Lançameto</label>
                        <input type="text" name="valor_lancamento" id="valor_lancamento" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" registro="" conta="" id="bt-lancar" type="button">Lançar</button>
            </div>
        </div>
    </div>
</div>


<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">attach_money</i>
        <h1>Caixas</h1>
    </div>

    <div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        Este Registro não pode ser excluído por já ter sido utilizado no sistema.
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" data-target="#novo-caixa-dialog" data-toggle="modal" id="bt-novo"> Criar Caixa</a>

        <?php if($ver_caixas->i == 's'): ?>
            <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-ver-resumo-caixas"> Ver Resumo dos Caixas</a>
            <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-ver-todos-lancamento"> Ver Todos os Lançamentos</a>
            <a href="javascript:void(0);" class="btn btn-default pmd-btn-raised" id="bt-caixas-fechados"> Ver Caixas Fechados</a>
        <?php endif; ?>

        <?php if($abrir_caixa->i == 's' && $ver_caixas->i == 'n'): ?>
            <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-fazer-lancamento" registro="<?php echo $caixa->id ?>"> Fazer Lançamento</a>
        <?php endif; ?>

        <?php if($fechar_caixa->i == 's' && $ver_caixas->i == 'n'): ?>
            <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-fechar-meu-caixa" registro="<?php echo $caixa->id ?>"> Fechar Caixa</a>
        <?php endif; ?>

        <div class="espaco20"></div>


        <div id="listagem">
            <?php
            if($ver_caixas->i == 's'):
                include_once('listagem.php');
            else:
                include_once('listagem-detalhes.php');
            endif;
            ?>
        </div>


        <div class="espaco20"></div>
    </section>

    <div class="oculto" id="ms-erro-transferencia-modal" data-target="#erro-transferencia-dialog" data-toggle="modal"></div>
    <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>
    <div class="oculto" id="ms-lancamento" data-target="#lancamentos-dialog" data-toggle="modal"></div>
    <div class="oculto" id="ms-fechar-caixa" data-target="#fechar-caixa-dialog" data-toggle="modal"></div>
