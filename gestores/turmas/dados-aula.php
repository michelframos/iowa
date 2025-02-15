<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$id_unidade = filter_input(INPUT_POST, 'id_unidade', FILTER_SANITIZE_NUMBER_INT);
$id_colega = filter_input(INPUT_POST, 'id_colega', FILTER_SANITIZE_NUMBER_INT);
$id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_SANITIZE_NUMBER_INT);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

$id_turma = filter_input(INPUT_POST, 'turma', FILTER_SANITIZE_STRING);
$turma = Turmas::find($id_turma);

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Aulas_Turmas::find($id);
$produto = Nomes_Produtos::find($registro->id_nome_produto);
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

<script src="js/aula.js"></script>

<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">book</i>
    <h1>Turma: <?php echo $turma->nome ?> - </h1>
    <h1>Dados da Aula do Dia <?php echo $registro->data->format('d/m/Y'); ?></h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar">Voltar</a>
    <div class="espaco20"></div>
    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Inicio Abas -->

    <div class="pmd-card pmd-z-depth">
        <div class="pmd-tabs pmd-tabs-bg">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#dados-aula" aria-controls="home" role="tab" data-toggle="tab">Dados da Aula</a></li>
                <li role="presentation"><a href="#alunos" aria-controls="about" role="tab" data-toggle="tab">Alunos</a></li>
            </ul>
        </div>

        <form action="" name="formDadosAula" id="formDadosAula" method="post">

        <div class="pmd-card-body">
            <div class="tab-content">

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <div role="tabpanel" class="tab-pane active" id="dados-aula">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
                        <label for="regular1" class="control-label">Data</label>
                        <input type="text" name="data" id="data" value="<?php echo !empty($registro->data) ? $registro->data->format('d/m/Y') : ''; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Situação</label>
                        <select name="id_situacao_aula" id="id_situacao_aula" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option></option>
                            <?php
                            $situacoes = Situacao_Aulas::all(array('order' => 'situacao asc'));
                            if(!empty($situacoes)):
                                foreach($situacoes as $situacao):
                                    echo $registro->id_situacao_aula == $situacao->id ? '<option selected value="'.$situacao->id.'">'.$situacao->situacao.' - '.$situacao->descricao.'</option>' : '<option value="'.$situacao->id.'">'.$situacao->situacao.' - '.$situacao->descricao.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Professor</label>
                        <select name="id_colega" id="id_colega" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option></option>
                            <?php
                            if(empty($registro->id_colega)):
                                $id_colega = $turma->id_colega;
                            else:
                                $id_colega = $registro->id_colega;
                            endif;
                            $professores = Colegas::all(array('conditions' => array('(status = ? or id = ?) and id_funcao = ?', 'a', $registro->id_colega, 3), 'order' => 'nome asc'));
                            if(!empty($professores)):
                                foreach($professores as $professor):
                                    echo $id_colega == $professor->id ? '<option selected value="'.$professor->id.'">'.$professor->nome.'</option>' : '<option value="'.$professor->id.'">'.$professor->nome.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                        <label for="regular1" class="control-label">Programação</label>
                        <input type="text" name="id_nome_produto" id="id_nome_produto" value="<?php echo $produto->nome_material; ?>" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                    </div>

                    <!--
                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Tarefa</label>
                        <select name="tarefa" id="tarefa" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value=""></option>
                            <option <?php echo $registro->tarefa == 's' ? 'selected' : '' ?> value="s">SIM</option>
                            <option <?php echo $registro->tarefa == 'n' ? 'selected' : '' ?> value="n">NÃO</option>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>
                    -->
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Conteúdo Padrão</label>
                        <textarea name="conteudo_padrao" id="conteudo_padrao" class="form-control"><?php echo $registro->conteudo_padrao; ?></textarea>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="regular1" class="control-label">Conteúdo Dado</label>
                        <textarea name="conteudo_dado" id="conteudo_dado" class="form-control" required><?php echo $registro->conteudo_dado; ?></textarea>
                    </div>
                    <div class="clear"></div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                        <label for="regular1" class="control-label">Horário de Início</label>
                        <input type="text" name="hora_inicio" id="hora_inicio" value="<?php echo $registro->hora_inicio; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                        <label for="regular1" class="control-label">Horário de Término</label>
                        <input type="text" name="hora_termino" id="hora_termino" value="<?php echo $registro->hora_termino; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                    </div>

                    <?php
                    if(!empty($registro->hora_inicio) && !empty($registro->hora_termino)):
                        //$duracao = date('H:i:s',(strtotime($registro->hora_inicio) - strtotime($registro->hora_termino)));

                        function intervalo( $entrada, $saida ) {
                            $entrada = explode( ':', $entrada );
                            $saida   = explode( ':', $saida );
                            $minutos = ( $saida[0] - $entrada[0] ) * 60 + $saida[1] - $entrada[1];
                            if( $minutos < 0 ) $minutos += 24 * 60;
                            return sprintf( '%d:%d', $minutos / 60, $minutos % 60 );
                        }

                        if(!empty($registro->hora_inicio) && !empty($registro->hora_termino)):
                            $duracao = intervalo($registro->hora_inicio, $registro->hora_termino);
                        endif;

                    endif;
                    ?>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                        <label for="regular1" class="control-label">Duração</label>
                        <input type="text" name="duracao" id="duracao" value="<?php echo $duracao; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                    </div>
                    <div class="espaco20"></div>


                </div>
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <div role="tabpanel" class="tab-pane" id="alunos">

                    <div class="pmd-card">
                        <div class="table-responsive">
                            <table class="table pmd-table table-hover">
                                <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th width="50">Presença</th>
                                    <!--<th width="50">Tarefa</th>-->
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $alunos_aulas = Aulas_Alunos::all(array('conditions' => array('id_aula = ? and id_turma = ?', $registro->id, $turma->id)));
                                if(!empty($alunos_aulas)):
                                    foreach($alunos_aulas as $aluno_aula):

                                        try{
                                            $aluno = Alunos::find($aluno_aula->id_aluno);
                                        }catch (\ActiveRecord\RecordNotFound $e){
                                            $aluno = '';
                                        }

                                        $aluno_turma = Alunos_Turmas::find_by_id_and_id_turma($aluno_aula->id_aluno_turma, $turma->id);

                                        try{
                                            $matricula = Matriculas::find($aluno_turma->id_matricula);
                                        } catch(\ActiveRecord\RecordNotFound $e){
                                            $matricula = '';
                                        }
                                        //if(!empty($matricula) && $matricula->status != 't'):
                                        if(!empty($matricula) && $matricula->status == 'a'):

                                            if($aluno->status == 'a'):
                                            echo '<tr>';
                                            echo '<td data-title="Aluno">'.$aluno->nome.'</td>';

                                            echo '<td data-title="Presença">';
                                            echo '<div class="pmd-switch">';
                                            echo '<label>';
                                            echo $aluno_aula->presente == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                                            echo '<span class="pmd-switch-label presente" turma="'.$turma->id.'" registro="'.$aluno_aula->id.'"></span>';
                                            echo ' </label>';
                                            echo '</div>';
                                            echo '</td>';

                                                /*
                                            echo '<td data-title="Tarefa">';
                                            echo '<div class="pmd-switch">';
                                            echo '<label>';
                                            echo $aluno_aula->tarefa == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                                            echo '<span class="pmd-switch-label tarefa" turma="'.$turma->id.'" registro="'.$aluno_aula->id.'"></span>';
                                            echo ' </label>';
                                            echo '</div>';
                                            echo '</td>';
                                                */

                                            echo '<tr/>';
                                            endif;

                                        endif;
                                    endforeach;
                                endif;
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

            </div>

            <div class="espaco20"></div>

            <button type="submit" name="salvar" id="salvar" value="Salvar" turma="<?php echo $turma->id ?>" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

        </div>

        <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

        </form>

    </div>

    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Fim Abas -->

</section>

<script type="text/javascript">
    $("#data").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>
