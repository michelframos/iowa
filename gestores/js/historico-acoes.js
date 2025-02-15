$(function(){

    $('#formPesquisa').submit(function(){return false});

    /*Pesquisa*/
    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('historico-acoes/listagem.php', {
            usuario: $('#usuario').val(),
            tela: $('#tela').val(),
            acao: $('#acao').val(),
            data_inicial: $('#data_inicial').val(),
            data_final: $('#data_final').val(),
        }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


});