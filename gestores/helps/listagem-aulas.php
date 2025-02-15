<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Helps::find($id);
$instrutor = Colegas::find($registro->id_colega);
$aluno = Alunos::find($registro->id_aluno);
?>

<script src="js/helps.js"></script>

<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">book</i>
    <h1>Dário de Classe do HELP - Aluno: <?php echo $aluno->nome ?> - Instrutor: <?php echo $instrutor->nome; ?></h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar">Voltar</a>
    <div class="espaco20"></div>
    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Inicio Abas -->

    <form action="" name="formIntegrantes" id="formIntegrantes" method="post">

        <div class="pmd-card">
            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th class="150">Data da Aula</th>
                        <th>Conteúdo Dado</th>
                        <th>Professor</th>
                        <th>Diário</th>
                        <th width="150">Aula Dada</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    $aulas = Aulas_Help::all(array('conditions' => array('id_help = ?', $registro->id), 'order' => 'data asc'));
                    if(!empty($aulas)):
                        foreach($aulas as $aula):

                            if(!empty($aula->id_colega)):
                                $professor = Colegas::find($aula->id_colega);
                            else:
                                $professor = '';
                            endif;

                            if(!empty($aula->id_situacao_aula) && $aula->id_situacao_aula != 0):
                                $situacao = Situacao_Aulas::find($aula->id_situacao_aula);
                            else:
                                $situacao = '';
                            endif;

                            echo '<tr>';
                            echo !empty($aula->data) ? '<td data-title="Data da Aula" width="150">'.$aula->data->format("d/m/Y").'</td>' : '<td></td>';
                            echo '<td data-title="Aluno">'.$aula->conteudo_dado.'</td>';
                            echo '<td data-title="Aluno">'.$professor->nome.'</td>';
                            echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-dados-aula" help="'.$registro->id.'" registro="'.$aula->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Dados desta aula"><i class="material-icons pmd-sm">class</i> </a></td>';
                            echo empty($situacao) ? '<td width="150" class="texto-centro">Não</td>' : '<td width="150" class="texto-centro">'.$situacao->descricao.'</td>';
                            echo '</tr>';
                        endforeach;
                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>

    </form>

</section>

<script type="text/javascript">
    $("#data").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>