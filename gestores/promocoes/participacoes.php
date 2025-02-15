<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    $id_promocao = filter_input(INPUT_POST, 'id_promocao', FILTER_SANITIZE_NUMBER_INT);
    $promocao = Promocoes::find($id_promocao);

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Promoções', 'c', 'index');
?>

<script src="js/promocoes.js"></script>

<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">grade</i>
    <h1>Envios e Participações na Promoção: <?php echo $promocao->nome; ?></h1>
</div>

<div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
    Este Registro não pode ser excluído por já ter sido utilizado no sistema.
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar">Voltar</a>
    <div class="espaco20"></div>

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Unidade</label>
            <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <?php
                $unidades = Unidades::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome_fantasia asc'));
                if(!empty($unidades)):
                    foreach($unidades as $unidade):
                        echo '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <!--
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Turma</label>
            <select name="turma" id="turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <?php
                $turmas = Turmas::all(array('order' => 'nome asc'));
                if(!empty($turmas)):
                    foreach($turmas as $turma):
                        echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
        </div>
        -->
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Pesquisar Participante</label>
            <input type="text" name="valor_pesquisa_promocao" id="valor_pesquisa_promocao" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <button type="button" name="pesquisar_promocao" id="pesquisar_promocao" id_promocao="<?php echo $promocao->id ?>" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

    <div id="listagem">
        <?php include_once('listagem-envios-participacoes.php'); ?>
    </div>

</section>

<div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>
