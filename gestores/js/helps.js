$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

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
        $.post('helps/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('helps/altera-help.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });


    $('#voltar').click(function(){
        $('#content').load('helps/helps.php');
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
            $.post('helps/listagem.php', { nome: $('#valor_pesquisa').val(), id_unidade: $('#id_unidade').val(), id_empresa: $('#id_empresa').val(), id_colega: $('#id_colega').val(), status: $('#status').val(), tipo_help: $('#tipo_help').val() }, function(data){
            $('#listagem').html(data);
        });

        return false;

    });
    /*Pesquisa*/


    $('.dia-semana').click(function(){

        var dia = $(this).attr('dia');

        var dados = 'acao=dia-semana&dia='+$(this).attr('dia')+'&id='+$(this).attr('registro');
        $.post('helps/acoes.php', { dados: dados }, function(data){

            if(data.status == 'n')
            {
                $('#hora_inicio_'+dia).val('');
                $('#hora_termino_'+dia).val('');
            }

        }, 'json');

    });


    $('#tipo_help').change(function(){

        if($(this).val() == 'help fixo'){
            $('#quantidade_helps').val('100').attr('readonly', true);
        } else {
            $('#quantidade_helps').val('').attr('readonly', false);
        }

    });


    $('#id_turma').change(function(){

        $('#id_aluno').html('<option>Carregando Alunos...</option>');

        var dados = 'acao=lista-alunos&id_turma='+$(this).val()+'&id='+$('#salvar').attr('registro');
        $.post('helps/acoes.php', { dados: dados }, function(data){

            $('#id_aluno').html(data);

        });

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

        $.post('helps/acoes.php', { dados: dados }, function(data){

            $('#bt-salvou').click();

            if(data.status == 'erro')
            {
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro_dias')
            {
                $('#ms-dias-modal').click();
            }
            else if(data.status == 'erro_data')
            {
                $('#data-help').html(data.data);
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'ok')
            {
                //$('#content').load('turmas/altera-turma.php', {id: id});
                $('#data_termino').val(data.data_termino);
                $('#ms-ok-modal').click();
            }

        }, 'json');

    });


    $('#listagem').on('click', '.bt-aprovar', function(){

        var dados = 'acao=aprovar&id='+$(this).attr('registro');
        $.post('helps/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#ms-aprovacao-dialog').click();
                $('#pesquisar').click();
            } else if(data.status == 'erro_data'){
                $('#data-help').html(data.data);
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });


    $('#listagem').on('click', '.bt-cancelar', function(){

        var dados = 'acao=reprovar&id='+$(this).attr('registro');
        $.post('helps/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#ms-cancelamento-dialog').click();
                $('#pesquisar').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });


    $('#listagem').on('click', '.bt-diario-classe', function(){

        $('#content').load('helps/listagem-aulas.php', {id: $(this).attr('registro')});

    });


    $('.bt-dados-aula').click(function(){

        var id_help = $(this).attr('help');
        var id = $(this).attr('registro');

        var dados = 'id='+id_help+'&id_aula='+$(this).attr('registro')+'&acao=verifica-aula';
        $.post('helps/acoes.php', {dados: dados}, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('helps/dados-aula.php', {help: id_help, id: id});
            }
        }, 'json');

    });


});
