<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data</th>
                <th>Usuário</th>
                <th>Tela</th>
                <th>Ação</th>
                <th>Descrição</th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(isset($_POST['usuario'])):

                if(!empty(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING))):
                    $id_usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
                else:
                    $id_usuario = '%';
                endif;

                if(!empty(filter_input(INPUT_POST, 'tela', FILTER_SANITIZE_STRING))):
                    $tela = filter_input(INPUT_POST, 'tela', FILTER_SANITIZE_STRING);
                else:
                    $tela = '%';
                endif;

                if(!empty(filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING))):
                    $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);
                else:
                    $acao = '%';
                endif;

                /*Data*/
                if(!empty(filter_input(INPUT_POST, 'data_inicial', FILTER_SANITIZE_STRING))):
                    $data_inicial = implode('-', array_reverse(explode('/', filter_input(INPUT_POST, 'data_inicial', FILTER_SANITIZE_STRING))));
                else:
                    $data_inicial = '';
                endif;

                if(!empty(filter_input(INPUT_POST, 'data_final', FILTER_SANITIZE_STRING))):
                    $data_final = implode('-', array_reverse(explode('/', filter_input(INPUT_POST, 'data_final', FILTER_SANITIZE_STRING))));
                else:
                    $data_final = '';
                endif;

                if(!empty($data_inicial) && empty($data_final)):
                    $data_final = $data_inicial;
                endif;

                if(empty($data_inicial)):
                    $registros = Historico_Acoes::all(array('conditions' => array('id_usuario like ? and tela like ? and acao like ?', $id_usuario, $tela, $acao),'order' => 'data asc'));
                else:
                    $registros = Historico_Acoes::all(array('conditions' => array('id_usuario like ? and tela like ? and acao like ? and data between ? and ?', $id_usuario, $tela, $acao, $data_inicial.' 00:00:00', $data_final.' 23:59:59'),'order' => 'data asc'));
                endif;
                if(!empty($registros)):
                    foreach($registros as $registro):
                        $usuario = Usuarios::find($registro->id_usuario);

                        echo '<tr>';
                        echo '<td data-title="Data Cadastro">'.$registro->data->format("d/m/Y").'</td>';
                        echo '<td data-title="Aluno">'.$usuario->nome.'</td>';
                        echo '<td data-title="Tela">'.$registro->tela.'</td>';
                        echo '<td data-title="Ação">'.$registro->acao.'</td>';
                        echo '<td data-title="Descrição">'.$registro->observacao.'</td>';
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
