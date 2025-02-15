<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$usuario = Usuarios::find(idUsuario());
?>


<div tabindex="-1" class="modal fade" id="senha-atual-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Senha Atual</h2>
            </div>
            <div class="modal-body">
                <p>Senha atual incorreta!</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="senhas-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Senhas</h2>
            </div>
            <div class="modal-body">
                <p>A nova senha e a confirmação não correspondem!</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="senha-branco-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Senha Vazia</h2>
            </div>
            <div class="modal-body">
                <p>A nova senha está em branco</p>
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


<script src="js/perfil-usuario.js"></script>

<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">account_circle</i>
    <h1> Meu Perfil </h1>
</div>

<section class="pmd-card pmd-z-depth padding-10" style="max-width: 1000px;">

    <div class="coluna-1-3">
        <div class="espaco20"></div>
        <div data-provides="fileinput" class="fileinput fileinput-new col-lg-3">
            <div data-trigger="fileinput" class="fileinput-preview thumbnail img-circle img-responsive">
                <img src="<?php echo HOME ?>/assets/imagens/usuarios/gde_<?php echo $usuario->imagem ?>" id="imagem-perfil">
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

    <div class="coluna-2-3"">

        <form action="" name="formPerfil" id="formPerfil" method="post">

            <h3 class="heading">Dados Pessoais</h3>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Nome de Usuário</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control empty" name="nome_usuario" id="nome_usuario" placeholder="" readonly value="<?php echo $usuario->login ?>"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Nome</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control empty" name="nome" id="nome" value="<?php echo $usuario->nome ?>" placeholder="Nome"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>


            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">E-mail</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control empty" name="email" id="email" value="<?php echo $usuario->email ?>" placeholder="E-mail"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <input type="submit" name="salvar-dados" id="salvar-dados" value="Salvar" class="btn pmd-btn-raised pmd-ripple-effect btn-primary">

            <div class="espaco20"></div>

            <h3 class="heading">Senha</h3>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Senha Atual</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control empty" name="senha_atual" id="senha_atual" placeholder="" value=""><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Senha</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control empty" name="nova_senha" id="nova_senha" placeholder="" value=""><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Confirmar Senha</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control empty" name="confirma_senha" id="confirma_senha" placeholder="" value=""><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <input type="button" name="salvar-senha" id="salvar-senha" value="Salvar" class="btn pmd-btn-raised pmd-ripple-effect btn-primary">
            <div class="espaco20"></div>

        </form>

    </div>

    <div class="oculto" id="ms-senha-atual-dialog" data-target="#senha-atual-dialog" data-toggle="modal"></div>
    <div class="oculto" id="ms-senhas-dialog" data-target="#senhas-dialog" data-toggle="modal"></div>
    <div class="oculto" id="ms-senha-branco-dialog" data-target="#senha-branco-dialog" data-toggle="modal"></div>
    <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

    <div class="clear"></div>
</section>