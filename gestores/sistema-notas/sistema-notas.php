<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Sistemade Notas', 'c', 'index');
?>
<script src="js/sistema-notas.js"></script>

<div tabindex="-1" class="modal fade" id="delete-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão deste sistema de notas? Esta ação é irreversível! </p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="simulador-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Simular Nota</h2>
            </div>
            <div class="modal-body">

                <p>Insira abaixo as notas desejadas. Não é obrigatório preencher todas as notas.</p>

                <form action="" name="formSimulador" id="formSimulador" method="post">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Prova Oral</label>
                        <input type="text" name="prova-oral" id="prova-oral" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Prova 1</label>
                        <input type="text" name="prova1" id="prova1" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Prova 2</label>
                        <input type="text" name="prova2" id="prova2" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Prova 3</label>
                        <input type="text" name="prova3" id="prova3" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Prova 4</label>
                        <input type="text" name="prova4" id="prova4" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Prova 5</label>
                        <input type="text" name="prova5" id="prova5" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Prova 6</label>
                        <input type="text" name="prova6" id="prova6" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="espaco20"></div>

                    <div id="resultado-simulador"></div>

                </form>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button id="bt-modal-simulador" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Calcular</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Fechar</button>
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
    <i class="material-icons texto-laranja pmd-md">format_shapes</i>
    <h1>Sistema de Notas</h1>
</div>

<div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
    Este Registro não pode ser excluído por já ter sido utilizado no sistema.
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-novo"> Novo Sistema de Notas</a>
    <button class="btn btn-primary pmd-btn-raised bt-simular" data-target="#simulador-dialog" data-toggle="modal">Simular Nota</button>
    <div class="espaco20"></div>

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Idioma</label>
            <select name="idioma" id="idioma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Selecione um Idioma</option>
                <?php
                $idiomas = Idiomas::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_idioma), 'order' => 'idioma asc'));
                if(!empty($idiomas)):
                    foreach($idiomas as $idioma):
                        echo $registro->id_idioma == $idioma->id ? '<option selected value="'.$idioma->id.'">'.$idioma->idioma.'</option>' : '<option value="'.$idioma->id.'">'.$idioma->idioma.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Pesquisar</label>
            <input type="text" name="valor" id="valor" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <button type="button" name="pesquisar" id="pesquisar" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

    <div id="listagem">
    <?php include_once('listagem.php'); ?>
    </div>

</section>

<div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

<script>
    $('#myModal').on('hidden.bs.modal', function (e) {
        // do something...
    })
</script>