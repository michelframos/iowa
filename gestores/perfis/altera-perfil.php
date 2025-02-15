<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Perfis::find($id);
$permissoes = Permissoes_Perfil::all(array('conditions' => array('id_perfil = ?', $id), 'order' => 'ordem asc'));

?>

<script src="js/perfis.js"></script>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já exite uma Categoria de Usuário com este nome.</p>
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
    <i class="material-icons texto-laranja pmd-md">portrait</i>
    <h1>Cadastro / Alteração de Categoria de Usuário</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
    <div class="espaco20"></div>

    <form action="" name="formDados" id="formDados" method="post" style="max-width: 600px;">

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Categoria</label>
            <input type="text" name="perfil" id="perfil" value="<?php echo $registro->perfil ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
        <div class="espaco20"></div>

        <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

    </form>
    <div class="espaco20"></div>

</section>
<div class="espaco20"></div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">security</i>
        <h1>Permissões</h1>
    </div>

    <div class="pmd-card">
        <div class="table-responsive">
            <table class="table pmd-table table-hover">
                <thead>
                <tr>
                    <th width="450">Permissão</th>
                    <th width="100">Permitido</th>
                    <th width="100">Incluir</th>
                    <th width="100">Alterar</th>
                    <th width="100">Excluir</th>
                    <th width="100">Consultar</th>
                    <th width="100">Ativar / Inativa</th>
                    <th width="100">Imprimir</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if(!empty($permissoes)):
                    foreach($permissoes as $permissao):
                        $opcoes = explode(',', $permissao->opcoes);
                ?>
                <tr>
                    <td><?php echo $permissao->tela ?></td>
                    <td class="texto-center">
                        <?php if(in_array('p', $opcoes)): ?>
                            <div class="pmd-switch">
                                <label>
                                    <input class="altera-permissao" registro="<?php echo $permissao->id ?>" permissao="p" name="permissao<?php echo $permissao->id ?>_p" <?php echo $permissao->p == 's' ? 'checked' : ''; ?> type="checkbox">
                                    <span class="pmd-switch-label"></span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="texto-center">
                        <?php if(in_array('i', $opcoes)): ?>
                            <div class="pmd-switch">
                                <label>
                                    <input class="altera-permissao" registro="<?php echo $permissao->id ?>" permissao="i" name="permissao<?php echo $permissao->id ?>_i" <?php echo $permissao->i == 's' ? 'checked' : ''; ?> type="checkbox">
                                    <span class="pmd-switch-label"></span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="texto-center">
                        <?php if(in_array('a', $opcoes)): ?>
                            <div class="pmd-switch">
                                <label>
                                    <input class="altera-permissao" registro="<?php echo $permissao->id ?>" permissao="a" name="permissao<?php echo $permissao->id ?>_a" <?php echo $permissao->a == 's' ? 'checked' : ''; ?> type="checkbox">
                                    <span class="pmd-switch-label"></span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="texto-center">
                        <?php if(in_array('e', $opcoes)): ?>
                            <div class="pmd-switch">
                                <label>
                                    <input class="altera-permissao" registro="<?php echo $permissao->id ?>" permissao="e" name="permissao<?php echo $permissao->id ?>_e" <?php echo $permissao->e == 's' ? 'checked' : ''; ?> type="checkbox">
                                    <span class="pmd-switch-label"></span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="texto-center">
                        <?php if(in_array('c', $opcoes)): ?>
                            <div class="pmd-switch">
                                <label>
                                    <input class="altera-permissao" registro="<?php echo $permissao->id ?>" permissao="c" name="permissao<?php echo $permissao->id ?>_c" <?php echo $permissao->c == 's' ? 'checked' : ''; ?> type="checkbox">
                                    <span class="pmd-switch-label"></span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="texto-center">
                        <?php if(in_array('ai', $opcoes)): ?>
                            <div class="pmd-switch">
                                <label>
                                    <input class="altera-permissao" registro="<?php echo $permissao->id ?>" permissao="ai" name="permissao<?php echo $permissao->id ?>_ai" <?php echo $permissao->ai == 's' ? 'checked' : ''; ?> type="checkbox">
                                    <span class="pmd-switch-label"></span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="texto-center">
                        <?php if(in_array('imp', $opcoes)): ?>
                            <div class="pmd-switch">
                                <label>
                                    <input class="altera-permissao" registro="<?php echo $permissao->id ?>" permissao="imp" name="permissao<?php echo $permissao->id ?>_imp" <?php echo $permissao->imp == 's' ? 'checked' : ''; ?> type="checkbox">
                                    <span class="pmd-switch-label"></span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                    endforeach;
                endif;
                ?>

                </tbody>
            </table>
        </div>
    </div>

</section>
