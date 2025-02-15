$(function () {

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $("#data_inicio, #data_termino").datetimepicker({
        format: "DD/MM/YYYY"
    });

    var tempo_indeterminado = $('#tempo_indeterminado').val();
    if(tempo_indeterminado == 's')
    {
        $('#data_termino').prop('disabled', true);
    }

    $('#tempo_indeterminado').change(function () {
        var tempo_indeterminado = $(this).val();
        if(tempo_indeterminado == 's')
        {
            $('#data_termino').prop('disabled', true);
        }
        else
        {
            $('#data_termino').prop('disabled', false);
        }
    });

    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('promocoes/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('promocoes/altera-promocao.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });

    $('#listagem').on('click', '.bt-enviar-link', function () {
        $('#content').load('promocoes/autorizar.php', { id: $(this).attr('registro') });
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

        $.post('promocoes/listagem.php', { valor_pesquisa: $('#valor_pesquisa').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#voltar').click(function(){
        $('#content').load('promocoes/promocoes.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('promocoes/altera-promocao.php', {id: $(this).attr('registro')});
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

        var tempo_indeterminado = $('#tempo_indeterminado').val();
        var data_termino = $('#data_termino').val();
        if(tempo_indeterminado == 'n' && data_termino == ''){
            exit;
        }

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $.post('promocoes/acoes.php', { dados: dados }, function(data){

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

        $.post('promocoes/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('promocoes/promocoes.php');
            }
            else if(data.status == 'erro')
            {
                $('#msg-nao-exclusao').show();
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

        $.post('promocoes/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });
    
    
    /******************************************************************************************************************/
    /*Participações*/
    $('#voltar').click(function(){
        $('#content').load('promocoes/promocoes.php');
    });

    $('#id_unidade').change(function(){

        var dados = $('#formPesquisa').serialize()+'&acao=busca-turmas';
        $.post('promocoes/acoes.php', { dados: dados }, function(data){

            $('#turma').html(data);

        });

    });

    $('#pesquisar_promocao').click(function () {

        $('#listagem').html('<div style="font-size: 1.3em; font-weight: bold;">Carregando, por favor aguarde...</div>');

        var valor_pesquisa_promocao = $('#valor_pesquisa_promocao').val();
        var id_promocao = $(this).attr('id_promocao');
        var id_unidade = $('#id_unidade').val();
        var turma = $('#turma').val();

        $('#listagem').load('promocoes/listagem-envios-participacoes.php', {
            valor_pesquisa_promocao: valor_pesquisa_promocao,
            id_promocao: id_promocao,
            id_unidade: id_unidade,
            turma: turma
        });
        /*
        $.post('promocoes/listagem-envios-participacoes.php', { valor_pesquisa_promocao: valor_pesquisa_promocao }, function(data){
            $('#listagem').html(data);
        });
        */
    });

    /*Envios e Participações*/
    $('#listagem').on('click', '.bt-envios-participacoes', function (){
        var id_promocao = $(this).attr('registro');
        $('#content').load('promocoes/participacoes.php', { id_promocao: id_promocao });
    });





});