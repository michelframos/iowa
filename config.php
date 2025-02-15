<?php
if(!isset($_SESSION)):
    session_start();
endif;

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Expires: Sat, 01 Jan 1990 01:00:00 GMT');
header('Last-Modified: ' .gmdate( 'D, d M Y H:i:s' ). ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

ob_start();
date_default_timezone_set('America/Sao_Paulo');
include_once('vendor/autoload.php');

/*Classe mudaURL*/
$pastas = array('classes/', '../classes/', '../../classes/', '../../../classes/');
foreach($pastas as $pasta):
    if(is_file($pasta.'mudaURL.php')):
        include_once ($pasta.'mudaURL.php');
        break;
    endif;
endforeach;

/*Conexão com Banco de Dados*/
$pastas = array('classes/activeRecord/', '../classes/activeRecord/', '../../classes/activeRecord/', '../../../classes/activeRecord/');
foreach($pastas as $pasta):
    if(is_file($pasta.'ActiveRecord.php')):
        include_once ($pasta."ActiveRecord.php");
        break;
    endif;
endforeach;

$cfg = ActiveRecord\Config::instance();

$pastas = array('models', '../models', '../../models', '../../../models');
foreach($pastas as $pasta):
    if(is_dir($pasta)):
        $cfg->set_model_directory($pasta);
        break;
    endif;
endforeach;

/*Verificando se o servidor é localhost ou não*/
$tipo_conexao = $_SERVER['HTTP_HOST'];
switch ($tipo_conexao):
    case '127.0.0.1':
    case 'localhost':
        define('HOME', 'http://localhost/iowa');
        $cfg->set_connections(array('development' => 'mysql://root:@localhost/iowa'));
        break;
//    case 'iowaidiomas.com.br':
//        define('HOME', 'https://iowaidiomas.com.br/teste');
//        $cfg->set_connections(array('development' => 'mysql://testevps:Mudar!123@localhost/iowatestevps'));
//
//        //amazon producao
////        define('HOME', 'https://iowaidiomas.com.br/sis');
////        $cfg->set_connections(array('development' => 'mysql://admin_iowa:Idiomas00@database-iowa.ct6qt8odcvei.us-east-1.rds.amazonaws.com/iowa_sis'));
//        break;
    case 'iowaidiomas.com.br':
    case 'www.iowaidiomas.com.br':
        define('HOME', 'https://iowaidiomas.com.br/sis');
        $cfg->set_connections(array('development' => 'mysql://iowaidiomas:Mudar!123@localhost/iowaidiomas;charset=utf8'));

        //amazon producao
//        define('HOME', 'https://iowaidiomas.com.br/sis');
//        $cfg->set_connections(array('development' => 'mysql://admin_iowa:Idiomas00@database-iowa.ct6qt8odcvei.us-east-1.rds.amazonaws.com/iowa_sis'));
        break;
endswitch;
//if (($tipo_conexao == 'localhost') || ($tipo_conexao == '127.0.0.1')):
//    define('HOME', 'http://localhost/iowa');
//    $cfg->set_connections(array('development' => 'mysql://root:@localhost/iowa_sis'));
//else:
    /*Homologação*/

    //define('HOME', 'https://www.iowaidiomas.com.br/teste');
    //$cfg->set_connections(array('development' => 'mysql://iowa_testes:idiomas00@179.188.51.46/iowa_testes'));


    /*Unidade Virtual*/
    /*
    define('HOME', 'http://www.iowaidiomas.com.br/virtual');
    $cfg->set_connections(array('development' => 'mysql://iowa_virtual:idiomas00@179.188.51.46/iowa_virtual'));
    */

    /*Produção*/
    //define('HOME', 'https://www.iowaidiomas.com.br/sis');
    //$cfg->set_connections(array('development' => 'mysql://iowa_sis:idiomas00@179.188.51.46/iowa_sis'));

    //define('HOME', 'https://iowaidiomas.com.br/sis');
    //$cfg->set_connections(array('development' => 'mysql://admin_iowa:Idiomas00@database-iowa.ct6qt8odcvei.us-east-1.rds.amazonaws.com/iowa_sis'));

    /*Teste IOWA*/
    //define('HOME', 'http://3.91.173.194/sis');
    //$cfg->set_connections(array('development' => 'mysql://admin_iowa:Iowa!2023.@database-iowa.ctuj3g0hf5ys.us-east-1.rds.amazonaws.com/iowa_sis'))

    /*Teste AWS*/
//    define('HOME', 'http://34.225.159.170/teste');
//    $cfg->set_connections(array('development' => 'mysql://admin_iowa:Idiomas00@database-iowa.ct6qt8odcvei.us-east-1.rds.amazonaws.com/iowa_teste'));

    /*Produção AWS*/
    //define('HOME', 'http://34.225.159.170/sis');
    //$cfg->set_connections(array('development' => 'mysql://admin_iowa:Idiomas00@database-iowa.ct6qt8odcvei.us-east-1.rds.amazonaws.com/iowa_sis'));

    /*Testes Michel*/
    /*
    define('HOME', 'http://www.iowaidiomas.com.br/teste-michel');
    $cfg->set_connections(array('development' => 'mysql://iowa_michel:Mudar123@iowa_michel.l70cnn1523.mysql.dbaas.com.br/iowa_michel'));
    */


    /*
    define('HOME', 'http://www.iowaidiomas.com.br/taboao');
    $cfg->set_connections(array('development' => 'mysql://iowa_taboa_sis:idiomas00@179.188.51.46/iowa_taboa_sis'));
    */

    /*
   define('HOME', 'http://iowaidiomas.com.br/teste-bb');
   $cfg->set_connections(array('development' => 'mysql://teste_bb:idiomas00@teste_bb.l70cnn1523.mysql.dbaas.com.br/teste_bb'));
    */

//endif;

//$pegaUrl = strip_tags(trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
//$pegaUrl = (empty($pegaUrl)) ? 'home' : $pegaUrl;
//$url = explode('/', $pegaUrl);

//Usuarios::find_by_sql("set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';");

/*Funções*/
//MÁSCARA
function mascara($valor, $mascara){
    $maskared = '';
    $k = 0;

    for($i = 0; $i<=strlen($mascara)-1; $i++):

        if($mascara[$i] == '#'):
            if(isset($valor[$k])):
                $maskared .= $valor[$k++];
            endif;
        else:
            if(isset($mascara[$i])):
                $maskared .= $mascara[$i];
            endif;
        endif;

    endfor;

    return $maskared;
}

function Mensagem($mensagem, $redirecionamento){
    echo '<script>';
    echo 'alert("'.$mensagem.'");';
    echo 'location.href="'.$redirecionamento.'"';
    echo '</script>';
}


/*Arredondando a nota*/
function arredonda($nota){

    $decimal = explode('.', number_format($nota, 1, '.', '.'));

    if(!empty($decimal[1])):

        if(($decimal[1] >= 1) and ($decimal[1] <= 5)):
            $decimal[1] = 5;
        elseif(($decimal[1] > 5)):
            $decimal[1] = 0;
            $decimal[0]++;
        endif;

    endif;


    return $decimal[0].'.'.$decimal[1];
    //return $decimal[1];

}

include_once('Rotas.php');
