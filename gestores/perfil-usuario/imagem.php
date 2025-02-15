<?php
include_once('../../config.php');
include_once('../funcoes_painel.php');
$registro = Usuarios::find(idUsuario());

try{
    unlink('../../assets/imagens/usuarios/peq_'.$registro->imagem);
    unlink('../../assets/imagens/usuarios/gde_'.$registro->imagem);
} catch (Exception $e){

}
$imagem = $_FILES['arquivo_imagem']['name'];
$extensao = pathinfo($imagem, PATHINFO_EXTENSION);
$imagem_tmp = $_FILES['arquivo_imagem']['tmp_name'];
$destino = '../../assets/imagens/usuarios/'.idUsuario().'.'.$extensao;
$img_peq = '../../assets/imagens/usuarios/peq_'.idUsuario().'.'.$extensao;
$img_gde = '../../assets/imagens/usuarios/gde_'.idUsuario().'.'.$extensao;
move_uploaded_file($imagem_tmp, $destino);

$usuario = Usuarios::find(idUsuario());
$usuario->imagem = idUsuario().'.'.$extensao;
$usuario->save();

include_once('../canvas.php');
$trata_imagem = new canvas();
$trata_imagem->carrega($destino)->redimensiona(100, 100, 'crop')->grava($img_gde);
$trata_imagem->carrega($destino)->redimensiona(40, 40, 'crop')->grava($img_peq);

//echo '{"imagem_gde" : "../assets/imagens/usuarios/gde_'.$usuario->imagem.'", "imagem" : "../assets/imagens/usuarios/peq_'.$usuario->imagem.'"}';
echo json_encode(array('imagem_gde' => '../assets/imagens/usuarios/gde_'.$usuario->imagem, 'imagem' => '../assets/imagens/usuarios/peq_'.$usuario->imagem));