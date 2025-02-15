<?php
    /*----------------------*/
    /*Verificando Permissões*/
    //verificaPermissao(idUsuario(), 'Contatos', 'i', 'contatos/contatos');

    $registro = new Idiomas();
    $registro->idioma = 'Novo Idioma';
    $registro->status = 'a';
    dadosCriacao($registro);
    $registro->save();

    //usuarioUtilizado();
    redireciona('idiomas/altera-idioma&id='.$registro->id);
?>

<section>

    <article class="padding-10">

        <div class="espaco50"></div>
        <h1 class="titulo"><span class="fa fa-clock-o"></span> Criando registro...</h1>
        <div class="espaco50"></div>

    </article>

</section>
