$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#msg-nao-exclusao').hide();

    /*Pesquisa*/
    $('#valor').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
        }
    });

    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('nome-provas/listagem.php', { valor: $('#valor').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/

    $('#bt-novo-nome-prova').click(function(){

        var dados = 'acao=novo';
        $.post('nome-provas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('nome-provas/altera-nome-prova.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });



    $('#bt-voltar-nome-provas').click(function(){
        $('#content').load('nome-provas/nome-provas.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('nome-provas/altera-nome-prova.php', {id: $(this).attr('registro')});
    });



    $('#salvar').click(function(){

        $('#formDados').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formDados').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $.post('nome-provas/acoes.php', { dados: dados }, function(data){

            $('#bt-salvou').click();

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




    $('.bt-excluir').click(function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('nome-provas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('nome-provas/nome-provas.php');
            }
            else if(data.status == 'erro')
            {
                $('#msg-nao-exclusao').show();
            }

        }, 'json');

    });




    $('.ativa-inativa').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('nome-provas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })

});