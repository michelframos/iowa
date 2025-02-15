<?php
namespace IowaGeral;

//use Controllers\HomeController;

use Admin\AdminController;
use Franquia\FranquiaController;
use Painel\PainelController;
use Controllers\TradutorController;
use Site\HomeController;

class Rotas {

    public $dados;

    public function __construct(){
        $this->dados = new \stdClass();
    }

    public function Dados()
    {
        return $this->dados;
    }

    public function Redireciona($redirecionamento)
    {
        header('location:'.$redirecionamento);
    }

    public function VerificaRota($nivel, $url, $rotas){

        $Url = explode('/', $url);

        foreach ($rotas as $rota):

            if($nivel == 'painel'):

                if($rota['link'] == $Url[0]):

                    $parametro = explode('@', $rota['controller']);
                    $controller = 'IowaPainel\\'.$parametro[0];
                    $metodo = $parametro[1];

                    $classe = new $controller();
                    $classe->$metodo();
                    //$dados = $classe->Dados();

                endif;

            elseif($nivel == 'helpers'):

                if($rota['link'] == $Url[0]):

                    $parametro = explode('@', $rota['controller']);
                    $controller = 'IowaHelpers\\'.$parametro[0];
                    $metodo = $parametro[1];

                    $classe = new $controller();
                    $classe->$metodo();
                    //$dados = $classe->Dados();

                endif;

            elseif($nivel == 'scripts'):

                if($rota['link'] == $Url[0]):

                    /*definindo namespace*/
                    switch ($rota['nivel']):
                        case 'painel':
                            $namespace = 'IowaPainel';
                            break;
                        case 'helpers':
                            $namespace = 'IowaHelpers';
                            break;
                        default:
                            $namespace = 'IowaPainel';
                    endswitch;

                    $parametro = explode('@', $rota['controller']);
                    $controller = $namespace.'\\'.$parametro[0];
                    $metodo = $parametro[1];

                    $classe = new $controller();
                    $classe->$metodo();
                    //$dados = $classe->Dados();

                    include_once(SCRIPTS.'/index.php');

                endif;

            endif;

        endforeach;

    }



}