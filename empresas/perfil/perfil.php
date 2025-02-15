<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');
    $registro = Empresas::find(idEmpresa());
?>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já existe uma Empresa com este CNPJ.</p>
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


<div tabindex="-1" class="modal fade" id="cnpj-invalido-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">CNPJ Inválido!</h2>
            </div>
            <div class="modal-body">
                <p>O CNPJ informado é inválido.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="confirma-senha-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Confirmação de Senha</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">A senha e a confirmação não são iguais.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="js/perfil.js"></script>

<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">account_circle</i>
    <h1> Meu Perfil </h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="coluna-1-3">
        <div class="espaco20"></div>
        <div data-provides="fileinput" class="fileinput fileinput-new col-lg-12">
            <div data-trigger="fileinput" class="fileinput-preview thumbnail img-circle img-responsive">
                <img src="<?php echo HOME ?>/assets/imagens/empresas/gde_<?php echo $registro->imagem ?>" id="imagem-perfil">
            </div>
            <div class="action-button">
                <span class="btn btn-default btn-raised btn-file ripple-effect">
                    <span class="fileinput-new"><i class="material-icons md-light pmd-xs">add</i></span>
                    <span class="fileinput-exists"><i class="material-icons md-light pmd-xs">mode_edit</i></span>
                    <input type="file" name="imagem" id="imagem">
                </span>
                <a data-dismiss="fileinput" class="btn btn-default btn-raised btn-file ripple-effect fileinput-exists" href="javascript:void(0);"><i class="material-icons md-light pmd-xs">close</i></a>
            </div>
        </div>
    </div>

    <div class="coluna-2-3">

    <form action="" name="formPerfil" id="formPerfil" method="post">

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Nome Fantasia</label>
            <input type="text" name="nome_fantasia" id="nome_fantasia" value="<?php echo $registro->nome_fantasia; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Razão Social</label>
            <input type="text" name="razao_social" id="razao_social" value="<?php echo $registro->razao_social; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
            <label for="regular1" class="control-label">CNPJ</label>
            <input type="text" name="cnpj" id="cnpj" value="<?php echo $registro->cnpj; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
            <label for="regular1" class="control-label">Inscrição Estadual</label>
            <input type="text" name="ie" id="ie" value="<?php echo $registro->ie; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
            <label for="regular1" class="control-label">Login</label>
            <input type="text" name="login" id="login" value="<?php echo $registro->login; ?>" class="form-control" <?php echo empty($registro->login) ? 'required' : ''; ?> <?php echo !empty($registro->login) ? 'readonly' : ''; ?>><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
            <label for="regular1" class="control-label">Senha</label>
            <input type="text" name="senha" id="senha" value="" class="form-control" <?php echo empty($registro->senha) ? 'required' : ''; ?>><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
            <label for="regular1" class="control-label">Confirma Senha</label>
            <input type="text" name="confirma_senha" id="confirma_senha" value="" class="form-control" <?php echo empty($registro->senha) ? 'required' : ''; ?>><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <div class="coluna-3 float-left">
            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">CEP</label>
                <input type="text" name="cep" id="cep" value="<?php echo $registro->cep; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
            <button type="button" name="busca-cep" id="busca-cep" value="Buscar Endereço" class="btn btn-info pmd-btn-raised">Buscar Endereço</button>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Endereço</label>
            <input type="text" name="rua" id="rua" value="<?php echo $registro->rua; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="coluna-1-3 float-left">
            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Número</label>
                <input type="text" name="numero" id="numero" value="<?php echo $registro->numero; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
        </div>

        <div class="coluna-2-3 float-left">
            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Bairro</label>
                <input type="text" name="bairro" id="bairro" value="<?php echo $registro->bairro; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
            </div>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Complemento</label>
            <input type="text" name="complemento" id="complemento" value="<?php echo $registro->complemento; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="coluna-3 float-left margin-right-5">
            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                <label>Estado</label>
                <select name="estado" id="estado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option></option>
                    <?php
                    $estados = Estados::all();
                    if(!empty($estados)):
                        foreach($estados as $estado):
                            echo $registro->estado == $estado->estado_id ? '<option selected value="'.$estado->estado_id.'">'.$estado->uf.'</option>' : '<option value="'.$estado->estado_id.'">'.$estado->uf.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
        </div>

        <div class="coluna-3 float-left">
            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                <label>Cidade</label>
                <select name="cidade" id="cidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option></option>
                </select>
                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
            </div>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-5">
            <label for="regular1" class="control-label">Telefone 1</label>
            <input type="text" name="telefone1" id="telefone1" value="<?php echo $registro->telefone1; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>


        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-5">
            <label for="regular1" class="control-label">Telefone 2</label>
            <input type="text" name="telefone2" id="telefone2" value="<?php echo $registro->telefone2; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">E-mail</label>
            <input type="text" name="email" id="email" value="<?php echo $registro->email; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
        <div class="espaco20"></div>

        <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-cnpj-invalido-modal" data-target="#cnpj-invalido-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-confirma-senha-modal" data-target="#confirma-senha-dialog" data-toggle="modal"></div>

    </form>

    </div>

    <div class="clear"></div>
</section>

<script>
    $(function () {
        <?php if(!empty($registro->estado)): ?>
        $.post('../includes/lista-cidades.php', {estado: <?php echo $registro->estado ?>}, function(data){

            $('#cidade').html(data);

            <?php
            if(!empty($registro->cidade)):
            ?>
            $('#cidade option[value="'+<?php echo $registro->cidade ?>+'"]').prop("selected", true);
            <?php
            else:
            ?>
            $('#cidade').html('');
            <?php
            endif;
            ?>

        });
        <?php endif; ?>

        $('#estado').change(function(){

            $.post('../includes/lista-cidades.php', {estado: $('#estado').val()}, function(data){

                $('#cidade').html(data);

            });
        });
    });
</script>