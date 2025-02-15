$(function(){

    $('#formNotasProva').submit(function(){return false});

    $('#voltar').click(function(){
        $('#content').load('turmas/turmas.php', { pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() });
    });


    $('#voltar-lista-notas').click(function(){

        $('#content').load('turmas/lista-provas.php', {id: $(this).attr('turma'), pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() });

    });


    $('.bt-notas-prova').click(function(){

        var id_turma = $(this).attr('turma');
        var id = $(this).attr('registro');

        var dados = 'id='+$(this).attr('turma')+'&id_prova='+$(this).attr('registro')+'&acao=notas-provas';
        $.post('turmas/acoes.php', {dados: dados}, function(data){

            if(data.status == 'ok'){
                $('#content').load('turmas/notas-provas.php', {turma: id_turma, id: id, pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val() });
            }

        }, 'json');

    });


    $('.salvar-notas').click(function(){

        var id_turma = $(this).attr('turma');
        var id = $(this).attr('prova');

        var dados = $('#formNotasProva').serialize()+'&id='+$(this).attr('turma')+'&id_prova='+$(this).attr('prova')+'&acao=salvar-notas';

        $.post('turmas/acoes.php', { dados: dados }, function(data){

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


    /*Observações do Professor*/
    $('#salvar-observacao-professor').click(function(){

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

        var dados = $('#formObservacoes').serialize()+'&acao=salvar-observacao&id='+$(this).attr('id_turma')+'&id_usuario='+$(this).attr('id_usuario');
        $.post('turmas/acoes.php', { dados: dados }, function (data){

            if(data.status == 'ok'){
                $('#cancelar-observacao-professor').click();
                $('#bt-salvou').click();
                $('#ms-sucesso-dialog').click();
                $('#formObservacoes')[0].reset();
            }

            if(data.status == 'erro-id_colega'){
                $('#cancelar-observacao-professor').click();
                $('#bt-salvou').click();
                $('#ms-erro-id-colega-dialog').click();
            }

        }, 'json');

    });


    /*##############################################*/
    /*ATA*/
    $('.bt-nova-ata').click(function () {
       var aluno = $(this).attr('aluno');
       $('#bt-salvar-ata').attr('aluno', aluno);
    });
    
    $('#bt-salvar-ata').click(function (){

        var id_aluno = $(this).attr('aluno');

        $('#formNovaAta').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formNovaAta').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });


        $('#ms-salvando-dialog').click();

        var dados = $('#formNovaAta').serialize()+'&id_aluno='+id_aluno+'&acao=salvar-ata';
        $.post('coachs/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){

                $('#bt-salvou').click();
                $('#formNovaAta')[0].reset();
                $('#listagem-atas-single').load('coachs/listagem-atas-single.php', { id: id_aluno});
                $('#listagem-atas').load('coachs/listagem-atas.php', { id: id_aluno});

            }

        }, 'json');

    });


});