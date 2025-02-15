<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'busca-turmas'):

    if(!empty($dados['id_unidade']) || !empty($dados['id_professor'])):

        $id_unidade = !empty($dados['id_unidade']) ? $dados['id_unidade'] : '%';
        $id_professor = !empty($dados['id_professor']) ? $dados['id_professor'] : '%';

        $turmas = Turmas::all(array('conditions' => array('id_unidade like ? and id_colega like ? and status = ?', $id_unidade, $id_professor, 'a'), 'order' => 'nome asc'));
        if(!empty($turmas)):
            echo '<option value="">Todas</option>';
            foreach($turmas as $turma):
                echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
            endforeach;
        endif;

    else:

        $turmas = Turmas::all(array('conditions' => ['status = ?', 'a'], 'order' => 'nome asc'));
        if(!empty($turmas)):
            echo '<option value="">Todas</option>';
            foreach($turmas as $turma):
                echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
            endforeach;
        endif;

    endif;

endif;

if($dados['acao'] == 'buscar-professores'):

    if(!empty($dados['id_unidade'])):

        $professores = Colegas::all(array('conditions' => array('status = ? and id_funcao = ? and id_unidade = ?', 'a', 3, $dados['id_unidade']), 'order' => 'apelido asc'));
        if(!empty($professores)):
            echo '<option value="">Todos</option>';
            foreach($professores as $professor):
                echo '<option value="'.$professor->id.'">'.$professor->apelido.'</option>';
            endforeach;
        endif;

    else:

        $professores = Colegas::all(array('conditions' => array('status = ? and id_funcao = ?', 'a', 3), 'order' => 'apelido asc'));
        if(!empty($professores)):
            echo '<option value="">Todos</option>';
            foreach($professores as $professor):
                echo '<option value="'.$professor->id.'">'.$professor->apelido.'</option>';
            endforeach;
        endif;

    endif;

endif;

if($dados['acao'] == 'gerar-relatorio'):

    if(empty($dados['id_unidade'])):
        $id_unidade = '%';
    else:
        $id_unidade = $dados['id_unidade'];
    endif;

    if(empty($dados['id_professor'])):
        $id_professor = '%';
    else:
        $id_professor = $dados['id_professor'];
    endif;

    if($dados['turma'] == ''):
        $id_turma = '%';
    else:
        $id_turma = $dados['turma'];
    endif;

    /*
    if($dados['nome'] == ''):
        $nome = '%';
    else:
        $nome = $dados['nome'].'%';
    endif;
    */

    /*Data*/
//    if(!empty($dados['data_inicial'])):
//        $data_inicial = implode('-', array_reverse(explode('/', $dados['data_inicial'])));
//    else:
//        $data_inicial = '';
//    endif;
//
//    if(!empty($dados['data_final'])):
//        $data_final = implode('-', array_reverse(explode('/', $dados['data_final'])));
//    else:
//        $data_final = '';
//    endif;
//
//    if(!empty($data_inicial) && empty($data_final)):
//        $data_final = $data_inicial;
//    endif;

    /*
    if(!empty($dados['considerar_abono'])):
        $considerar_abono = $dados['considerar_abono'];
    else:
        $considerar_abono = 'n';
    endif;
    */

    //$turmas = Turmas::all(array('conditions' => array('id_unidade like ? and id like ?', $id_unidade, $id_turma), 'order' => 'nome asc'));

//    if(!empty($data_inicial) && empty($data_final)):
//        $data_final = $data_inicial;
//    endif;


    echo '<h2 class="titulo">RELATÓRIO F7</h2>';

    echo "
        <div class='table-responsive'>
           <table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Unidade</th>
                        <th>Turma</th>
                        <th>Professor</th>
                    </tr>
                </thead>
                
                <tbody>
               ";

            $alunos = VAlunosTurmas::all(['conditions' => ['id_unidade like ? and id_turma like ? and id_colega like ? and id_situacao = ?', $id_unidade, $id_turma, $id_professor, 1], 'order' => 'nome asc']);
            foreach ($alunos as $aluno):

                echo "
                <tr>
                    <td colspan='4'>{$aluno->nome} - ".(Unidades::find_by_id($aluno->id_unidade)->nome_fantasia)." - {$aluno->nome_turma} - ".(Colegas::find_by_id($aluno->id_colega)->nome)."</td>
                </tr>  
                ";

//                echo "
//                    <tr>
//                        <td>{$aluno->nome}</td>
//                        <td>".(Unidades::find_by_id($aluno->id_unidade)->nome_fantasia)."</td>
//                        <td>{$aluno->nome_turma}</td>
//                        <td>".(Colegas::find_by_id($aluno->id_colega)->nome)."</td>
//                    </tr>
//                ";

            endforeach;

    echo "
            </tbody>
        </table>
    </div>
    <div class='espaco20'></div>
    ";





endif;
