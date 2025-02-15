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
                <th>Colega</th>
                <th>Unidade</th>
                <th>Função</th>
                <th width="100">Status</th>
                <th colspan="2">Ações</th>
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

                if(!empty($_POST['funcao'])):
                    $funcao = filter_input(INPUT_POST, 'funcao', FILTER_VALIDATE_INT);
                else:
                    $funcao = '%';
                endif;

                if(!empty($_POST['unidade'])):
                    $unidade = filter_input(INPUT_POST, 'unidade', FILTER_VALIDATE_INT);
                else:
                    $unidade = '%';
                endif;

                if(!empty($_POST['status'])):
                    //$coach_id_choach = filter_input(INPUT_POST, 'coach_id_choach', FILTER_VALIDATE_INT);
                    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
                else:
                    $status = '%';
                endif;


                $registros = Colegas::all(array('conditions' => array('(nome like ? or apelido like ? or rg like ? or cpf like ?) and id_funcao like ? and id_unidade like ? and status like ?', '%'.$valor_pesquisa.'%', '%'.$valor_pesquisa.'%', $valor_pesquisa.'%', $valor_pesquisa.'%', $funcao, $unidade, $status),'order' => 'nome asc'));
                if(!empty($registros)):
                    foreach($registros as $registro):
                        try{
                            $unidade = Unidades::find($registro->id_unidade);
                        } catch(\ActiveRecord\RecordNotFound $e){

                        }

                        try{
                            $funcao = Funcoes::find($registro->id_funcao);
                        } catch(\ActiveRecord\RecordNotFound $e){

                        }

                        echo '<tr>';
                        echo '<td data-title="Data Cadastro">'.$registro->data_criacao->format("d/m/Y").'</td>';
                        echo '<td data-title="Colega">'.$registro->apelido.'</td>';
                        echo !empty($unidade->nome_fantasia) ? '<td data-title="Unidade">'.$unidade->nome_fantasia.'</td data-title="Unidade">' : '<td></td>';
                        echo !empty($funcao->funcao) ? '<td data-title="Função">'.$funcao->funcao.'</td data-title="Unidade">' : '<td></td>';
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

            else:

                echo '<div class="titulo fw-bold size-1-5">Selecione os filtros desejados e clique em Pesquisar</div>';

            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
