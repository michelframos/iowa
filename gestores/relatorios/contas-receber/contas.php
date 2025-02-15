<?php
    include_once('../../../config.php');
    include_once('../../funcoes_painel.php');

    /*Verificando Permissões*/
    verificaPermissao(idUsuario(), 'Relatório - Contas a Receber', 'c', 'index');

?>
<!--<script src="js/jQuery.print.min.js"></script>-->
<script src="js/jquery-printme.min.js"></script>
<script src="js/rel-contas-receber.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">description</i>
    <h1>Relatório de Contas a Receber</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">

    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Inicio Abas -->
    <div class="pmd-card pmd-z-depth">
        <div class="pmd-tabs pmd-tabs-bg">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#parcelas" aria-controls="home" role="tab" data-toggle="tab">Parcelas</a></li>
                <!--
                <li role="presentation"><a href="#datas" aria-controls="about" role="tab" data-toggle="tab">Datas</a></li>
                <li role="presentation"><a href="#sacado" aria-controls="about" role="tab" data-toggle="tab">Sacado</a></li>
                -->
            </ul>
        </div>

        <div class="pmd-card-body">
            <div class="tab-content">

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <div role="tabpanel" class="tab-pane active" id="parcelas">

                    <!-- Situação da Parcela -->
                    <div style="width: 100%;">
                        <div class="float-left margin-right-5">
                            <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                                <input type="checkbox" name="a_receber" value="a_receber">
                                <span>A Receber</span>
                            </label>
                        </div>

                        <div class="float-left margin-right-5">
                            <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                                <input type="checkbox" name="recebidas" value="recebidas">
                                <span>Recebidas</span>
                            </label>
                        </div>

                        <div class="float-left margin-right-5">
                            <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                                <input type="checkbox" name="canceladas" value="canceladas">
                                <span>Canceladas</span>
                            </label>
                        </div>

                        <!--
                        <div class="float-left margin-right-5">
                            <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
                                <input type="checkbox" name="vencidas" value="vencidas">
                                <span>Vencidas</span>
                            </label>
                        </div>
                        -->

                    </div>
                    <div class="espaco20"></div>

                    <!-- Tipo de Cobrança -->
                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                        <label>Forma de Recebimento</label>
                        <select name="forma_pagamento" id="forma_pagamento" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option value="%">Todas</option>
                            <?php
                            $formas_pagamento = Formas_Pagamento::all(array('order' => 'forma_pagamento asc'));
                            if(!empty($formas_pagamento)):
                                foreach($formas_pagamento as $forma):
                                    echo '<option value="'.$forma->id.'">'.$forma->forma_pagamento.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <!-- Categoria -->
                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                        <label>Categoria da Parcela</label>
                        <select name="motivo" id="motivo" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option value="%">Todas</option>
                            <option value="Parcelas">Parcelas</option>
                            <?php
                            $motivos = Motivos_Parcela::all(array('order' => 'motivo asc'));
                            if(!empty($motivos)):
                                foreach($motivos as $motivo):
                                    echo '<option value="'.$motivo->id.'">'.$motivo->motivo.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                        <label>Unidades</label>
                        <select name="unidade" id="unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value="%"></option>

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

                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                        <label>Turma</label>
                        <select name="id_turma" id="id_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option value="%">Selecione uma Turma</option>
                            <?php
                            /*
                            $turmas = Turmas::all(array('order' => 'nome asc'));
                            if(!empty($turmas)):
                                foreach($turmas as $turma):
                                    echo '<option matricula="'.$matricula->id.'" value="'.$turma->id.'">'.$turma->nome.'</option>';
                                endforeach;
                            endif;
                            */
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>

                    <!--
                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
                        <label>Idioma</label>
                        <select name="id_idioma" id="id_idioma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
                            <option value="%">Todos</option>
                            <?php
                            $idiomas = Idiomas::all(array('order' => 'idioma asc'));
                            if(!empty($idiomas)):
                                foreach($idiomas as $idioma):
                                    echo '<option value="'.$idioma->id.'">'.$idioma->idioma.'</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                    </div>
                    -->
                    <div class="clear"></div>

                    <div class="coluna-metade">
                        <label>Vencimento Entre</label>
                        <div class="clear"></div>

                        <div class="margin-right-10 coluna2">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Data Inicio</label>
                                <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                            </div>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                            <label for="regular1" class="control-label">Data Final</label>
                            <input type="text" name="data_final" id="data_final" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div class="coluna-metade">
                        <label>Pagamento Entre</label>
                        <div class="clear"></div>

                        <div class="margin-right-10 coluna2">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Data Inicio</label>
                                <input type="text" name="data_inicial_pagamento" id="data_inicial_pagamento" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                            </div>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                            <label for="regular1" class="control-label">Data Final</label>
                            <input type="text" name="data_final_pagamento" id="data_final_pagamento" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                    <div class="clear"></div>

                    <div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                            <label>Sacado</label>
                            <select name="tipo_sacado" id="tipo_sacado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value="">Todos</option>
                                <option value="aluno">Aluno</option>
                                <option value="empresa">Cliente</option>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>


                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                            <label>Situação do Aluno</label>
                            <select name="situacao_aluno" id="situacao_aluno" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value="%">Todos</option>
                                <option value="a">Ativos</option>
                                <option value="i">Inativos</option>
                                <option value="s">Stand By</option>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>


                        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
                            <label for="regular1" class="control-label">Nome do Aluno</label>
                            <select name="nome_aluno" id="nome_aluno" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php
                                $alunos = Alunos::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome asc'));
                                if(!empty($alunos)):
                                    foreach ($alunos as $aluno):
                                        echo '<option value="'.$aluno->nome.'">'.$aluno->nome.'</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <!-- <input type="text" name="nome_aluno" id="nome_aluno" value="" class="form-control"><span class="pmd-textfield-focused"></span> -->
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                            <label>Empresa</label>
                            <select name="id_empresa" id="id_empresa" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value="%">Todas</option>
                                <?php
                                $empresas = Empresas::all(array('order' => 'nome_fantasia asc'));
                                if(!empty($empresas)):
                                    foreach($empresas as $empresa):
                                        echo '<option value="'.$empresa->id.'">'.$empresa->nome_fantasia.'</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="clear"></div>

                        <div class="clear"></div>
                    </div>

                </div>
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <!--
                <div role="tabpanel" class="tab-pane" id="datas">

                    <div class="coluna-metade">
                        <label>Vencimento Entre</label>
                        <div class="clear"></div>

                        <div class="margin-right-10 coluna2">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Data Inicio</label>
                                <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                            </div>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                            <label for="regular1" class="control-label">Data Final</label>
                            <input type="text" name="data_final" id="data_final" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div class="coluna-metade">
                        <label>Pagamento Entre</label>
                        <div class="clear"></div>

                        <div class="margin-right-10 coluna2">
                            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                <label for="regular1" class="control-label">Data Inicio</label>
                                <input type="text" name="data_inicial_pagamento" id="data_inicial_pagamento" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                            </div>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
                            <label for="regular1" class="control-label">Data Final</label>
                            <input type="text" name="data_final_pagamento" id="data_final_pagamento" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                    <div class="clear"></div>

                </div>
                -->
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <!--
                <div role="tabpanel" class="tab-pane" id="sacado">

                    <div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                            <label>Sacado</label>
                            <select name="tipo_sacado" id="tipo_sacado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value="">Todos</option>
                                <option value="aluno">Aluno</option>
                                <option value="empresa">Cliente</option>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
                            <label for="regular1" class="control-label">Nome do Aluno</label>
                            <input type="text" name="nome_aluno" id="nome_aluno" value="" class="form-control"><span class="pmd-textfield-focused"></span>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                            <label>Situação do Aluno</label>
                            <select name="situacao_aluno" id="situacao_aluno" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value="%">Todas</option>
                                <option value="a">Ativos</option>
                                <option value="i">Inativos</option>
                                <option value="s">Stand By</option>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
                            <label>Empresa</label>
                            <select name="id_empresa" id="id_empresa" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option value="%">Todas</option>
                                <?php
                                $empresas = Empresas::all(array('order' => 'nome_fantasia asc'));
                                if(!empty($empresas)):
                                    foreach($empresas as $empresa):
                                        echo '<option value="'.$empresa->id.'">'.$empresa->nome_fantasia.'</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="clear"></div>

                        <div class="clear"></div>
                    </div>


                </div>
                -->
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

            </div>
        </div>

        <div class="espaco20"></div>

    </div>
    <div class="espaco20"></div>
    <!-- Final Abas -->
    <!-- --------------------------------------------------------------------------------------------------- -->


    <!--
    <form action="" name="formPesquisa" id="formPesquisa" method="post">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Unidades</label>
            <select name="unidade" id="unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value=""></option>

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
            <select name="turma" id="turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Selecione uma Unidade</option>

            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
            <label for="regular1" class="control-label">Nome do Aluno</label>
            <input type="text" name="nome" id="nome" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <label>Situação do Aluno</label>
            <select name="situacao" id="situacao" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <option value="a">Ativo</option>
                <option value="i">Inativo</option>
                <option value="s">Stand By</option>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
            <label for="regular1" class="control-label">Data Inicial</label>
            <input type="text" name="data_inicial" id="data_inicial" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
            <label for="regular1" class="control-label">Data Final</label>
            <input type="text" name="data_final" id="data_final" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>
        -->

        <button type="button" name="gerar-relatorio" id="gerar-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Gerar Relatório</button>

        <?php if(Permissoes::find_by_id_usuario_and_tela_and_imp(idUsuario(), 'Relatório - Contas a Receber', 's')): ?>
        <button type="button" name="imprimir-relatorio" id="imprimir-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Imprimir Relatório</button>
        <?php endif; ?>

        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

</section>

<section class="pmd-card pmd-z-depth padding-10">

    <div id="relatorio"></div>

</section>

<script type="text/javascript">
    $("#data_inicial, #data_final, #data_inicial_pagamento, #data_final_pagamento").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>