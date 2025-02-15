<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data Cadastrto</th>
                <th>Turma</th>
                <th>Unidade</th>
                <th>Idioma</th>
                <th>Horário</th>
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

            if(!empty($_POST['id_unidade'])):
                $id_unidade = filter_input(INPUT_POST, 'id_unidade', FILTER_SANITIZE_NUMBER_INT);
            else:
                $id_unidade = '%';
            endif;

            if(!empty($_POST['id_produto'])):
                $id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_SANITIZE_NUMBER_INT);
            else:
                $id_produto = '%';
            endif;

            if(!empty($_POST['status'])):
                $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
            else:
                $status = 'a';
            endif;

            if(!empty($_POST['nome'])):
                $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
            endif;


            $registros = Turmas::all(array('conditions' => array('nome like ? and id_colega like ? and id_unidade like ? and id_produto like ? and status like ?', '%'.$nome.'%', $id_colega, $id_unidade, $id_produto, $status),'order' => 'nome asc'));

            if(!empty($registros) && isset($_POST['nome'])):
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

                    echo '<td>';

                        if($registro->segunda == 's'):
                            echo 'Segunda-Feira das ' . $registro->hora_inicio_segunda . ' às ' . $registro->hora_termino_segunda . '<br>';
                        endif;

                        if($registro->terca == 's'):
                            echo 'Terca-Feira das ' . $registro->hora_inicio_terca . ' às ' . $registro->hora_termino_terca . '<br>';
                        endif;

                        if($registro->quarta == 's'):
                            echo 'Quarta-Feira das ' . $registro->hora_inicio_quarta . ' às ' . $registro->hora_termino_quarta . '<br>';
                        endif;

                        if($registro->quinta == 's'):
                            echo 'Quinta-Feira das ' . $registro->hora_inicio_quinta . ' às ' . $registro->hora_termino_quinta . '<br>';
                        endif;

                        if($registro->sexta == 's'):
                            echo 'Sexta-Feira das ' . $registro->hora_inicio_sexta . ' às ' . $registro->hora_termino_sexta . '<br>';
                        endif;

                        if($registro->sabado == 's'):
                            echo 'Sábado das ' . $registro->hora_inicio_sabado . ' às ' . $registro->hora_termino_sabado . '<br>';
                        endif;

                        if($registro->domingo == 's'):
                            echo 'Sábado das ' . $registro->hora_inicio_domingo . ' às ' . $registro->hora_termino_domingo;
                        endif;

                    echo '</td>';

                    echo '<td data-title="Status">';
                    echo '<div class="pmd-switch">';
                    echo '<label>';
                    echo $registro->status == 'a' ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                    echo '<span class="pmd-switch-label ativa-inativa" registro="'.$registro->id.'"></span>';
                    echo ' </label>';
                    echo '</div>';

                    //echo '<td><button type="button" name="atualizar" id="atualizar" id_turma="'.$registro->id.'" class="btn btn-info pmd-btn-raised">Atualizar</button></td>';

                    echo '</td>';
                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-diario-classe" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Diário de Classe"><i class="material-icons pmd-sm">class</i> </a></td>';
                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-lista-provas" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Notas de Provas"><i class="material-icons pmd-sm">spellcheck</i> </a></td>';
                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera" registro="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>';
                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-excluir" registro="'.$registro->id.'" data-target="#delete-dialog" data-toggle="modal" data-trigger="hover" data-placement="top" title="Excluir"><i class="material-icons pmd-sm">delete_forever</i> </a></td>';
                    echo '</tr>';

                endforeach;

            else:

                echo '<div class="titulo fw-bold size-1-5">Selecione os filtros desejados e clique em Pesquisar</div>';

            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
