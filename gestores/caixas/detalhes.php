<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $caixa = Caixas::find($id);
?>

<script src="js/caixas.js"></script>

<div tabindex="-1" class="modal fade" id="remover-pagamento-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Estornar Pagamento</h2>
            </div>
            <div class="modal-body">
                <p>Confirma o estorno deste pagamento?</p>
                <div class="espaco20"></div>

                <form action="" name="formEstorno" id="formEstorno" method="post">

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

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" registro="" id="bt-estornar" type="button">Estornar</button>
                <button data-dismiss="modal" id="bt-cancelar-estorno" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-primary">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">attach_money</i>
        <h1>Detalhes do Caixa: <?php echo $caixa->id ?></h1>
    </div>

    <div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        Este Registro não pode ser excluído por já ter sido utilizado no sistema.
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
        <div class="espaco20"></div>

        <div>
            <h3 class="h3">Situação: <?php echo $caixa->situacao ?></h3>
        </div>

        <div id="listagem">
            <?php include_once('listagem-detalhes.php'); ?>
        </div>

    </section>
