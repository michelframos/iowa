<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $registro = Usuarios::find($id);
?>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p id="modal-mensagem"></p>
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


<div tabindex="-1" class="modal fade" id="confirma-senha-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Confirmação de Senha</h2>
            </div>
            <div class="modal-body">
                <p>A senha e a confirmação não são iguais.</p>
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

<script src="js/usuarios.js"></script>

<!-- Start Content -->
    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">account_circle</i>
        <h1>Cadastro / Alteração de Usuário</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar">Voltar</a>
        <div class="espaco20"></div>

        <form action="" name="formDados" id="formDados" method="post">

            <!-- --------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Abas -->
            <div class="pmd-card pmd-z-depth">

                <div class="pmd-card-body">

                    <div style="max-width: 800px;">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                        <label>Categoria de Usuário</label>
                        <select name="id_perfil" id="id_perfil" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option></option>
                            <?php
                            $perfis = Perfis::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_perfil), 'order' => 'perfil asc'));
                            if(!empty($perfis)):
                                foreach($perfis as $perfil):
                                    echo $registro->id_perfil == $perfil->id ? '<option selected value="'.$perfil->id.'">'.$perfil->perfil.'</option>' : '<option value="'.$perfil->id.'">'.$perfil->perfil.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                        <label>Ligar ao Colega IOWA</label>
                        <select name="id_colega" id="id_colega" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <?php
                            $colegas = Colegas::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_colega), 'order' => 'nome asc'));
                            if(!empty($colegas)):
                                foreach($colegas as $colega):
                                    echo $registro->id_colega == $colega->id ? '<option selected value="'.$colega->id.'">'.$colega->nome.'</option>' : '<option value="'.$colega->id.'">'.$colega->nome.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Nome</label>
                        <input type="text" name="nome" id="nome" value="<?php echo $registro->nome; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Login</label>
                        <input type="text" name="login" id="login" value="<?php echo $registro->login; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo $registro->email; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Senha</label>
                        <input type="password" name="senha" id="senha" value="" class="form-control" <?php echo empty($registro->senha) ? 'required' : '' ?>><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Confirme a Senha</label>
                        <input type="password" name="confirma-senha" id="confirma-senha" value="" class="form-control" <?php echo empty($registro->senha) ? 'required' : '' ?>><span class="pmd-textfield-focused"></span>
                    </div>

                    </div>

                </div>

            </div>
            <div class="espaco20"></div>
            <!-- Final Abas -->
            <!-- --------------------------------------------------------------------------------------------------- -->

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-confirma-senha" data-target="#confirma-senha-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

        </form>

    </section>