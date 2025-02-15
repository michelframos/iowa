<?php
/*
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
*/

function autenticaAluno($login, $senha)
{
    if(Alunos::find_by_login_and_senha($login, $senha)):
        return true;
    else:
        return false;
    endif;
}