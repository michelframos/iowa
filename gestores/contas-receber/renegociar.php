<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

$id_parcela = filter_input(INPUT_POST, 'parcela', FILTER_VALIDATE_INT);
$parcela = Parcelas::find($id_parcela);

try{
    $opcoes_cobranca = Opcoes_Cobranca::find(1);
} catch(\ActiveRecord\RecordNotFound $e){
    $opcoes_cobranca = '';
}

try{
    $matricula = Matriculas::find($parcela->id_matricula);
    $turma = Turmas::find($parcela->id_turma);
    $aluno = Alunos::find($parcela->id_aluno);
} catch (\ActiveRecord\RecordNotFound $e)
{
    $aluno = '';
    $turma = '';
    $matricula = '';
}

if($matricula->responsavel_financeiro == 2):
    $empresa = Empresas::find($matricula->id_empresa_financeiro);
endif;

try{
    $empresa = Empresas::find($parcela->id_empresa);
} catch(\ActiveRecord\RecordNotFound $e){

}

/*Calculo de Juros e Multa*/
$data_atual = new DateTime("now");
if(!empty($parcela->data_vencimento)):
    $dias = $parcela->data_vencimento->diff($data_atual);
    $dias_atraso = $dias->format('%R%a');
else:
    $dias_atraso = 0;
endif;

if($dias_atraso > 0):
    $multa = $parcela->total*($opcoes_cobranca->multa/100);
else:
    $multa = 0;
endif;


if($dias_atraso > 0):
    $juros_mora = ($parcela->total*($opcoes_cobranca->juros/100))*$dias_atraso;
else:
    $juros_mora = 0;
endif;

//echo $parcela->id;

?>

<!-- Start Content -->
<div class="titulo">
    <h2>Dados da Parcela Original</h2>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <form action="" name="formDados" id="formDados" method="post" style="max-width: 800px;">

        <div class="clear"></div>
        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Aluno</label>
            <input type="text" name="aluno" disabled value="<?php echo !empty($aluno->nome) ? $aluno->nome : $empresa->nome_fantasia ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Turma</label>
            <input type="text" name="turma" disabled value="<?php echo $turma->nome ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
            <label for="regular1" class="control-label">Valor da Parcela</label>
            <input type="text" disabled name="valor_original" id="valor_original" value="<?php echo number_format($parcela->valor, 2, ',', '.') ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
            <label for="regular1" class="control-label">Juros</label>
            <input type="text" disabled name="juros" id="juros" value="<?php echo number_format($juros_mora, 2, ',', '.') ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
            <label for="regular1" class="control-label">Multa</label>
            <input type="text" disabled name="multa" id="multa" value="<?php echo number_format($multa, 2, ',', '.') ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
            <label for="regular1" class="control-label">Acréscimo</label>
            <input type="text" disabled name="acrescimo" id="acrescimo" value="<?php echo number_format($parcela->acrescimo, 2, ',', '.') ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
            <label for="regular1" class="control-label">Desconto</label>
            <input type="text" disabled name="desconto" value="<?php echo number_format($parcela->desconto, 2, ',', '.') ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
            <label for="regular1" class="control-label">Valor Total da Parcel</label>
            <input type="text" disabled name="total" id="total" value="<?php echo number_format($parcela->total, 2, ',', '.') ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
            <label for="regular1" class="control-label">Data de Vencimento</label>
            <input type="text" disabled name="data_vencimento_antiga" value="<?php echo !empty($parcela->data_vencimento) ? $parcela->data_vencimento->format('d/m/Y') : ''; ?>" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

    </form>

</section>

<div class="titulo">
    <h2>Dados da Nova Parcela e Boleto</h2>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <form action="" name="formNovosDados" id="formNovosDados" method="post" style="max-width: 800px;">

        <input type="hidden" name="id_parcela" id="id_parcela" value="<?php echo $parcela->id ?>">
        <input type="hidden" name="importar_acrescimos" id="importar_acrescimos" value="s">


        <?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Remover Acréscimos', 's')):?>

            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
                <label for="regular1" class="control-label">Novo Valor da Parcela</label>
                <input type="text" name="valor_parcela" id="valor_parcela" value="<?php echo number_format($parcela->total+$multa+$juros_mora, 2, ',', '.') ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

        <?php else: ?>

            <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-4 margin-right-10">
                <label for="regular1" class="control-label">Novo Valor da Parcela</label>
                <input type="hidden" name="valor_parcela" id="valor_parcela" value="<?php echo number_format($parcela->total+$multa+$juros_mora, 2, ',', '.') ?>" required>
                <input type="text" name="valor_" id="valor_" disabled value="<?php echo number_format($parcela->total+$multa+$juros_mora, 2, ',', '.') ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

        <?php endif; ?>

        <div class="form-group pmd-textfield pmd-textfield-floating-label coluna-3 margin-right-10">
            <label for="regular1" class="control-label">Nova Data de Vencimento</label>
            <input type="text" name="data_vencimento" id="data_vencimento" value="" class="form-control" required><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

    </form>

</section>

<div class="espaco20"></div>
<?php if(Permissoes::find_by_id_usuario_and_tela_and_a(idUsuario(), 'Remover Acréscimos', 's')): ?>
<button type="button" name="remover_acrescimos" id="remover_acrescimos" value="Salvar" class="btn btn-info pmd-btn-raised">Remover Acréscimos</button>
<?php endif; ?>

<button type="button" name="gerar" id="gerar" value="Gerar" class="btn btn-info pmd-btn-raised">Gerar Nova Parcela e Boleto</button>
<div class="espaco20"></div>

<div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
<div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>

<script>
    $(function(){
        $("#data_vencimento").datetimepicker({
            format: "DD/MM/YYYY"
        });

        $('#valor_parcela').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    });
</script>
