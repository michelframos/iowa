<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

/*Funcções da API*/

if($dados['acao'] == 'sair'):

    $ch = curl_init("http://eu91.chat-api.com/instance87130/logout?token=n599nsn91juqeyfk");

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $retorno = curl_exec($ch);
    curl_close($ch);

    echo $retorno;

endif;

if($dados['acao'] == 'status'):

    $ch = curl_init("http://eu91.chat-api.com/instance87130/status?&no_wakeup=false&token=n599nsn91juqeyfk");

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $retorno = curl_exec($ch);
    curl_close($ch);

    echo $retorno;

endif;


if($dados['acao'] == 'recarregar'):

    $ch = curl_init("http://eu91.chat-api.com/instance87130/expiry?token=n599nsn91juqeyfk");

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $retorno = curl_exec($ch);
    curl_close($ch);

    echo $retorno;

endif;

if($dados['acao'] == 'reiniciar'):

    $ch = curl_init("http://eu91.chat-api.com/instance87130/reboot?token=n599nsn91juqeyfk");

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $retorno = curl_exec($ch);
    curl_close($ch);

    echo $retorno;

endif;

/*Fim Funcções da API*/
/*##############################################################################################################*/

if($dados['acao'] == 'listar-turmas'):

    $id_unidade = $dados['id_unidade'];
    if(empty($id_unidade)):
        $id_unidade = '%';
    endif;

    $id_idioma = $dados['id_idioma'];
    if(empty($id_idioma)):
        $id_idioma = '%';
    endif;

    $turmas = Turmas::all(['conditions' => ['id_unidade like ? and id_idioma like ? and status = ?', $id_unidade, $id_idioma, 'a'], 'order' => 'nome asc']);
    if(!empty($turmas)):
        echo "<option value=''>Todas</option>";
        foreach ($turmas as $turma):
            echo "<option value='{$turma->id}'>{$turma->nome}</option>";
        endforeach;
    endif;

endif;

if($dados['acao'] == 'listar-alunos'):

    $id_unidade = $dados['id_unidade'];
    if(empty($id_unidade)):
        $id_unidade = '%';
    endif;

    $id_idioma = $dados['id_idioma'];
    if(empty($id_idioma)):
        $id_idioma = '%';
    endif;

    $id_turma = $dados['id_turma'];
    if(empty($id_turma)):
        $id_turma = '%';
    endif;

    $tipo = $dados['tipo'];

    $nome = $dados['nome'];

    $alunos = Alunos::find_by_sql("
      select 
      alunos_turmas.*, 
      turmas.id_idioma, 
      turmas.nome as nome_turma, 
      alunos.id as id_do_aluno,
      alunos.nome, 
      alunos.celular, 
      alunos.nome_responsavel,
      alunos.celular_responsavel 
      from alunos_turmas 
      inner join turmas on alunos_turmas.id_turma = turmas.id 
      inner join alunos on alunos_turmas.id_aluno = alunos.id
      inner join matriculas on alunos_turmas.id_aluno = matriculas.id_aluno and alunos_turmas.id_turma = matriculas.id_turma
      where turmas.id_unidade like '{$id_unidade}'
      and turmas.id_idioma like '{$id_idioma}'
      and turmas.id like '{$id_turma}' 
      and alunos.nome like '%{$nome}%'
      and matriculas.status = 'a'
      and turmas.status = 'a'
      GROUP BY alunos.id order by alunos.id
      ");

    if(!empty($alunos)):

        ?>
        <!-- Basic Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th width="50">
                        <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                            <input type="checkbox" value="" id="selecionar-todos">
                            <span></span>
                        </label>
                    </th>
                    <th>Aluno</th>
                    <th>Celular</th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($alunos as $aluno):

                    if($tipo == ''):

                        echo '<tr>';

                            echo '<td>';
                            echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                            echo '<input type="checkbox" value="'.$aluno->id_do_aluno.'" class="aluno">';
                            echo '<span></span>';
                            echo '</label>';
                            echo '</td>';

                            echo '<td>Aluno: '.$aluno->nome.'</td>';
                            echo '<td>'.$aluno->celular.'</td>';

                        echo '</tr>';

                        if(!empty($aluno->nome_responsavel)):
                        echo '<tr>';

                            echo '<td>';
                            echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                            echo '<input type="checkbox" value="'.$aluno->id_do_aluno.'" class="aluno">';
                            echo '<span></span>';
                            echo '</label>';
                            echo '</td>';

                            echo '<td>Responsável: '.$aluno->nome_responsavel.'('.$aluno->nome.')</td>';
                            echo '<td>'.$aluno->celular_responsavel.'</td>';

                        echo '</tr>';
                        endif;

                    endif;

                    if($tipo == 'aluno'):

                        echo '<tr>';

                        echo '<td>';
                        echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                        echo '<input type="checkbox" value="'.$aluno->id_do_aluno.'" class="aluno">';
                        echo '<span></span>';
                        echo '</label>';
                        echo '</td>';

                        echo '<td>Aluno: '.$aluno->nome.'</td>';
                        echo '<td>'.$aluno->celular.'</td>';

                        echo '</tr>';

                    endif;

                    if($tipo == 'responsavel'):

                        if(!empty($aluno->nome_responsavel)):
                            echo '<tr>';

                            echo '<td>';
                            echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                            echo '<input type="checkbox" value="'.$aluno->id_do_aluno.'" class="aluno">';
                            echo '<span></span>';
                            echo '</label>';
                            echo '</td>';

                            echo '<td>Responsável: '.$aluno->nome_responsavel.'('.$aluno->nome.')</td>';
                            echo '<td>'.$aluno->celular_responsavel.'</td>';

                            echo '</tr>';
                        endif;

                    endif;

                endforeach;
                ?>
                </tbody>
            </table>
        </div>

        <?php
    endif;

endif;
