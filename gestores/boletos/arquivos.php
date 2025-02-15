<h1>Listando os últimos 30 arquivos gerados</h1>

<!-- Reflow table -->
<div class="pmd-card pmd-z-depth">
    <div class="table-responsive">
        <table class="table pmd-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Data do Arquivo</th>
                <th>Nome do Arquivo</th>
                <th>Baixar</th>
            </tr>
            </thead>

            <tbody>

            <?php
            $arquivos = Arquivos_Cnab::all(array('order' => 'data desc', 'limit' => 30));
            if(!empty($arquivos)):
                foreach($arquivos as $arquivo):
                    echo '<tr class="table-success">';
                    echo '<th scope="row">'.$arquivo->id.'</th>';
                    echo '<td>'.$arquivo->data->format('d/m/Y H:i:s').'</td>';
                    echo '<td>'.$arquivo->arquivo.'</td>';
                    echo '<td><a href="boletos/baixar.php?arquivo='.$arquivo->arquivo.'" target="_blank"><i class="material-icons md-light pmd-md">file_download </i></a></td>';
                    echo '</tr>';
                endforeach;
            endif;
            ?>

            </tbody>

        </table>
    </div>
</div>