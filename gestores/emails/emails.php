<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    try{
        $registro = Envio_Emails::find(1);
    } catch(\ActiveRecord\RecordNotFound $e){
        $email = new Envio_Emails();
        $email->id = 1;
        $email->save();

        $registro = Envio_Emails::find(1);
    }

?>

<script src="js/emails.js"></script>

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
        <i class="material-icons texto-laranja pmd-md">drafts</i>
        <h1>Configurações de Envio de Emails</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <form action="" name="formDados" id="formDados" method="post" style="max-width: 1000px;">

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Email</label>
                <input type="text" name="email" id="email" value="<?php echo $registro->email ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Senha</label>
                <input type="text" name="senha" id="senha" value="<?php echo $registro->senha ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Usuário SMTP</label>
                <input type="text" name="usuario_smtp" id="usuario_smtp" value="<?php echo $registro->usuario_smtp ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">SMTP</label>
                <input type="text" name="smtp" id="smtp" value="<?php echo $registro->smtp ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Porta do Servidor SMTP</label>
                <input type="text" name="porta_smtp" id="porta_smtp" value="<?php echo $registro->porta_smtp ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna2">
                <label>Requer Autenticação</label>
                <select name="requer_autenticacao" id="requer_autenticacao" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                    <option <?php echo $registro->requer_autenticacao == 's' ? 'selected' : '' ?> value="s">Sim</option>
                    <option <?php echo $registro->requer_autenticacao == 'n' ? 'selected' : '' ?> value="n">Não</option>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna2">
                <label>Tipo Autenticação</label>
                <select name="tipo_autenticacao" id="tipo_autenticacao" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                    <option <?php echo $registro->tipo_autenticacao == 'ssl' ? 'selected' : '' ?> value="ssl">SSL</option>
                    <option <?php echo $registro->tipo_autenticacao == 'tsl' ? 'selected' : '' ?> value="tsl">TSL</option>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
            <div class="clear"></div>

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

        </form>

    </section>