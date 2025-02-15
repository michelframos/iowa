<?php
include_once('../../../config.php');
include_once('../../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'gerar-relatorio'):

    if(!empty($dados['unidade'])):
        $id_unidade = $dados['unidade'];
    else:
        $id_unidade = '%';
    endif;

    if(!empty($dados['funcao'])):
        $id_funcao = $dados['funcao'];
    else:
        $id_funcao = '%';
    endif;

    /*Data*/
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

    if(!empty($data_inicial) && empty($data_final)):
        $data_final = $data_inicial;
    endif;

    if(empty($data_inicial)):
        $registros = Colegas::all(array('conditions' => array('id_unidade like ? and id_funcao like ? and status = ?', $id_unidade, $id_funcao, 'a')));
    else:
        $registros = Colegas::all(array('conditions' => array('id_unidade like ? and id_funcao like ? and data_admissao between ? and ? and status = ?', $id_unidade, $id_funcao, $data_inicial, $data_final, 'a')));
    endif;

    if(!empty($registros)):

        echo '<h2 class="titulo">COLEGAS IOWA</h2>';
        ?>

        <div class="table-responsive">
        <table class="table pmd-table table-hover">
        <thead>
        <tr>
            <th width="150">Nome</th>
            <th width="150">Telefone</th>
            <th width="150">Celular</th>
            <th width="150">Email</th>
            <th width="150">Função</th>
            <th width="150">Data Admissão</th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach($registros as $registro):

            try{
                $funcao = Funcoes::find($registro->id_funcao);
            } catch (Exception $e){
                $funcao = '';
            }


            echo '<tr>';
                echo '<td>'.$registro->nome.'</td>';
                echo !empty($registro->telefone) ? '<td>'.mascara($registro->telefone, "(##)####-####").'</td>' : '<td></td>';
                echo !empty($registro->celular) ? '<td>'.mascara($registro->celular, "(##)#####-####").'</td>' : '<td></td>';
                echo '<td>'.$registro->email.'</td>';
                echo '<td>'.$funcao->funcao.'</td>';
                echo !empty($registro->data_admissao) ? '<td>'.$registro->data_admissao->format('d/m/Y').'</td>' : '<td></td>';
            echo '</tr>';

        endforeach;
        ?>
            </tbody>
        </table>
        </div>
        <div class="espaco20"></div>

        <?php
    else:

        echo '<div class="text-center fw-bold size-1-5">NENHUM ALUNO ENCONTRADO.</div>';

    endif;

endif;
