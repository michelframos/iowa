<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Gestão de Boletos', 'c', 'index');
?>

<script src="js/boletos.js"></script>

<div tabindex="-1" class="modal fade" id="enviar-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Boletos</h2>
            </div>
            <div class="modal-body">
                <p>Selecione ao menos um boleto para enviar por email.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="emails-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Envio de Boletos</h2>
            </div>
            <div class="modal-body">
                <p>Enviando boletos por email. Por favor, aguarde...</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary oculto" id="bt-terminou-envio" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="boletos-enviados-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Envio de Boletos</h2>
            </div>
            <div class="modal-body">
                <p>Boletos enviados com sucesso.</p>
                <div id="erros-envio"></div>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="erro-envio-boletos-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Erro - Envio de Boletos</h2>
            </div>
            <div class="modal-body">
                <p>Ops! Algo deu errado no envio de boletos via email. Por favor verifique as configurações de email em GERAL > Configurações de Envio de Emails e tente novamente.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="boleto-gerado-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Boleto gerado</h2>
            </div>
            <div class="modal-body">
                <p>A nova parcela e boleto foram gerados com sucesso.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" id="bt-gerado" type="button">OK</button>
            </div>
        </div>
    </div>
</div>



<div tabindex="-1" class="modal fade" id="excluir-boleto" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão de Boleto</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão deste boleto? Esta ação é irreversível!</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" id="bt-excluir-boleto" boleto="" type="button">Excluir</button>
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="selecao-boletos" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Seleção de Boleto(s)</h2>
            </div>
            <div class="modal-body">
                <p>Selecione um ou mais boletos antes de excluir.</p>
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
        <h1>Gestão de Boletos</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <!-- --------------------------------------------------------------------------------------------------- -->
        <!-- Inicio Abas -->
        <div class="pmd-card pmd-z-depth">
            <div class="pmd-tabs pmd-tabs-bg">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a id="tab-selecionar-parcelas" href="#selecionar-parcelas" aria-controls="home" role="tab" data-toggle="tab">Selecionar Parcelas</a></li>
                    <li role="presentation"><a href="#arquivos" aria-controls="about" role="tab" data-toggle="tab">Arquivos de Remessa</a></li>
                    <li role="presentation" class="oculto"><a id="tab-renegociar" href="#renegociar" aria-controls="home" role="tab" data-toggle="tab"></a></li>
                </ul>
            </div>

            <div class="pmd-card-body">
                <div class="tab-content">

                    <!-- --------------------------------------------------------------------------------------- -->
                    <!-- Conteúdo de Uma Aba -->
                    <div role="tabpanel" class="tab-pane active" id="selecionar-parcelas">

                        <form name="formParcelas" id="formParcelas" method="post">

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

                            <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                                <label>Sacado</label>
                                <select name="sacado" id="sacado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                                    <option value="">Todos</option>
                                    <option value="aluno">Aluno</option>
                                    <option value="empresa">Empresa</option>
                                </select>
                                <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                            </div>
                            <div class="clear"></div>

                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Pesquisar</label>
                                <input type="text" name="valor_pesquisa" id="valor_pesquisa" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                            </div>

                            <button type="button" name="filtrar" id="filtrar" value="Filtrar" class="btn btn-info pmd-btn-raised">Filtrar</button>
                            <button type="button" name="enviar" id="enviar" value="Enviar" class="btn btn-info pmd-btn-raised">Enviar Selecionados Por Email</button>
                            <button type="button" name="excluir" id="excluir" value="Enviar" class="btn btn-danger pmd-btn-raised" data-target="#excluir-boleto" data-toggle="modal">Excluir Boleto(s)</button>
                            <div class="espaco20"></div>

                        </form>

                        <div id="lista-selecionar-parcelas">
                            <?php include_once('selecionar-parcelas.php'); ?>
                        </div>

                        <div class="oculto" id="ms-enviar-dialog" data-target="#enviar-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-emails-dialog" data-target="#emails-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-boletos-enviados-dialog" data-target="#boletos-enviados-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-erro-envio-boletos-dialog" data-target="#erro-envio-boletos-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-boleto-gerado-dialog" data-target="#boleto-gerado-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>
                        <div class="oculto" id="ms-selecao-boletos" data-target="#selecao-boletos" data-toggle="modal"></div>

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
                    <div role="tabpanel" class="tab-pane" id="renegociar">

                        <form name="formParcelas" id="formParcelas" method="post">

                            <button type="button" class="btn btn-danger pmd-btn-raised" id="voltar"> Voltar </button >

                            <div id="box-renegociar">
                                <?php include_once('renegociar.php') ?>
                            </div>

                        </form>

                    </div>
                    <!-- Conteúdo de Uma Aba -->
                    <!-- --------------------------------------------------------------------------------------- -->

                </div>
            </div>

        </div>

    </section>

<!--
<script>

        $("#data_inicial, #data_final, #data_vencimento").datetimepicker({
            format: "DD/MM/YYYY"
        });

        $('#valor_parcela').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

</script>
-->