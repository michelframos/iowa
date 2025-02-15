<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Nomes_Produtos::find($id);
$programacao = Programa_Aulas::all(array('conditions' => array('id_nome_produto = ?', $registro->id), 'order' => 'aula asc'));
?>

<script src="js/programacao.js"></script>

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
    <i class="material-icons texto-laranja pmd-md">assignment</i>
    <h1>Cadastro / Alteração de Programação e Conteúdo de Aulas</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
    <div class="espaco20"></div>

    <ul class="aviso">
        <li class="icone-aviso"><i class="material-icons texto-laranja pmd-md">info</i></li>
        <li class="texto-aviso">A alteração das Horas do Estágio implicará na exclusão da Programação e Conteúdo atual.</li>
    </ul>
    <div class="espaco20"></div>

    <form action="" name="formDados" id="formDados" method="post" style="max-width: 600px;">

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Nome do Material</label>
            <input type="text" name="nome_material" id="nome_material" readonly value="<?php echo $registro->nome_material ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Horas Semanais</label>
            <input type="text" name="horas_semanais" id="horas_semanais" readonly value="<?php echo $registro->horas_semanais ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Horas do Estágio</label>
            <input type="text" name="horas_estagio" id="horas_estagio" value="<?php echo $registro->horas_estagio ?>" required class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Número de Aulas</label>
            <input type="text" name="numero_aulas" id="numero_aulas" readonly value="<?php echo $registro->numero_aulas ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
        <div class="espaco20"></div>

        <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

    </form>

</section>

<?php
if(!empty($programacao)):
?>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">assignment_returned</i>
        <h1>Programação e Conteúdo</h1>
    </div>
    <div class="espaco20"></div>

    <form action="" name="formConteudo" id="formConteudo" method="post">

        <button type="submit" name="salvar_conteudo" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised salvar-conteudo">Salvar Conteúdo</button>
        <div class="espaco20"></div>

    <?php
    foreach($programacao as $conteudo):
    ?>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Conteúdo da Aula <?php echo $conteudo->aula ?></label>
            <input type="text" name="conteudo[<?php echo $conteudo->id ?>]" id="conteudo_<?php echo $conteudo->id ?>" value="<?php echo $conteudo->conteudo ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

    <?php
    endforeach;
    ?>
        <button type="submit" name="salvar_conteudo" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised salvar-conteudo">Salvar Conteúdo</button>
        <div class="espaco20"></div>

    </form>

</section>

<?php
endif;
?>

<div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>
