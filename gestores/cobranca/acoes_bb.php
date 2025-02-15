<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');

use CnabPHP\Remessa;

parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);

if($dados['acao'] == 'salvar'):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Geração de Cobrança', 'a');

    try{
        $registro = Opcoes_Cobranca::find(1);
        $registro->tipo_acao = $dados['tipo_acao'];

        if(isset($dados['iniciar_sequencia'])):
            $registro->iniciar_sequencia = 's';
        else:
            $registro->iniciar_sequencia = 'n';
        endif;

        $registro->numero_inicial = $dados['numero_inicial'];

        if(isset($dados['quantidade_maxima'])):
            $registro->quantidade_maxima = 's';
        else:
            $registro->quantidade_maxima = 'n';
        endif;

        $registro->quantidade = $dados['quantidade'];

        if(isset($dados['adicionar_taxa'])):
            $registro->adicionar_taxa = 's';
        else:
            $registro->adicionar_taxa = 'n';
        endif;

        $registro->taxa = $dados['taxa'];

        if(isset($dados['discriminar_observacao'])):
            $registro->discriminar_observacao = 's';
        else:
            $registro->discriminar_observacao = 'n';
        endif;

        if(isset($dados['imprimir_endereco'])):
            $registro->imprimir_endereco = 's';
        else:
            $registro->imprimir_endereco = 'n';
        endif;

        $registro->instrucoes_atraso = $dados['instrucoes_atraso'];

        $multa = str_replace(',', '.', $dados['multa']);
        $registro->multa = $multa;
        $registro->instrucoes_mora = $dados['instrucoes_mora'];

        $juros = str_replace(',', '.', $dados['juros']);
        $registro->juros = $juros;


        $registro->campo_livre1 = $dados['campo_livre1'];
        $registro->campo_livre2 = $dados['campo_livre2'];
        $registro->mensagem_complementar = $dados['mensagem_complementar'];
        $registro->save();

    } catch( \ActiveRecord\RecordNotFound $e){
        $registro = new Opcoes_Cobranca();
        $registro->id = 1;
        $registro->tipo_acao = $dados['tipo_acao'];

        if(isset($dados['iniciar_sequencia'])):
            $registro->iniciar_sequencia = 's';
        else:
            $registro->iniciar_sequencia = 'n';
        endif;

        $registro->numero_inicial = $dados['numero_inicial'];

        if(isset($dados['quantidade_maxima'])):
            $registro->quantidade_maxima = 's';
        else:
            $registro->quantidade_maxima = 'n';
        endif;

        $registro->quantidade = $dados['quantidade'];

        if(isset($dados['adicionar_taxa'])):
            $registro->adicionar_taxa = 's';
        else:
            $registro->adicionar_taxa = 'n';
        endif;

        $registro->taxa = $dados['taxa'];

        if(isset($dados['discriminar_observacao'])):
            $registro->discriminar_observacao = 's';
        else:
            $registro->discriminar_observacao = 'n';
        endif;

        if(isset($dados['imprimir_endereco'])):
            $registro->imprimir_endereco = 's';
        else:
            $registro->imprimir_endereco = 'n';
        endif;

        $registro->instrucoes_atraso = $dados['instrucoes_atraso'];

        $multa = str_replace(',','.', $dados['multa']);
        $registro->multa = $multa;
        $registro->instrucoes_mora = $dados['instrucoes_mora'];

        $juros = str_replace(',', '.', $dados['juros']);
        $registro->juros = $juros;
        $registro->campo_livre1 = $dados['campo_livre1'];
        $registro->campo_livre2 = $dados['campo_livre2'];
        $registro->mensagem_complementar = $dados['mensagem_complementar'];
        $registro->save();
        $registro->save();
    }

    adicionaHistorico(idUsuario(), idColega(), 'Geração de Cobrança', 'Alteração', 'As configurações para a geração de cobrança foram alteradas.');

    echo json_encode(array('status' => 'ok'));

endif;




if($dados['tipo_acao'] == 'arquivo_cnab' && !isset($dados['acao'])):

    /*Verificando Permissões*/
    verificaPermissaoPost(idUsuario(), 'Geração de Cobrança', 'i');

    $dados_banco = IowaPainel\UnidadesController::getDadosBanco($dados['id_unidade'], $dados['codigo_banco']);

    /*Pegando os dados para geração do arquivo de remessa*/
    parse_str(filter_input(INPUT_POST, 'dados', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $dados);


    switch ($dados_banco->codigo_banco):
        case '001':
            IowaPainel\CobrancaController::geraRemessaBancoBrasil($dados);
            break;

        case '756':
            IowaPainel\CobrancaController::geraRemessaSicoob($dados);
            break;
    endswitch;


    echo json_encode(array('status' => 'ok'));

endif;


//function nossoNumero($convenio, $sequencial)
function nossoNumero($sequencial)
{

    //$tamanhoConvenio = strlen($convenio);

    /*
    if($tamanhoConvenio <= 4):
        $nosso_numero_sem_dv = str_pad($convenio, 4, 0, STR_PAD_LEFT).str_pad($sequencial, 7, 0, STR_PAD_LEFT);
        $dv = modulo_11($nosso_numero_sem_dv);
        $nosso_numero = $nosso_numero_sem_dv.$dv;

    elseif( $tamanhoConvenio > 4 && $tamanhoConvenio <= 6):
        $nosso_numero_sem_dv = str_pad($convenio, 6, 0, STR_PAD_LEFT).str_pad($sequencial, 5, 0, STR_PAD_LEFT);
        $dv = modulo_11($nosso_numero_sem_dv);
        $nosso_numero = $nosso_numero_sem_dv.$dv;

    elseif($tamanhoConvenio > 6):
        $nosso_numero_sem_dv = str_pad($convenio, 7, 0, STR_PAD_LEFT).str_pad($sequencial, 10, 0, STR_PAD_LEFT);
        $nosso_numero = $nosso_numero_sem_dv;
    endif;
    */

    $nosso_numero_sem_dv = str_pad($sequencial, 10, 0, STR_PAD_LEFT).str_pad(" ", 3, ' ', STR_PAD_LEFT);
    return $nosso_numero_sem_dv;

}

function modulo_11($num, $base=9, $r=0) {
    $soma = 0;
    $fator = 2;
    for ($i = strlen($num); $i > 0; $i--) {
        $numeros[$i] = substr($num,$i-1,1);
        $parcial[$i] = $numeros[$i] * $fator;
        $soma += $parcial[$i];
        if ($fator == $base) {
            $fator = 1;
        }
        $fator++;
    }

    $digito = $soma % 11;
    if($digito < 10):
        $resto = $digito;
    elseif ($digito == 10):
        $resto = 'X';
    elseif($digito == 0):
        $resto = 0;
    endif;

    return $resto;

    /*
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;

        //corrigido
        if ($digito == 10) {
            $digito = "X";
        }


        if (strlen($num) == "43") {
            //ent�o estamos checando a linha digit�vel
            if ($digito == "0" or $digito == "X" or $digito > 9) {
                $digito = 1;
            }
        }
        return $digito;
    }
    elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
    */
}
