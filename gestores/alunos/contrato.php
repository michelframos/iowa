<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$matricula = Matriculas::find(filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_STRING));
$aluno = Alunos::find(filter_input(INPUT_POST, 'aluno', FILTER_SANITIZE_STRING));
$contrato = Textos::find(filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING));

// leitura das datas
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$semana = date('w');

// configuração mes

switch ($mes){

    case 1: $mes = "Janeiro"; break;
    case 2: $mes = "Fevereiro"; break;
    case 3: $mes = "Março"; break;
    case 4: $mes = "Abril"; break;
    case 5: $mes = "Maio"; break;
    case 6: $mes = "Junho"; break;
    case 7: $mes = "Julho"; break;
    case 8: $mes = "Agosto"; break;
    case 9: $mes = "Setembro"; break;
    case 10: $mes = "Outubro"; break;
    case 11: $mes = "Novembro"; break;
    case 12: $mes = "Dezembro"; break;

}

// configuração semana

switch ($semana) {

    case 0: $semana = "domingo"; break;
    case 1: $semana = "segunda-feira"; break;
    case 2: $semana = "terça-feira"; break;
    case 3: $semana = "quarta-feira"; break;
    case 4: $semana = "quinta-feira"; break;
    case 5: $semana = "sexta-feira"; break;
    case 6: $semana = "sábado"; break;

}

/*Substituindo dados no contrato*/
switch($matricula->responsavel_financeiro){

    case(3):

        /*Substituindo campos*/
        /*Aluno*/
        try{
            $cidade = Cidades::find($aluno->cidade);
            $estado = Estados::find($aluno->estado);
        } catch (Exception $e){
            $cidade = '';
            $estado = '';
        }


        $texto = str_replace('{{NomeResponsavel}}', $aluno->nome, $contrato->texto);
        $texto = str_replace('{{LoginAluno}}', $aluno->login, $texto);
        $texto = str_replace('{{ProfissaoResponsavelFinanceiro}}', '', $texto);
        $texto = str_replace('{{RGResponsavel}}', $aluno->rg, $texto);
        $texto = str_replace('{{CPFResponsavel}}', $aluno->cpf, $texto);
        $texto = str_replace('{{EnderecoResponsavel}}', $aluno->endereco, $texto);
        $texto = str_replace('{{CompEnderecoResponsavel}}', $aluno->complemento, $texto);
        $texto = str_replace('{{CidadeResponsavel}}', $cidade->nome, $texto);
        $texto = str_replace('{{EstadoResponsavel}}', $estado->uf, $texto);
        $texto = str_replace('{{DataAtual}}', date('d/m/Y'), $texto);
        $texto = str_replace('{{DataAtualExtenso}}', "$semana, $dia de $mes de $ano", $texto);
        break;

    case(2):

        /*Empresa*/
        $empresa = Empresas::find($matricula->id_empresa_financeiro);

        try{
            $cidade = Cidades::find($empresa->cidade);
            $estado = Estados::find($empresa->estado);
        } catch (Exception $e){
            $cidade = '';
            $estado = '';
        }


        $texto = str_replace('{{NomeResponsavel}}', $empresa->razao_social, $contrato->texto);
        $texto = str_replace('{{LoginAluno}}', $aluno->login, $texto);
        $texto = str_replace('{{ProfissaoResponsavelFinanceiro}}', '', $texto);
        $texto = str_replace('{{RGResponsavel}}', '', $texto);
        $texto = str_replace('{{CPFResponsavel}}', $empresa->cnpj, $texto);
        $texto = str_replace('{{EnderecoResponsavel}}', $empresa->rua, $texto);
        $texto = str_replace('{{CompEnderecoResponsavel}}', $empresa->complemento, $texto);
        $texto = str_replace('{{CidadeResponsavel}}', $cidade->nome, $texto);
        $texto = str_replace('{{EstadoResponsavel}}', $estado->uf, $texto);
        $texto = str_replace('{{DataAtual}}', date('d/m/Y'), $texto);
        $texto = str_replace('{{DataAtualExtenso}}', "$semana, $dia de $mes de $ano", $texto);
        break;

    case(1):

        /*Parente*/
        try{
            $cidade = Cidades::find($aluno->cidade_responsavel);
        } catch(\ActiveRecord\RecordNotFound $e){
            $cidade = '';
        }

        try{
            $estado = Estados::find($aluno->estado_responsavel);
        } catch(\ActiveRecord\RecordNotFound $e){
            $estado = '';
        }


        $texto = str_replace('{{NomeResponsavel}}', $aluno->nome_responsavel, $contrato->texto);
        $texto = str_replace('{{LoginAluno}}', $aluno->login, $texto);
        $texto = str_replace('{{ProfissaoResponsavelFinanceiro}}', '', $texto);
        $texto = str_replace('{{RGResponsavel}}', $aluno->rg_responsavel, $texto);
        $texto = str_replace('{{CPFResponsavel}}', $aluno->cpf_responsavel, $texto);
        $texto = str_replace('{{EnderecoResponsavel}}', $aluno->endereco_responsavel, $texto);
        $texto = str_replace('{{CompEnderecoResponsavel}}', $aluno->complemento_responsavel, $texto);
        $texto = str_replace('{{CidadeResponsavel}}', $cidade->nome, $texto);
        $texto = str_replace('{{EstadoResponsavel}}', $estado->uf, $texto);
        $texto = str_replace('{{DataAtual}}', date('d/m/Y'), $texto);
        $texto = str_replace('{{DataAtualExtenso}}', "$semana, $dia de $mes de $ano", $texto);
        break;

}

?>

<script src="js/ckeditor/ckeditor.js"></script>

<button name="voltar-documentos" id="voltar-documentos" value="Voltar" class="btn btn-info pmd-btn-raised">Voltar</button>
<div class="espaco20"></div>

<div class="form-group pmd-textfield pmd-textfield-floating-label">
    <textarea class="form-control" name="texto" id="texto" style="height: 500px;"><?php echo $texto ?></textarea>
</div>

<script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.

    CKEDITOR.replace('texto', {
        customConfig: 'js/ckeditor/custom_config.js'
    });

</script>
