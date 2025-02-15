$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#taxa').mask('0,00000', {reverse: true});

    $('#msg-nao-exclusao').hide();

    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('formas-recebimento/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('formas-recebimento/altera-forma.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


    /*Pesquisa*/
    $('#valor_pesquisa').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
        }
    });

    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('formas-recebimento/listagem.php', { valor_pesquisa: $('#valor_pesquisa').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#voltar').click(function(){
        $('#content').load('formas-recebimento/formas-recebimento.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('formas-recebimento/altera-forma.php', {id: $(this).attr('registro')});
    });



    $('#salvar').click(function(){

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $.post('formas-recebimento/acoes.php', { dados: dados }, function(data){

            if(data.status == 'erro')
            {
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'ok')
            {
                $('#ms-ok-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('formas-recebimento/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('formas-recebimento/formas-recebimento.php');
            }
            else if(data.status == 'erro')
            {
                $('#mensagem-modal').html(data.mensagem);
                $('#ms-nao-exclusao-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });




    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('formas-recebimento/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })

});