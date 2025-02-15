<?php
    session_start();
    /*
    if(!isset($_SESSION)):
        session_start();
    endif;
    */

    /*
    if(empty($_GET['tela']) || !isset($_GET['tela'])):
        header('location: index.php?tela=inicio');
    endif;
    */

    /*Verifica se sessão foi criado*/
    function verificaSessao(){
        if(!isset($_SESSION['empresa']['id'])):
            //Mensagem('Você precisa estar logado para acessar a Área da Empresa.', 'login.php');
            header('location:login.php');
        endif;
    }

    function idEmpresa(){
        return $_SESSION['empresa']['id'];
    }


    /*Função Mostrar Menu*/
    function MostrarMenu($id_usuario, $tela){

        $permissao = Permissoes::find(array('conditions' => array('id_usuario = ? and tela = ? and a = ? and i = ? and e = ? and c = ? and ai = ?', $id_usuario, $tela, 'n', 'n', 'n', 'n', 'n')));
        if(empty($permissao)):
            return true;
        else:
            return false;
        endif;
    }


    /*Dados de criação*/
    function dadosCriacao($variavel){
        $variavel->criado_por = $_SESSION['empresa']['id'];
        $variavel->data_criacao = date('Y-m-d H:i:s');
        $variavel->alterado_por = $_SESSION['empresa']['id'];
        $variavel->data_alteracao = date('Y-m-d H:i:s');
    }

    function dadosAlteracao($variavel){
        $variavel->alterado_por = $_SESSION['empresa']['id'];
        $variavel->data_alteracao = date('Y-m-d H:i:s');
    }


    function redireciona($tela){
        header('location:?tela='.$tela);
    }


    function sairPainel(){
        unset($_SESSION['empresa']);
        header('location:login.php');
    }