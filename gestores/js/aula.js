$(function(){

    $('#formDadosAula').submit(function(){return false});

    $('#hora_inicio').mask('00:00', {reverse: true});
    $('#hora_termino').mask('00:00', {reverse: true});

    $('#voltar').click(function(){
        $('#content').load('turmas/listagem-aulas.php', {id: $('#salvar').attr('turma'), pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val()});
    });


    $('#salvar').click(function(){

        $('#formDadosAula').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formDadosAula').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formDadosAula').find('textarea').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });


        var dados = $('#formDadosAula').serialize()+'&id='+$(this).attr('turma')+'&id_aula='+$(this).attr('registro')+'&acao=salvar-dados-aula';

        $('#ms-salvando-dialog').click();

        $.post('turmas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'erro')
            {
                $('#bt-salvou').click();
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro_dias')
            {
                $('#bt-salvou').click();
                $('#ms-dias-modal').click();
            }
            else if(data.status == 'ok')
            {
                //$('#ms-ok-modal').click();
                $('#bt-salvou').click();
                $('#content').load('turmas/listagem-aulas.php', {id: $('#salvar').attr('turma'), pesquisa: $('#pesquisa').val(), id_unidade: $('#id_unidade').val(), id_colega: $('#id_colega').val(), id_produto: $('#id_produto').val(), status: $('#status').val()});
            }

        }, 'json');

    });

    $('.presente').click(function(){

        var dados = 'id='+$(this).attr('turma')+'&id_aula_aluno='+$(this).attr('registro')+'&acao=presente';

        $.post('turmas/acoes.php', { dados: dados }, function(data){});

    });

    $('.tarefa').click(function(){

        var dados = 'id='+$(this).attr('turma')+'&id_aula_aluno='+$(this).attr('registro')+'&acao=tarefa';

        $.post('turmas/acoes.php', { dados: dados }, function(data){});

    });

});