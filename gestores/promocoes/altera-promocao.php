<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $registro = Promocoes::find($id);
?>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já existe uma Promoção este nome.</p>
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

<script src="js/promocoes.js"></script>

<!-- Start Content -->
    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">grade</i>
        <h1>Cadastro / Alteração de Promoção</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar">Voltar</a>
        <div class="espaco20"></div>

        <form action="" name="formDados" id="formDados" method="post">

            <div class="coluna-1-3">
                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Data de Início</label>
                    <input type="text" name="data_inicio" id="data_inicio" value="<?php echo !empty($registro->data_inicio) ? $registro->data_inicio->format('d/m/Y') : ''; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>
            </div>

            <div class="coluna-1-3">
                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Data de Término</label>
                    <input type="text" name="data_termino" id="data_termino" value="<?php echo !empty($registro->data_termino) ? $registro->data_termino->format('d/m/Y') : ''; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>
            </div>

            <div class="coluna-1-3">
                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Por Tempo Indeterminado</label>
                    <select name="tempo_indeterminado" id="tempo_indeterminado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                        <option <?php echo $registro->tempo_indeterminado == 'n' ? 'selected' : '' ?> value="n">Não</option>
                        <option <?php echo $registro->tempo_indeterminado == 's' ? 'selected' : '' ?> value="s">Sim</option>
                    </select>
                </div>
            </div>

            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Nome</label>
                <input type="text" name="nome" id="nome" value="<?php echo $registro->nome; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Mensagem</label>
                <textarea class="form-control" name="mensagem" id="mensagem"><?php echo $registro->mensagem ?></textarea>
            </div>

            <div class="coluna-1-3">
                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Número de Cupons Disponíveis</label>
                    <input type="text" name="numero_cupons" id="numero_cupons" value="<?php echo $registro->numero_cupons; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                </div>
            </div>

            <div class="coluna-1-3">
                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                    <label for="regular1" class="control-label">Promoção Para</label>
                    <select name="para" id="para" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                        <option value=""></option>
                        <option <?php echo $registro->para == 'aluno' ? 'selected' : '' ?> value="aluno">O próprio aluno</option>
                        <option <?php echo $registro->para == 'compartilhamento' ? 'selected' : '' ?> value="compartilhamento">Compartilhamento</option>
                    </select>
                </div>
            </div>
            <div class="clear"></div>

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

        </form>

    </section>