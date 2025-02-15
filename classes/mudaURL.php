<?php

class mudaURL {

    public function mudarUrl($url) {

    if (isset($url)):
        if (is_file($url . ".php")):
            include_once ($url . ".php");
        else:
            throw new Exception("Desculpe, a página que você tentou acessar não existe!");
        endif;
    endif;
    
    }
}

