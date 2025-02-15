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
                <th>Sistema</th>
                <th>Idioma</th>
                <th>Provas</th>
                <th width="100">Status</th>
                <th colspan="2"></th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(isset($_POST['valor'])):
                if(!empty($_POST['valor'])):
                    $valor = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_STRING);
                else:
                    $valor = '';
                endif;

                if(!empty($_POST['idioma'])):
                    $idioma = filter_input(INPUT_POST, 'idioma', FILTER_VALIDATE_INT);
                else:
                    $idioma = '%';
                endif;

                $registros = Sistema_Notas::all(array('conditions' => array('nome like ? and id_idioma like ?', '%'.$valor.'%', $idioma),'order' => 'nome asc'));
                if(!empty($registros)):
                    foreach($registros as $registro):

                        $prova_oral = '';
                        $prova1 = '';
                        $prova2 = '';
                        $prova3 = '';
                        $prova4 = '';
                        $prova5 = '';
                        $prova6 = '';

                        $p1 = '';
                        $p2 = '';
                        $p3 = '';
                        $p4 = '';
                        $p5 = '';
                        $p6 = '';
                        $po = '';

                        try {
                            $idioma = Idiomas::find($registro->id_idioma);
                            $prova_oral = Nome_Provas::find($registro->id_nome_prova_oral);
                            $prova1 = Nome_Provas::find($registro->id_nome_prova1);
                            $prova2 = Nome_Provas::find($registro->id_nome_prova2);
                            $prova3 = Nome_Provas::find($registro->id_nome_prova3);
                            $prova4 = Nome_Provas::find($registro->id_nome_prova4);
                            $prova5 = Nome_Provas::find($registro->id_nome_prova5);
                            $prova6 = Nome_Provas::find($registro->id_nome_prova6);
                        } catch (\ActiveRecord\RecordNotFound $e){

                        }

                        /*Calculando Coeficiente*/
                        $num_provas = 0;
                        !empty($prova1->nome) ? $num_provas++ : $num_provas = $num_provas;
                        !empty($prova2->nome) ? $num_provas++ : $num_provas = $num_provas;
                        !empty($prova3->nome) ? $num_provas++: $num_provas = $num_provas;
                        !empty($prova4->nome) ? $num_provas++ : $num_provas = $num_provas;
                        !empty($prova5->nome) ? $num_provas++ : $num_provas = $num_provas;
                        !empty($prova6->nome) ? $num_provas++ : $num_provas = $num_provas;

                        !empty($prova_oral->nome) ? $po = $prova_oral->nome : $po = '';

                        !empty($prova1->nome) ? $p1 = $prova1->nome : $p1 = '';
                        !empty($prova2->nome) ? $p2 = ' + ' . $prova2->nome : $p2 = '';
                        !empty($prova3->nome) ? $p3 = ' + ' . $prova3->nome : $p3 = '';
                        !empty($prova4->nome) ? $p4 = ' + ' . $prova4->nome : $p4 = '';
                        !empty($prova5->nome) ? $p5 = ' + ' . $prova5->nome : $p5 = '';
                        !empty($prova6->nome) ? $p6 = ' + ' . $prova6->nome : $p6 = '';

                        echo '<tr>';
                        echo '<td data-title="Data Cadastro">'.$registro->data_criacao->format("d/m/Y").'</td>';
                        echo '<td data-title="Nome do Sistema">'.$registro->nome.'</td>';
                        echo '<td data-title="Idioma">'.$idioma->idioma.'</td>';

                        echo '<td data-title="Provas">';
                        echo !empty($prova_oral->nome) ? 'Prova Oral: '.$prova_oral->nome.'<br/>' : '';
                        echo !empty($prova1->nome) ? 'Prova 1: '.$prova1->nome.'<br/>' : '';
                        echo !empty($prova2->nome) ? 'Prova 2: '.$prova2->nome.'<br/>' : '';
                        echo !empty($prova3->nome) ? 'Prova 3: '.$prova3->nome.'<br/>' : '';
                        echo !empty($prova4->nome) ? 'Prova 4: '.$prova4->nome.'<br/>' : '';
                        echo !empty($prova5->nome) ? 'Prova 5: '.$prova5->nome.'<br/>' : '';
                        echo !empty($prova6->nome) ? 'Prova 6: '.$prova6->nome.'<br/>' : '';
                        echo '(('.$p1.$p2.$p3.$p4.$p5.$p6.')/'.$num_provas.' + '.$po.')/2';
                        echo '<div class="espaco20"></div>';
                        echo '</td>';

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
