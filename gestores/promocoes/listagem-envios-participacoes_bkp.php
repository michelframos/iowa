<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$pesquisa = filter_input(INPUT_POST, 'valor_pesquisa_promocao', FILTER_SANITIZE_STRING);
$id_promocao = filter_input(INPUT_POST, 'id_promocao', FILTER_SANITIZE_STRING);
$promocao = Promocoes::find($id_promocao);

$id_unidade = filter_input(INPUT_POST, 'id_unidade', FILTER_SANITIZE_STRING);
//$id_turma = filter_input(INPUT_POST, 'turma', FILTER_SANITIZE_STRING);

empty($id_unidade) ? $id_unidade = '%' : '';
//empty($id_turma) ? $id_turma = '%' : '';

if(isset($_POST['valor_pesquisa_promocao'])):
?>
<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data do Envio</th>
                <th>Para o ALuno</th>
                <th>Mensagem</th>
                <th width="100">Acessou o Link</th>
                <th width="100">Data do Acesso</th>
            </tr>
            </thead>
            <tbody>
            <?php
            //$turmas = Turmas::find_by_sql("select id, id_unidade, nome from turmas where id_unidade like '{$id_unidade}' and id like '{$id_turma}'");
            $turmas = Turmas::find_by_sql("select id, id_unidade, nome from turmas where id_unidade like '{$id_unidade}' ");
            if(!empty($turmas)):
                foreach ($turmas as $turma):

                    /*
                    echo '<tr>';
                    echo '<td colspan="5" style="font-size: 1.3em; font-weight: bold;">'.$turma->nome.'</td>';
                    echo '</tr>';
                    */

                    $alunos_turmas = Alunos_Turmas::all(['conditions' => ['id_turma = ?', $turma->id], 'group' => 'id_turma, id_aluno']);
                    if(!empty($alunos_turmas)):
                        foreach ($alunos_turmas as $aluno_turma):

                            $envios = EnviosPromocoes::all(['conditions' => ['id_promocao = ? and id_aluno = ?', $promocao->id, $aluno_turma->id_aluno], 'order' => 'data asc', 'group' => 'codigo']);
                            if(!empty($envios)):
                                foreach ($envios as $envio):

                                    try{
                                        $aluno = Alunos::find($envio->id_aluno);
                                    } catch (Exception $e){
                                        $aluno = '';
                                    }

                                    echo '<tr>';
                                    echo !empty($envio->data) ? '<td>'.$envio->data->format('d/m/Y').'</td>' : '<td></td>';
                                    echo '<td>'.$aluno->nome.'</td>';
                                    echo '<td>'.$envio->mensagem.'</td>';
                                    echo $envio->utilizado == 's' ? '<td>Sim</td>' : '<td>Não</td>';
                                    echo !empty($envio->data_participacao) ? '<td>'.$envio->data_participacao->format('d/m/Y').'</td>' : '<td></td>';
                                    echo '</tr>';

                                    $participacoes = Participacoes_Promocoes::all(['conditions' => ['id_envio_promocao = ? and ( coalesce(nome_participante, "") like ? or coalesce(email_participante, "") like ? or coalesce(telefone_participante, "") like ? )', $envio->id, '%'.$pesquisa.'%', '%'.$pesquisa.'%', '%'.$pesquisa.'%']]);

                                    echo '<tr>';
                                    echo '<td colspan="5">';

                                    if(!empty($participacoes)):
                                        echo '<div class="padding-10">';
                                        echo '<div class="col-md-3">DADOS DO(S) PARTICIPANTE(S)</div>';
                                        echo '<div class="espaco"></div>';
                                        foreach ($participacoes as $participacao):
                                            echo '
                                    
                                    <div class="clear"></div>
                                    <div class="col-md-3">Nome: '.$participacao->nome_participante.'</div>
                                    <div class="col-md-3">E-mail: '.$participacao->email_participante.'</div>
                                    <div class="col-md-3">Celular: '.$participacao->telefone_participante.'</div>
                                    <div class="col-md-3">Interesse: '.$participacao->interesse.'</div>
                                    <div class="espaco"></div>';
                                        endforeach;
                                        echo '</div>';
                                    endif;

                                    echo '</td>';
                                    echo '</tr>';

                                    echo '<tr><td colspan="5" style="height: 2px !important; padding: 0 !important; background: #a3a3a3;"></td></tr>';

                                endforeach;
                            endif;


                        endforeach;
                    endif;
                endforeach;
            endif;
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php
endif;