<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);
$registro = Alunos::find(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));
$matriculas = Matriculas::all(array('conditions' => array('id_aluno = ?', $registro->id), 'order' => 'data_criacao asc'));
?>

<div style="max-width: 800px;">

    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
        <label>Matrícula</label>
        <select name="id_matricula" id="id_matricula" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
            <option value=""></option>
            <?php
            if(!empty($matriculas)):
                foreach($matriculas as $matricula):
                    $turma = Turmas::find($matricula->id_turma);
                    echo '<option matricula="'.$matricula->id.'" value="'.$matricula->id.'">'.$turma->nome.'</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
        <label>Selecione um Documento</label>
        <select name="id_texto" id="id_texto" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
            <option value=""></option>
            <?php
            $textos = Textos::all(array('order' => 'titulo asc'));
            if(!empty($textos)):
                foreach($textos as $texto):
                    echo '<option value="'.$texto->id.'">'.$texto->titulo.'</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <button type="button" name="editar-documento" id="editar-documento" registro="<?php echo $registro->id ?>" value="Ver Documento" class="btn btn-info pmd-btn-raised">Ver Documento</button>
    <div class="espaco20"></div>

</div>
