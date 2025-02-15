<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    //verificaPermissao(idUsuario(), 'Categorias de Usuários', 'c', 'index');

?>
<script src="js/perfis.js"></script>

<div tabindex="-1" class="modal fade" id="delete-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Confirma a exclusão desta Categoria de Usuário? Esta ação é irreversível! </p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">Cancelar</button>
                <button data-dismiss="modal" id="bt-modal-excluir" registro="" type="button" class="btn pmd-btn-raised pmd-ripple-effect btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="permissao-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Permissão Negada</h2>
            </div>
            <div class="modal-body">
                <p id="msg-permissao-dialog"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">textsms</i>
    <h1>Central de Conhecimento</h1>
</div>

<div role="alert" class="alert alert-danger alert-dismissible oculto" id="msg-nao-exclusao">
    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
    Este Registro não pode ser excluído por já ter sido utilizado no sistema.
</div>

<section class="pmd-card pmd-z-depth padding-10 flex">

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/a0FGyuHPZN4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como alterar sua senha
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/vllKV4pEFRQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como cadastrar, matricular, inserir financeiro e obs de um aluno
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/LUWAqo0cB0Q" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como inserir algum valor no financeiro do aluno
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/VqO_pJ8PRK0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como marcar, cancelar ou um HELP para aluno
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/Dl-1aaxsJJo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como registrar uma aula dada
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/4jsezNnAsbk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como inserir um dia a mais no diário
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/3_5OYmz2DoU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como registrar e consultar notas e obs para cada aluno
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/5EP5AKTT-G0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como registrar um HELP dado para um aluno
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/Jq9oxbdj5kU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como consultar helps por aluno/instrutor, etc.
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/TuW6lR-qsd4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como transferir aluno
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/N37NsyIBG8w" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como aprovar ou reprovar um HELP ou Speedclass
        </div>

    </div>

    <!--Media and Description area -->
    <div class="pmd-card pmd-card-default pmd-z-depth coluna-metade">

        <!-- Card media -->
        <div class="pmd-card-media">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/ayewRnoO74c" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Card body -->
        <div class="pmd-card-body size-1-5 bold">
            Como receber do aluno no i sys
        </div>

    </div>

    <div class="espaco20"></div>

</section>

<script>
    $('#myModal').on('hidden.bs.modal', function (e) {
        // do something...
    })
</script>