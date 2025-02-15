<button type="button" name="transferir-aluno" data-target="#transferir-dialog" data-toggle="modal" id="transferir-aluno" value="Transferir Aluno" class="btn btn-info pmd-btn-raised">Transferir Aluno</button>
<div class="espaco20"></div>

<div class="pmd-card">
    <div class="table-responsive">
        <table class="table pmd-table table-hover">
            <thead>
            <tr>
                <th class="150">Data Matrícula</th>
                <th>Aluno</th>
                <th width="150">Situação</th>
                <th width="150">Motivo Desistência</th>
                <th width="150">Situação da Matrícula</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php

            $alunos_turmas = Alunos_Turmas::all(array('conditions' => array('id_turma = ?', $registro->id)));
            if(!empty($alunos_turmas)):
                foreach($alunos_turmas as $aluno_turma):

                    try{
                        $matricula = Matriculas::find($aluno_turma->id_matricula);
                    } catch(\ActiveRecord\RecordNotFound $e){
                        $matricula = '';
                    }


                    if(!empty($matricula->id_aluno)):
                        try {
                            $aluno = Alunos::find($matricula->id_aluno);
                        }catch (\ActiveRecord\RecordNotFound $e){
                            $aluno = '';
                        }
                    endif;

                    if(!empty($matricula->id_situacao_aluno_turma)):
                        $situacao = Situacao_Aluno_Turma::find($matricula->id_situacao_aluno_turma);
                    endif;

                    if(!empty($matricula->id_motivo_desistencia) && $matricula->id_motivo_desistencia != 0):
                        $motivo = Motivos_Desistencia::find($matricula->id_motivo_desistencia);
                    else:
                        $motivo = '';
                    endif;

                    $situacao_matricula = array(
                      'a' => 'Ativa',
                      'i' => 'Inativa',
                      's' => 'Stand By',
                      't' => 'Transferida',
                    );

                    echo '<tr>';
                    echo !empty($matricula->data_matricula) ? '<td data-title="Data Matrícula" width="150">'.$matricula->data_matricula->format("d/m/Y").'</td>' : '<td></td>';
                    echo '<td data-title="Aluno">'.$aluno->nome.'</td>';
                    echo '<td width="150">'.$situacao->situacao.'</td>';
                    echo !empty($motivo) ? '<td class="texto-centro">'.$motivo->motivo.'</td>' : '<td class="texto-centro">Vigente</td>';
                    echo '<td class="texto-centro">'.$situacao_matricula[$matricula->status].'</td>';
                    echo '<td width="20" data-title=""><a class="btn btn-info btn-sm pmd-ripple-effect pmd-btn-fab pmd-btn-flat bt-altera-integrante" registro="'.$aluno->id.'" turma="'.$registro->id.'" data-toggle="popover" data-trigger="hover" data-placement="top" title="Alterar"><i class="material-icons pmd-sm">mode_edit</i> </a></td>';
                    echo '</tr>';
                endforeach;
            endif;
            ?>

            </tbody>
        </table>
    </div>
</div>
