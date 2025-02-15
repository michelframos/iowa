<?php
    try{
        $registro = Opcoes_Cobranca::find(1);
    } catch(\ActiveRecord\RecordNotFound $e){
        $registro = '';
    }
?>

<form name="formGerarCobranca" id="formGerarCobranca" method="post">

    <div class="form-group pmd-textfield coluna-3 float-left">
        <label>Unidade</label>
        <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
            <option value=""></option>
            <?php
            $unidades = Unidades::all(['order' => 'nome_fantasia asc']);
            if(!empty($unidades)):
                foreach ($unidades as $unidade):
                    echo '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield coluna-3 float-left">
        <label>Banco</label>
        <select name="codigo_banco" id="codigo_banco" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
            <option value=""></option>
            <?php
            $bancos = BancosModel::all(['order' => 'nome asc']);
            if(!empty($bancos)):
                foreach ($bancos as $banco):
                    echo '<option value="'.$banco->codigo.'">'.$banco->nome.'</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield coluna-3 float-left">
        <label>Ação</label>
        <select name="tipo_acao" id="tipo_acao" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
            <!--<option <?php echo $registro->tipo_acao == 'imprimir' ? 'selected' : ''; ?> value="imprimir">Imprimir Boletos</option>-->
            <option <?php echo $registro->tipo_acao == 'arquivo_cnab' ? 'selected' : ''; ?>  value="arquivo_cnab">Gerar Arquivo CNAB</option>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>
    <div class="clear"></div>

    <h2 class="titulo size-1-5">Opções/Observações de Impressão/Geração</h2>
    <div class="clear"></div>

    <div class="coluna-3">
        <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
            <input type="checkbox" <?php echo $registro->iniciar_sequencia == 's' ? 'checked' : ''; ?> name="iniciar_sequencia" id="iniciar_sequencia">
            <span></span>
            Imprimir a partir do boleto Nº:
        </label>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Nº Inicial</label>
            <input type="text" name="numero_inicial" id="numero_inicial" value="<?php echo $registro->numero_inicial; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>
    </div>

    <div class="coluna-3">
        <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
            <input type="checkbox" <?php echo $registro->quantidade_maxima == 's' ? 'checked' : ''; ?> name="quantidade_maxima" id="quantidade_maxima">
            <span></span>
            Quantidade máxima de títulos a serem gerados:
        </label>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Quantidade</label>
            <input type="text" name="quantidade" id="quantidade" value="<?php echo $registro->quantidade; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>
    </div>

    <div class="coluna-3">
        <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
            <input type="checkbox" <?php echo $registro->adicionar_taxa == 's' ? 'checked' : ''; ?> name="adicionar_taxa" id="adicionar_taxa">
            <span></span>
            Adicionar taxa ao boleto:
        </label>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Taxa</label>
            <input type="text" name="taxa" id="taxa" value="<?php echo number_format($registro->taxa, 2, ',', '.'); ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>

    <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
        <input type="checkbox" <?php echo $registro->discriminar_observacao == 's' ? 'checked' : ''; ?> name="discriminar_observacao" id="discriminar_observacao">
        <span></span>
        Discriminar no campo 'Observações' as parcelas que foram cobradas
    </label>
    <div class="clear"></div>

    <label class="checkbox-inline pmd-checkbox pmd-checkbox-ripple-effect">
        <input type="checkbox" <?php echo $registro->imprimir_endereco == 's' ? 'checked' : ''; ?> name="imprimir_endereco" id="imprimir_endereco">
        <span></span>
        Imprimir endereço do sacado no topo do boleto (Boleto A4 duas vias)
    </label>
    <div class="clear"></div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
        <label for="regular1" class="control-label">Instrução que antecede o valor da multa por atraso: </label>
        <input type="text" name="instrucoes_atraso" id="instrucoes_atraso" value="<?php echo $registro->instrucoes_atraso; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
        <label for="regular1" class="control-label">Multa por atraso (%): </label>
        <input type="text" name="multa" id="multa" value="<?php echo number_format($registro->multa, 5, ',', '.'); ?>" class="form-control"><span class="pmd-textfield-focused"></span>
    </div>
    <div class="clear"></div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna2">
        <label for="regular1" class="control-label">Instrução que antecede o valor da mora diária: </label>
        <input type="text" name="instrucoes_mora" id="instrucoes_mora" value="<?php echo $registro->instrucoes_mora; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3">
        <label for="regular1" class="control-label">Mora diária (%): </label>
        <input type="text" name="juros" id="juros" value="<?php echo number_format($registro->juros, 5, ',', '.'); ?>" class="form-control"><span class="pmd-textfield-focused"></span>
    </div>
    <div class="clear"></div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label">
        <label for="regular1" class="control-label">Campo livre:</label>
        <input type="text" name="campo_livre1" id="campo_livre1" value="<?php echo $registro->campo_livre1; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
    </div>
    <div class="clear"></div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label">
        <label for="regular1" class="control-label">Campo livre:</label>
        <input type="text" name="campo_livre2" id="campo_livre2" value="<?php echo $registro->campo_livre2; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
    </div>
    <div class="clear"></div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label">
        <label for="regular1" class="control-label">Mensagem complementar: </label>
        <input type="text" name="mensagem_complementar" id="mensagem_complementar" value="<?php echo $registro->mensagem_complementar; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
    </div>
    <div class="clear"></div>

    <button type="button" name="salvar" id="salvar" value="Salvar" class="btn btn-info pmd-btn-raised">Salvar Opções e Observações</button>
    <button type="button" name="gerar" id="gerar" value="Gerar" class="btn btn-info pmd-btn-raised">Gerar</button>
    <div class="espaco20"></div>

    <div class="oculto" id="ms-imprimir-gerar-dialog" data-target="#imprimir-gerar-dialog" data-toggle="modal"></div>

    <div class="clear"></div>
</form>