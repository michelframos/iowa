<?php
    if(!isset($_SESSION)):
        session_start();
    endif;

    /*Verifica se sessão foi criado*/
    function verificaSessao(){
        if(!isset($_SESSION['usuario'])):
            Mensagem('Você precisa estar logado para acessar o Painel de Contole do site.', '../index.php');
        endif;
    }

    function idUsuario(){
        return $_SESSION['usuario']['id'];
    }

    function idColega(){
        try{
            $usuario = Usuarios::find_by_id(idUsuario());
            return $usuario->id_colega;
        } catch (Exception $e){
            return 0;
        }
    }

    function usuarioUtilizado(){
        $id = $_SESSION['usuario']['id'];
        $usuario = Usuarios::find($id);
        $usuario->utilizado = 's';
        $usuario->save();
    }

    /*Verificando Permissão*/
    function verificaPermissao($id_usuario, $tela, $permissao, $redireciona){
        $pega_permissao = Permissoes::find(array('conditions' => array('id_usuario = ? and tela = ?', $id_usuario, $tela)));
        if($pega_permissao->$permissao == 's'):
            return true;
        else:
            Mensagem('Desculpe! Você nao tem permissão para acessar este recurso!', '?tela='.$redireciona);
            exit();
        endif;
    }

    function verificaPermissaoPost($id_usuario, $tela, $permissao){
        $pega_permissao = Permissoes::find(array('conditions' => array('id_usuario = ? and tela = ?', $id_usuario, $tela)));
        if($pega_permissao->$permissao == 's'):
            return true;
        else:
            echo json_encode(array('status' => 'erro-permissao', 'mensagem' => 'Você não tem permissão para executar esta ação no sistema.'));
            exit();
        endif;
    }


    /*Gravação do histórico*/
    function adicionaHistorico($id_usuario, $id_colega, $tela, $acao, $observacao){
        $historico = new Historico_Acoes();
        $historico->id_usuario = $id_usuario;
        $historico->data = date('Y-m-d H:i:s');
        $historico->id_colega = !empty($id_colega) ? $id_colega : null;
        $historico->tela = $tela;
        $historico->acao = $acao;
        $historico->observacao = $observacao;
        $historico->save();
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
        $variavel->criado_por = $_SESSION['usuario']['id'];
        $variavel->data_criacao = date('Y-m-d H:i:s');
        $variavel->alterado_por = $_SESSION['usuario']['id'];
        $variavel->data_alteracao = date('Y-m-d H:i:s');
    }

    function dadosAlteracao($variavel){
        $variavel->alterado_por = $_SESSION['usuario']['id'];
        $variavel->data_alteracao = date('Y-m-d H:i:s');
    }


    function redireciona($tela){
        header('location:?tela='.$tela);
    }


    function sairPainel(){
        unset($_SESSION['usuario']);
        header('location:../login.php');
    }


    function geraUrl($string){

        $string = strtolower($string);

        // assume que esteja em UTF-8
        $map = array(
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'è' => 'e',
            'í' => 'i',
            'ï' => 'i',
            'ì' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'ñ' => 'n',
            '_' => '-',
            ' ' => '-',
            '+' => '-',
            '´' => '-',
            '!' => '-',
            '@' => '-',
            '#' => '-',
            '$' => '-',
            '%' => '-',
            '¨' => '-',
            '&' => '-',
            '*' => '-',
            '(' => '-',
            ')' => '-',
            '[' => '-',
            ']' => '-',
            '{' => '-',
            '}' => '-',
            'º' => '-',
            'ª' => '-',
            '``' => '-',
            '\'' => '-'
        );

        return strtr($string, $map); // funciona corretamente

    }
