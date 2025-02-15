$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#msg-nao-exclusao').hide();
    $('#valor').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('valores-hora-aula/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('valores-hora-aula/altera-valor.php', {id: data.id});
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

        $.post('valores-hora-aula/listagem.php', { valor: $('#valor_pesquisa').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#bt-voltar').click(function(){
        $('#content').load('valores-hora-aula/valores.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('valores-hora-aula/altera-valor.php', {id: $(this).attr('registro')});
    });



    $('#salvar').click(function(){

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $.post('valores-hora-aula/acoes.php', { dados: dados }, function(data){

            if(data.status == 'erro')
            {
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'ok')
            {
                $('#ms-ok-modal').click();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('valores-hora-aula/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('valores-hora-aula/valores.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro')
            {
                //$('#msg-nao-exclusao').show();
                $('#mensagem-modal').html(data.mensagem);
                $('#ms-nao-exclusao-modal').click();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('valores-hora-aula/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })

    $('#listagem').on('click', '.aplicar-categoria', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=aplicar-categoria';

        $.post('valores-hora-aula/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })

});