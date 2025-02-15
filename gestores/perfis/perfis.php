<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    //verificaPermissao(idUsuario(), 'Categorias de Usuários', 'c', 'index');

?>
<script src="js/perfis.js"></script>

<div tabindex="-1" class="modal fade" id="delete-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão desta Categoria de Usuário? Esta ação é irreversível! </p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Excluir</button>
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
    <h1>Categorias de Usuários</h1>
</div>

<div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
    Este Registro não pode ser excluído por já ter sido utilizado no sistema.
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-novo"> Nova Categoria de Usuário</a>
    <div class="espaco20"></div>

    <div class="pmd-card">
        <div class="table-responsive">
            <table class="table pmd-table table-hover">
                <thead>
                <tr>
                    <th width="150">Data Cadastrto</th>
                    <th>Categoria</th>
                    <th width="100">Lista Como Gerente</th>
                    <th width="100">Status</th>
                    <th colspan="2"></th>
                </tr>
                </thead>
                <tbody>

                <?php
                $registros = Perfis::all(array('order' => 'perfil asc'));
                if(!empty($registros)):
                    foreach($registros as $registro):
                        echo '<tr>';
                        echo '<td data-title="Data Cadastro">'.$registro->data_criacao->format("d/m/Y").'</td>';
                        echo '<td data-title="Perfil">'.$registro->perfil.'</td>';

                        echo '<td data-title="Status">';
                        echo '<div class="pmd-switch">';
                        echo '<label>';
                        echo $registro->listar_como_gerente == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                        echo '<span class="pmd-switch-label lista-gerente" registro="'.$registro->id.'"></span>';
                        echo ' </label>';
                        echo '</div>';
                        echo '</td>';

                        echo '<td data-title="Status">';
                        echo '<div class="pmd-switch">';
                        echo '<label>';
                        echo $registro->status == 'a' ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                        echo '<span class="pmd-switch-label ativa-inativa" registro="'.$registro->id.'"></span>';
                        echo ' </label>';
                        echo '</div>';
                        echo '</td>';

                        echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>';
                        echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-excluir" registro="'.$registro->id.'" data-target="#delete-dialog" data-toggle="modal" data-trigger="hover" data-placement="top" title="Excluir"><i class="material-icons pmd-sm">delete_forever</i> </a></td>';
                    endforeach;
                endif;
                ?>

                </tbody>
            </table>
        </div>
    </div>

    <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

</section>

<script>
    $('#myModal').on('hidden.bs.modal', function (e) {
        // do something...
    })
</script>