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
                <th>Nome de Produto</th>
                <th>Hora(s) Por Aula</th>
                <th>Horas do Estágio</th>
                <th width="50" colspan="2"></th>
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

                $registros = Nomes_Produtos::all(array('conditions' => array('nome_material like ? and programacao = ?', '%'.$valor_pesquisa.'%','s'),'order' => 'nome_material asc'));
                if(!empty($registros)):
                    foreach($registros as $registro):
                        echo '<tr>';
                        echo '<td data-title="Data Cadastro">'.$registro->data_criacao->format("d/m/Y").'</td>';
                        echo '<td data-title="Nome de Produto">'.$registro->nome_material.'</td>';
                        echo '<td data-title="Hora(s) Por Aula">'.$registro->horas_semanais.' Hora(s) Por Aula</td>';
                        echo '<td data-title="Horas do Estágio">'.$registro->horas_estagio.' Hora(s) de Estágio</td>';

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
