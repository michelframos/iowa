<?php
include_once ('../config.php');
include_once('../gestores/funcoes_painel.php');

verificaSessao();

//remover permissao: Adicionas Aulas;
$remover_permissoes = Permissoes::all(['conditions' => ['tela = ?', 'Adicionas Aulas']]);
if(!empty($remover_permissoes)):
    foreach ($remover_permissoes as $permissao):
        $permissao->delete();
    endforeach;
endif;
