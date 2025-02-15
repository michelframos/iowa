<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Promocoes::find($dados['id']);

if($dados['acao'] == 'novo'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Promoções', 'i');

    $registro = new Promocoes();
    $registro->nome = 'Nova Promoção';
    $registro->tempo_indeterminado = 'n';
    $registro->status = 'a';
    dadosCriacao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Promoções', 'Inclusão', 'Uma nova Promoção foi cadastrada.');

    echo json_encode(array('status' => 'ok', 'id' => $registro->id));

endif;


if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Promoções', 'a');

    if($registro->nome != $dados['nome']):
        /*Verificando duplicidade*/
        if(Promocoes::find_by_nome($dados['nome'])):
            echo json_encode(array('status' => 'erro'));
            exit();
        endif;
    endif;

    /*Salvando Alterações*/
    $nome = trim($dados['nome']);
    $data_inicio = implode('-', array_reverse(explode('/', trim($dados['data_inicio']))));
    $data_termino = implode('-', array_reverse(explode('/', trim($dados['data_termino']))));
    $tempo_indeterminado = trim($dados['tempo_indeterminado']);
    $mensagem =  trim($dados['mensagem']);
    $desconto = trim($dados['desconto']);
    $numero_cupons = trim($dados['numero_cupons']);
    $para = trim($dados['para']);

    $registro->nome = $nome;
    !empty($data_inicio) ? $registro->data_inicio = $data_inicio : $registro->data_inicio = null;
    !empty($data_termino) ? $registro->data_termino = $data_termino : $registro->data_termino = null;
    $registro->tempo_indeterminado = $tempo_indeterminado;
    $registro->mensagem = $mensagem;

    $desconto = str_replace(',', '.', $desconto);
    $desconto = str_replace(',', '.', $desconto);
    $registro->desconto = $desconto;

    $registro->numero_cupons = $numero_cupons;
    $registro->numero_envios = 0;
    $registro->para = $para;
    dadosAlteracao($registro);
    $registro->save();

    adicionaHistorico(idUsuario(), idColega(), 'Promoções', 'Alteração', 'A Promoção '.$registro->nome.' foi alterada.');

    echo json_encode(array('status' => 'ok'));

endif;


if($dados['acao'] == 'excluir'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Promoções', 'e');

    /*
    if($registro->utilizado == 's'):
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Esta Natureza de Conta a Pagar não pode ser excluída por já ter sido utilizada no sistema.'));
        exit();
    endif;
    */

    adicionaHistorico(idUsuario(), idColega(), 'Promoções', 'Exclusão', 'A Promoção '.$registro->nome.' foi excluída.');

    $registro->delete();
    echo json_encode(array('status' => 'ok', 'mensagem' => ''));

endif;


if($dados['acao'] == 'ativa-inativa'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Promoções', 'ai');

    if($registro->status == 'a'):
        $registro->status = 'i';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Promoções', 'Inativação', 'A Promoção '.$registro->nome.' foi inativada.');
    else:
        $registro->status = 'a';
        $registro->save();
        adicionaHistorico(idUsuario(), idColega(), 'Promoções', 'Ativação', 'A Promoção '.$registro->nome.' foi ativada.');
    endif;

endif;


if($dados['acao'] == 'listar-turmas'):

    $id_unidade = $dados['id_unidade'];
    if(empty($id_unidade)):
        $id_unidade = '%';
    endif;

    $turmas = Turmas::all(['conditions' => ['id_unidade like ? and status = ?', $id_unidade, 'a'], 'order' => 'nome asc']);
    if(!empty($turmas)):
        echo "<option value=''>Todas</option>";
        foreach ($turmas as $turma):
            echo "<option value='{$turma->id}'>{$turma->nome}</option>";
        endforeach;
    endif;

endif;


if($dados['acao'] == 'busca-turmas'):

    if(!empty($dados['id_unidade'])):

        $turmas = Turmas::all(array('conditions' => array('id_unidade = ?', $dados['id_unidade']), 'order' => 'nome asc'));
        if(!empty($turmas)):
            echo '<option value="">Todas</option>';
            foreach($turmas as $turma):
                echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
            endforeach;
        endif;

    else:

        $turmas = Turmas::all(array('order' => 'nome asc'));
        if(!empty($turmas)):
            echo '<option value="">Todas</option>';
            foreach($turmas as $turma):
                echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
            endforeach;
        endif;

    endif;

endif;


if($dados['acao'] == 'listar-alunos'):

    $id_unidade = $dados['id_unidade'];
    if(empty($id_unidade)):
        $id_unidade = '%';
    endif;

    $id_turma = $dados['id_turma'];
    if(empty($id_turma)):
        $id_turma = '%';
    endif;

    $status = $dados['status_aluno'];
    if(empty($status)):
        $status = '%';
    endif;

    $nome = $dados['nome'];

    $alunos = Alunos::find_by_sql("
      select 
      alunos_turmas.*, 
      turmas.id_idioma, 
      turmas.nome as nome_turma, 
      alunos.id as id_do_aluno,
      alunos.nome, 
      alunos.celular,
      alunos.status
      from alunos_turmas 
      inner join turmas on alunos_turmas.id_turma = turmas.id 
      inner join alunos on alunos_turmas.id_aluno = alunos.id
      inner join matriculas on alunos_turmas.id_aluno = matriculas.id_aluno and alunos_turmas.id_turma = matriculas.id_turma
      where turmas.id_unidade like '{$id_unidade}'
      and turmas.id like '{$id_turma}' 
      and alunos.nome like '%{$nome}%'
      and matriculas.status like '{$status}'
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
                    <th>Status</th>
                    <th>Aluno</th>
                    <th>Celular</th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($alunos as $aluno):

                    echo '<tr>';

                    echo '<td>';
                    echo '<label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">';
                    echo '<input type="checkbox" value="'.$aluno->id_do_aluno.'" class="aluno">';
                    echo '<span></span>';
                    echo '</label>';
                    echo '</td>';

                    echo $aluno->status == 'a' ? '<td>Ativo</td>' : '<td>Inativo</td>';
                    echo '<td>Aluno: '.$aluno->nome.'</td>';
                    echo '<td>'.$aluno->celular.'</td>';

                    echo '</tr>';

                endforeach;
                ?>
                </tbody>
            </table>
        </div>

    <?php
    endif;

endif;
