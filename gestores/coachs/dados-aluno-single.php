<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Alunos::find($id);
$turmas = Turmas::find_by_sql("select alunos.id, alunos.nome, alunos_turmas.id_turma, alunos_turmas.id_aluno, turmas.nome as nome_turma, turmas.`status`, turmas.id_idioma from alunos inner join alunos_turmas on alunos.id = alunos_turmas.id_aluno inner join turmas on alunos_turmas.id_turma = turmas.id where turmas.status = 'a' and alunos.id = {$registro->id}");
?>

<div tabindex="-1" class="modal fade" id="nova-ata-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Nova Ata</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formNovaAta" id="formNovaAta" method="post">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna2">
                        <label>Turma</label>
                        <select name="id_turma" id="id_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option></option>
                            <?php
                            if(!empty($turmas)):
                                foreach($turmas as $turma):
                                    echo '<option value="'.$turma->id_turma.'">'.$turma->nome_turma.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield">
                        <label class="control-label">Texto da Ata</label>
                        <textarea required class="form-control" name="nova-ata" id="nova-ata" style="height: 100px;" required></textarea>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-salvar-ata" aluno="<?php echo $registro->id ?>">Salvar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="alterar-ata-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterar Ata</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formAlteraAta" id="formAlteraAta" method="post">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna2">
                        <label>Turma</label>
                        <select name="id_altera_turma" id="id_altera_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option></option>
                            <?php
                            if(!empty($turmas)):
                                foreach($turmas as $turma):
                                    echo '<option value="'.$turma->id_turma.'">'.$turma->nome_turma.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield">
                        <label class="control-label">Texto da Ata</label>
                        <textarea required class="form-control" name="alterar-ata" id="alterar-ata" style="height: 100px;"></textarea>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Coachs - Altera Ata do Aluno', 's')): ?>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-alterar-ata" ata="" aluno="<?php echo $registro->id ?>">Salvar</button>
                <?php endif; ?>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/coachs.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">group_add</i>
    <h1> Dados do Aluno</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar-coachs">Voltar</a>
    <div class="espaco20"></div>

    <div class="pmd-card pmd-z-depth padding-10">

        <?php
        if(!empty($turmas)):
            foreach($turmas as $turma):

                echo '<hr>';
                echo '<h2>'.$turma->nome_turma.'</h2><br>';
                $aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and id_situacao_aula <> ?', $turma->id_turma, 0)));
                $numero_aulas = count($aulas);
                ?>

                <div class="table-responsive">
                    <table class="table pmd-table table-hover">
                        <thead>
                        <tr>
                            <th width="70%">Aluno</th>
                            <!--<th class="texto-centro" width="10%">Numero de Presenças</th>-->
                            <th class="texto-centro" width="10%">Numero de Faltas</th>
                            <th class="texto-centro" width="10%">Frequencia Estágio</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php

                            $dados_aluno = Alunos::find($registro->id);
                            //$frequencia = Aulas_Alunos::find_all_by_id_turma_and_id_aluno_and_presente($turma->id, $aluno->id_aluno, 's');
                            //$faltas = Aulas_Alunos::find_all_by_id_turma_and_id_aluno_and_presente($turma->id, $aluno->id_aluno, 'n');

                            $frequencia = 0;
                            if(!empty($aulas)):
                                foreach($aulas as $aula):
                                    if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $registro->id, 's')):
                                        $frequencia++;
                                    endif;
                                endforeach;
                            endif;

                            $numero_faltas = 0;
                            if(!empty($aulas)):
                                foreach($aulas as $aula):
                                    if(Aulas_Alunos::find_by_id_aula_and_id_aluno_and_presente($aula->id, $registro->id, 'n')):
                                        $numero_faltas++;
                                    endif;
                                endforeach;
                            endif;

                            if($numero_aulas > 0):
                                $aproveitamento = ($frequencia/$numero_aulas)*100;
                            endif;

                            echo '<tr>';
                            echo '<td class="texto-centro" width="5%">'.$dados_aluno->nome.'</td>';
                            //echo '<td class="texto-centro" width="20%">'.count($frequencia).'</td>';
                            echo '<td class="texto-centro" width="5%">'.$numero_faltas.'</td>';
                            echo '<td class="texto-centro" width="5%">'.number_format($aproveitamento, 2, ',', '').'%</td>';
                            echo '</tr>';

                        ?>
                        </tbody>
                    </table>
                </div>


                <!-- Observações -->
                <?php
                $observacoes = Observacoes_Professores::all(array('conditions' => array('id_aluno = ? and id_turma = ?', $registro->id, $turma->id_turma), 'order' => 'data asc'));

                if(!empty($observacoes)):
                    echo '<h2>OBSERVAÇÕES</h2>';
                    echo '<div class="clear"></div>';

                    echo '<div class="table-responsive">';
                    echo '<table class="table pmd-table table-hover">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th width="100">Data</th>';
                    echo '<th>Observação</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    foreach($observacoes as $observacao):
                        echo '<tr>';
                            echo '<td width="100">'.$observacao->data->format('d/m/Y').'</td>';
                            echo '<td>'.$observacao->observacao.'</td>';
                        echo '</tr>';
                    endforeach;

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                endif;
                ?>
                <!-- Fim Observações -->


                <?php
                $notas = Notas_Provas::find_all_by_id_turma_and_id_aluno($turma->id_turma, $registro->id);
                if(!empty($notas)):

                    echo '<h2>NOTAS</h2>';

                    echo '<div class="table-responsive">';
                    echo '<table class="table pmd-table table-hover">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th class="150">Prova</th>';
                    echo '<th>Nota</th>';
                    echo '<th width="150">Data</th>';
                    echo '</thead>';
                    echo '<tbody>';

                    foreach($notas as $nota):

                        $prova = Provas_Turmas::find($nota->id_prova_turma);
                        $nome_prova = Nome_Provas::find($prova->id_nome_prova);

                        echo '<tr>';

                        if($prova->prova == '_oral'):
                            echo '<td class="texto-centro">Prova Oral</td>';
                        else:
                            echo '<td class="texto-centro">Prova '.$nome_prova->nome.'</td>';
                        endif;

                        echo '<td class="texto-centro">'.$nota->nota.'</td>';
                        echo !empty($prova->data) ? '<td class="texto-centro">'.$prova->data->format('d/m/Y').'</td>' : '<td></td>';

                        echo '</tr>';

                    endforeach;

                    echo '</tbody>';
                    echo '</thead>';
                    echo '</table>';
                    echo '</div>';

                endif;

            endforeach;
        endif;
        ?>

    </div>

    <div class="pmd-card pmd-z-depth padding-10">

        <h1>ATAS</h1>
        <div class="espaco20"></div>
        <?php if(Permissoes::find_by_id_usuario_and_tela_and_i(idUsuario(), 'Coachs - Criar Ata Para Aluno', 's')): ?>
        <button type="button" name="bt-nova-ata" id="bt-nova-ata" data-target="#nova-ata-dialog" data-toggle="modal" value="Nova Ata" class="btn btn-danger pmd-btn-raised">Nova Ata</button>
        <?php endif; ?>
        <div class="espaco20"></div>

        <?php if(Permissoes::find_by_id_usuario_and_tela_and_c(idUsuario(), 'Coachs - Consultar Atas do Aluno', 's')): ?>
        <div id="listagem-atas-single">
            <?php include_once('listagem-atas-single.php'); ?>
        </div>
        <?php endif; ?>

    </div>

</section>