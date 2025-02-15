<!-- --------------------------------------------------------------------------------------- -->
<!-- Conteúdo de Uma Aba -->

    <?php
        include_once('../../config.php');
        include_once('../funcoes_painel.php');
        parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

        if(!empty($dados['id_unidade'])):
            $id_unidade = $dados['id_unidade'];
        else:
            $id_unidade = '%';
        endif;


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
            $datas = " and (parcelas.data_vencimento between '{$data_inicial}' and '{$data_final}')";
        else:
            $datas = '';
        endif;



        if(!empty($dados['valor_inicial'])):
            $valor_inicial = str_replace(".", "", $dados['valor_inicial']);
            $valor_inicial = str_replace(",", ".", $valor_inicial);
        else:
            $valor_inicial = "";
        endif;

        if(!empty($dados['valor_final'])):
            $valor_final = str_replace(".", "", $dados['valor_final']);
            $valor_final = str_replace(",", ".", $valor_final);
        else:
            $valor_final = "";
        endif;

        if(!empty($valor_inicial) and empty($valor_final)):
            $valor_final = $valor_inicial;
        endif;

        if(!empty($valor_final)):
            $valores = " and (parcelas.valor >= {$valor_inicial} and parcelas.valor <= {$valor_final})";
        else:
            $valores = '';
        endif;


        if(!empty($dados['sacado'])):
            $pagante = $dados['sacado'];
        else:
            $pagante = '%';
        endif;

        if($pagante == 'aluno'):
            if(!empty($dados['nome'])):
                //$nome = $dados['nome'].'%';
                $nome = " and COALESCE(alunos.nome, '') like '%".trim($dados['nome'])."%'";
                $boleto = " and COALESCE(parcelas.boleto, '') like 'n' ";
            else:
                $nome = " and COALESCE(alunos.nome, '') like '%' ";
                $boleto = " and COALESCE(parcelas.boleto, '') like 'n' ";
            endif;
        elseif($pagante == 'empresa'):
            //$nome = "";
            $nome = " and COALESCE(alunos.nome, '') like '%".trim($dados['nome'])."%'";
            $boleto = " and COALESCE(parcelas.boleto, '') like 'n' ";
        elseif($pagante == '%'):
            //$nome = "";
            $nome = " and COALESCE(alunos.nome, '') like '%".trim($dados['nome'])."%'";
            $boleto = " and COALESCE(parcelas.boleto, '') like 'n' ";
        endif;

        $situacao_aluno = $dados['situacao-aluno'];

        if($pagante == 'aluno'):
            if(!empty($situacao_aluno)):
                $situacao_do_aluno = " and matriculas.status = '{$situacao_aluno}'";
            else:
                $situacao_do_aluno = " and (COALESCE(matriculas.status, '') like '%')";
            endif;
        elseif($pagante == 'empresa'):
            $situacao_do_aluno = '';
        endif;

        $sql = "select parcelas.*, alunos.situacao_aluno, alunos.nome, turmas.id_unidade, matriculas.status as status_matricula from parcelas left join alunos on parcelas.id_aluno = alunos.id left join matriculas on parcelas.id_matricula = matriculas.id";
        $sql1.=" left join turmas on parcelas.id_turma = turmas.id ";
        $sql2 = " where coalesce(parcelas.id_turma, '') like '{$id_turma}' ".$datas.$valores." and parcelas.pagante like '{$pagante}' ".$situacao_do_aluno." and coalesce(turmas.id_unidade, '') like '{$id_unidade}' and pago = 'n' and coalesce(parcelas.pausada, '') <> 's' ";
        $sql3 = $boleto.$nome." order by alunos.nome asc";

        //echo $sql.$sql1.$sql2.$sql3;

        if(!empty($dados)):
            $parcelas = Parcelas::find_by_sql($sql.$sql1.$sql2.$sql3);
        else:
            $parcela = '';
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
                <th class="text-center">Nº Parcela</th>
                <th>Sacado</th>
                <th>Unidade</th>
                <th class="text-center">Data Vencto.</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Categoria</th>
                <th class="text-center">Status</th>
                <th class="text-center">Sitação Aluno</th>
                <th class="text-center">Nº Boleto</th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(!empty($parcelas)):
                foreach($parcelas as $parcela):

                    if($parcela->pagante == 'aluno'):
                        try{
                            $aluno = Alunos::find($parcela->id_aluno);
                            $sacado = $aluno->nome;
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $sacado = '';
                        }

                    elseif($parcela->pagante == 'empresa'):
                        try{
                            $empresa = Empresas::find($parcela->id_empresa);
                            $sacado = $empresa->nome_fantasia;
                        } catch(\ActiveRecord\RecordNotFound $e){
                            $sacado = '';
                        }

                    endif;


                    /*
                    try{
                        $situacao = Situacao_Aluno::find($aluno->id_situacao);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $situacao = '';
                    }
                    */

                    if(!empty($situacao_aluno)):

                        if(($parcela->pagante == 'aluno' && $parcela->status_matricula == $situacao_aluno) || ($parcela->pagante == 'empresa')):

                            try{
                                $nome_unidade = Unidades::find($parcela->id_unidade);
                            } catch (Exception $e){
                                $nome_unidade = '';
                            }

                            echo '<tr>';

                            if($parcela->pago == 'n' && $parcela->cancelada == 'n'):
                                echo '<td>';
                                echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                                echo '<input type="checkbox" value="'.$parcela->id.'" class="parcela">';
                                echo '<span></span>';
                                echo '</label>';
                                echo '</td>';
                            else:
                                echo '<td></td>';
                            endif;

                            echo '<td class="text-center">'.$parcela->parcela.'</td>';
                            echo '<td>'.$sacado.'</td>';
                            echo '<td>'.$nome_unidade->nome_fantasia.'</td>';
                            echo '<td class="text-center">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                            echo '<td class="text-center">R$ '.number_format($parcela->total, 2, ',', '.').'</td>';
                            echo '<td></td>';
                            echo $parcela->pago == 's' ? '<td class="text-center">Pago</td>' : '<td class="text-center">Não Pago</td>';

                            if($parcela->pagante == 'aluno'):
                                echo $parcela->status_matricula == 'a' ? '<td class="text-center">Ativo</td>' : ($parcela->status_matricula == 'i' ? '<td class="text-center">Inativo</td>' : '<td class="text-center">Stand By</td>');
                            else:
                                echo '<td></td>';
                            endif;
                            echo '<td></td>';

                            echo '</tr>';

                        endif;

                    elseif(empty($situacao_aluno)):

                        try{
                            $nome_unidade = Unidades::find($parcela->id_unidade);
                        } catch (Exception $e){
                            $nome_unidade = '';
                        }

                        echo '<tr>';

                        if($parcela->pago == 'n' && $parcela->cancelada == 'n'):
                            echo '<td>';
                            echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                            echo '<input type="checkbox" value="'.$parcela->id.'" class="parcela">';
                            echo '<span></span>';
                            echo '</label>';
                            echo '</td>';
                        else:
                            echo '<td></td>';
                        endif;

                        echo '<td class="text-center">'.$parcela->parcela.'</td>';
                        echo '<td>'.$sacado.'</td>';
                        echo '<td>'.$nome_unidade->nome_fantasia.'</td>';
                        echo '<td class="text-center">'.$parcela->data_vencimento->format('d/m/Y').'</td>';
                        echo '<td class="text-center">R$ '.number_format($parcela->total, 2, ',', '.').'</td>';
                        echo '<td></td>';
                        echo $parcela->pago == 's' ? '<td class="text-center">Pago</td>' : '<td class="text-center">Não Pago</td>';

                        if($parcela->pagante == 'aluno'):
                            echo $parcela->status_matricula == 'a' ? '<td class="text-center">Ativo</td>' : ($parcela->status_matricula == 'i' ? '<td class="text-center">Inativo</td>' : '<td class="text-center">Stand By</td>');
                        else:
                            echo '<td></td>';
                        endif;
                        echo '<td></td>';

                        echo '</tr>';

                    endif;

                endforeach;
            endif;
            ?>

            </tbody>
        </table>
    </div>

<!-- Conteúdo de Uma Aba -->
<!-- --------------------------------------------------------------------------------------- -->
