<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$id_turma = filter_input(INPUT_POST, 'turma', FILTER_VALIDATE_INT);
$turma = Turmas::find($id_turma);
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$prova = Provas_Turmas::find($id);
$nome_prova = Nome_Provas::find($prova->id_nome_prova);

$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$id_unidade = filter_input(INPUT_POST, 'id_unidade', FILTER_SANITIZE_NUMBER_INT);
$id_colega = filter_input(INPUT_POST, 'id_colega', FILTER_SANITIZE_NUMBER_INT);
$id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_SANITIZE_NUMBER_INT);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
?>

<input type="hidden" id="pesquisa" value="<?php echo $pesquisa ?>"/>
<input type="hidden" id="id_unidade" value="<?php echo $id_unidade ?>"/>
<input type="hidden" id="id_colega" value="<?php echo $id_colega ?>"/>
<input type="hidden" id="id_produto" value="<?php echo $id_produto ?>"/>
<input type="hidden" id="status" value="<?php echo $status ?>"/>

<div tabindex="-1" class="modal fade" id="alterado-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Notas</h2>
            </div>
            <div class="modal-body">
                <p>Notas salvas com sucesso.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ATA DO ALUNO -->
<div tabindex="-1" class="modal fade" id="nova-ata-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Nova Ata</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formNovaAta" id="formNovaAta" method="post">

                    <input type="hidden" name="id_turma" id="id_turma" value="<?php echo $turma->id ?>"/>

                    <div class="form-group pmd-textfield">
                        <label class="control-label">Texto da Ata</label>
                        <textarea required class="form-control" name="nova-ata" id="nova-ata" style="height: 100px;" required></textarea>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-salvar-ata" aluno="">Salvar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL DE ATA DO ALUNO -->

<script src="js/provas.js"></script>

<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">book</i>
    <h1>Turma: <?php echo $turma->nome ?> - </h1>
    <h1>Notas da Prova: <?php echo $nome_prova->nome ?></h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" turma="<?php echo $turma->id ?>" id="voltar-lista-notas">Voltar</a>
    <div class="espaco20"></div>
    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Inicio Abas -->

    <form action="" name="formNotasProva" id="formNotasProva" method="post">

        <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Alterar Notas dos Alunos', 's')): ?>
        <button type="submit" name="salvar1" value="Salvar" prova="<?php echo $prova->id ?>" turma="<?php echo $turma->id ?>" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised salvar-notas">Salvar Notas</button>
        <?php endif; ?>
        <div class="espaco20"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
            <label for="regular1" class="control-label">Data da Prova</label>
            <input type="text" name="data" id="data" value="<?php echo !empty($prova->data) ? $prova->data->format('d/m/Y') : ''; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
        </div>
        <div class="espaco20"></div>

        <div class="pmd-card">
            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th>Aluno</th>
                        <?php if(Permissoes::find_by_id_usuario_and_tela_and_c(idUsuario(), 'Coachs - Consultar Atas do Aluno', 's')): ?>
                        <th class="text-center">Ata</th>
                        <?php endif; ?>
                        <th class="150">Nota</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    $notas = Notas_Provas::all(array('conditions' => array('id_prova_turma = ?', $prova->id)));
                    if(!empty($notas)):
                        foreach($notas as $nota):

                            try{
                                $aluno = Alunos::find($nota->id_aluno);

                                $aluno_turma = Matriculas::find_by_sql(" select alunos_turmas.* from alunos_turmas inner join matriculas on alunos_turmas.id_matricula = matriculas.id where alunos_turmas.id_turma = {$nota->id_turma} and alunos_turmas.id_aluno = {$nota->id_aluno} ");
                                $matricula = Matriculas::find($aluno_turma[0]->id_matricula);

                                //if(($matricula->status == 'a') || (($matricula->status == 't') && !empty($nota->nota))):
                                if(($matricula->status != 'i')):


                                    echo '<tr>';
                                    echo '<td data-title="Aluno">'.$aluno->nome.'</td>';

                                    if(Permissoes::find_by_id_usuario_and_tela_and_c(idUsuario(), 'Coachs - Consultar Atas do Aluno', 's')):
                                    echo '<td><button type="button" name="bt-nova-ata" id="bt-nova-ata" data-target="#nova-ata-dialog" data-toggle="modal" value="Nova Ata" aluno="'.$aluno->id.'" class="btn btn-danger pmd-btn-raised bt-nova-ata">Nova Ata</button></td>';
                                    endif;

                                    echo '<td class="150">';
                                    echo '<div class="form-group pmd-textfield pmd-textfield-floating-label">';
                                    echo '<input size="3" type="text" name="nota_'.$nota->id.'" id="nota_'.$nota->id.'" value="'.$nota->nota.'" class="form-control texto-centro"><span class="pmd-textfield-focused"></span>';
                                    echo '</div>';
                                    echo '</td>';
                                    echo '</tr>';

                                endif;

                            } catch (Exception $e){
                                $aluno = '';
                            }


                        endforeach;
                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>

        <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Alterar Notas dos Alunos', 's')): ?>
        <button type="submit" name="salvar2" value="Salvar" prova="<?php echo $prova->id ?>" turma="<?php echo $turma->id ?>" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised salvar-notas">Salvar Notas</button>
        <?php endif; ?>
        <div class="espaco20"></div>

        <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

    </form>

</section>

<script type="text/javascript">
    $("#data").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>
