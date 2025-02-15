<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
?>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th width="150">Data Inicio</th>
                <th>Aluno</th>
                <th>Instrutor</th>
                <th>Nº HELPs</th>
                <th>Tipo</th>
                <th>Unidade</th>
                <th>Horário(s)</th>
                <th width="100">Status</th>
                <th>Ação</th>
                <th>Diário</th>
            </tr>
            </thead>
            <tbody>

            <?php
            if(!empty($_POST['id_colega'])):
                $id_colega = filter_input(INPUT_POST, 'id_colega', FILTER_SANITIZE_NUMBER_INT);
            else:
                $id_colega = '%';
            endif;

            if(!empty($_POST['id_empresa'])):
                $id_empresa = filter_input(INPUT_POST, 'id_empresa', FILTER_SANITIZE_NUMBER_INT);
            else:
                $id_empresa = '%';
            endif;

            if(!empty($_POST['id_unidade'])):
                $id_unidade = filter_input(INPUT_POST, 'id_unidade', FILTER_SANITIZE_NUMBER_INT);
            else:
                $id_unidade = '%';
            endif;

            if(!empty($_POST['tipo_help'])):
                $tipo_help = filter_input(INPUT_POST, 'tipo_help', FILTER_SANITIZE_STRING);
            else:
                $tipo_help = '%';
            endif;

            if(!empty($_POST['status'])):
                $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
            else:
                $status = '%';
            endif;

            if(!empty($_POST['nome'])):
                $nome = '%'.filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING).'%';
            else:
                $nome = '%';
            endif;


            $sql = "select helps.id as id_help, helps.segunda, helps.terca, helps.quarta, helps.quinta, helps.sexta, helps.sabado, helps.domingo, helps.hora_inicio_segunda, helps.hora_termino_segunda, helps.hora_inicio_terca, helps.hora_termino_terca, helps.hora_inicio_quarta, helps.hora_termino_quarta, ";
            $sql.= "helps.hora_inicio_quinta, helps.hora_termino_quinta, helps.hora_inicio_sexta, helps.hora_termino_sexta, helps.hora_inicio_sabado, helps.hora_termino_sabado, helps.hora_inicio_domingo, helps.hora_termino_domingo, helps.id_unidade, helps.id_empresa, helps.id_colega, helps.tipo_help, helps.id_aluno, helps.quantidade_helps, helps.data_inicio, helps.status, alunos.id, alunos.nome ";
            $sql.= "from helps INNER JOIN alunos on helps.id_aluno = alunos.id WHERE helps.id_colega like '{$id_colega}' and helps.id_empresa like '{$id_empresa}' and helps.id_unidade like '{$id_unidade}' and helps.tipo_help like '{$tipo_help}' and helps.status like '{$status}' and alunos.nome like '{$nome}'";
            $registros = Helps::find_by_sql($sql);

            $tipo_help = ['help' => 'Help', 'help fixo' => 'Help Fixo', 'speed class' => 'Speed Class'];

            if(!empty($registros) && isset($_POST['nome'])):
                foreach($registros as $registro):

                    if(!empty($registro->id_unidade)):
                        $unidade = Unidades::find($registro->id_unidade);
                    endif;

                    if(!empty($registro->id_aluno)):
                        $aluno = Alunos::find($registro->id_aluno);
                    endif;


                    if(!empty($registro->id_colega)):
                        $instrutor = Colegas::find($registro->id_colega);
                    endif;


                    $dias_horarios = array();
                    /*Pegando data e horarios*/
                    if($registro->segunda == 's'):
                        $dias_horarios['Segunda-Feira']['inicio'] = $registro->hora_inicio_segunda;
                        $dias_horarios['Segunda-Feira']['termino'] = $registro->hora_termino_segunda;
                    endif;

                    if($registro->terca == 's'):
                        $dias_horarios['Terca-Feira']['inicio'] = $registro->hora_inicio_terca;
                        $dias_horarios['Terca-Feira']['termino'] = $registro->hora_termino_terca;
                    endif;

                    if($registro->quarta == 's'):
                        $dias_horarios['Quarta-Feira']['inicio'] = $registro->hora_inicio_quarta;
                        $dias_horarios['Quarta-Feira']['termino'] = $registro->hora_termino_quarta;
                    endif;

                    if($registro->quinta == 's'):
                        $dias_horarios['Quinta-Feira']['inicio'] = $registro->hora_inicio_quinta;
                        $dias_horarios['Quinta-Feira']['termino'] = $registro->hora_termino_quinta;
                    endif;

                    if($registro->sexta == 's'):
                        $dias_horarios['Sexta-Feira']['inicio'] = $registro->hora_inicio_sexta;
                        $dias_horarios['Sexta-Feira']['termino'] = $registro->hora_termino_sexta;
                    endif;

                    if($registro->sabado == 's'):
                        $dias_horarios['Sábado']['inicio'] = $registro->hora_inicio_sabado;
                        $dias_horarios['Sábado']['termino'] = $registro->hora_termino_sabado;
                    endif;

                    if($registro->domingo == 's'):
                        $dias_horarios['Domingo']['inicio'] = $registro->hora_inicio_domingo;
                        $dias_horarios['Domingo']['termino'] = $registro->hora_termino_domingo;
                    endif;
                    /*Fim Pegando data e horarios*/

                    echo '<tr>';

                        echo !empty($registro->data_inicio) ? '<td data-title="Data Inicio">'.$registro->data_inicio->format("d/m/Y").'</td>' : '<td></td>';
                        echo !empty($aluno->nome) ? '<td data-title="Aluno">'.$aluno->nome.'</td>' : '<td></td>';
                        echo !empty($instrutor->nome) ? '<td data-title="Instruto">'.$instrutor->nome.'</td>' : '<td></td>';
                        echo '<td data-title="Nº HELPs">'.$registro->quantidade_helps.'</td>';
                        echo '<td data-title="Tipo">'.$tipo_help[$registro->tipo_help].'</td>';
                        echo !empty($unidade->nome_fantasia) ? '<td data-title="Idioma">'.$unidade->nome_fantasia.'</td>' : '<td></td>';

                        if(!empty($dias_horarios)):
                            $horario = '';
                            foreach($dias_horarios as $i => $v):
                                $horario .= $i.' das '.$v['inicio'].' às '.$v['termino'] . '<br>';
                            endforeach;
                        endif;

                        echo '<td>'.$horario.'</td>';

                        /*Definindo qual botão aparecerá para o usuário*/
                        switch($registro->status){
                            case 'p':
                                $botao = '<button type="button" class="btn pmd-btn-flat pmd-ripple-effect btn-success bt-aprovar" registro="'.$registro->id_help.'">Aprovar</button> <br> <button type="button" class="btn pmd-btn-flat pmd-ripple-effect btn-danger bt-cancelar" registro="'.$registro->id_help.'">Reprovar</button>';
                                $status = 'Aguardando Aprovação';
                                $diario = '';
                                break;
                            case 'a':
                                $botao = '<button type="button" class="btn pmd-btn-flat pmd-ripple-effect btn-danger bt-cancelar" registro="'.$registro->id_help.'">Cancelar</button>';
                                $status = 'Em Andamento';
                                $diario = '<a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-diario-classe" registro="'.$registro->id_help.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-original-title="Diário de Classe"><i class="material-icons pmd-sm">class</i> </a>';
                                break;
                            case 'i':
                                /*$botao = '<button type="button" class="btn pmd-btn-flat pmd-ripple-effect btn-success bt-reativar" registro="'.$registro->id_help.'">Reativar</button>';*/
                                $botao = '';
                                $status = 'Cancelado';
                                $diario = '';
                                break;
                        }

                        echo '<td>'.$status.'</td>';
                        echo '<td>'.$botao.'</td>';
                        echo '<td width="20" data-title="">'.$diario.'</td>';

                    echo '</tr>';

                endforeach;

            else:

                echo '<div class="titulo fw-bold size-1-5">Selecione os filtros desejados e clique em Pesquisar</div>';

            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
