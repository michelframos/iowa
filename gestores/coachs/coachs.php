<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Coachs', 'c', 'index');
?>

<script src="js/coachs.js"></script>


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


<div tabindex="-1" class="modal fade" id="delete-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão deste Nome de Prova? Esta ação é irreversível! </p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</div>


<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">person</i>
        <h1>COACHs</h1>
    </div>

    <div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        Este Registro não pode ser excluído por já ter sido utilizado no sistema.
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <!--
        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-novo"> Novo Coach</a>
        <div class="espaco20"></div>
        -->

        <!-- Form de Pesquisa -->
        <form action="" name="formPesquisa" id="formPesquisa" method="post">

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                <label>Coach</label>
                <select name="id_coach" id="id_coach" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option></option>
                    <?php
                    $colegas = Colegas::all(array('conditions' => array('status = ? and id_funcao = ?', 'a', 2), 'order' => 'nome asc'));
                    if(!empty($colegas)):
                        foreach($colegas as $colega):
                            echo $registro->id_colega == $colega->id ? '<option selected value="'.$colega->id.'">'.$colega->nome.'</option>' : '<option value="'.$colega->id.'">'.$colega->nome.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                <label>Instrutores</label>
                <select name="id_colega" id="id_colega" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option></option>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                <label>Status da Turma</label>
                <select name="status_turma" id="status_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="a">Ativas</option>
                    <option value="i">Inativas</option>
                    <option value="%">Todas</option>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Nome do Aluno</label>
                <input type="text" name="valor_pesquisa" id="valor_pesquisa" value="" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <button type="button" name="pesquisar" id="pesquisar" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
            <div class="espaco20"></div>
        </form>
        <!-- Form de Pesquisa -->

        <div id="listagem">
            <?php include_once('listagem.php'); ?>
        </div>


    </section>
