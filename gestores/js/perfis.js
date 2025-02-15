$(function(){

    $('#formDados').submit(function(){return false});
    $('#msg-nao-exclusao').hide();

    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('perfis/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('perfis/altera-perfil.php', {id: data.id});
            }
        }, 'json');

    });



    $('#bt-voltar').click(function(){
        $('#content').load('perfis/perfis.php');
    });



    $('.bt-altera').click(function(){
        var id = $(this).attr('registro');
        var dados = 'acao=atualiza-permissoes&id='+$(this).attr('registro');
        $.post('perfis/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('perfis/altera-perfil.php', {id: id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');
    });



    $('#salvar').click(function(){

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $.post('perfis/acoes.php', { dados: dados }, function(data){

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


    $('.altera-permissao').click(function(){
        var dados = 'acao=altera-permissao&permissao='+$(this).attr('permissao')+'&id_permissao_perfil='+$(this).attr('registro');
        $.post('perfis/acoes.php', { dados: dados }, function(data){

        }, 'json');
    });


    $('.bt-excluir').click(function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('perfis/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('perfis/perfis.php');
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




    $('.lista-gerente').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=lista-gerente';

        $.post('perfis/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })



    $('.ativa-inativa').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('perfis/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })

});