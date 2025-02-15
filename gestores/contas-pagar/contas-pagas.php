<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');
?>

<script src="js/contas_pagar.js"></script>

<div tabindex="-1" class="modal fade" id="delete-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão deste Caixa e todos os seus registros? Esta ação é irreversível! </p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="nova-conta-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Nova Conta a Pagar</h2>
            </div>
            <div class="modal-body">

                <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                    <label for="regular1" class="control-label">Data Lançamento</label>
                    <input type="text" name="data_lancamento" id="data_lancamento" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                    <label for="regular1" class="control-label">Data Vencimento</label>
                    <input type="text" name="data_vencimento" id="data_vencimento" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                    <label for="regular1" class="control-label">Valor</label>
                    <input type="text" name="valor" id="valor" value="<?php echo number_format(0, 2, ',', '.'); ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>
                <div class="clear"></div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                    <label>Natureza da Conta</label>
                    <select name="natureza" id="natureza" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                        <option value=""></option>
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

                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label class="control-label">Observações</label>
                    <textarea name="observacoes" id="observacoes" class="form-control"></textarea>
                </div>
                <div class="clear"></div>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">Lançar</button>
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

                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Caixa de Origem</label>
                    <input type="text" name="caixa_origem" id="caixa_origem" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Saldo em Caixa</label>
                    <input type="text" name="saldo_caixa" id="saldo_caixa" value="" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                </div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                    <label>Transferir Para</label>
                    <select name="tranferir_para" id="tranferir_para" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                        <option value="outro_caixa">Outro Caixa</option>
                        <option value="conta_bancaria">Uma Conta Bancária</option>
                    </select>
                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                </div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                    <label>Caixas</label>
                    <select name="caixa" id="caixa" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                        <option value=""></option>
                        <?php
                        $caixas_abertos = Caixas::all(array('conditions' => array('situacao = ?', 'aberto'),'order' => 'data_abertura, hora_abertura asc'));
                        if(!empty($caixas_abertos)):
                            foreach($caixas_abertos as $caixa_aberto):
                                $dono_caixa = Responsaveis_Caixa::find_by_id_caixa_and_tipo($caixa_aberto->id, 'dono');
                                $colega = Colegas::find($dono_caixa->id_colega);
                                echo '<option value="'.$caixa_aberto->id.'">'.$colega->apelido.'</option>';
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                </div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
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
                <div class="espaco20"></div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Valor da Tranferência</label>
                    <input type="text" name="valor_tranferencia" id="valor_tranferencia" value="" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                </div>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">Transferir</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">receipt</i>
        <h1>Contas Pagas</h1>
    </div>

    <div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        Este Registro não pode ser excluído por já ter sido utilizado no sistema.
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar"> Voltar</a>
        <div class="espaco20"></div>

        <!-- Form de Pesquisa -->
        <form action="" name="formPesquisaPagas" id="formPesquisaPagas" method="post">
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

            <button type="button" name="pesquisar_pagas" id="pesquisar_pagas" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
            <div class="espaco20"></div>
        </form>
        <!-- Form de Pesquisa -->

        <div id="listagem">
            <?php include_once('listagem-contas-pagas.php'); ?>
        </div>

    </section>

<script type="text/javascript">
    $("#data_lancamento, #data_vencimento, #data_inicial, #data_final").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>