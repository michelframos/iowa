<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Sistema_Notas::find($id);

?>

<style>
    .prova-oculta{ display: none; }
</style>

<script src="js/sistema-notas.js"></script>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já exite um Sistema de Notas com este nome.</p>
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
    <h1>Cadastro / Alteração de Sistema de Nota</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
    <div class="espaco20"></div>

    <form action="" name="formDados" id="formDados" method="post" style="max-width: 600px;">

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Idioma</label>
            <select name="idioma" id="idioma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                <option>Selecione um Idioma</option>
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

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Nome do Sistema de Notas</label>
            <input type="text" name="nome" id="nome" value="<?php echo $registro->nome ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Prova Oral</label>
            <select name="prova-oral" id="prova-oral" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" >
                <option value="">Selecione uma Prova</option>
                <?php
                $nomes = Nome_Provas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                if(!empty($nomes)):
                    foreach($nomes as $nome):
                        echo $registro->id_nome_prova_oral == $nome->id ? '<option selected value="'.$nome->id.'">'.$nome->nome.'</option>' : '<option value="'.$nome->id.'">'.$nome->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="prova-a-mostra">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Prova 1</label>
            <select name="prova1" id="prova1" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" >
                <option value="">Selecione uma Prova</option>
                <?php
                $nomes = Nome_Provas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                if(!empty($nomes)):
                    foreach($nomes as $nome):
                        echo $registro->id_nome_prova1 == $nome->id ? '<option selected value="'.$nome->id.'">'.$nome->nome.'</option>' : '<option value="'.$nome->id.'">'.$nome->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        </div>

        <div <?php echo !(empty($registro->id_nome_prova2)) ? 'class="prova-a-mostra"' : 'class="prova-oculta"' ?> id="prova-oculta2">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Prova 2</label>
            <select name="prova2" id="prova2" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" >
                <option value="">Selecione uma Prova</option>
                <?php
                $nomes = Nome_Provas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                if(!empty($nomes)):
                    foreach($nomes as $nome):
                        echo $registro->id_nome_prova2 == $nome->id ? '<option selected value="'.$nome->id.'">'.$nome->nome.'</option>' : '<option value="'.$nome->id.'">'.$nome->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        </div>

        <div <?php echo !(empty($registro->id_nome_prova3)) ? 'class="prova-a-mostra"' : 'class="prova-oculta"' ?> id="prova-oculta3">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Prova 3</label>
            <select name="prova3" id="prova3" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" >
                <option value="">Selecione uma Prova</option>
                <?php
                $nomes = Nome_Provas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                if(!empty($nomes)):
                    foreach($nomes as $nome):
                        echo $registro->id_nome_prova3 == $nome->id ? '<option selected value="'.$nome->id.'">'.$nome->nome.'</option>' : '<option value="'.$nome->id.'">'.$nome->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        </div>

        <div <?php echo !(empty($registro->id_nome_prova4)) ? 'class="prova-a-mostra"' : 'class="prova-oculta"' ?> id="prova-oculta4">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Prova 4</label>
            <select name="prova4" id="prova4" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" >
                <option value="">Selecione uma Prova</option>
                <?php
                $nomes = Nome_Provas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                if(!empty($nomes)):
                    foreach($nomes as $nome):
                        echo $registro->id_nome_prova4 == $nome->id ? '<option selected value="'.$nome->id.'">'.$nome->nome.'</option>' : '<option value="'.$nome->id.'">'.$nome->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        </div>

        <div <?php echo !(empty($registro->id_nome_prova5)) ? 'class="prova-a-mostra"' : 'class="prova-oculta"' ?> id="prova-oculta5">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Prova 5</label>
            <select name="prova5" id="prova5" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" >
                <option value="">Selecione uma Prova</option>
                <?php
                $nomes = Nome_Provas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                if(!empty($nomes)):
                    foreach($nomes as $nome):
                        echo $registro->id_nome_prova5 == $nome->id ? '<option selected value="'.$nome->id.'">'.$nome->nome.'</option>' : '<option value="'.$nome->id.'">'.$nome->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        </div>

        <div <?php echo !(empty($registro->id_nome_prova6)) ? 'class="prova-a-mostra"' : 'class="prova-oculta"' ?> id="prova-oculta6">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
            <label>Prova 6</label>
            <select name="prova6" id="prova6" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" >
                <option value="">Selecione uma Prova</option>
                <?php
                $nomes = Nome_Provas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                if(!empty($nomes)):
                    foreach($nomes as $nome):
                        echo $registro->id_nome_prova6 == $nome->id ? '<option selected value="'.$nome->id.'">'.$nome->nome.'</option>' : '<option value="'.$nome->id.'">'.$nome->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        </div>

        <button type="submit" name="adicionar-prova" id="adicionar-prova" class="btn btn-info pmd-btn-raised">Adicionar Prova</button>
        <div class="espaco20"></div>
        <div id="provas-do-sistema"></div>

        <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
        <div class="espaco20"></div>

        <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

    </form>

</section>
