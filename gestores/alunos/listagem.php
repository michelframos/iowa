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
                <th>Aluno</th>
                <th>Unidade</th>
                <th>Situação</th>
                <th>Matrícula(s)</th>
                <th colspan="2"></th>
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

                if(!empty($_POST['situacao'])):
                    $situacao = filter_input(INPUT_POST, 'situacao', FILTER_SANITIZE_STRING);
                else:
                    $situacao = '';
                endif;

                if(!empty($_POST['unidade'])):
                    $unidade = filter_input(INPUT_POST, 'unidade', FILTER_SANITIZE_STRING);
                else:
                    $unidade = '';
                endif;

                if(!empty($_POST['origem'])):
                    $origem = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_STRING);
                else:
                    $origem = '';
                endif;

                $registros = Alunos::all(array('conditions' => array('(nome like ? or rg like ? or cpf like ? or nome_responsavel like ? or rg_responsavel like ? or cpf_responsavel like ? ) and id_situacao like ? and id_unidade like ? and id_origem like ? ', '%'.$valor_pesquisa.'%', $valor_pesquisa.'%', $valor_pesquisa.'%', '%'.$valor_pesquisa.'%', $valor_pesquisa.'%', $valor_pesquisa.'%', $situacao, $unidade, $origem), 'order' => 'nome asc'));
                if(!empty($registros)):
                    foreach($registros as $registro):

                        /*Verificando data de cadastro do aluno*/
                        $data_atual = new DateTime("now");
                        $dias = $registro->data_criacao->diff($data_atual);
                        //$dias_atraso = $dias->d;
                        $dias_cadastro = $dias->format('%R%a');

                        /*apagando alunos com nome Novo Aluno*/
                        if($registro->nome == 'Novo Aluno' && $registro->id_situacao == 0 && $registro->id_unidade == 0 && $registro->id_origem == 0 && empty($registro->data_nascimento) && $dias_cadastro >= 1):
                            $registro->delete();
                        endif;

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

                        echo '<td>';

                            $matriculas = Matriculas::all(array('conditions' => array('id_aluno = ? and status = ?', $registro->id, 'a')));
                            if(!empty($matriculas)):
                                foreach($matriculas as $matricula):
                                    try{
                                        $turma = Turmas::find($matricula->id_turma);

                                        switch($matricula->status)
                                        {
                                            case 'a': $status = 'Ativa'; break;
                                            case 'i': $status = 'Inativa'; break;
                                            case 's': $status = 'Stand By'; break;
                                            case 't': $status = 'Transferido'; break;
                                        }

                                        echo $turma->nome . ' [ '.$status.' ]'.'<br>';
                                    } catch (Exception $e){

                                    }

                                endforeach;
                            endif;

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
