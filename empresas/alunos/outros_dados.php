<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$empresa = Empresas::find(idEmpresa());

$id = filter_input(INPUT_POST, 'id_turma', FILTER_VALIDATE_INT);
$turma = Turmas::find($id);

$aulas = Aulas_Turmas::all(array('conditions' => array('id_turma = ? and (id_situacao_aula <> ? and id_situacao_aula <> 2 and id_situacao_aula <> 3 and id_situacao_aula <> 5 and id_situacao_aula <> 10)', $turma->id, 0)));

$id_aluno = filter_input(INPUT_POST, 'id_aluno', FILTER_VALIDATE_INT);
$aluno = Alunos::find($id_aluno);

?>

<!-- Start Content -->
<section class="padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id_turma="<?php echo $turma->id ?>" id="bt-voltar-alunos">Voltar</a>

    <h1 class="headline">Turma: <?php echo $turma->nome ?></h1>
    <h1 class="headline">Aluno: <?php echo $aluno->nome ?></h1>
    <div class="espaco20"></div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="pmd-card">
            <div class="table-responsive">
                <table class="table pmd-table table-hover">
                    <thead>
                    <tr>
                        <th width="150">Data Falta</th>
                        <th>Abonada</th>
                        <th>Justificativa</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(!empty($aulas)):
                        foreach($aulas as $aula):
                            echo '<tr>';
                                if(Aulas_Alunos::all(array('conditions' => array('id_aula = ? and id_aluno = ? and presente = ?', $aula->id, $aluno->id, 'n')))):
                                    $aula_aluno = Aulas_Alunos::find(array('conditions' => array('id_aula = ? and id_aluno = ? and presente = ?', $aula->id, $aluno->id, 'n')));
                                    echo '<td width="150">'.$aula->data->format('d/m/Y').'</td>';
                                    echo ($aula_aluno->abonada != 's') ? '<td width="150">Não</td>' : '<td width="150">Sim</td>';
                                    echo '<td>'.$aula_aluno->justificativa.'</td>' ;
                                endif;
                            echo '</tr>';
                        endforeach;
                    endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>

    </section>

</section>
<div class="pmd-sidebar-overlay"></div>

<script src="js/alunos.js"></script>