$(function(){

    $('#formDados').submit(function(){return false});
    $('#formConteudo').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#msg-nao-exclusao').hide();
    $('#valor').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    $('#bt-novo').click(function(){

        /*
        var dados = 'acao=novo';
        $.post('nomes-produtos/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('nomes-produtos/altera-nome-produto.php', {id: data.id});
            }
        }, 'json');
        */

        $('#content').load('programacao/selecao-produto.php');

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

        $.post('programacao/listagem.php', { valor_pesquisa: $('#valor_pesquisa').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#bt-voltar').click(function(){
        $('#content').load('programacao/programacao.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('programacao/altera-programacao.php', {id: $(this).attr('registro')});
    });


    $('.cria-programacao').click(function(){

        $('#content').load('programacao/altera-programacao.php', {id: $(this).attr('registro')});

    });


    $('#salvar').click(function(){

        var id = $(this).attr('registro');
        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $('#ms-salvando-dialog').click();

        $.post('programacao/acoes.php', { dados: dados }, function(data){

            if(data.status == 'erro')
            {
                $('#bt-salvou').click();
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#bt-salvou').click();
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'ok')
            {
                //$('#ms-ok-modal').click();
                $('#bt-salvou').click();
                $('#content').load('programacao/altera-programacao.php', {id: id});
            }

        }, 'json');

    });

    $('.salvar-conteudo').click(function(){

        var id = $(this).attr('registro');
        var dados = $('#formConteudo').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar-conteudo';

        $('#ms-salvando-dialog').click();

        $.post('programacao/acoes.php', { dados: dados }, function(data){

            if(data.status == 'erro')
            {
                $('#bt-salvou').click();
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#bt-salvou').click();
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'ok')
            {
                $('#bt-salvou').click();
                $('#content').load('programacao/altera-programacao.php', {id: id});
                //$('#ms-ok-modal').click();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('programacao/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('programacao/programacao.php');
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



    /*
    $('.ativa-inativa').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('nomes-produtos/acoes.php', { dados: dados }, function(data){});

    })
    */

});