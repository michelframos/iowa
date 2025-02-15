<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$id_unidade = filter_input(INPUT_POST, 'id_unidade', FILTER_SANITIZE_NUMBER_INT);
$id_colega = filter_input(INPUT_POST, 'id_colega', FILTER_SANITIZE_NUMBER_INT);
$id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_SANITIZE_NUMBER_INT);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

$usuario = Usuarios::find(idUsuario());

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Turmas::find($id);
?>

<input type="hidden" id="pesquisa" value="<?php echo $pesquisa ?>"/>
<input type="hidden" id="id_unidade" value="<?php echo $id_unidade ?>"/>
<input type="hidden" id="id_colega" value="<?php echo $id_colega ?>"/>
<input type="hidden" id="id_produto" value="<?php echo $id_produto ?>"/>
<input type="hidden" id="status" value="<?php echo $status ?>"/>


<div tabindex="-1" class="modal fade" id="adiciona-aula-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Adicionar Aula</h2>
            </div>
            <div class="modal-body">

                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                    <label for="regular1" class="control-label">Data</label>
                    <input type="text" name="data" id="data" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                </div>
                <div class="clear"></div>

                <!--
                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                    <label for="regular1" class="control-label">Horário de Início</label>
                    <input type="text" name="hora_inicio" id="hora_inicio" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                </div>

                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                    <label for="regular1" class="control-label">Horário de Término</label>
                    <input type="text" name="hora_termino" id="hora_termino" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                </div>
                -->

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" registro="<?php echo $registro->id ?>" id="adicionar-aula" type="button">Adicionar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="adiciona-pacote-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Adicionar Pacote</h2>
            </div>
            <div class="modal-body">

                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                    <label for="regular1" class="control-label">Número de Aulas</label>
                    <input type="number" min="1" name="numero-aulas" id="numero-aulas" value="1" class="form-control" required><span class="pmd-textfield-focused"></span>
                </div>
                <div class="clear"></div>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" registro="<?php echo $registro->id ?>" id="adicionar-pacote" type="button">Adicionar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/turmas.js"></script>

<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">book</i>
    <h1>Dário de Classe: <?php echo $registro->nome ?></h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar">Voltar</a>

    <?php if($registro->adicionar_aulas != 's' || $usuario->id_perfil == 2): ?>
    <button name="nova-aula" class="btn btn-primary pmd-btn-raised" data-target="#adiciona-aula-dialog" data-toggle="modal">Adicionar Aula</button>
    <?php endif; ?>


    <?php if((Permissoes::find_by_id_usuario_and_tela_and_p(idUsuario(), 'Mostrar Botão Adicionar Pacote', 's'))): ?>
    <button name="novo-pacote" class="btn btn-primary pmd-btn-raised" data-target="#adiciona-pacote-dialog" data-toggle="modal">Adicionar Pacote</button>
    <?php endif; ?>
    <div class="espaco20"></div>
    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Inicio Abas -->

    <form action="" name="formIntegrantes" id="formIntegrantes" method="post">

        <div class="pmd-card">
            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th class="150">Data da Aula</th>
                        <th>Programação</th>
                        <th>Conteúdo Padrão</th>
                        <th>Conteúdo Dado</th>
                        <th>Professor</th>
                        <th>Diário</th>
                        <th width="150">Aula Dada</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ?', $registro->id), 'order' => 'data desc'));
                    if(!empty($aulas)):
                        foreach($aulas as $aula):

                            $produto = Nomes_Produtos::find($aula->id_nome_produto);

                            if(!empty($aula->id_colega)):
                                $professor = Colegas::find($aula->id_colega);
                            else:
                                $professor = '';
                            endif;

                            if(!empty($aula->id_situacao_aula) && $aula->id_situacao_aula != 0):
                                $situacao = Situacao_Aulas::find($aula->id_situacao_aula);
                            else:
                                $situacao = '';
                            endif;

                            echo '<tr>';
                            echo !empty($aula->data) ? '<td data-title="Data da Aula" width="150">'.$aula->data->format("d/m/Y").'</td>' : '<td></td>';
                            echo '<td data-title="Aluno">'.$produto->nome_material.'</td>';
                            echo '<td data-title="Aluno">'.$aula->conteudo_padrao.'</td>';
                            echo '<td data-title="Aluno">'.substr($aula->conteudo_dado, 0, 200).'</td>';
                            echo '<td data-title="Aluno">'.$professor->nome.'</td>';

                            if((Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Registrar Aula no Diario de Classe', 's')) && (Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Registrar Aulas Somente nas Classes em que é Instrutor', 'n'))):
                                echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-dados-aula" turma="'.$registro->id.'" registro="'.$aula->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Dados desta aula"><i class="material-icons pmd-sm">class</i> </a></td>';
                            elseif((Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Registrar Aula no Diario de Classe', 's')) && ((Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Registrar Aulas Somente nas Classes em que é Instrutor', 's')) && ($usuario->id_colega == $registro->id_colega))):
                                echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-dados-aula" turma="'.$registro->id.'" registro="'.$aula->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Dados desta aula"><i class="material-icons pmd-sm">class</i> </a></td>';
                            else:
                                echo '<td></td>';
                            endif;

                            echo empty($situacao) ? '<td width="150" class="texto-centro">Não</td>' : '<td width="150" class="texto-centro">'.$situacao->descricao.'</td>';
                            echo '</tr>';
                        endforeach;
                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>

    </form>

</section>

<script type="text/javascript">
    $("#data").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>
