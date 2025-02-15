<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$id_unidade = filter_input(INPUT_POST, 'id_unidade', FILTER_SANITIZE_NUMBER_INT);
$id_colega = filter_input(INPUT_POST, 'id_colega', FILTER_SANITIZE_NUMBER_INT);
$id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_SANITIZE_NUMBER_INT);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Turmas::find($id);
?>

<input type="hidden" id="pesquisa" value="<?php echo $pesquisa ?>"/>
<input type="hidden" id="id_unidade" value="<?php echo $id_unidade ?>"/>
<input type="hidden" id="id_colega" value="<?php echo $id_colega ?>"/>
<input type="hidden" id="id_produto" value="<?php echo $id_produto ?>"/>
<input type="hidden" id="status" value="<?php echo $status ?>"/>

<div tabindex="-1" class="modal fade" id="observacao-professor-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Observação do Professor</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formObservacoes" id="formObservacoes" method="post">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                        <label>Aluno</label>
                        <select name="id_aluno" id="id_aluno" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option value=""></option>
                            <?php
                            $alunos = Alunos_Turmas::find_by_sql("select alunos_turmas.id as id_aluno_turma, alunos.id, alunos.nome from alunos_turmas inner join alunos on alunos_turmas.id_aluno = alunos.id where alunos_turmas.id_turma = {$registro->id}");
                            if(!empty($alunos)):
                                foreach($alunos as $aluno):

                                    try{
                                        $matricula = Matriculas::find_by_id_turma_and_id_aluno($registro->id, $aluno->id);
                                    } catch (\ActiveRecord\RecordNotFound $e){
                                        $matricula = '';
                                    }

                                    if($matricula->status != 't'):
                                        echo '<option value="'.$aluno->id.'">'.$aluno->nome.'</option>';
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label class="control-label">Observação</label>
                        <textarea name="observacao" id="observacao" required class="form-control"></textarea>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" id_turma="<?php echo $registro->id ?>" id_usuario="<?php echo idUsuario(); ?>" type="button" id="salvar-observacao-professor">Salvar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button" id="cancelar-observacao-professor">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="erro-id-colega-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Relacionamento não encontrado!</h2>
            </div>
            <div class="modal-body">

                <p>O usuário logado atualmente não está vinculado a nenhum Colega IOWA, por favor varfique o cadastro do usuário logado e vincule-o a um Colega IOWA e tente novamente.</p>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="sucesso-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Observação Salva</h2>
            </div>
            <div class="modal-body">

                <p>Observação salva com sucesso.</p>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<script src="js/provas.js"></script>

<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">book</i>
    <h1>Provas da Turma: <?php echo $registro->nome ?></h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar">Voltar</a>
    <a href="javascript:void(0);" class="btn btn-primary pmd-btn-raised" data-target="#observacao-professor-dialog" data-toggle="modal">Observação do Professor</a>
    <div class="espaco20"></div>
    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Inicio Abas -->

    <form action="" name="formIntegrantes" id="formIntegrantes" method="post">

        <div class="pmd-card">
            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th class="150">Data da Prova</th>
                        <th>Sistema de Notas</th>
                        <th>Prova</th>
                        <th width="50">Ações</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    $provas = Provas_Turmas::all(array('conditions' => array('id_turma = ?', $registro->id), 'order' => 'id_nome_prova asc'));
                    if(!empty($provas)):
                        foreach($provas as $prova):

                            $sistema_notas = Sistema_Notas::find($prova->id_sistema_nota);
                            $nome_prova = Nome_Provas::find($prova->id_nome_prova);

                            echo '<tr>';
                            echo !empty($prova->data) ? '<td data-title="Data da Aula" width="150">'.$prova->data->format("d/m/Y").'</td>' : '<td></td>';
                            echo '<td data-title="Sistema de Notas">'.$sistema_notas->nome.'</td>';
                            echo '<td data-title="Prova">'.$nome_prova->nome.'</td>';
                            echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-notas-prova" turma="'.$registro->id.'" registro="'.$prova->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Lançar Notas desta Prova"><i class="material-icons pmd-sm">class</i> </a></td>';
                            echo '</tr>';
                        endforeach;
                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>

    </form>

    <div class="oculto" id="ms-sucesso-dialog" data-target="#sucesso-dialog" data-toggle="modal"></div>
    <div class="oculto" id="ms-erro-id-colega-dialog" data-target="#erro-id-colega-dialog" data-toggle="modal"></div>

</section>
