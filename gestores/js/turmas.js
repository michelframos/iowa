$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});
    $('#formIntegrantes').submit(function(){return false});
    $('#formLimiteFaltas').submit(function(){return false});
    $('#msg-nao-exclusao').hide();

    $('#hora_inicio_segunda').mask('00:00', {reverse: true});
    $('#hora_termino_segunda').mask('00:00', {reverse: true});

    $('#hora_inicio_terca').mask('00:00', {reverse: true});
    $('#hora_termino_terca').mask('00:00', {reverse: true});

    $('#hora_inicio_quarta').mask('00:00', {reverse: true});
    $('#hora_termino_quarta').mask('00:00', {reverse: true});

    $('#hora_inicio_quinta').mask('00:00', {reverse: true});
    $('#hora_termino_quinta').mask('00:00', {reverse: true});

    $('#hora_inicio_sexta').mask('00:00', {reverse: true});
    $('#hora_termino_sexta').mask('00:00', {reverse: true});

    $('#hora_inicio_sabado').mask('00:00', {reverse: true});
    $('#hora_termino_sabado').mask('00:00', {reverse: true});

    $('#hora_inicio').mask('00:00', {reverse: true});
    $('#hora_termino').mask('00:00', {reverse: true});

    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('turmas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('turmas/altera-turma.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


    $('#adicionar_aulas_turma').change(function () {

        let adicionar_aulas = $('#adicionar_aulas_turma').is(':checked') ? 's' : 'n';
        var dados = '&id='+$('#id_turma').val()+'&adicionar_aulas='+adicionar_aulas+'&acao=alterar_adicionar_aulas';
        $.post('turmas/acoes.php', { dados: dados }, function (data) {
            if(data.status === 'ok')
            {
                $('#ms-ok-modal').click();
            }
        });

    });


    $('#voltar').click(function(){
        $('#content').load('turmas/turmas.php', { pesquisa: $('#pesquisa').val(), pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() });
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
        $.post('turmas/listagem.php', { nome: $('#valor_pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() }, function(data){
            $('#listagem').html(data);
        });

        return false;

    });
    /*Pesquisa*/


    /*Diário de Classe*/
    $('#listagem').on('click', '.bt-diario-classe', function(){
        $('#content').load('turmas/listagem-aulas.php', {id: $(this).attr('registro'), pesquisa: $('#valor_pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() });
    });

    $('.bt-dados-aula').click(function(){

        var id_turma = $(this).attr('turma');
        var id = $(this).attr('registro');

        var dados = 'id='+$(this).attr('turma')+'&id_aula='+$(this).attr('registro')+'&acao=verifica-aula';
        $.post('turmas/acoes.php', {dados: dados}, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('turmas/dados-aula.php', {id: id, turma: id_turma, pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val()});
            }
        }, 'json');

    });


    $('#adicionar-aula').click(function(){

        var dados = 'acao=adicionar-aula&id='+$(this).attr('registro')+'&data='+$('#data').val();
        $.post('turmas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('turmas/listagem-aulas.php', {id: $('#adicionar-aula').attr('registro'), pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() });
            }

        }, 'json');

    });

    $('#adicionar-pacote').click(function(){

        var dados = 'acao=adicionar-pacote&id='+$(this).attr('registro')+'&numero_aulas='+$('#numero-aulas').val();
        $.post('turmas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('turmas/listagem-aulas.php', {id: $('#adicionar-aula').attr('registro'), pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() });
            }

        }, 'json');

    });

    /*Fim diário de Classe*/

    /*Lista de Provas*/
    $('#listagem').on('click', '.bt-lista-provas', function(){

        var id = $(this).attr('registro');

        var dados = 'id='+$(this).attr('registro')+'&acao=verifica-provas';
        $.post('turmas/acoes.php', {dados: dados}, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('turmas/lista-provas.php', {id: id, pesquisa: $('#valor_pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() });
            }
        }, 'json');

    });
    /*Fim Lista de Provas*/


    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('turmas/altera-turma.php', {id: $(this).attr('registro')});
    });


    $('#mudar_estagio').click(function(){

        $('#nome').removeAttr('readonly');
        $('#salvar').addClass('oculto');
        $('#alterar-estagio').removeClass('oculto');
        $(this).hide();

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

        var id = $(this).attr('registro');
        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $.post('turmas/acoes.php', { dados: dados }, function(data){

            $('#bt-salvou').click();

            if(data.status == 'erro')
            {
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro_dias')
            {
                $('#ms-dias-modal').click();
            }
            else if(data.status == 'ok')
            {
                //$('#content').load('turmas/altera-turma.php', {id: id});
                $('#data_termino').val(data.data_termino);
                $('#content-aulas').empty('').load('turmas/listagem-aulas.php', {id: $('#salvar').attr('registro')});
                $('#ms-ok-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });


    $('#salvar-limite-faltas').click(function (){

        var id = $(this).attr('registro');
        var dados = $('#formLimiteFaltas').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar-limite-faltas';

        $.post('turmas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#ms-ok-modal').click();
            }

        }, 'json');

    });


    $('#alterar-estagio').click(function(){

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=mudar-estagio';

        $.post('turmas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'erro')
            {
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro_dias')
            {
                $('#ms-dias-modal').click();
            }
            else if(data.status == 'ok')
            {
                $('#content-aulas').empty('').load('turmas/listagem-aulas.php', {id: $('#salvar').attr('registro')});
                $('#ms-mudanca-estagio-modal').click();
            }

        }, 'json');

    });



    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('turmas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('turmas/turmas.php');
            }
            else if(data.status == 'erro')
            {
                $('#msg-nao-exclusao').show();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('turmas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });



    $('.dia-semana').click(function(){

        var dia = $(this).attr('dia');

        var dados = 'acao=dia-semana&dia='+$(this).attr('dia')+'&id='+$(this).attr('registro');
        $.post('turmas/acoes.php', { dados: dados }, function(data){

             if(data.status == 'n')
            {
                $('#hora_inicio_'+dia).val('');
                $('#hora_termino_'+dia).val('');
            }

        }, 'json');

    });


    /*---------------------------------------------------------------------------------------*/
    /*INTEGRANTES DA TURMA*/

    $('#bt-transferir').click(function(){

        $('#formTranferir').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formTranferir').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });


        var dados = $('#formTranferir').serialize()+'&id='+$(this).attr('turma')+'&acao=transferir';
        $.post('turmas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {

            }

        }, 'json');

    });


    /*Botão para atualizar matriculas e financeiro*/
    $('#listagem').on('click', '#atualizar', function (){

        var id_turma = $(this).attr('id_turma');
        var dados = 'acao=atualizar-matricula&id_turma='+id_turma;
        $.post('turmas/acoes.php', { dados: dados }, function (data){

            if(data.status == 'ok')
            {
                alert('Atualizado');
            }

        }, 'json');

    });


});
