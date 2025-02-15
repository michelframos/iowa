<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $registro = Colegas::find($id);
?>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Este nome de Prova para este Idioma já existe.</p>
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


<div tabindex="-1" class="modal fade" id="cpf-invalido-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">CPF Inválido!</h2>
            </div>
            <div class="modal-body">
                <p>O CPF informado é inválido.</p>
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

<script src="js/colegas.js"></script>

<!-- Start Content -->
    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">assignment_ind</i>
        <h1>Cadastro / Alteração de Colega IOWA</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
        <div class="espaco20"></div>

        <form action="" name="formDados" id="formDados" method="post">

            <!-- --------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Abas -->
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-tabs pmd-tabs-bg">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#dados-cadastrais" aria-controls="home" role="tab" data-toggle="tab">Dados Cadastrais</a></li>
                        <li role="presentation"><a href="#banco" aria-controls="about" role="tab" data-toggle="tab">Dados Bancários</a></li>
                        <li role="presentation"><a href="#funcao" aria-controls="about" role="tab" data-toggle="tab">Função</a></li>
                    </ul>
                </div>

                <div class="pmd-card-body">
                    <div class="tab-content">

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane active" id="dados-cadastrais">

                            <div style="max-width: 800px;">


                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Unidade</label>
                                    <select name="unidade" id="unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
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

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Apelido</label>
                                    <input type="text" name="apelido" id="apelido" value="<?php echo $registro->apelido; ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Nome</label>
                                    <input type="text" name="nome" id="nome" value="<?php echo $registro->nome; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                    <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
                                        <label for="regular1" class="control-label">RG</label>
                                        <input type="text" name="rg" id="rg" value="<?php echo $registro->rg; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                    </div>

                                    <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3 margin-right-10">
                                        <label for="regular1" class="control-label">CPF</label>
                                        <input type="text" name="cpf" id="cpf" value="<?php echo $registro->cpf; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                    </div>

                                    <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
                                        <label for="regular1" class="control-label">Data Nascimento</label>
                                        <input type="text" name="data_nascimento" id="data_nascimento" value="<?php echo !empty($registro->data_nascimento) ? $registro->data_nascimento->format('d/m/Y') : ''; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                    </div>
                                <div class="clear"></div>

                                    <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
                                        <label for="regular1" class="control-label">Telefone</label>
                                        <input type="text" name="telefone" id="telefone" value="<?php echo $registro->telefone; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                    </div>

                                    <div class="form-group pmd-textfield pmd-textfield-floating-label float-left coluna-3">
                                        <label for="regular1" class="control-label">Celular</label>
                                        <input type="text" name="celular" id="celular" value="<?php echo $registro->celular; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                    </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Email</label>
                                    <input type="text" name="email" id="email" value="<?php echo $registro->email; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <label for="regular1" class="control-label">CEP</label>
                                    <input type="text" name="cep" id="cep" value="<?php echo $registro->cep; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 float-left">
                                    <button type="button" name="busca-cep" id="busca-cep" value="Buscar Endereço" class="btn btn-info pmd-btn-raised">Buscar Endereço</button>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Endereço</label>
                                    <input type="text" name="endereco" id="endereco" value="<?php echo $registro->endereco; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Número</label>
                                    <input type="text" name="numero" id="numero" value="<?php echo $registro->numero; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" value="<?php echo $registro->bairro; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Complemento</label>
                                    <input type="text" name="complemento" id="complemento" value="<?php echo $registro->complemento; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                <div class="clear"></div>

                                <div class="coluna-3 float-left margin-right-5">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                        <label>Estado</label>
                                        <select name="estado" id="estado" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <?php
                                            $estados = Estados::all();
                                            if(!empty($estados)):
                                                foreach($estados as $estado):
                                                    echo $registro->estado == $estado->estado_id ? '<option selected value="'.$estado->estado_id.'">'.$estado->uf.'</option>' : '<option value="'.$estado->estado_id.'">'.$estado->uf.'</option>';
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>

                                <div class="coluna-3 float-left">
                                    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                        <label>Cidade</label>
                                        <select name="cidade" id="cidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                        </select>
                                        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                    </div>
                                </div>
                                <div class="clear"></div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Data Admissão</label>
                                    <input type="text" name="data_admissao" id="data_admissao" value="<?php echo !empty($registro->data_admissao) ? $registro->data_admissao->format('d/m/Y') : ''; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="clear"></div>
                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="banco">

                            <div style="max-width: 800px;">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Banco</label>
                                    <input type="text" name="banco" id="banco" value="<?php echo $registro->banco; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Agência</label>
                                    <input type="text" name="agencia" id="agencia" value="<?php echo $registro->agencia; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Conta</label>
                                    <input type="text" name="conta" id="conta" value="<?php echo $registro->conta; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="clear"></div>
                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                        <!-- --------------------------------------------------------------------------------------- -->
                        <!-- Conteúdo de Uma Aba -->
                        <div role="tabpanel" class="tab-pane" id="funcao">

                            <div style="max-width: 800px;">

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Função</label>
                                    <select name="funcao" id="funcao" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <option></option>
                                        <?php
                                        $funcoes = Funcoes::all(array('order' => 'funcao asc'));
                                        if(!empty($funcoes)):
                                            foreach($funcoes as $funcao):
                                                echo $registro->id_funcao == $funcao->id ? '<option selected value="'.$funcao->id.'">'.$funcao->funcao.'</option>' : '<option value="'.$funcao->id.'">'.$funcao->funcao.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>

                                <div id="dados1">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Contábil</label>
                                    <input type="text" name="adm_contabil" id="adm_contabil" value="<?php echo $registro->adm_contabil; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Valor IOWA</label>
                                    <input type="text" name="adm_valor_iowa" id="adm_valor_iowa" value="<?php echo $registro->adm_valor_iowa; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>
                                </div>

                                <div id="dados2">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                    <label for="regular1" class="control-label">Contábil</label>
                                    <input type="text" name="choach_valor_hora" id="choach_valor_hora" value="<?php echo $registro->choach_valor_hora; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Coachs</label>
                                    <select name="coach_id_choach" id="coach_id_choach" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <option></option>
                                        <?php
                                        $coachs = Colegas::all(array('conditions' => array('id_funcao = ? and (status = ? or id = ?)', 2,'a', $registro->coach_id_choach), 'order' => 'nome asc'));
                                        if(!empty($coachs)):
                                            foreach($coachs as $coach):
                                                echo $coach->id == $registro->coach_id_choach ? '<option selected value="'.$coach->id.'">'.$coach->nome.'</option>' : '<option value="'.$coach->id.'">'.$coach->nome.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>
                                </div>

                                <div id="dados3">
                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Categoria do Instrutor</label>
                                    <select name="instrutor_categoria" id="instrutor_categoria" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <option></option>
                                        <?php
                                        $categorias = Categorias_Instrutor::all();
                                        if(!empty($categorias)):
                                            foreach($categorias as $categoria):
                                                echo $registro->instrutor_categoria == $categoria->categoria ? '<option selected value="'.$categoria->categoria.'">'.$categoria->categoria.'</option>' : '<option value="'.$categoria->categoria.'">'.$categoria->categoria.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>

                                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                    <label>Coachs</label>
                                    <select name="instrutor_id_coach" id="instrutor_id_coach" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                        <option></option>
                                        <?php
                                        $coachs = Colegas::all(array('conditions' => array('id_funcao = ? and (status = ? or id = ?)', 2,'a', $registro->instrutor_id_coach), 'order' => 'nome asc'));
                                        if(!empty($coachs)):
                                            foreach($coachs as $coach):
                                                echo $coach->id == $registro->instrutor_id_coach ? '<option selected value="'.$coach->id.'">'.$coach->nome.'</option>' : '<option value="'.$coach->id.'">'.$coach->nome.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
                                </div>
                                </div>

                                <div class="clear"></div>
                            </div>

                        </div>
                        <!-- Conteúdo de Uma Aba -->
                        <!-- --------------------------------------------------------------------------------------- -->

                    </div>
                </div>

            </div>
            <div class="espaco20"></div>
            <!-- Final Abas -->
            <!-- --------------------------------------------------------------------------------------------------- -->

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-cpf-invalido-modal" data-target="#cpf-invalido-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

        </form>

    </section>

<script>

    $(function(){

        $('#data_admissao').mask('00/00/0000');
        $("#data_admissao").datetimepicker({
            format: "DD/MM/YYYY"
        });

        <?php if(!empty($registro->estado)): ?>
        $.post('../includes/lista-cidades.php', {estado: <?php echo $registro->estado ?>}, function(data){

            $('#cidade').html(data);
            $('#cidade option[value="'+<?php echo $registro->cidade ?>+'"]').prop("selected", true);

        });
        <?php endif; ?>

        $('#estado').change(function(){

            $.post('../includes/lista-cidades.php', {estado: $('#estado').val()}, function(data){

                $('#cidade').html(data);

            });
        });

        <?php if(!empty($registro->id_funcao)): ?>
        $('#dados'+<?php echo $registro->id_funcao ?>).show();
        <?php endif; ?>

    });
</script>