<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">


    <?php if(empty($_POST['valor_pesquisa'])): ?>

    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data Cadastrto</th>
                <th>Turma</th>
                <th>Unidade</th>
                <th>Idioma</th>
                <th width="100">Status</th>
                <th colspan="3"></th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(!empty($_POST['id_colega'])):
                $id_colega = filter_input(INPUT_POST, 'id_colega', FILTER_SANITIZE_NUMBER_INT);
            else:
                $id_colega = '%';
            endif;


            if(!empty($_POST['status_turma'])):
                $status_turma = filter_input(INPUT_POST, 'status_turma', FILTER_SANITIZE_STRING);
            else:
                $status_turma = 'a';
            endif;


            if(!empty($_POST['valor_pesquisa'])):
                $nome_aluno = filter_input(INPUT_POST, 'valor_pesquisa', FILTER_SANITIZE_STRING);
            endif;

            $registros = Turmas::all(array('conditions' => array('id_colega like ? and status like ?', $id_colega, $status_turma),'order' => 'nome asc'));
            if(!empty($registros) && isset($_POST['id_colega'])):
                foreach($registros as $registro):

                    if(!empty($registro->id_idioma)):
                        $idioma = Idiomas::find($registro->id_idioma);
                    endif;

                    if(!empty($registro->id_unidade)):
                        $unidade = Unidades::find($registro->id_unidade);
                    endif;

                    echo '<tr>';
                    echo '<td data-title="Data Cadastro">'.$registro->data_criacao->format("d/m/Y").'</td>';
                    echo '<td data-title="Nome da Prova">'.$registro->nome.'</td>';
                    echo !empty($unidade->nome_fantasia) ? '<td data-title="Idioma">'.$unidade->nome_fantasia.'</td>' : '<td></td>';
                    echo !empty($idioma->idioma) ? '<td data-title="Idioma">'.$idioma->idioma.'</td>' : '<td></td>';
                    echo '<td data-title="Status">';
                    echo '<div class="pmd-switch">';
                    echo '<label>';
                    echo $registro->status == 'a' ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                    echo '<span class="pmd-switch-label ativa-inativa" registro="'.$registro->id.'"></span>';
                    echo ' </label>';
                    echo '</div>';
                    echo '</td>';

                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera-turma" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>';
                    echo '<tr>';

                endforeach;


            else:

                echo '<div class="titulo fw-bold size-1-5">Selecione os filtros desejados e clique em Pesquisar</div>';

            endif;

            ?>

            </tbody>
        </table>

    </div>

    <?php elseif(!empty($_POST['valor_pesquisa'])): ?>

        <div class="table-responsive">
            <table class="table pmd-table table-hover">
                <thead>
                <tr>
                    <th width="150">Data Cadastrto</th>
                    <th>Aluno</th>
                    <th>Unidade</th>
                    <th>Situação</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php
                if(isset($_POST['valor_pesquisa'])):
                    if(!empty($_POST['valor_pesquisa'])):
                        $valor_pesquisa = filter_input(INPUT_POST, 'valor_pesquisa', FILTER_SANITIZE_STRING);
                    else:
                        $valor_pesquisa = '';
                    endif;


                    $registros = Alunos::all(array('conditions' => array('nome like ?', '%'.$valor_pesquisa.'%'), 'order' => 'nome asc'));
                    if(!empty($registros)):
                        foreach($registros as $registro):
                            if(!empty($registro->id_unidade)):
                                $unidade = Unidades::find($registro->id_unidade);
                            endif;

                            try{
                                $situacao = Situacao_Aluno::find($registro->id_situacao);
                            } catch(\ActiveRecord\RecordNotFound $e){
                                $situacao = '';
                            }

                            echo '<tr>';
                            echo '<td data-title="Data Cadastro">'.$registro->data_criacao->format("d/m/Y").'</td>';
                            echo '<td data-title="Aluno">'.$registro->nome.'</td>';
                            echo '<td data-title="Unidade">'.$unidade->nome_fantasia.'</td>';
                            echo '<td data-title="Situação">'.$situacao->situacao.'</td>';

                            echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera-aluno" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>';
                            echo '</tr>';
                        endforeach;
                    endif;

                else:

                    echo '<div class="titulo fw-bold size-1-5">Selecione os filtros desejados e clique em Pesquisar</div>';

                endif;
                ?>

                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>
