<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$registro = Turmas::find($id);

function intervalo( $entrada, $saida ) {
    $entrada = explode( ':', $entrada );
    $saida   = explode( ':', $saida );
    $minutos = ( $saida[0] - $entrada[0] ) * 60 + $saida[1] - $entrada[1];
    if( $minutos < 0 ) $minutos += 24 * 60;
    return sprintf( '%d:%d', $minutos / 60, $minutos % 60 );
}

?>

<script src="js/coachs.js"></script>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já exite uma Turma com este Nome na Unidade selecionada.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="dias-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Seleção de Dias</h2>
            </div>
            <div class="modal-body">
                <p>Selecione os dias em que haverão aulas.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<div tabindex="-1" class="modal fade" id="alterado-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterações</h2>
            </div>
            <div class="modal-body">
                <p>Alterações salvas com sucesso.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="mudanca-estagio-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Mudança de Estágio</h2>
            </div>
            <div class="modal-body">
                <p>Mudança de Estágio realizada com sucesso.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="transferir-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Transferência de Aluno</h2>
            </div>
            <div class="modal-body">

                <form name="formTranferir" id="formTranferir" method="post">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                        <label>Aluno</label>
                        <select name="id_aluno_turma" id="id_aluno_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option value=""></option>
                            <?php
                            $alunos_turma = Alunos_Turmas::find_all_by_id_turma($registro->id);
                            if(!empty($alunos_turma)):
                                foreach($alunos_turma as $aluno_turma):
                                    try{
                                        $matricula = Matriculas::find($aluno_turma->id_matricula);
                                    } catch(\ActiveRecord\RecordNotFound $e){
                                        $matricula = '';
                                    }

                                    $dados_aluno = Alunos::find($aluno_turma->id_aluno);

                                    if($matricula->status != 't'):
                                        echo '<option value="'.$aluno_turma->id.'">'.$dados_aluno->nome.'</option>';
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                        <label>Nova Turma</label>
                        <select name="id_turma_destino" id="id_turma_destino" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option value=""></option>
                            <?php
                            $turmas = Turmas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                            if(!empty($turmas)):
                                foreach($turmas as $turma):
                                    echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" id="bt-transferir" turma="<?php echo $registro->id; ?>" type="button">Transferir</button>
            </div>
        </div>
    </div>
</div>

