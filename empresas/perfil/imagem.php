<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$registro = Empresas::find(idEmpresa());

if(!file_exists('../../assets/imagens/empresas/')):
    mkdir('../../assets/imagens/empresas/', 0777, true);
endif;

try{
    unlink('../../assets/imagens/empresas/peq_'.$registro->imagem);
    unlink('../../assets/imagens/empresas/gde_'.$registro->imagem);
} catch (Exception $e){

}

$imagem = $_FILES['arquivo_imagem']['name'];
$extensao = pathinfo($imagem, PATHINFO_EXTENSION);
$imagem_tmp = $_FILES['arquivo_imagem']['tmp_name'];
$destino = '../../assets/imagens/empresas/'.idEmpresa().'.'.$extensao;
$img_peq = '../../assets/imagens/empresas/peq_'.idEmpresa().'.'.$extensao;
$img_gde = '../../assets/imagens/empresas/gde_'.idEmpresa().'.'.$extensao;
move_uploaded_file($imagem_tmp, $destino);

$registro->imagem = idEmpresa().'.'.$extensao;
$registro->save();

include_once('../canvas.php');
$trata_imagem = new canvas();
$trata_imagem->carrega($destino)->redimensiona(200, 200, 'crop')->grava($img_gde);
$trata_imagem->carrega($destino)->redimensiona(40, 40, 'crop')->grava($img_peq);

//echo '{"imagem_gde" : "../assets/imagens/usuarios/gde_'.$usuario->imagem.'", "imagem" : "../assets/imagens/usuarios/peq_'.$usuario->imagem.'"}';
echo json_encode(array('imagem_gde' => '../assets/imagens/empresas/gde_'.$registro->imagem, 'imagem' => '../assets/imagens/empresas/peq_'.$registro->imagem));