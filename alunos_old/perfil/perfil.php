<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">account_circle</i>
    <h1> Meu Perfil </h1>
</div>

<section class="pmd-card pmd-z-depth padding-10" style="max-width: 1000px;">

    <div class="padding-10">
        <blockquote>
            Mantenh seus dados sempre atualizados
        </blockquote>
    </div>

    <div class="coluna-1-3">
        <div class="espaco20"></div>
        <div data-provides="fileinput" class="fileinput fileinput-new col-lg-3">
            <div data-trigger="fileinput" class="fileinput-preview thumbnail img-circle img-responsive">
                <img src="<?php echo HOME ?>/assets/imagens/alunos/aluno-padrao.png">
            </div>
            <div class="action-button">
                <span class="btn btn-default btn-raised btn-file ripple-effect">
                    <span class="fileinput-new"><i class="material-icons md-light pmd-xs">add</i></span>
                    <span class="fileinput-exists"><i class="material-icons md-light pmd-xs">mode_edit</i></span>
                    <input type="file" name="...">
                </span>
                <a data-dismiss="fileinput" class="btn btn-default btn-raised btn-file ripple-effect fileinput-exists" href="javascript:void(0);"><i class="material-icons md-light pmd-xs">close</i></a>
            </div>
        </div>
    </div>

    <div class="coluna-2-3"">

        <form action="" name="formPerfil" id="formPerfil">

            <h3 class="heading">Dados Pessoais</h3>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Nome de Usuário</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control empty" name="nome_usuario" id="nome_usuario" placeholder="" value="Nome de Usuário"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Nome</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control empty" name="nome" id="nome" placeholder="Nome"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Telefone</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control empty" name="telefone" id="telefone" placeholder="Telefone"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">E-mail</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control empty" name="email" id="email" placeholder="E-mail"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <input type="submit" name="salvar" id="salvar" value="Salvar" class="btn pmd-btn-raised pmd-ripple-effect btn-primary">

            <div class="espaco20"></div>

            <h3 class="heading">Senha</h3>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Senha</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control empty" name="senha" id="senha" placeholder="Telefone" value="123"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield">
                <label class="col-sm-3 control-label" for="">Confirmar Senha</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control empty" name="confirma_senha" id="confirma_senha" placeholder="Telefone" value="123"><span class="pmd-textfield-focused"></span>
                </div>
            </div>
            <div class="espaco20"></div>

            <input type="submit" name="salvar" id="salvar" value="Salvar" class="btn pmd-btn-raised pmd-ripple-effect btn-primary">
            <div class="espaco20"></div>

        </form>

    </div>

    <div class="clear"></div>
</section>