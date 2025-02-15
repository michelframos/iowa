<?php
use OpenBoleto\Banco\BancoDoBrasil;
use OpenBoleto\Banco\Sicoob;
use OpenBoleto\Agente;

include_once ('vendor/openboleto/openboleto/autoloader.php');

include_once('config.php');

$opcoes_cobranca = \IowaPainel\CobrancaController::getOpcoesCobranca();

$chave_boleto = filter_input(INPUT_GET, 'boleto', FILTER_SANITIZE_STRING);
$boleto_sistema = Boletos::find_by_chave_and_cancelado($chave_boleto, 'n');
$parcela = Parcelas::find($boleto_sistema->id_parcela);

$dados_banco = IowaPainel\UnidadesController::getDadosBanco($boleto_sistema->id_unidade, $boleto_sistema->codigo_banco);

/*pegando dados da Unidade*/
try{
    //$usar_dados = Unidades::find_by_usar_dados_boleto('s');
    $usar_dados = Unidades::find($boleto_sistema->id_unidade);

    $cnpj = str_replace('.', '', $usar_dados->cnpj);
    $cnpj = str_replace('/', '', $cnpj);
    $cnpj = str_replace('-', '', $cnpj);

    $nome_empresa = $usar_dados->razao_social;

    $numero_agencia = explode('-', $dados_banco->agencia);
    $numero_conta_corrente = explode('-', $dados_banco->conta);

    $convenio = str_replace('-', '', $dados_banco->codigo_cliente);

    //$estado_empresa = Estados::find($usar_dados->estado);
    //$cidade_empresa = Cidades::find($usar_dados->cidade);
    $estado_empresa = '';
    $cidade_empresa = '';

} catch (\ActiveRecord\RecordNotFound $e){
    $usar_dados = '';
}


if(empty($parcela->parcela)):
    $numero_parcela = '001';
else:
    $numero_parcela = str_pad($parcela->parcela, 3, '0', STR_PAD_LEFT);
endif;

if($parcela->pagante=='aluno'):
    $aluno=Alunos::find($parcela->id_aluno);

    try{
        $matricula = Matriculas::find($parcela->id_matricula);
    } catch(\ActiveRecord\RecordNotFound $e){
        $matricula = '';
    }


    if($matricula->responsavel_financeiro == 3):


        try{
            $cidade = Cidades::find($aluno->cidade);
        } catch(\ActiveRecord\RecordNotFound $e){
            $cidade = '';
        }

        try{
            $estado = Estados::find($aluno->estado);
        } catch(\ActiveRecord\RecordNotFound $e){
            $estado = '';
        }

        $nome=$aluno->nome;
        $cpf_cnpj = mascara($aluno->cpf, "###.###.###-##");
        $endereco1=$aluno->endereco.','.$aluno->numero.'-'.$aluno->bairro;
        $endereco2=$cidade->nome.'-'.$estado->uf.'-'.$aluno->cep;


    elseif($matricula->responsavel_financeiro == 1):


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

        $nome=$aluno->nome_responsavel." (aluno(a): {$aluno->nome}): CPF: ".mascara($aluno->cpf_responsavel, "###.###.###-##");
        $cpf_cnpj = mascara($aluno->cpf_responsavel, "###.###.###-##");
        $endereco1=$aluno->endereco_responsavel.','.$aluno->numero_responsavel.'-'.$aluno->bairro_responsavel;
        $endereco2=$cidade->nome.'-'.$estado->uf.'-'.$aluno->cep_responsavel;

    endif;


elseif($parcela->pagante=='empresa'):
    $empresa=Empresas::find($parcela->id_empresa);

    try{
        $cidade=Cidades::find($empresa->cidade);
    }catch(\ActiveRecord\RecordNotFound$e){
        $cidade='';
    }

    try{
        $estado=Estados::find($empresa->estado);
    }catch(\ActiveRecord\RecordNotFound$e){
        $estado='';
    }

    $nome=$empresa->razao_social;
    $cnpj = mascara($cnpj, "##.###.###/####-##");
    $endereco1=$empresa->rua.','.$empresa->numero.'-'.$empresa->bairro;
    $endereco2=$cidade->nome.'-'.$estado->uf.'-'.$aluno->cep;

endif;

$sacado = new Agente($nome, $cpf_cnpj, $endereco1, $aluno->cep, $cidade->nome, $estado->uf);
//$cedente = new Agente($nome_empresa, mascara($cnpj, "##.###.###/####-##"), $usar_dados->rua.', '.$usar_dados->numero.' - '.$usar_dados->bairro, $usar_dados->cep, $cidade_empresa->nome, $estado_empresa->nome);
$cedente = new Agente($nome_empresa, mascara($cnpj, "##.###.###/####-##"), '');

$boleto = new BancoDoBrasil(array(
    // Parâmetros obrigatórios
    'dataVencimento' => $boleto_sistema->data_vencimento,
    'valor' => $boleto_sistema->valor,
    'sequencial' => $boleto_sistema->nosso_numero, // Para gerar o nosso número
    'sacado' => $sacado,
    'cedente' => $cedente,
    'agencia' => $numero_agencia[0], // Até 4 dígitos
    'carteira' => 17,
    'conta' => $numero_conta_corrente[0], // Até 8 dígitos
    'convenio' => $convenio, // 4, 6 ou 7 dígitos
));

$boleto->setNumeroDocumento($boleto_sistema->numero_boleto);
$boleto->setEspecieDoc('DM');

echo $boleto->getOutput();
