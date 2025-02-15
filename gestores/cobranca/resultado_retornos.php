<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<h1>Listando os últimos 30 resultados</h1>

<!-- Reflow table -->
<div class="pmd-card pmd-z-depth">
    <div class="table-responsive">
        <table class="table pmd-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Data do Resultado</th>
                <!--<th>Nome do Arquivo</th>-->
                <th>Ver</th>
            </tr>
            </thead>

            <tbody>

            <?php
            $arquivos = Resultado_Retornos::all(array('order' => 'data desc', 'limit' => 30));
            if(!empty($arquivos)):
                foreach($arquivos as $arquivo):
                    echo '<tr class="table-success">';
                    echo '<th scope="row">'.$arquivo->id.'</th>';
                    echo !empty($arquivo->data) ? '<td>'.$arquivo->data->format('d/m/Y H:i:s').'</td>' : '<td></td>';
                    //echo '<td>'.$arquivo->arquivo.'</td>';
                    //echo '<td><a href="cobranca/retorno/'.$arquivo->arquivo.'" target="_blank"><i class="material-icons md-light pmd-md">file_download </i></a></td>';
                    echo '<td><a href="cobranca/retorno/resultado.php?resultado='.$arquivo->id.'" target="_blank"><i class="material-icons md-light pmd-md">file_download </i></a></td>';
                    echo '</tr>';
                endforeach;
            endif;
            ?>

            </tbody>

        </table>
    </div>
</div>