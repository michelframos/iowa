$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#msg-nao-exclusao').hide();

    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('sistema-notas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('sistema-notas/altera-sistema-nota.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


    /*Pesquisa*/
    $('#valor').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
        }
    });

    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('sistema-notas/listagem.php', { valor: $('#valor').val(), idioma: $('#idioma').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#bt-voltar').click(function(){
        $('#content').load('sistema-notas/sistema-notas.php');
    });


    $('#adicionar-prova').click(function(){

        var id = 1;
        /*Contando elementos*/
        if(id == 6){
            $(this).hide();
        } else {
            $.each( $( ".prova-a-mostra" ), function() {
                id++;
            });
        }

        $('#prova-oculta'+id).slideDown(150).addClass('prova-a-mostra');

        if(id == 6){
            $(this).hide();
        }

    });


    $('#bt-modal-simulador').click(function(){

        var dados = $('#formSimulador').serialize()+'&acao=simular-nota';

        $.post('sistema-notas/acoes.php', { dados: dados }, function(data){
            $('#resultado-simulador').html('Nota: ' + data.resultado);
        }, 'json');

    });


    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('sistema-notas/altera-sistema-nota.php', {id: $(this).attr('registro')});
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

        $.post('sistema-notas/acoes.php', { dados: dados }, function(data){

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




    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('sistema-notas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('sistema-notas/sistema-notas.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro')
            {
                $('#msg-nao-exclusao').show();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('sistema-notas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })

});