<!-- Atas -->
<div tabindex="-1" class="modal fade" id="nova-ata-turma-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Nova Ata</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formNovaAtaTurma" id="formNovaAtaTurma" method="post">

                    <div class="form-group pmd-textfield">
                        <label class="control-label">Texto da Ata</label>
                        <textarea required class="form-control" name="nova-ata-turma" id="nova-ata" style="height: 100px;" required></textarea>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-salvar-ata-turma" aluno="<?php echo $registro->id ?>" turma="<?php echo $registro->id ?>">Salvar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="alterar-ata-turma-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterar Ata</h2>
            </div>
            <div class="modal-body">

                <form action="" name="formAlteraAtaTurma" id="formAlteraAtaTurma" method="post">

                    <div class="form-group pmd-textfield">
                        <label class="control-label">Texto da Ata</label>
                        <textarea required class="form-control" name="alterar-ata-turma" id="alterar-ata-turma" style="height: 100px;"></textarea>
                    </div>

                </form>

            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-alterar-ata-turma" ata="" aluno="<?php echo $registro->id ?>" turma="<?php echo $registro->id ?>">Salvar</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fim Atas -->

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">group_add</i>
    <h1> Cadastro / Alteração de Turma</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="voltar-coachs">Voltar</a>
    <div class="espaco20"></div>
    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Inicio Abas -->
    <div class="pmd-card pmd-z-depth">
        <div class="pmd-tabs pmd-tabs-bg">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#dados-turma" aria-controls="home" role="tab" data-toggle="tab">Dados da Turma</a></li>
                <li role="presentation"><a href="#integrantes" aria-controls="about" role="tab" data-toggle="tab">Integrantes</a></li>
                <li role="presentation"><a href="#atas" aria-controls="about" role="tab" data-toggle="tab">Atas</a></li>
            </ul>
        </div>

        <div class="pmd-card-body">
            <div class="tab-content">

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <div role="tabpanel" class="tab-pane active" id="dados-turma">


                    <form action="" name="formDados" id="formDados" method="post" style="max-width: 800px;">

                        <button type="button" name="mudar_estagio" id="mudar_estagio" value="Salvar" class="btn btn-info pmd-btn-raised oculto">Mudar Estágio</button>
                        <div class="espaco20"></div>

                        <div style="max-width: 800px;">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <?php
                                    if(!empty($registro->id_unidade) || $registro->id_unidade != 0):
                                        $readonly = 'readonly=""';
                                    else:
                                        $readonly = '';
                                    endif;
                                ?>
                                <label for="regular1" class="control-label">Nome da Turma</label>
                                <input type="text" name="nome" id="nome" value="<?php echo $registro->nome ?>" <?php echo $readonly ?> class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>

                            <?php if(empty($registro->id_unidade) || $registro->id_unidade == 0): ?>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                <label>Unidade</label>
                                <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option></option>
                                    <?php
                                    $unidades = Unidades::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_unidade), 'order' => 'nome_fantasia asc'));
                                    if(!empty($unidades)):
                                        foreach($unidades as $unidade):
                                            echo $registro->id_unidade == $unidade->id ? '<option selected value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>' : '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>

                            <?php else: ?>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Unidade</label>
                                    <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                        <?php
                                        $unidades = Unidades::find($registro->id_unidade);
                                        echo $registro->id_unidade == $unidades->id ? '<option selected value="'.$unidades->id.'">'.$unidades->nome_fantasia.'</option>' : '<option value="'.$unidades->id.'">'.$unidades->nome_fantasia.'</option>';
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>

                            <?php endif; ?>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                <label>Idioma</label>
                                <select name="id_idioma" id="id_idioma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option></option>
                                    <?php
                                    $idiomas = Idiomas::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_idioma), 'order' => 'idioma asc'));
                                    if(!empty($idiomas)):
                                        foreach($idiomas as $idioma):
                                            echo $registro->id_idioma == $idioma->id ? '<option selected value="'.$idioma->id.'">'.$idioma->idioma.'</option>' : '<option value="'.$idioma->id.'">'.$idioma->idioma.'</option>';
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>


                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                <label>Programação de Conteúdo</label>
                                <select name="id_produto" id="id_produto" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option></option>
                                    <?php
                                    $produtos = Nomes_Produtos::all(array('conditions' => array('(status = ? or id = ?) and programacao = ?', 'a', $registro->id_produto, 's'), 'order' => 'nome_material asc'));
                                    if(!empty($produtos)):
                                        foreach($produtos as $produto):
                                            echo $registro->id_produto == $produto->id ? '<option selected value="'.$produto->id.'">'.$produto->nome_material.'</option>' : '<option value="'.$produto->id.'">'.$produto->nome_material.'</option>';
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>


                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                <label>Sistema de Notas</label>
                                <select name="id_sistema_notas" id="id_sistema_notas" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option></option>
                                    <?php
                                    $sistema_notas = Sistema_Notas::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_sistema_notas), 'order' => 'nome asc'));
                                    if(!empty($sistema_notas)):
                                        foreach($sistema_notas as $sistema_nota):
                                            echo $registro->id_sistema_notas == $sistema_nota->id ? '<option selected value="'.$sistema_nota->id.'">'.$sistema_nota->nome.'</option>' : '<option value="'.$sistema_nota->id.'">'.$sistema_nota->nome.'</option>';
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                <label>Instrutor</label>
                                <select name="id_colega" id="id_colega" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option></option>
                                    <?php
                                    $colegas = Colegas::all(array('conditions' => array('(status = ? or id = ?) and id_funcao = ?', 'a', $registro->id_colega, 3), 'order' => 'nome asc'));
                                    if(!empty($colegas)):
                                        foreach($colegas as $colega):
                                            echo $registro->id_colega == $colega->id ? '<option selected value="'.$colega->id.'">'.$colega->apelido.'</option>' : '<option value="'.$colega->id.'">'.$colega->apelido.'</option>';
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                <label>Valor Hora Aula</label>
                                <select name="id_valor_hora_aula" id="id_valor_hora_aula" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option></option>
                                    <?php
                                    $valores = Valores_Hora_Aula::all(array('conditions' => array('status = ? or id = ?', 'a', $registro->id_valor_hora_aula), 'order' => 'nome asc'));
                                    if(!empty($valores)):
                                        foreach($valores as $valor):
                                            echo $registro->id_valor_hora_aula == $valor->id ? '<option selected value="'.$valor->id.'">'.$valor->nome.'</option>' : '<option value="'.$valor->id.'">'.$valor->nome.'</option>';
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>

                        </div>


                        <h2 class="h2">Dias e Horários de Aula</h2>
                        <div class="espaco20"></div>


                        <div class="margin-right-10">
                            <div class="float-left coluna-4 margin-right-10">
                                <td data-title="Status">
                                    <div class="pmd-switch">
                                        <label>
                                        Segunda-Feira
                                        <div class="espaco"></div>
                                        <?php echo $registro->segunda == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">' ?>
                                        <span class="pmd-switch-label dia-semana" registro="<?php echo $registro->id ?>" dia="segunda"></span>
                                        </label>
                                    </div>
                                </td>
                            </div>

                            <div class="horarios-aula">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 loat-left">
                                    <label for="regular1" class="control-label">Horário de Início</label>
                                    <input type="text" name="hora_inicio_segunda" id="hora_inicio_segunda" value="<?php echo $registro->hora_inicio_segunda; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Horário de Término</label>
                                    <input type="text" name="hora_termino_segunda" id="hora_termino_segunda" value="<?php echo $registro->hora_termino_segunda; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <?php
                                if(!empty($registro->hora_inicio_segunda) && !empty($registro->hora_termino_segunda)):
                                    //$duracao = date('H:i:s',(strtotime($registro->hora_inicio) - strtotime($registro->hora_termino)));

                                    if(!empty($registro->hora_inicio_segunda) && !empty($registro->hora_termino_segunda)):
                                        $duracao_segunda = intervalo($registro->hora_inicio_segunda, $registro->hora_termino_segunda);
                                    endif;

                                endif;
                                ?>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Duração</label>
                                    <input type="text" name="duracao" id="duracao" value="<?php echo $duracao_segunda; ?>" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                        </div>
                        <div class="espaco20"></div>

                        <div class="margin-right-10">
                            <div class="float-left coluna-4 margin-right-10">
                                <td data-title="Status">
                                    <div class="pmd-switch">
                                        <label>
                                        Terça-Feira
                                        <div class="espaco"></div>
                                        <?php echo $registro->terca == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">' ?>
                                        <span class="pmd-switch-label dia-semana" registro="<?php echo $registro->id ?>" dia="terca"></span>
                                        </label>
                                    </div>
                                </td>
                            </div>


                            <div class="horarios-aula">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 loat-left">
                                    <label for="regular1" class="control-label">Horário de Início</label>
                                    <input type="text" name="hora_inicio_terca" id="hora_inicio_terca" value="<?php echo $registro->hora_inicio_terca; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Horário de Término</label>
                                    <input type="text" name="hora_termino_terca" id="hora_termino_terca" value="<?php echo $registro->hora_termino_terca; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <?php
                                if(!empty($registro->hora_inicio_terca) && !empty($registro->hora_termino_terca)):
                                    //$duracao = date('H:i:s',(strtotime($registro->hora_inicio) - strtotime($registro->hora_termino)));

                                    if(!empty($registro->hora_inicio_terca) && !empty($registro->hora_termino_terca)):
                                        $duracao_terca = intervalo($registro->hora_inicio_terca, $registro->hora_termino_terca);
                                    endif;

                                endif;
                                ?>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Duração</label>
                                    <input type="text" name="duracao_terca" id="duracao_terca" value="<?php echo $duracao_terca; ?>" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                        </div>
                        <div class="espaco20"></div>

                        <div class="margin-right-10">
                            <div class="float-left coluna-4 margin-right-10">
                                <td data-title="Status">
                                    <div class="pmd-switch">
                                        <label>
                                        Quarta-Feira
                                        <div class="espaco"></div>
                                        <?php echo $registro->quarta == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">' ?>
                                        <span class="pmd-switch-label dia-semana" registro="<?php echo $registro->id ?>" dia="quarta"></span>
                                        </label>
                                    </div>
                                </td>
                            </div>


                            <div class="horarios-aula">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 loat-left">
                                    <label for="regular1" class="control-label">Horário de Início</label>
                                    <input type="text" name="hora_inicio_quarta" id="hora_inicio_quarta" value="<?php echo $registro->hora_inicio_quarta; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Horário de Término</label>
                                    <input type="text" name="hora_termino_quarta" id="hora_termino_quarta" value="<?php echo $registro->hora_termino_quarta; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <?php
                                if(!empty($registro->hora_inicio_quarta) && !empty($registro->hora_termino_quarta)):
                                    //$duracao = date('H:i:s',(strtotime($registro->hora_inicio) - strtotime($registro->hora_termino)));

                                    if(!empty($registro->hora_inicio_quarta) && !empty($registro->hora_termino_quarta)):
                                        $duracao_quarta = intervalo($registro->hora_inicio_quarta, $registro->hora_termino_quarta);
                                    endif;

                                endif;
                                ?>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Duração</label>
                                    <input type="text" name="duracao_quarta" id="duracao_quarta" value="<?php echo $duracao_quarta; ?>" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                        </div>
                        <div class="espaco20"></div>

                        <div class="margin-right-10">
                            <div class="float-left coluna-4 margin-right-10">
                                <td data-title="Status">
                                    <div class="pmd-switch">
                                        <label>
                                        Quinta-Feira
                                        <div class="espaco"></div>
                                        <?php echo $registro->quinta == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">' ?>
                                        <span class="pmd-switch-label dia-semana" registro="<?php echo $registro->id ?>" dia="quinta"></span>
                                        </label>
                                    </div>
                                </td>
                            </div>


                            <div class="horarios-aula">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 loat-left">
                                    <label for="regular1" class="control-label">Horário de Início</label>
                                    <input type="text" name="hora_inicio_quinta" id="hora_inicio_quinta" value="<?php echo $registro->hora_inicio_quinta; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Horário de Término</label>
                                    <input type="text" name="hora_termino_quinta" id="hora_termino_quinta" value="<?php echo $registro->hora_termino_quinta; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <?php
                                if(!empty($registro->hora_inicio_quinta) && !empty($registro->hora_termino_quinta)):
                                    //$duracao = date('H:i:s',(strtotime($registro->hora_inicio) - strtotime($registro->hora_termino)));

                                    if(!empty($registro->hora_inicio_quinta) && !empty($registro->hora_termino_quinta)):
                                        $duracao_quinta = intervalo($registro->hora_inicio_quinta, $registro->hora_termino_quinta);
                                    endif;

                                endif;
                                ?>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Duração</label>
                                    <input type="text" name="duracao_quinta" id="duracao_quinta" value="<?php echo $duracao_quinta; ?>" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                        </div>
                        <div class="espaco20"></div>

                        <div class="margin-right-10">
                            <div class="float-left coluna-4 margin-right-10">
                                <td data-title="Status">
                                    <div class="pmd-switch">
                                        <label>
                                        Sexta-Feira
                                        <div class="espaco"></div>
                                        <?php echo $registro->sexta == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">' ?>
                                        <span class="pmd-switch-label dia-semana" registro="<?php echo $registro->id ?>" dia="sexta"></span>
                                        </label>
                                    </div>
                                </td>
                            </div>


                            <div class="horarios-aula">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 loat-left">
                                    <label for="regular1" class="control-label">Horário de Início</label>
                                    <input type="text" name="hora_inicio_sexta" id="hora_inicio_sexta" value="<?php echo $registro->hora_inicio_sexta; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Horário de Término</label>
                                    <input type="text" name="hora_termino_sexta" id="hora_termino_sexta" value="<?php echo $registro->hora_termino_sexta; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <?php
                                if(!empty($registro->hora_inicio) && !empty($registro->hora_termino)):
                                    //$duracao = date('H:i:s',(strtotime($registro->hora_inicio) - strtotime($registro->hora_termino)));

                                    if(!empty($registro->hora_inicio_sexta) && !empty($registro->hora_termino_sexta)):
                                        $duracao_sexta = intervalo($registro->hora_inicio_sexta, $registro->hora_termino_sexta);
                                    endif;

                                endif;
                                ?>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Duração</label>
                                    <input type="text" name="duracao_sexta" id="duracao_sexta" value="<?php echo $duracao_sexta; ?>" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                        </div>
                        <div class="espaco20"></div>

                        <div class="margin-right-10">
                            <div class="float-left coluna-4 margin-right-10">
                                <td data-title="Status">
                                    <div class="pmd-switch">
                                        <label>
                                        Sábado
                                        <div class="espaco"></div>
                                        <?php echo $registro->sabado == 's' ? '<input type="checkbox" checked>' : '<input type="checkbox">' ?>
                                        <span class="pmd-switch-label dia-semana" registro="<?php echo $registro->id ?>" dia="sabado"></span>
                                        </label>
                                    </div>
                                </td>
                            </div>


                            <div class="horarios-aula">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 loat-left">
                                    <label for="regular1" class="control-label">Horário de Início</label>
                                    <input type="text" name="hora_inicio_sabado" id="hora_inicio_sabado" value="<?php echo $registro->hora_inicio_sabado; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Horário de Término</label>
                                    <input type="text" name="hora_termino_sabado" id="hora_termino_sabado" value="<?php echo $registro->hora_termino_sabado; ?>" class="form-control" ><span class="pmd-textfield-focused"></span>
                                </div>

                                <?php
                                if(!empty($registro->hora_inicio_sabado) && !empty($registro->hora_termino_sabado)):
                                    //$duracao = date('H:i:s',(strtotime($registro->hora_inicio) - strtotime($registro->hora_termino)));

                                    if(!empty($registro->hora_inicio_sabado) && !empty($registro->hora_termino_sabado)):
                                        $duracao_sabado = intervalo($registro->hora_inicio_sabado, $registro->hora_termino_sabado);
                                    endif;

                                endif;
                                ?>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 float-left">
                                    <label for="regular1" class="control-label">Duração</label>
                                    <input type="text" name="duracao_sabado" id="duracao_sabado" value="<?php echo $duracao_sabado; ?>" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                                </div>
                            </div>
                        </div>
                        <div class="espaco20"></div>

                        <h2 class="h2">Datas de Início e Término</h2>
                        <div class="espaco20"></div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                            <label for="regular1" class="control-label">Data de Início</label>
                            <input type="text" name="data_inicio" id="data_inicio" value="<?php echo !empty($registro->data_inicio) ? $registro->data_inicio->format('d/m/Y') : ''; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                            <label for="regular1" class="control-label">Data de Término</label>
                            <input type="text" name="data_termino" id="data_termino" value="<?php echo !empty($registro->data_termino) ? $registro->data_termino->format('d/m/Y') : ''; ?>" class="form-control" readonly><span class="pmd-textfield-focused"></span>
                        </div>

                        <div class="espaco20"></div>

                        <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised oculto">Salvar</button>
                        <button type="submit" name="alterar-estagio" id="alterar-estagio" value="Alterar Estágio" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised oculto">Mudar Estágio</button>
                        <div class="espaco20"></div>

                        <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-mudanca-estagio-modal" data-target="#mudanca-estagio-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-dias-modal" data-target="#dias-dialog" data-toggle="modal"></div>

                    </form>

                </div>
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <div role="tabpanel" class="tab-pane" id="integrantes">

                    <form action="" name="formIntegrantes" id="formIntegrantes" method="post">

                        <?php include_once('listagem-integrantes.php'); ?>

                    </form>

                </div>
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <div role="tabpanel" class="tab-pane" id="atas">

                    <form action="" name="formIntegrantes" id="formIntegrantes" method="post">

                        <div id="listagem-atas-turma">
                            <?php include_once('listagem-atas-turma.php'); ?>
                        </div>

                    </form>

                </div>
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

            </div>

        </div>
    </div>
    <div class="espaco20"></div>
    <!-- Final Abas -->
    <!-- --------------------------------------------------------------------------------------------------- -->

    <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

</section>

<script type="text/javascript">
    $("#data_inicio").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>