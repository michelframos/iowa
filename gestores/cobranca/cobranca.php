<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Geração de Cobrança', 'c', 'index');
?>

<script src="js/cobranca.js"></script>

<div tabindex="-1" class="modal fade" id="imprimir-gerar-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Imprimir/Gerar</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a Geração/Impressão de Boletos?</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-imprimir-gerar" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Sim</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="boletos-gerados-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Boletos Gerados</h2>
            </div>
            <div class="modal-body">
                <div id="lista-boletos">Os boletos foram gerados com sucesso. Você poderá administrá-los no menus Gestão de Boletos.</div>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-caixa-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Caixa Não Identificado!</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Não existe caixa aberto para o usuario logado! Por favor, abra o caixa antes de prossegir.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="retorno-importado-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Arquivo de Retorno Importado!</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Arquivo de retorno importado com sucesso. Confira o resultado da importação na aba RESULTADO RETORNOS.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="selecao-unidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Seleção de Unidade</h2>
            </div>
            <div class="modal-body">
                <p id="mensagem-modal">Selecione a Unidade para a qual você deseja emitir boletos.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="permissao-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Permissão Negada</h2>
            </div>
            <div class="modal-body">
                <p id="msg-permissao-dialog"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">confirmation_number</i>
        <h1>Geração de Cobrança</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <!-- --------------------------------------------------------------------------------------------------- -->
        <!-- Inicio Abas -->
        <div class="pmd-card pmd-z-depth">
            <div class="pmd-tabs pmd-tabs-bg">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#selecionar-parcelas" aria-controls="home" role="tab" data-toggle="tab">Selecionar Parcelas</a></li>
                    <li role="presentation"><a href="#gerar-cobranca" aria-controls="about" role="tab" data-toggle="tab">Gerar Cobrança</a></li>
                    <li role="presentation"><a href="#arquivos" aria-controls="about" role="tab" data-toggle="tab">Arquivos de Remessa</a></li>
                    <li role="presentation"><a href="#ler-retorno" aria-controls="about" role="tab" data-toggle="tab">Ler Retorno</a></li>
                    <li role="presentation"><a href="#resultado_retornos" aria-controls="about" role="tab" data-toggle="tab">Resultado Retornos</a></li>
                </ul>
            </div>

            <div class="pmd-card-body">
                <div class="tab-content">

                    <!-- --------------------------------------------------------------------------------------- -->
                    <!-- Conteúdo de Uma Aba -->
                    <div role="tabpanel" class="tab-pane active" id="selecionar-parcelas">

                        <form name="formParcelas" id="formParcelas" method="post">

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                <label>Unidade</label>
                                <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                    <option value="%">Todas</option>
                                    <?php
                                    $unidades = Unidades::all(array('order' => 'nome_fantasia asc'));
                                    if(!empty($unidades)):
                                        foreach($unidades as $unidade):
                                            echo '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>


                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                <label>Turma</label>
                                <select name="id_turma" id="id_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                    <option value="%">Todas</option>
                                    <?php
                                    $turmas = Turmas::all(array('conditions' => array('status = ?', 'a'),'order' => 'nome asc'));
                                    if(!empty($turmas)):
                                        foreach($turmas as $turma):
                                            echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>
                            <div class="clear"></div>

                            <div class="coluna-3">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                                    <label for="regular1" class="control-label">Vencimento Entre</label>
                                    <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                                    <label for="regular1" class="control-label">E</label>
                                    <input type="text" name="data_final" id="data_final" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                            </div>

                            <div class="coluna-3">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                                    <label for="regular1" class="control-label">Valor Entre</label>
                                    <input type="text" name="valor_inicial" id="valor_inicial" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                                    <label for="regular1" class="control-label">E</label>
                                    <input type="text" name="valor_final" id="valor_final" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                            </div>
                            <div class="clear"></div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                <label>Aluno</label>
                                <input type="text" name="nome" id="nome" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                <label>Sacado</label>
                                <select name="sacado" id="sacado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option value="">Todos</option>
                                    <option value="aluno">Aluno</option>
                                    <option value="empresa">Empresa</option>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                <label>Situação do Aluno</label>
                                <select name="situacao-aluno" id="situacao-aluno" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option value=""></option>
                                    <option value="a">Ativo</option>
                                    <option value="i">Inativo</option>
                                    <option value="s">Stand By</option>
                                    <?php
                                    /*
                                    $situacoes = Situacao_Aluno::all(array('order' => 'situacao asc'));
                                    if(!empty($situacoes)):
                                        foreach($situacoes as $situacao):
                                            echo $registro->id_situacao == $situacao->id ? '<option selected value="'.$situacao->id.'">'.$situacao->situacao.'</option>' : '<option value="'.$situacao->id.'">'.$situacao->situacao.'</option>';
                                        endforeach;
                                    endif;
                                    */
                                    ?>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>
                            <div class="clear"></div>

                            <button type="button" name="filtrar" id="filtrar" value="Filtrar" class="btn btn-info pmd-btn-raised">Filtrar</button>
                            <div class="espaco20"></div>

                        </form>

                        <div id="lista-selecionar-parcelas">
                            <?php include_once('selecionar-parcelas.php'); ?>
                        </div>

                    </div>
                    <!-- Conteúdo de Uma Aba -->
                    <!-- --------------------------------------------------------------------------------------- -->

                    <!-- --------------------------------------------------------------------------------------- -->
                    <!-- Conteúdo de Uma Aba -->
                    <div role="tabpanel" class="tab-pane" id="gerar-cobranca">

                        <?php include_once('gerar-cobranca.php'); ?>

                    </div>
                    <!-- Conteúdo de Uma Aba -->
                    <!-- --------------------------------------------------------------------------------------- -->

                    <!-- --------------------------------------------------------------------------------------- -->
                    <!-- Conteúdo de Uma Aba -->
                    <div role="tabpanel" class="tab-pane" id="arquivos">

                        <?php include_once('arquivos.php'); ?>

                    </div>
                    <!-- Conteúdo de Uma Aba -->
                    <!-- --------------------------------------------------------------------------------------- -->

                    <!-- --------------------------------------------------------------------------------------- -->
                    <!-- Conteúdo de Uma Aba -->
                    <div role="tabpanel" class="tab-pane" id="ler-retorno">

                        <?php include_once('ler-retorno.php'); ?>

                    </div>
                    <!-- Conteúdo de Uma Aba -->
                    <!-- --------------------------------------------------------------------------------------- -->

                    <!-- --------------------------------------------------------------------------------------- -->
                    <!-- Conteúdo de Uma Aba -->
                    <div role="tabpanel" class="tab-pane" id="resultado_retornos">

                        <?php include_once('resultado_retornos.php'); ?>

                    </div>
                    <!-- Conteúdo de Uma Aba -->
                    <!-- --------------------------------------------------------------------------------------- -->

                </div>
            </div>

        </div>

        <div class="oculto" id="ms-boletos-gerados-dialog" data-target="#boletos-gerados-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-erro-caixa-modal" data-target="#erro-caixa-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-retorno-importado-dialog" data-target="#retorno-importado-dialog" data-toggle="modal"></div>
        <div class="oculto" id="ms-selecao-unidade-dialog" data-target="#selecao-unidade-dialog" data-toggle="modal"></div>

    </section>

<script type="text/javascript">

    $("#data_inicial, #data_final").datetimepicker({
        format: "DD/MM/YYYY"
    });

</script>