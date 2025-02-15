<form name="formLerRetorno" id="formLerRetorno" method="post" enctype="multipart/form-data">

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
    <div class="clear"></div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
        <label>Caixas</label>
        <select name="caixa" id="caixa" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" required>
            <option value=""></option>
            <?php
            $caixas_abertos = Caixas::all(array('conditions' => array('situacao = ?', 'aberto'),'order' => 'data_abertura, hora_abertura asc'));
            if(!empty($caixas_abertos)):
                foreach($caixas_abertos as $caixa_aberto):
                    $usuario = Usuarios::find($caixa_aberto->id_colega);
                    echo '<option value="'.$caixa_aberto->id.'">'.$caixa_aberto->nome.' - ['.$usuario->nome.']</option>';
                endforeach;
            endif;
            ?>
        </select>
        <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
    </div>

    <div class="form-group pmd-textfield pmd-textfield-floating-label">
        <label for="regular1" class="control-label">Selecionar Arquivo de Retorno</label>
        <input type="file" name="retorno" id="retorno" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
    </div>

    <button type="submit" name="ler" id="ler" value="Ler" class="btn btn-info pmd-btn-raised">Ler Retorno</button>
    <div class="espaco20"></div>

</form>

<div id="resultado_retorno"></div>