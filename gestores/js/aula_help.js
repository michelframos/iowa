$(function(){

    $('#formDadosAula').submit(function(){return false});

    $('#hora_inicio').mask('00:00', {reverse: true});
    $('#hora_termino').mask('00:00', {reverse: true});

    $('#voltar').click(function(){

        $('#content').load('helps/listagem-aulas.php', {id: $(this).attr('registro')});

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


        var dados = $('#formDadosAula').serialize()+'&id='+$(this).attr('help')+'&id_aula='+$(this).attr('registro')+'&acao=salvar-dados-aula';

        $.post('helps/acoes.php', { dados: dados }, function(data){

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
                //$('#ms-ok-modal').click();
                $('#content').load('helps/listagem-aulas.php', {id: $('#salvar').attr('help')});
            }

        }, 'json');

    });

});
