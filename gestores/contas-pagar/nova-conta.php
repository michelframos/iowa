<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Contas a Pagar', 'i', 'index');
?>

<script src="js/contas_pagar.js"></script>


<div tabindex="-1" class="modal fade" id="selecione-unidade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Unidade não selecionada</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Selecione a Unidade que deseja inserir nesta conta a pagar.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="unidade-ja-adicionada" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Unidade Duplicada</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">A Unidade selecionada já foi inserida a conta a pagar anteriormente.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-porcentagem" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Erro na Porcentagem</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">A soma das porcentagens é diferente de 100%.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-valor" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Erro no Valor</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">A soma dos valores é diferente do valor da conta a pagar.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>



<div tabindex="-1" class="modal fade" id="erro-unidade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Unidades</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Selecione pelo menos uma unidade para esta conta a pagar.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
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

<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">receipt</i>
        <h1>Cadastro / Alteração de Contas a Pagar</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar" registro="<?php echo $registro->id ?>">Voltar</a>
        <div class="espaco20"></div>

        <form action="" name="formDados" id="formDados" method="post" style="max-width: 800px;">

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                <label for="regular1" class="control-label">Data Lançamento</label>
                <input type="text" name="data_lancamento" id="data_lancamento" value="<?php echo !empty($registro->data_lancamento) ? $registro->data_lancamento->format('d/m/Y') : ''; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                <label for="regular1" class="control-label">Número de Parcelas</label>
                <input type="text" name="numero_parcelas" id="numero_parcelas" value="1" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                <label for="regular1" class="control-label">Data Vencimento</label>
                <input type="text" name="data_vencimento" id="data_vencimento" value="<?php echo !empty($registro->data_vencimento) ? $registro->data_vencimento->format('d/m/Y') : ''; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label float-left">
                <label for="regular1" class="control-label">Valor</label>
                <input type="text" name="valor" id="valor" value="<?php echo number_format($registro->valor, 2, ',', '.'); ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                <label>Fornecedor</label>
                <select name="id_fornecedor" id="id_fornecedor" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value=""></option>
                    <?php
                    $fornecedores = Fornecedores::all(array('conditions' => array('status = ?', 'a'), 'order' => 'fornecedor asc'));
                    if(!empty($fornecedores)):
                        foreach($fornecedores as $fornecedor):
                            echo $registro->id_fornecedor == $fornecedor->id ? '<option selected value="'.$fornecedor->id.'">'.$fornecedor->fornecedor.'</option>' : '<option value="'.$fornecedor->id.'">'.$fornecedor->fornecedor.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                <label>Categoria</label>
                <select name="id_categoria" id="id_categoria" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value=""></option>
                    <?php
                    $categorias = Categorias_Lancamentos::all(array('conditions' => array('status = ?', 'a'), 'order' => 'categoria asc'));
                    if(!empty($categorias)):
                        foreach($categorias as $categoria):
                            echo $registro->id_categoria == $categoria->id ? '<option selected value="'.$categoria->id.'">'.$categoria->categoria.'</option>' : '<option value="'.$categoria->id.'">'.$categoria->categoria.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
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
                            echo $registro->id_natureza == $natureza->id ? '<option selected value="'.$natureza->id.'">'.$natureza->natureza.'</option>' : '<option value="'.$natureza->id.'">'.$natureza->natureza.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                <label>Selecione uma Unidade e clique em Adicionar</label>
                <select name="unidade" id="unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value=""></option>
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

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                <label>Inserir como Porcentagem ou Valor</label>
                <select name="porcentagem-valor" id="porcentagem-valor" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="v">Valor</option>
                    <option value="p">Porcentagem</option>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
            <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-adicionar-unidade">Adicionar Unidade</button>
            <div class="clear"></div>

            <div class="espaco20"></div>
            <label>Unidades Desta Conta</label>
            <form action="" name="formUnidadesContaPagar" id="formUnidadesContaPagar" method="post">

                <div class="table-responsive">
                    <table class="table pmd-table table-hover">
                        <thead>
                        <tr>
                            <th>Unidade</th>
                            <th width="80" class="texto-centro"></th>
                            <th width="80" class="texto-centro"></th>
                        </tr>
                        </thead>
                        <tbody id="lista-unidades">

                        </tbody>
                    </table>
                </div>

            </form>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label class="control-label">Observações</label>
                <textarea name="observacoes" id="observacoes" class="form-control"></textarea>
            </div>
            <div class="clear"></div>

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-selecione-unidade" data-target="#selecione-unidade" data-toggle="modal"></div>
            <div class="oculto" id="ms-unidade-ja-adicionada" data-target="#unidade-ja-adicionada" data-toggle="modal"></div>
            <div class="oculto" id="ms-erro-porcentagem" data-target="#erro-porcentagem" data-toggle="modal"></div>
            <div class="oculto" id="ms-erro-valor" data-target="#erro-valor" data-toggle="modal"></div>
            <div class="oculto" id="ms-erro-unidade" data-target="#erro-unidade" data-toggle="modal"></div>
            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

        </form>

    </section>

<script>
    $(function(){
        $("#data_lancamento, #data_vencimento").datetimepicker({
            format: "DD/MM/YYYY"
        });
    });
</script>
