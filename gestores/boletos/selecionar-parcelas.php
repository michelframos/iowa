<!-- --------------------------------------------------------------------------------------- -->
<!-- Conteúdo de Uma Aba -->

    <?php
        include_once('../../config.php');
        include_once('../funcoes_painel.php');
        parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

        if(!empty($dados['id_turma'])):
            $id_turma = $dados['id_turma'];
        else:
            $id_turma = '%';
        endif;


        if(!empty($dados['data_inicial'])):
            $data_inicial = implode('-', array_reverse(explode('/', $dados['data_inicial'])));
        else:
            $data_inicial = '';
        endif;

        if(!empty($dados['data_final'])):
            $data_final = implode('-', array_reverse(explode('/', $dados['data_final'])));
        else:
            $data_final = '';
        endif;

        if(!empty($data_inicial) and empty($data_final)):
            $data_final = $data_inicial;
        endif;

        if(!empty($data_inicial)):
            $datas = " and (boletos.data_vencimento between '{$data_inicial}' and '{$data_final}')";
        else:
            $datas = '';
        endif;


        if(!empty($dados['sacado'])):
            $pagante = $dados['sacado'];
        else:
            $pagante = '%';
        endif;


        if(!empty($dados['valor_pesquisa'])):
            $nome = $dados['valor_pesquisa'].'%';
        else:
            $nome = '%';
        endif;


        $sql = "select boletos.*, parcelas.id as id_tabela_parcelas, parcelas.pagante, parcelas.id_aluno, parcelas.id_empresa, alunos.nome, empresas.razao_social, empresas.nome_fantasia, turmas.id as id_tabela_turmas, turmas.nome as nome_turma from boletos inner join parcelas on boletos.numero_boleto = parcelas.numero_boleto left join alunos on parcelas.id_aluno = alunos.id left join empresas on parcelas.id_empresa = empresas.id left join turmas on parcelas.id_turma = turmas.id";
        $sql2 = " where parcelas.id_turma like '{$id_turma}' ".$datas." and parcelas.pagante like '{$pagante}' and (COALESCE(alunos.nome, '') like '{$nome}' or COALESCE(empresas.razao_social, '') like '{$nome}' or COALESCE(empresas.nome_fantasia, '') like '{$nome}') and boletos.cancelado = 'n' order by alunos.nome, empresas.nome_fantasia asc";

        if(!empty($dados)):
            $boletos = Boletos::find_by_sql($sql.$sql2);
        else:
            $boletos = '';
        endif;
        //$parcelas = Parcelas::all(array('conditions' => array('id_turma like ? '.$datas.$valores.' and pagante like ? and pago = ?', $id_turma, $pagante, 'n'), 'order' => 'data_vencimento asc'));

    ?>

    <!-- Basic Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>
                    <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                        <input type="checkbox" value="" id="selecionar-todos">
                        <span></span>
                    </label>
                </th>
                <th class="text-center">Nº Boleto</th>
                <th class="text-center">Unidade</th>
                <th>Sacado</th>
                <th class="text-center">Data Vencto.</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Status</th>
                <th>Imprimir</th>
                <th>Renegociar</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(!empty($boletos)):
                foreach($boletos as $boleto):

                    if($boleto->pagante == 'aluno'):
                        try{
                            $aluno = Alunos::find($boleto->id_aluno);
                            $sacado = $aluno->nome;
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $sacado = '';
                        }

                    elseif($boleto->pagante == 'empresa'):
                        try{
                            $empresa = Empresas::find($boleto->id_empresa);
                            $sacado = $empresa->nome_fantasia;
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $sacado = '';
                        }

                    endif;

                    /*selecionado unidade*/
                    try{
                        $unidade = Unidades::find($boleto->id_unidade);
                    } catch (Exception $e) {
                        $unidade = '';
                    }

                    echo '<tr>';

                    if($boleto->pago == 'n'):
                        echo '<td>';
                        echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                        echo '<input type="checkbox" value="'.$boleto->id.'" class="boleto">';
                        echo '<span></span>';
                        echo '</label>';
                        echo '</td>';
                    else:
                        echo '<td></td>';
                    endif;

                    echo '<td class="text-center">'.$boleto->numero_boleto.'</td>';
                    echo !empty($unidade) ? '<td class="text-center">'.$unidade->nome_fantasia.'</td>' : '<td class="text-center"></td>';
                    echo '<td>'.$sacado.'</td>';
                    echo '<td class="text-center">'.$boleto->data_vencimento->format('d/m/Y').'</td>';
                    echo '<td class="text-center">R$ '.number_format($boleto->valor, 2, ',', '.').'</td>';
                    echo $boleto->pago == 's' ? '<td class="text-center">Pago</td>' : '<td class="text-center">Não Pago</td>';

                    $pega_permissao = Permissoes::find(array('conditions' => array('id_usuario = ? and tela = ?', idUsuario(), 'Gestão de Boletos')));

                    $impresso = \IowaHelpers\BancoHelper::impresso($boleto->codigo_banco);

                    echo $pega_permissao->imp == 's' ? '<td class="texto-center"><a href="'.HOME.'/'.$impresso.'?boleto='.$boleto->chave.'" target="_blank" class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat"><i class="material-icons pmd-sm">print</i></a></td>' : '<td class="texto-center"></td>';
                    echo ($boleto->pago == 'n') ? '<td class="texto-center"><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-renegociar" boleto="'.$boleto->id.'" data-trigger="hover" title="Renegociar"><i class="material-icons pmd-sm">assignment_late</i></a></td>' : '<td></td>';
                    echo '</tr>';

                endforeach;
            endif;
            ?>

            </tbody>
        </table>
    </div>

<!-- Conteúdo de Uma Aba -->
<!-- --------------------------------------------------------------------------------------- -->
